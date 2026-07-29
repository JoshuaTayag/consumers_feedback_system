<?php
// app/Jobs/SendSmsJob.php

namespace App\Jobs;

use App\Services\M360SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 5;

    public function __construct(
        protected array $to,
        protected string $text,
        protected bool $isIntl = false,
    ) {}

    public function handle(M360SmsService $sms): void
    {
        $sms->send($this->to, $this->text, $this->isIntl);
    }
}