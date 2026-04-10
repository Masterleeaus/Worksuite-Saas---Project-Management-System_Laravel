<?php

namespace Modules\TitanZero\Services\Docs;

/**
 * Pass 4 extractor: intentionally conservative.
 * If a PDF parser isn't available, we fail with a clear message.
 */
class PdfTextExtractor
{
    public function extract(string $pdfPath): array
    {
        // Try system `pdftotext` if available (common on servers).
        $cmd = 'pdftotext '.escapeshellarg($pdfPath).' - -layout 2>/dev/null';
        $output = @shell_exec($cmd);

        if (is_string($output) && trim($output) !== '') {
            return [
                'text' => $output,
                'meta' => ['method' => 'pdftotext'],
            ];
        }

        return [
            'text' => '',
            'meta' => ['method' => 'none'],
            'error' => 'No PDF text extractor available. Install pdftotext (poppler-utils) or add a PHP PDF parser.',
        ];
    }
}
