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

        $tools = (array) config('promotionmanagement_ai.tools', []);
        $now = now();

        foreach ($tools as $tool) {
            if (!is_array($tool) || empty($tool['tool_name'])) {
                continue;
            }

            DB::table('ai_tools_registry')->updateOrInsert(
                ['tool_name' => $tool['tool_name']],
                [
                    'title' => $tool['title'] ?? null,
                    'description' => $tool['description'] ?? null,
                    'risk_level' => $tool['risk_level'] ?? 'low',
                    'is_enabled' => 1,
                    'input_schema' => isset($tool['input_schema']) ? json_encode($tool['input_schema'], JSON_UNESCAPED_UNICODE) : null,
                    'meta' => isset($tool['meta']) ? json_encode($tool['meta'], JSON_UNESCAPED_UNICODE) : null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('ai_tools_registry')) {
            return;
        }

        $tools = (array) config('promotionmanagement_ai.tools', []);
        $toolNames = array_values(array_filter(array_map(
            fn ($tool) => is_array($tool) ? ($tool['tool_name'] ?? null) : null,
            $tools
        )));

        if (!empty($toolNames)) {
            DB::table('ai_tools_registry')->whereIn('tool_name', $toolNames)->delete();
        }
    }
};
