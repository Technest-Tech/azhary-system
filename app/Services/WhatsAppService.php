<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl = 'https://wasenderapi.com/api';
    private $apiKey;

    public function __construct()
    {
        // Use provided API key or get from env
        $this->apiKey = env('WASENDER_API_KEY', '8b40f461c42db71793ddff8e42887837b0367ab11ccddbffc02e7b833b2ab39e');
    }

    /**
     * Format phone number to WhatsApp JID format
     * JID format: countrycode+number@s.whatsapp.net
     */
    public function formatPhoneToJid($phoneNumber)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If phone doesn't start with country code, assume it's local format
        // You may need to adjust this based on your country code
        // For example, if your numbers are stored without country code, add it here
        
        return $phone . '@s.whatsapp.net';
    }

    /**
     * Upload PDF to a publicly accessible location and get URL
     */
    public function uploadPdf($pdfContent, $filename)
    {
        // Save PDF to storage
        $path = 'reports/' . $filename;
        Storage::disk('public')->put($path, $pdfContent);
        
        // Get public URL - ensure it's a full URL
        $baseUrl = config('app.url');
        $url = $baseUrl . '/storage/' . $path;
        
        return $url;
    }

    /**
     * Upload image to a publicly accessible location and get URL
     */
    public function uploadImage($imageContent, $filename)
    {
        // Save image directly to public/reports/ for better accessibility
        // This bypasses storage symlink issues and CDN blocks
        $publicPath = public_path('reports');
        
        // Ensure reports directory exists
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
        
        // Save file
        $filePath = $publicPath . '/' . $filename;
        file_put_contents($filePath, $imageContent);
        
        // Set proper permissions
        chmod($filePath, 0644);
        
        // Get public URL - direct access without /storage/ prefix
        $baseUrl = config('app.url');
        $url = $baseUrl . '/reports/' . $filename;
        
        return $url;
    }

    /**
     * Send image via WhatsApp
     */
    public function sendImage($phoneNumber, $imageContent, $fileName, $caption = null)
    {
        try {
            $jid = $this->formatPhoneToJid($phoneNumber);
            
            // Upload image to get a publicly accessible URL
            $imageUrl = $this->uploadImage($imageContent, $fileName);
            
            // Verify the URL is accessible (test if it's publicly accessible)
            try {
                $testResponse = Http::timeout(10)->head($imageUrl);
                if (!$testResponse->successful()) {
                    Log::warning('Image URL may not be publicly accessible', [
                        'url' => $imageUrl,
                        'status' => $testResponse->status()
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Could not verify image URL accessibility', [
                    'url' => $imageUrl,
                    'error' => $e->getMessage()
                ]);
            }
            
            $payload = [
                'to' => $jid,
                'imageUrl' => $imageUrl,
            ];
            
            if ($caption) {
                $payload['caption'] = $caption;
            }
            
            Log::info('Sending image via WhatsApp', [
                'phone' => $phoneNumber,
                'url' => $imageUrl,
                'file' => $fileName
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($this->apiUrl . '/send-message', $payload);
            
            $responseData = $response->json();
            $statusCode = $response->status();
            
            if ($response->successful()) {
                Log::info('WhatsApp image sent successfully', [
                    'phone' => $phoneNumber,
                    'file' => $fileName,
                    'url' => $imageUrl,
                    'status' => $statusCode,
                    'response' => $responseData
                ]);
                return [
                    'success' => true, 
                    'data' => $responseData,
                    'message_id' => $responseData['id'] ?? null,
                    'image_url' => $imageUrl
                ];
            } else {
                $errorMessage = $responseData['message'] ?? $response->body() ?? 'Unknown error';
                Log::error('WhatsApp API error', [
                    'phone' => $phoneNumber,
                    'status' => $statusCode,
                    'url' => $imageUrl,
                    'payload' => $payload,
                    'response' => $responseData,
                    'body' => $response->body()
                ]);
                return [
                    'success' => false, 
                    'error' => $errorMessage,
                    'status_code' => $statusCode,
                    'full_response' => $responseData,
                    'image_url' => $imageUrl
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send PDF document via WhatsApp - sends file directly, not as link
     */
    public function sendDocument($phoneNumber, $pdfContent, $fileName, $caption = null)
    {
        try {
            $jid = $this->formatPhoneToJid($phoneNumber);
            
            // Upload PDF to get a publicly accessible URL
            // The API will download from this URL and send as document attachment
            $documentUrl = $this->uploadPdf($pdfContent, $fileName);
            
            $payload = [
                'to' => $jid,
                'documentUrl' => $documentUrl,
                'fileName' => $fileName,
            ];
            
            if ($caption) {
                $payload['caption'] = $caption;
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($this->apiUrl . '/send-message', $payload);
            
            $responseData = $response->json();
            $statusCode = $response->status();
            
            if ($response->successful()) {
                Log::info('WhatsApp document sent successfully', [
                    'phone' => $phoneNumber,
                    'file' => $fileName,
                    'status' => $statusCode,
                    'response' => $responseData
                ]);
                return [
                    'success' => true, 
                    'data' => $responseData,
                    'message_id' => $responseData['id'] ?? null
                ];
            } else {
                $errorMessage = $responseData['message'] ?? $response->body() ?? 'Unknown error';
                Log::error('WhatsApp API error', [
                    'phone' => $phoneNumber,
                    'status' => $statusCode,
                    'payload' => $payload,
                    'response' => $responseData,
                    'body' => $response->body()
                ]);
                return [
                    'success' => false, 
                    'error' => $errorMessage,
                    'status_code' => $statusCode,
                    'full_response' => $responseData
                ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send text message via WhatsApp
     */
    public function sendText($phoneNumber, $text)
    {
        try {
            $jid = $this->formatPhoneToJid($phoneNumber);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/send-message', [
                'to' => $jid,
                'text' => $text,
            ]);
            
            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            } else {
                return ['success' => false, 'error' => $response->body()];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp text message error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
