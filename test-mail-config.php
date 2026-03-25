<?php
// filepath: c:\SYSTEM\lics\consumer_feedback_system\test-graph-html.php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Messages\MailMessage;

$mailMessage = (new MailMessage)
    ->subject('HTML Test')
    ->greeting('Hello!')
    ->line('This is **bold** text')
    ->line('This is *italic* text')
    ->action('Click Me', 'https://example.com')
    ->line('This should be HTML formatted');

// Get the HTML
$html = $mailMessage->render();

echo "Generated HTML Length: " . strlen($html) . " characters\n";
echo "First 200 characters:\n";
echo substr($html, 0, 200) . "\n\n";

// Send test using Notification::route() which is the correct way
use Illuminate\Support\Facades\Notification;

Notification::route('mail', 'joshuatayag3029@gmail.com')
    ->notify(new class($mailMessage) extends \Illuminate\Notifications\Notification {
        public $msg;
        public function __construct($msg) { $this->msg = $msg; }
        public function via($n) { return ['mail']; }
        public function toMail($n) { return $this->msg; }
    });

echo "Test email sent!\n";