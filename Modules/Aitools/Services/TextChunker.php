<?php

namespace Modules\Aitools\Services;

class TextChunker
{
    /**
     * Chunk plain text into reasonably sized pieces.
     * This is intentionally simple + deterministic.
     */
    public function chunk(string $text, int $maxChars = 1200): array
    {
        $text = trim(str_replace(["\r\n", "\r"], "\n", $text));
        if ($text === '') {
            return [];
        }

        $parts = preg_split("/\n\n+/", $text) ?: [];
        $chunks = [];

        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '') continue;

            if (mb_strlen($p) <= $maxChars) {
                $chunks[] = $p;
                continue;
            }

            // Further split long paragraphs into slices.
            $start = 0;
            $len = mb_strlen($p);
            while ($start < $len) {
                $slice = mb_substr($p, $start, $maxChars);
                $chunks[] = trim($slice);
                $start += $maxChars;
            }
        }

        // Final cleanup.
        $chunks = array_values(array_filter(array_map('trim', $chunks)));

        return $chunks;
    }
}
