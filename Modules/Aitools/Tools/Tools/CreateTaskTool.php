<?php

namespace Modules\Aitools\Tools\Tools;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Aitools\Tools\Contracts\AiToolInterface;
use Modules\Aitools\Tools\DTO\AitoolsContext;

class CreateTaskTool implements AiToolInterface
{
    public static function name(): string { return 'create_task'; }

    public static function description(): string
    {
        return 'Create a task (draft by default). For MVP, can write if tasks table supports basic columns.';
    }

    public static function schema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string', 'description' => 'Task title/heading.'],
                'description' => ['type' => 'string', 'description' => 'Task details.'],
                'due_date' => ['type' => 'string', 'description' => 'YYYY-MM-DD or ISO datetime (optional).'],
                'assignee_user_id' => ['type' => 'integer', 'description' => 'User ID to assign (optional).'],
                'dry_run' => ['type' => 'boolean', 'description' => 'If true (default), do not write; return a proposal payload.'],
            ],
            'required' => ['title'],
        ];
    }

    public function execute(AitoolsContext $ctx, array $args): array
    {
        $title = trim((string)($args['title'] ?? ''));
        $description = (string)($args['description'] ?? '');
        $due = $args['due_date'] ?? null;
        $assignee = isset($args['assignee_user_id']) ? (int)$args['assignee_user_id'] : null;
        $dryRun = array_key_exists('dry_run', $args) ? (bool)$args['dry_run'] : true;

        $proposal = [
            'title' => $title,
            'description' => $description,
            'due_date' => $due,
            'assignee_user_id' => $assignee,
        ];

        if (!Schema::hasTable('tasks')) {
            return ['success' => false, 'error' => 'Tasks table not found.', 'proposal' => $proposal];
        }

        if ($dryRun) {
            return ['success' => true, 'mode' => 'draft', 'proposal' => $proposal];
        }

        // Best-effort insert: only if common columns exist.
        $data = [];
        $table = 'tasks';

        // title column variants
        if (Schema::hasColumn($table, 'heading')) $data['heading'] = $title;
        elseif (Schema::hasColumn($table, 'title')) $data['title'] = $title;
        elseif (Schema::hasColumn($table, 'name')) $data['name'] = $title;

        if (Schema::hasColumn($table, 'description')) $data['description'] = $description;
        elseif (Schema::hasColumn($table, 'details')) $data['details'] = $description;

        if ($due) {
            if (Schema::hasColumn($table, 'due_date')) $data['due_date'] = $due;
            elseif (Schema::hasColumn($table, 'deadline')) $data['deadline'] = $due;
        }

        if (Schema::hasColumn($table, 'company_id')) $data['company_id'] = $ctx->companyId;
        if (Schema::hasColumn($table, 'user_id')) $data['user_id'] = $ctx->userId;

        if ($assignee !== null) {
            if (Schema::hasColumn($table, 'assigned_to')) $data['assigned_to'] = $assignee;
            elseif (Schema::hasColumn($table, 'assignee_id')) $data['assignee_id'] = $assignee;
        }

        if (Schema::hasColumn($table, 'created_at')) $data['created_at'] = now();
        if (Schema::hasColumn($table, 'updated_at')) $data['updated_at'] = now();

        if (count($data) < 2) {
            return ['success' => false, 'error' => 'Tasks table does not have expected columns for safe insert.', 'proposal' => $proposal];
        }

        $id = DB::table($table)->insertGetId($data);

        return ['success' => true, 'mode' => 'executed', 'task_id' => $id, 'data' => $data];
    }
}
