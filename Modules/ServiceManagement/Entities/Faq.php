<?php

namespace Modules\ServiceManagement\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\ServiceManagement\Traits\CompanyScoped;

class Faq extends Model
{
    use CompanyScoped;
    use HasFactory;
    use HasUuid;

    protected $casts = [
        'is_active' => 'integer',
        'tags' => 'array',
    ];

    protected $fillable = [
        'title',
        'question',
        'answer',
        'service_id',
        'company_id',
        'visibility',
        'source_type',
        'tags',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function scopeOfStatus($query, $status)
    {
        return $query->where('is_active', $status);
    }

    protected static function booted(): void
    {
        // Keep TitanAI knowledge-base index in sync (RAG-friendly).
        static::saved(function (self $faq) {
            $faq->syncToAiKnowledgeBase();
        });

        static::deleted(function (self $faq) {
            $faq->deleteFromAiKnowledgeBase();
        });
    }

    public function syncToAiKnowledgeBase(): void
    {
        // Avoid hard dependency: only sync if the KB table exists.
        if (!Schema::hasTable('ai_kb_documents')) {
            return;
        }

        $title = $this->title ?: Str::limit(strip_tags((string) $this->question), 120, '…');

        $content = trim(
            "Q: " . trim((string) $this->question) . "\n\n" .
            "A: " . trim((string) $this->answer)
        );

        $hash = hash('sha256', $content);

        // Upsert by (source_table, source_id, company_id). company_id nullable is allowed (global/shared docs).
        $existing = DB::table('ai_kb_documents')
            ->where('source_table', 'faqs')
            ->where('source_id', $this->id)
            ->where(function ($q) {
                // Match null company_id safely
                if (is_null($this->company_id)) {
                    $q->whereNull('company_id');
                } else {
                    $q->where('company_id', $this->company_id);
                }
            })
            ->first();

        $now = now();

        $payload = [
            'company_id' => $this->company_id,
            'source_table' => 'faqs',
            'source_id' => $this->id,
            'title' => $title,
            'content' => $content,
            'content_hash' => $hash,
            // Embedding fields intentionally left blank (handled by TitanAI indexing worker).
            'status' => 'pending',
            'updated_at' => $now,
        ];

        if ($existing) {
            // Only reset status if content changed (so embedding workers can be efficient).
            if (($existing->content_hash ?? null) !== $hash) {
                $payload['status'] = 'pending';
                $payload['last_indexed_at'] = null;
            } else {
                // Keep existing status/index time
                unset($payload['status']);
            }

            DB::table('ai_kb_documents')->where('id', $existing->id)->update($payload);
        } else {
            $payload['id'] = (string) Str::uuid();
            $payload['created_at'] = $now;

            DB::table('ai_kb_documents')->insert($payload);
        }
    }

    public function deleteFromAiKnowledgeBase(): void
    {
        if (!Schema::hasTable('ai_kb_documents')) {
            return;
        }

        DB::table('ai_kb_documents')
            ->where('source_table', 'faqs')
            ->where('source_id', $this->id)
            ->when(is_null($this->company_id), fn($q) => $q->whereNull('company_id'), fn($q) => $q->where('company_id', $this->company_id))
            ->delete();
    }

    protected static function newFactory()
    {
        return \Modules\ServiceManagement\Database\factories\FaqFactory::new();
    }
}