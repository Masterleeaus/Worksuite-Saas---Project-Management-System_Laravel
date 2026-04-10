<?php

namespace Modules\TitanDocs\Services;

use Modules\TitanDocs\Entities\DocumentTemplate;

/**
 * DocumentGenerationService
 *
 * Handles document generation from templates.
 * All AI-assisted generation is routed through TitanCore — never direct OpenAI/Claude calls.
 */
class DocumentGenerationService
{
    /**
     * Generate a document from a template using provided merge field values.
     * For AI generation, delegates to TitanCore::generateDocument().
     *
     * @param  DocumentTemplate    $template
     * @param  array<string,mixed> $context   Merge field values (client, employee, booking data etc.)
     * @param  string              $mode      'manual' | 'template' | 'ai'
     * @return string  Rendered HTML content
     */
    public function generate(DocumentTemplate $template, array $context, string $mode = 'template'): string
    {
        if ($mode === 'ai') {
            return $this->generateViaAi($template, $context);
        }

        return $template->render($context);
    }

    /**
     * Route AI generation through TitanCore — never call AI providers directly.
     */
    private function generateViaAi(DocumentTemplate $template, array $context): string
    {
        // AI calls MUST route through TitanCore
        if (!class_exists(\Modules\TitanCore\Services\TitanCoreRouter::class)) {
            // TitanCore not installed — fall back to template render
            return $template->render($context);
        }

        try {
            /** @var \Modules\TitanCore\Services\TitanCoreRouter $router */
            $router = app(\Modules\TitanCore\Services\TitanCoreRouter::class);

            $result = $router->route([
                'task'        => 'generate_document',
                'type'        => $template->document_type,
                'template_id' => $template->id,
                'context'     => $context,
            ]);

            return data_get($result, 'content', $template->render($context));
        } catch (\Throwable $e) {
            // Graceful fallback to template rendering
            return $template->render($context);
        }
    }
}
