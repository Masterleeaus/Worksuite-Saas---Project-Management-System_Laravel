<?php

namespace Modules\CustomerConnect\Services\Premium;

class TemplateSanitizer
{
    public function sanitizeForChannel(string $channel, string $text): string
    {
        $text = trim($text);

        // Normalize whitespace
        $text = preg_replace('/\r\n?/', "\n", $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);

        if ($channel === 'sms' || $channel === 'whatsapp') {
            // Keep it plain, no HTML tags
            $text = strip_tags($text);
            // Hard trim very long bursts (still allow >160; providers split)
            return mb_substr($text, 0, 2000);
        }

        if ($channel === 'telegram') {
            // Telegram: keep plain text, avoid unsupported HTML
            $text = strip_tags($text);
            return mb_substr($text, 0, 4000);
        }

        // email default: allow html-ish (caller can pass html separately)
        return $text;
    }

    public function mergeVars(string $template, array $vars): string
    {
        // Simple {var} replacement
        return preg_replace_callback('/\{([a-zA-Z0-9_\.]+)\}/', function($m) use ($vars){
            $key = $m[1];
            return isset($vars[$key]) ? (string)$vars[$key] : $m[0];
        }, $template);
    }
}
