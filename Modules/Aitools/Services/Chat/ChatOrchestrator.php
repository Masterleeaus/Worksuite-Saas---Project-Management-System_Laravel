<?php

namespace Modules\Aitools\Services\Chat;

use Illuminate\Support\Facades\DB;
use Modules\Aitools\Entities\AiToolsConversation;
use Modules\Aitools\Entities\AiToolsMessage;
use Modules\Aitools\Entities\AiToolsToolCall;
use Modules\Aitools\Tools\ToolRegistry;
use Modules\Aitools\Tools\DTO\AitoolsContext;

/**
 * Pass 2: Minimal chat engine with conversation persistence and
 * a pragmatic tool routing strategy (MVP-safe).
 *
 * Design goals:
 * - Never crash if optional tables/columns differ (best-effort).
 * - Persist messages + tool calls for later improvements.
 * - Use Titan Zero LLM for natural language responses.
 */
class ChatOrchestrator
{
    public function __construct(
        protected ToolRegistry $registry,
        protected ToolHeuristics $heuristics
    ) {}

    /**
     * @return array{success:bool, conversation_id?:int, message?:string, reply?:string, tools?:array}
     */
    public function handle(string $userText, ?int $conversationId = null, array $meta = []): array
    {
        $userText = trim($userText);
        if ($userText === '') {
            return ['success' => false, 'message' => __('Empty message.')];
        }

        $userId = auth()->id();
        $companyId = $this->resolveCompanyId();

        $conversation = $this->getOrCreateConversation($conversationId, $userText, $userId, $companyId);

        // Persist user message
        $userMsg = AiToolsMessage::create([
            'conversation_id' => $conversation->id,
            'company_id'      => $companyId,
            'user_id'         => $userId,
            'role'            => 'user',
            'content'         => $userText,
            'meta'            => $meta ?: null,
        ]);

        $ctx = new AitoolsContext(
            companyId: $companyId,
            userId: $userId,
            timezone: $this->resolveTimezone(),
            locale: app()->getLocale() ?: 'en'
        );

        // 1) Optional: explicit tool invocation syntax
        //    /tool tool_name {"arg":"value"}
        $explicit = $this->heuristics->parseExplicitToolCall($userText);

        $toolRuns = [];
        $toolDataForPrompt = '';

        if ($explicit) {
            $toolRuns[] = $this->runTool($explicit['name'], $ctx, $explicit['args'], $conversation->id);
        } else {
            // 2) Heuristic tool routing (MVP): detect intent and run one tool
            $auto = $this->heuristics->pickToolForMessage($userText);
            if ($auto) {
                $toolRuns[] = $this->runTool($auto['name'], $ctx, $auto['args'], $conversation->id);
            }
        }

        if (!empty($toolRuns)) {
            $toolDataForPrompt = "\n\nDATA (JSON):\n" . json_encode($toolRuns, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Build a conservative system prompt
        $prompt = $this->buildPrompt($userText, $conversation->id, $toolDataForPrompt);

        $reply = $this->callTitanZero($prompt, $userId, $companyId);

        if (!($reply['success'] ?? false)) {
            return ['success' => false, 'message' => $reply['message'] ?? __('Unable to reply right now.')];
        }

        $replyText = trim((string)($reply['text'] ?? ''));

        // Persist assistant message
        AiToolsMessage::create([
            'conversation_id' => $conversation->id,
            'company_id'      => $companyId,
            'user_id'         => $userId,
            'role'            => 'assistant',
            'content'         => $replyText,
            'meta'            => [
                'tokens' => $reply['tokens'] ?? null,
                'tool_runs' => $toolRuns ?: null,
            ],
        ]);

        return [
            'success' => true,
            'conversation_id' => $conversation->id,
            'reply' => $replyText,
            'tools' => $toolRuns,
        ];
    }

    protected function getOrCreateConversation(?int $conversationId, string $firstText, ?int $userId, ?int $companyId): AiToolsConversation
    {
        if ($conversationId) {
            $found = AiToolsConversation::query()->where('id', $conversationId);
            if ($companyId !== null) {
                $found->where(function ($q) use ($companyId) {
                    $q->whereNull('company_id')->orWhere('company_id', $companyId);
                });
            }
            $conv = $found->first();
            if ($conv) {
                return $conv;
            }
        }

        $title = mb_substr($firstText, 0, 80);

        return AiToolsConversation::create([
            'company_id' => $companyId,
            'user_id'    => $userId,
            'title'      => $title,
            'channel'    => 'widget',
            'status'     => 'open',
            'meta'       => null,
        ]);
    }

    protected function runTool(string $name, AitoolsContext $ctx, array $args, int $conversationId): array
    {
        $start = microtime(true);
        $status = 'ok';
        $result = null;
        $err = null;

        try {
            $tool = $this->registry->get($name);
            $result = $tool->execute($ctx, $args);
        } catch (\Throwable $e) {
            $status = 'error';
            $err = $e->getMessage();
            $result = ['error' => true, 'message' => $err];
        }

        $duration = (int) round((microtime(true) - $start) * 1000);

        try {
            AiToolsToolCall::create([
                'conversation_id' => $conversationId,
                'company_id'      => $ctx->companyId,
                'user_id'         => $ctx->userId,
                'tool_name'       => $name,
                'args'            => $args,
                'result'          => $result,
                'status'          => $status,
                'duration_ms'     => $duration,
            ]);
        } catch (\Throwable $e) {
            // ignore logging failures
        }

        return [
            'tool' => $name,
            'status' => $status,
            'duration_ms' => $duration,
            'args' => $args,
            'result' => $result,
        ];
    }

    protected function buildPrompt(string $userText, int $conversationId, string $toolDataForPrompt = ''): string
    {
        // Provide a small amount of conversational context (last ~8 messages)
        $history = '';
        try {
            $msgs = AiToolsMessage::query()
                ->where('conversation_id', $conversationId)
                ->orderByDesc('id')
                ->limit(8)
                ->get()
                ->reverse();

            foreach ($msgs as $m) {
                $role = strtoupper((string)$m->role);
                $content = trim((string)$m->content);
                $history .= "{$role}: {$content}\n";
            }
        } catch (\Throwable $e) {
            $history = '';
        }

        $system = "You are Titan Zero running inside Worksuite SaaS as the Aitools assistant.\n"
            . "Be concise, practical, and business-focused.\n"
            . "If DATA(JSON) is provided, use it as the source of truth.\n"
            . "If you cannot answer from available data, say what you need.\n";

        return $system . "\nCONTEXT:\n" . $history . "\nUSER: " . $userText . $toolDataForPrompt;
    }

    protected function callTitanZero(string $prompt, ?int $userId, ?int $companyId): array
    {
        try {
            /** @var \Modules\TitanZero\Services\TitanZeroService $tz */
            $tz = app(\Modules\TitanZero\Services\TitanZeroService::class);

            return $tz->generate(
                $prompt,
                'English',
                800,
                0.3,
                1,
                $userId,
                $companyId,
                null
            );
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => __('Unable to contact AI right now.')];
        }
    }

    protected function resolveCompanyId(): ?int
    {
        try {
            if (function_exists('company') && company()) {
                return (int) company()->id;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            $u = auth()->user();
            if ($u && isset($u->company_id)) {
                return (int) $u->company_id;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return null;
    }

    protected function resolveTimezone(): string
    {
        try {
            $u = auth()->user();
            if ($u && isset($u->timezone) && $u->timezone) {
                return (string) $u->timezone;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return config('app.timezone', 'UTC');
    }
}
