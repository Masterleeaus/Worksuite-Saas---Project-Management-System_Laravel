<?php

namespace Modules\TitanZero\Services\Docs;

class Chunker
{
    public function chunk(string $text, int $maxChars = 1200): array
    {
        $text = trim($text);
        if ($text === '') return [];

        $paras = preg_split('/\n\s*\n/', $text);
        $chunks = [];
        $buf = '';

        foreach ($paras as $p) {
            $p = trim($p);
            if ($p === '') continue;

            if (mb_strlen($buf) + mb_strlen($p) + 2 <= $maxChars) {
                $buf = $buf === '' ? $p : ($buf."\n\n".$p);
            } else {
                if ($buf !== '') $chunks[] = $buf;
                $buf = $p;
            }
        }
        if ($buf !== '') $chunks[] = $buf;

        return $chunks;
    }
}
