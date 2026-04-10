<?php

namespace Modules\Aitools\Services;

use Illuminate\Support\Arr;
use Modules\Aitools\Entities\AiPrompt;
use Modules\Aitools\Entities\AiPromptRun;

class PromptRunner
{
    /**
     * Resolve a prompt and run it through the default chat model.
     *
     * @return array{ok:bool, output:?string, run_id:?int, reason:?string, usage:?array}
     */
    public static function run(array $input): array
    {
        AiResolver::bootstrapFromLegacySetting();

        $namespace = (string) Arr::get($input, 'namespace', 'default');
        $slug = (string) Arr::get($input, 'slug', '');
        $version = (int) Arr::get($input, 'version', 1);
        $locale = (string) Arr::get($input, 'locale', 'en');
        $vars = (array) Arr::get($input, 'vars', []);
        $system = (string) Arr::get($input, 'system', '');
        $temperature = (float) Arr::get($input, 'temperature', 0.3);
        $operation = (string) Arr::get($input, 'operation', 'prompt_run');

        if (!$slug) {
            return ['ok' => false, 'output' => null, 'run_id' => null, 'reason' => 'Missing slug', 'usage' => null];
        }

        $prompt = AiPrompt::query()
            ->withoutGlobalScope(\App\Scopes\CompanyScope::class)
            ->whereNull('company_id')
            ->where('namespace', $namespace)
            ->where('slug', $slug)
            ->where('version', $version)
            ->where('locale', $locale)
            ->first();

        if (!$prompt) {
            return ['ok' => false, 'output' => null, 'run_id' => null, 'reason' => 'Prompt not found', 'usage' => null];
        }

        $rendered = self::render($prompt->prompt_body, $vars);

        $provider = AiResolver::defaultProvider(optional(company())->id);
        $model = AiResolver::defaultModel('chat', optional(company())->id, optional($provider)->id);

        $client = AiRuntime::clientForProvider($provider);

        $messages = [];
        if ($system) {
            $messages[] = ['role' => 'system', 'content' => $system];
        }
        $messages[] = ['role' => 'user', 'content' => $rendered];

        $run = AiPromptRun::create([
            'company_id' => optional(company())->id,
            'user_id' => optional(auth()->user())->id,
            'namespace' => $namespace,
            'slug' => $slug,
            'version' => $version,
            'locale' => $locale,
            'operation' => $operation,
            'status' => 'ok',
            'input_json' => json_encode($input),
            'meta' => [
                'provider' => optional($provider)->driver,
                'model' => optional($model)->name,
            ],
        ]);

        $resp = $client->chat($messages, [
            'model' => optional($model)->name ?: 'gpt-4o-mini',
            'temperature' => $temperature,
        ]);

        if (!($resp['ok'] ?? false)) {
            $run->status = 'error';
            $run->error_message = (string)($resp['reason'] ?? 'AI error');
            $run->save();
            return ['ok' => false, 'output' => null, 'run_id' => $run->id, 'reason' => $run->error_message, 'usage' => null];
        }

        $usage = $resp['usage'] ?? ['prompt_tokens' => 0, 'completion_tokens' => 0, 'total_tokens' => 0];
        $out = (string)($resp['content'] ?? '');

        $run->output_text = $out;
        $run->prompt_tokens = (int)($usage['prompt_tokens'] ?? 0);
        $run->completion_tokens = (int)($usage['completion_tokens'] ?? 0);
        $run->total_tokens = (int)($usage['total_tokens'] ?? 0);
        $run->save();

        // Usage log (Pass 2)
        try {
            AiLogger::logUsage([
                'company_id' => optional(company())->id,
                'user_id' => optional(auth()->user())->id,
                'provider_id' => optional($provider)->id,
                'model_id' => optional($model)->id,
                'operation' => $operation,
                'prompt_tokens' => (int)($usage['prompt_tokens'] ?? 0),
                'completion_tokens' => (int)($usage['completion_tokens'] ?? 0),
                'total_tokens' => (int)($usage['total_tokens'] ?? 0),
                'request_hash' => AiLogger::hash($operation, $rendered),
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        return ['ok' => true, 'output' => $out, 'run_id' => $run->id, 'reason' => null, 'usage' => $usage];
    }

    /**
     * Very simple variable renderer: replaces {{key}}.
     */
    public static function render(string $template, array $vars): string
    {
        foreach ($vars as $k => $v) {
            $template = str_replace('{{' . $k . '}}', (string) $v, $template);
        }
        return $template;
    }
}
