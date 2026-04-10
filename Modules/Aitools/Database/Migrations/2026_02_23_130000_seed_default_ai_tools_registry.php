<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ai_tools_registry')) {
            return;
        }

        // Idempotent seed of built-in tools.
        $now = now();
        $rows = [
            [
                'tool_name' => 'kb_search',
                'title' => 'KB Search',
                'description' => 'Semantic search over Knowledge Base chunks',
                'risk_level' => 'low',
                'is_enabled' => 1,
                'input_schema' => json_encode(['required' => ['query']]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tool_name' => 'summarise_text',
                'title' => 'Summarise Text',
                'description' => 'Summarise text into a concise summary',
                'risk_level' => 'low',
                'is_enabled' => 1,
                'input_schema' => json_encode(['required' => ['text']]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tool_name' => 'classify_intent',
                'title' => 'Classify Intent',
                'description' => 'Classify text into one of the provided labels',
                'risk_level' => 'low',
                'is_enabled' => 1,
                'input_schema' => json_encode(['required' => ['text']]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tool_name' => 'extract_json',
                'title' => 'Extract JSON',
                'description' => 'Extract structured JSON from free text',
                'risk_level' => 'low',
                'is_enabled' => 1,
                'input_schema' => json_encode(['required' => ['text']]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'tool_name' => 'rewrite_with_tone',
                'title' => 'Rewrite With Tone',
                'description' => 'Rewrite text with a specified tone and audience',
                'risk_level' => 'low',
                'is_enabled' => 1,
                'input_schema' => json_encode(['required' => ['text']]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('ai_tools_registry')->updateOrInsert(
                ['tool_name' => $row['tool_name']],
                [
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'risk_level' => $row['risk_level'],
                    'is_enabled' => $row['is_enabled'],
                    'input_schema' => $row['input_schema'],
                    'updated_at' => $row['updated_at'],
                    // Don't overwrite created_at if already present.
                    'created_at' => DB::raw('COALESCE(created_at, '.DB::getPdo()->quote($row['created_at']).')'),
                ]
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('ai_tools_registry')) {
            return;
        }
        DB::table('ai_tools_registry')->whereIn('tool_name', [
            'kb_search', 'summarise_text', 'classify_intent', 'extract_json', 'rewrite_with_tone'
        ])->delete();
    }
};
