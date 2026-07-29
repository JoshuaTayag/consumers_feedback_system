<?php
// app/Services/SmsTemplateRenderer.php

namespace App\Services;

use App\Enums\SmsTemplate;
use InvalidArgumentException;

class SmsTemplateRenderer
{
    /**
     * Render a template with the given data.
     *
     * @param SmsTemplate $template
     * @param array<string, string> $data  Keys should match placeholder names, e.g. ['CONTROL_NO' => 'CM-0001']
     */
    public function render(SmsTemplate $template, array $data): string
    {
        $body = config("sms_templates.{$template->value}.body");

        if (! $body) {
            throw new InvalidArgumentException("No template body configured for [{$template->value}].");
        }

        // Find every {PLACEHOLDER} the template actually needs
        preg_match_all('/\{([A-Z_]+)\}/', $body, $matches);
        $required = array_unique($matches[1]);

        // Normalize incoming data keys to uppercase so callers can't typo casing
        $data = array_change_key_case($data, CASE_UPPER);

        $missing = array_diff($required, array_keys($data));

        if (! empty($missing)) {
            throw new InvalidArgumentException(
                "Missing template variables for [{$template->value}]: " . implode(', ', $missing)
            );
        }

        $replacements = [];
        foreach ($required as $key) {
            $replacements['{' . $key . '}'] = (string) $data[$key];
        }

        return strtr($body, $replacements);
    }
}