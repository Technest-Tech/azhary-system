<?php

namespace Tests\Feature;

use App\Services\WhatsAppService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    private array $written = [];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.url' => 'https://manage.azharyfr.com',
            'services.acadmyq.base_url' => 'https://api.acadmyq.com',
            'services.acadmyq.api_key' => 'wa_test_key',
        ]);
    }

    protected function tearDown(): void
    {
        foreach ($this->written as $file) {
            @unlink(public_path('reports/' . $file));
        }

        parent::tearDown();
    }

    public function test_image_is_sent_to_acadmyq_as_a_public_url_with_digits_only_phone(): void
    {
        Http::fake(['api.acadmyq.com/*' => Http::response(['message_id' => 'msg_1'], 202)]);
        $file = $this->fileName();

        $result = (new WhatsAppService())->sendImage('+33 6 12 34 56 78', 'png-bytes', $file, 'Rapport');

        $this->assertTrue($result['success']);
        $this->assertSame('msg_1', $result['message_id']);
        $this->assertFileExists(public_path('reports/' . $file));

        Http::assertSent(function (Request $request) use ($file) {
            return $request->url() === 'https://api.acadmyq.com/api/wa/v1/messages'
                && $request->hasHeader('Authorization', 'Bearer wa_test_key')
                && str_starts_with($request->header('Idempotency-Key')[0], 'azhary-')
                && $request['to'] === '33612345678'
                && $request['image_url'] === 'https://manage.azharyfr.com/reports/' . $file
                && $request['caption'] === 'Rapport'
                && !isset($request['text']);
        });
    }

    public function test_text_is_sent_and_00_prefix_is_dropped(): void
    {
        Http::fake(['api.acadmyq.com/*' => Http::response(['message_id' => 'msg_2'], 202)]);

        $result = (new WhatsAppService())->sendText('0041791234567', 'Bonjour');

        $this->assertTrue($result['success']);
        Http::assertSent(fn (Request $request) => $request['to'] === '41791234567'
            && $request['text'] === 'Bonjour'
            && !isset($request['image_url']));
    }

    public function test_a_replayed_send_counts_as_success(): void
    {
        Http::fake(['api.acadmyq.com/*' => Http::response(['status' => 'duplicate'], 200)]);

        $this->assertTrue((new WhatsAppService())->sendText('33612345678', 'x')['success']);
    }

    public function test_not_connected_is_reported_as_a_failure(): void
    {
        Http::fake(['api.acadmyq.com/*' => Http::response(['error' => 'not_connected'], 409)]);

        $result = (new WhatsAppService())->sendText('33612345678', 'x');

        $this->assertFalse($result['success']);
        $this->assertSame('not_connected', $result['error']);
        $this->assertSame(409, $result['status_code']);
    }

    public function test_nothing_is_sent_without_an_api_key(): void
    {
        config(['services.acadmyq.api_key' => null]);
        Http::fake();

        $result = (new WhatsAppService())->sendText('33612345678', 'x');

        $this->assertFalse($result['success']);
        Http::assertNothingSent();
    }

    public function test_test_send_routes_are_gone(): void
    {
        Http::fake();

        $this->get('/test-send-whatsapp-text')->assertNotFound();
        $this->get('/test-send-whatsapp')->assertNotFound();
        Http::assertNothingSent();
    }

    private function fileName(): string
    {
        return $this->written[] = 'test-report-' . uniqid() . '.png';
    }
}
