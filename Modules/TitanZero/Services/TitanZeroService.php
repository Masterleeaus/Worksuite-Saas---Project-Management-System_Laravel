<?php

namespace Modules\TitanZero\Services;

use Modules\AICore\Contracts\AI\ClientInterface as TitanClient;
use Modules\TitanZero\Entities\TitanZeroUsage;
use Modules\TitanCore\Services\UsageCostLogger;

class TitanZeroService
{
    /**
     * Titan Zero service talks to Titan Core on behalf of the module.
     */
    public function __construct(protected TitanClient $client, protected UsageCostLogger $usage)
    {
    }

    /**
     * Generate AI content for a given prompt.
     *
     * The model is selected at Super Admin level only via config('aiassistant.model').
     *
     * @param  string  $prompt
     * @param  string  $language
     * @param  int     $maxTokens
     * @param  float   $temperature
     * @param  int     $maxResults
     * @return array{success: bool, text?: string, tokens?: int|null, message?: string}
     */
    public function generate(
        string $prompt,
        string $language,
        int $maxTokens,
        float $temperature,
        int $maxResults = 1,
        ?int $userId = null,
        ?int $companyId = null,
        ?int $templateId = null
    ): array
    {
        $langText = "Provide response in {$language} language.\n\n ";

        $model = config('aiassistant.model', 'gpt-5-nano');

        try {
            $result = $this->client->chat([
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => $prompt . ' ' . $langText,
                    ],
                ],
                'model'       => $model,
                'temperature' => $temperature,
                'max_tokens'  => $maxTokens,
                'n'           => $maxResults,
            ]);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => __('Text was not generated due to Invalid API Key'),
            ];
        }

        if (is_string($result)) {
            $response = json_decode($result, true);
        } else {
            $response = $result;
        }

        if (! is_array($response) || ! isset($response['choices'])) {
            return [
                'success' => false,
                'message' => __('Text was not generated due to Invalid API Key'),
            ];
        }

        $text = '';
        $counter = 1;

        if (count($response['choices']) > 1) {
            foreach ($response['choices'] as $value) {
                $choiceText = $value['message']['content'] ?? ($value['text'] ?? '');
                $text      .= $counter . '. ' . ltrim($choiceText) . "\r\n\r\n\r\n";
                $counter++;
            }
        } else {
            $choiceText = $response['choices'][0]['message']['content']
                ?? ($response['choices'][0]['text'] ?? '');
            $text = $choiceText;
        }

        $tokens = $response['usage']['completion_tokens'] ?? ($response['usage']['total_tokens'] ?? null);


        // Cost + token telemetry (tenant scoped).
        try {
            $this->usage->logFromOpenAIResponse('chat', $response, [
                'tenant_id' => $companyId, // Worksuite often uses company_id as tenant proxy here
                'user_id' => $userId,
                'agent_slug' => 'titan_zero',
                'provider' => 'openai',
                'model' => $model,
                'template_id' => $templateId,
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        // Log lightweight usage row for reporting/limits.
        try {
            TitanZeroUsage::create([
                'user_id'        => $userId,
                'company_id'     => $companyId,
                'template_id'    => $templateId,
                'tokens_used'    => $tokens ?? 0,
                'requests_count' => 1,
            ]);
        } catch (\Throwable $e) {
            // Failing to log should not break user flow.
        }

        return [
            'success' => true,
            'text'    => $text,
            'tokens'  => $tokens,
        ];
    }
}
