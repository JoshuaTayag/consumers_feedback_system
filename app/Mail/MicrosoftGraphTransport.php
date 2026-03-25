<?php
// filepath: c:\SYSTEM\lics\consumer_feedback_system\app\Mail\MicrosoftGraphTransport.php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\Part\DataPart;
use Illuminate\Support\Facades\Log;

class MicrosoftGraphTransport extends AbstractTransport
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $tenantId;
    protected string $userEmail;

    public function __construct(string $clientId, string $clientSecret, string $tenantId, string $userEmail)
    {
        parent::__construct();
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tenantId     = $tenantId;
        $this->userEmail    = $userEmail;
    }

    protected function doSend(SentMessage $message): void
    {
        $accessToken = $this->getAccessToken();
        $email       = MessageConverter::toEmail($message->getOriginalMessage());

        // Build recipients
        $toRecipients = [];
        foreach ($email->getTo() as $address) {
            $toRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name'    => $address->getName() ?? '',
                ],
            ];
        }

        // CC recipients
        $ccRecipients = [];
        foreach ($email->getCc() as $address) {
            $ccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name'    => $address->getName() ?? '',
                ],
            ];
        }

        // BCC recipients
        $bccRecipients = [];
        foreach ($email->getBcc() as $address) {
            $bccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name'    => $address->getName() ?? '',
                ],
            ];
        }

        // Get email body - FIXED: Better HTML detection and Gmail compatibility
        $htmlBody = $email->getHtmlBody();
        $textBody = $email->getTextBody();
        
        // Check if we actually have HTML content
        $hasHtml = !empty($htmlBody) && strip_tags($htmlBody) !== $htmlBody;
        
        if ($hasHtml) {
            // Use HTML content type
            $contentType = 'HTML';
            $content = $this->cleanHtmlForGmail($htmlBody);
        } else {
            // Use plain text
            $contentType = 'Text';
            $content = $textBody ?? strip_tags($htmlBody ?? '');
        }

        // Build mail message
        $mailBody = [
            'message' => [
                'subject' => $email->getSubject() ?? '(no subject)',
                'body'    => [
                    'contentType' => $contentType,
                    'content'     => $content,
                ],
                'toRecipients' => $toRecipients,
            ],
        ];

        // Add CC if exists
        if (!empty($ccRecipients)) {
            $mailBody['message']['ccRecipients'] = $ccRecipients;
        }

        // Add BCC if exists
        if (!empty($bccRecipients)) {
            $mailBody['message']['bccRecipients'] = $bccRecipients;
        }

        // Add from address
        $mailBody['message']['from'] = [
            'emailAddress' => [
                'address' => config('mail.from.address'),
                'name'    => config('mail.from.name') ?? '',
            ],
        ];

        // Handle attachments
        $attachments = [];
        foreach ($email->getAttachments() as $attachment) {
            if ($attachment instanceof DataPart) {
                $attachments[] = [
                    '@odata.type' => '#microsoft.graph.fileAttachment',
                    'name' => $attachment->getFilename() ?? 'attachment',
                    'contentType' => $attachment->getMediaType() . '/' . $attachment->getMediaSubtype(),
                    'contentBytes' => base64_encode($attachment->getBody()),
                ];
            }
        }

        if (!empty($attachments)) {
            $mailBody['message']['attachments'] = $attachments;
        }

        // Log what we're sending (helpful for debugging Gmail issues)
        Log::info('Microsoft Graph Email Sending', [
            'to' => $email->getTo()[0]->getAddress() ?? 'unknown',
            'subject' => $email->getSubject(),
            'contentType' => $contentType,
            'hasAttachments' => !empty($attachments),
            'bodyLength' => strlen($content),
        ]);

        // Send email via Microsoft Graph
        $response = Http::withToken($accessToken)
            ->post("https://graph.microsoft.com/v1.0/users/{$this->userEmail}/sendMail", $mailBody);

        if ($response->failed()) {
            Log::error('Microsoft Graph Send Failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Microsoft Graph mail error: ' . $response->body());
        }
        
        Log::info('Microsoft Graph Email Sent Successfully');
    }

    /**
     * Clean HTML to ensure Gmail compatibility
     * Gmail is strict about HTML formatting and strips certain elements
     */
    protected function cleanHtmlForGmail(string $html): string
    {
        // Remove any <!DOCTYPE> declarations that might confuse Gmail
        $html = preg_replace('/<!DOCTYPE[^>]*>/i', '', $html);
        
        // Remove any XML declarations
        $html = preg_replace('/<\?xml[^>]*\?>/i', '', $html);
        
        // Ensure we have proper HTML structure
        if (stripos($html, '<html') === false) {
            $html = '<html><head><meta charset="UTF-8"></head><body>' . $html . '</body></html>';
        }
        
        // Clean up extra whitespace
        $html = trim($html);
        
        return $html;
    }

    protected function getAccessToken(): string
    {
        $url = "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token";

        $response = Http::asForm()->post($url, [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope'         => 'https://graph.microsoft.com/.default',
            'grant_type'    => 'client_credentials',
        ]);

        if ($response->failed() || !isset($response->json()['access_token'])) {
            throw new \Exception('Failed to get access token: ' . $response->body());
        }

        return $response->json()['access_token'];
    }

    public function __toString(): string
    {
        return 'microsoft_graph';
    }
}