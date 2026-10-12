<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Sends WhatsApp messages through the Acadmyq gateway (api.acadmyq.com).
 *
 * Acadmyq answers 202 when a message is queued for delivery — not when it is
 * delivered — and 200 + status=duplicate when an Idempotency-Key is replayed.
 * It only sends text and images; images must be a public https URL, which the
 * gateway downloads itself.
 */
class WhatsAppService
{
    private $apiUrl;
    private $apiKey;
    private $timeout;

    public function __construct()
    {
        $this->apiUrl = rtrim(config('services.acadmyq.base_url'), '/');
        $this->apiKey = config('services.acadmyq.api_key');
        $this->timeout = (int) config('services.acadmyq.timeout', 30);
    }

    /**
     * Acadmyq wants the number as digits in international format (e.g. 33612345678).
     */
    public function formatPhone($phoneNumber)
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $phoneNumber);

        // 0033… is the same number as +33…
        if (str_starts_with($phone, '00')) {
            $phone = substr($phone, 2);
        }

        return $phone;
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
            // Upload image to get a publicly accessible URL
            $imageUrl = $this->uploadImage($imageContent, $fileName);

            // Acadmyq downloads the image itself, so it must be public https
            if (!str_starts_with($imageUrl, 'https://')) {
                Log::warning('Report image URL is not https; Acadmyq will reject it', [
                    'url' => $imageUrl,
                ]);
            }

            $payload = [
                'to' => $this->formatPhone($phoneNumber),
                'image_url' => $imageUrl,
            ];

            if ($caption) {
                $payload['caption'] = $caption;
            }

            Log::info('Sending image via WhatsApp', [
                'phone' => $phoneNumber,
                'url' => $imageUrl,
                'file' => $fileName
            ]);

            $result = $this->send($payload);
            $result['image_url'] = $imageUrl;

            return $result;
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
     * Send text message via WhatsApp
     */
    public function sendText($phoneNumber, $text)
    {
        try {
            return $this->send([
                'to' => $this->formatPhone($phoneNumber),
                'text' => $text,
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp text message error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Post one message to Acadmyq and translate its answer into our result array.
     */
    private function send(array $payload)
    {
        if (empty($this->apiKey)) {
            Log::error('WhatsApp not sent: WHATSAPP_API_KEY is not set', ['to' => $payload['to']]);
            return ['success' => false, 'error' => 'WhatsApp API key is not configured'];
        }

        $response = Http::withToken($this->apiKey)
            ->acceptJson()
            ->withHeaders(['Idempotency-Key' => 'azhary-' . Str::uuid()])
            ->timeout($this->timeout)
            ->post($this->apiUrl . '/api/wa/v1/messages', $payload);

        $responseData = $response->json();
        $statusCode = $response->status();

        // 202 = queued for delivery; 200 + duplicate = this exact send was already accepted
        if ($statusCode === 202 || ($statusCode === 200 && ($responseData['status'] ?? null) === 'duplicate')) {
            Log::info('WhatsApp message accepted by Acadmyq', [
                'to' => $payload['to'],
                'status' => $statusCode,
                'response' => $responseData
            ]);
            return [
                'success' => true,
                'data' => $responseData,
                'message_id' => $responseData['message_id'] ?? null,
            ];
        }

        // 409 not_connected = no WhatsApp number is linked to this API key (rescan the QR in Acadmyq)
        $errorMessage = $responseData['error'] ?? $responseData['message'] ?? $response->body();
        Log::error('WhatsApp API error', [
            'to' => $payload['to'],
            'status' => $statusCode,
            'payload' => $payload,
            'response' => $responseData,
            'body' => $response->body()
        ]);
        return [
            'success' => false,
            'error' => $errorMessage ?: 'Unknown error',
            'status_code' => $statusCode,
            'full_response' => $responseData
        ];
    }
}
