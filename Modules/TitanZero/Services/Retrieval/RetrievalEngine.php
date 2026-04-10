<?php

namespace Modules\TitanZero\Services\Retrieval;

use Modules\TitanZero\Entities\TitanZeroDocumentChunk;

class RetrievalEngine
{
    public function search(string $query, int $limit = 5): array
    {
        return $this->searchWithFilters($query, [], $limit);
    }

    public function searchWithFilters(string $query, array $filters = [], int $limit = 5): array
    {
        $q = trim($query);
        if ($q === '') return [];

        $terms = preg_split('/\s+/', mb_strtolower($q));
        $terms = array_values(array_filter(array_unique($terms), fn($t) => mb_strlen($t) >= 3));
        if (!$terms) return [];

        $builder = TitanZeroDocumentChunk::query()->with('document');

        $builder->whereHas('document', function($d) use ($filters) {
            if (!empty($filters['exclude_superseded'])) {
                $d->where('is_superseded', false);
            }
            if (!empty($filters['jurisdiction'])) {
                $d->where('jurisdiction', $filters['jurisdiction']);
            }
            if (!empty($filters['doc_type'])) {
                $d->where('doc_type', $filters['doc_type']);
            }
            if (!empty($filters['authority_level'])) {
                $d->where('authority_level', $filters['authority_level']);
            }
            if (!empty($filters['coach_key'])) {
                $d->where(function($w) use ($filters) {
                    $w->whereNull('coach_override')
                      ->orWhere('coach_override', $filters['coach_key']);
                });
            }

            if (!empty($filters['include_tags']) && is_array($filters['include_tags'])) {
                $tags = array_values(array_filter($filters['include_tags']));
                if ($tags) {
                    $d->whereHas('tags', function($t) use ($tags) {
                        $t->whereIn('key', $tags);
                    });
                }
            }
            if (!empty($filters['exclude_tags']) && is_array($filters['exclude_tags'])) {
                $tags = array_values(array_filter($filters['exclude_tags']));
                if ($tags) {
                    $d->whereDoesntHave('tags', function($t) use ($tags) {
                        $t->whereIn('key', $tags);
                    });
                }
            }
        });

        $builder->where(function($w) use ($terms) {
            foreach ($terms as $t) {
                $w->orWhereRaw('LOWER(content) LIKE ?', ['%'.$t.'%']);
            }
        });

        $chunks = $builder->orderByDesc('id')->limit($limit * 3)->get();

        $scored = [];
        foreach ($chunks as $c) {
            $text = mb_strtolower($c->content);
            $score = 0;
            foreach ($terms as $t) {
                if (strpos($text, $t) !== false) $score += 1;
            }
            $pref = (int)($c->document?->preferred_weight ?? 0);
            $score += ($pref > 0) ? min(5, intdiv($pref, 20)) : 0;
            if ($score > 0) {
                $scored[] = ['chunk' => $c, 'score' => $score];
            }
        }
        usort($scored, fn($a,$b) => $b['score'] <=> $a['score']);

        $out = [];
        foreach (array_slice($scored, 0, $limit) as $row) {
            $c = $row['chunk'];
            $out[] = [
                'document_id' => $c->document_id,
                'document_title' => $c->document?->title,
                'chunk_index' => $c->chunk_index,
                'content' => $c->content,
                'content_hash' => $c->content_hash,
                'score' => $row['score'],
                'doc' => [
                    'doc_type' => $c->document?->doc_type,
                    'authority_level' => $c->document?->authority_level,
                    'jurisdiction' => $c->document?->jurisdiction,
                    'is_superseded' => (bool)($c->document?->is_superseded),
                    'preferred_weight' => (int)($c->document?->preferred_weight ?? 0),
                    'coach_override' => $c->document?->coach_override,
                ],
            ];
        }
        return $out;
    }
}
