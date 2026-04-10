<?php

namespace Modules\TitanZero\DTO;

class Citation
{
    public function __construct(
        public int $document_id,
        public string $document_title,
        public int $chunk_index,
        public string $content_hash
    ) {}
}
