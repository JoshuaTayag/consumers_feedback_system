<?php
// app/Services/M360SmsService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use App\Enums\SmsTemplate;

class M360SmsService
{
    public function __construct(
        protected string $baseUrl = '',
        protected string $appKey = '',
        protected string $appSecret = '',
        protected string $senderId = '',
    ) {
        $this->baseUrl   = config('m360.base_url');
        $this->appKey    = config('m360.app_key');
        $this->appSecret = config('m360.app_secret');
        $this->senderId  = config('m360.sender_id');
    }

    /**
     * Send an SMS to one or more recipients.
     *
     * @param array<string> $to  Recipient mobile numbers (local format, e.g. 09171234567)
     * @param string $text       Message body
     * @param bool $isIntl       Set true if any recipient is a non-PH number
     * @param string|null $requestId Optional idempotency/tracking id
     */
    public function send(array $to, string $text, bool $isIntl = false, ?string $requestId = null): array
    {
        $payload = [
            'from' => $this->senderId,
            'to'   => $to,
            'dcs'  => 0,
            'content' => [
                'text' => $text,
            ],
        ];

        if ($isIntl) {
            $payload['is_intl'] = true;
        }

        if ($requestId) {
            $payload['request_id'] = $requestId;
        }

        $response = Http::withBasicAuth($this->appKey, $this->appSecret)
            ->acceptJson()
            ->timeout(10)
            ->retry(2, 200) // retry twice on connection errors, 200ms apart
            ->post($this->baseUrl, $payload);

        if ($response->failed()) {
            Log::error('M360 SMS send failed', [
                'status' => $response->status(),
                'body'   => $response->json() ?? $response->body(),
                'to'     => $to,
            ]);

            throw new RuntimeException(
                'M360 SMS send failed: ' . ($response->json('message') ?? 'Unknown error')
            );
        }

        return $response->json();
    }
    public function sendTemplate(
        array $to,
        SmsTemplate $template,
        array $data,
        SmsTemplateRenderer $renderer,
        ?string $requestId = null,
    ): array {
        $text = $renderer->render($template, $data);

        return $this->send($to, $text, requestId: $requestId);
    }
}