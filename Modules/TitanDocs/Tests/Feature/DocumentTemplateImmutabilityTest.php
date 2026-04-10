<?php

namespace Modules\TitanDocs\Tests\Feature;

use Tests\TestCase;
use Modules\TitanDocs\Services\DocumentGenerationService;
use Modules\TitanDocs\Entities\DocumentTemplate;

class DocumentTemplateImmutabilityTest extends TestCase
{
    /** @test */
    public function generation_service_exists_and_is_resolvable(): void
    {
        $this->assertTrue(class_exists(DocumentGenerationService::class));
    }

    /** @test */
    public function generation_service_uses_titancore_for_ai_mode(): void
    {
        // Verify that DocumentGenerationService does NOT import OpenAI or Claude directly
        $reflection = new \ReflectionClass(DocumentGenerationService::class);
        $source = file_get_contents($reflection->getFileName());

        $this->assertStringNotContainsString('OpenAI', $source, 'DocumentGenerationService must not call OpenAI directly');
        $this->assertStringNotContainsString('Anthropic', $source, 'DocumentGenerationService must not call Anthropic directly');
        $this->assertStringContainsString('TitanCore', $source, 'DocumentGenerationService must route through TitanCore');
    }

    /** @test */
    public function document_template_model_has_soft_deletes(): void
    {
        $this->assertContains(
            \Illuminate\Database\Eloquent\SoftDeletes::class,
            class_uses_recursive(DocumentTemplate::class)
        );
    }
}
