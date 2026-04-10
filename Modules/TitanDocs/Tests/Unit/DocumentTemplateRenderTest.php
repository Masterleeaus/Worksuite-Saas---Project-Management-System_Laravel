<?php

namespace Modules\TitanDocs\Tests\Unit;

use Tests\TestCase;
use Modules\TitanDocs\Entities\DocumentTemplate;

class DocumentTemplateRenderTest extends TestCase
{
    /** @test */
    public function merge_fields_are_replaced_in_rendered_output(): void
    {
        $template = new DocumentTemplate([
            'name'          => 'Test Template',
            'template_type' => 'employee',
            'document_type' => 'employment_letter',
            'html_content'  => '<p>Dear {{employee_name}}, your role is {{job_title}}.</p>',
            'required_fields' => ['employee_name', 'job_title'],
            'is_active'     => true,
            'is_global'     => false,
            'created_by'    => 1,
        ]);

        $rendered = $template->render([
            'employee_name' => 'Jane Smith',
            'job_title'     => 'Senior Cleaner',
        ]);

        $this->assertStringContainsString('Jane Smith', $rendered);
        $this->assertStringContainsString('Senior Cleaner', $rendered);
        $this->assertStringNotContainsString('{{employee_name}}', $rendered);
        $this->assertStringNotContainsString('{{job_title}}', $rendered);
    }

    /** @test */
    public function merge_fields_sanitise_html_to_prevent_injection(): void
    {
        $template = new DocumentTemplate([
            'name'          => 'XSS Test Template',
            'template_type' => 'employee',
            'document_type' => 'generic',
            'html_content'  => '<p>Hello {{name}}</p>',
            'required_fields' => ['name'],
            'is_active'     => true,
            'is_global'     => false,
            'created_by'    => 1,
        ]);

        $rendered = $template->render([
            'name' => '<script>alert("xss")</script>',
        ]);

        // Script tags must be stripped / escaped — not executed as HTML
        $this->assertStringNotContainsString('<script>', $rendered);
    }

    /** @test */
    public function unmatched_placeholders_remain_in_output(): void
    {
        $template = new DocumentTemplate([
            'name'          => 'Partial Template',
            'template_type' => 'client',
            'document_type' => 'service_contract',
            'html_content'  => '<p>Hello {{client_name}}, amount is {{amount}}.</p>',
            'required_fields' => ['client_name', 'amount'],
            'is_active'     => true,
            'is_global'     => false,
            'created_by'    => 1,
        ]);

        // Only provide one of the two required fields
        $rendered = $template->render(['client_name' => 'Acme Corp']);

        $this->assertStringContainsString('Acme Corp', $rendered);
        $this->assertStringContainsString('{{amount}}', $rendered);
    }
}
