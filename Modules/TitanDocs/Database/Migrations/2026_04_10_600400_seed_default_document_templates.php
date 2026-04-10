<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('document_templates')) {
            return;
        }

        $templates = [
            [
                'name' => 'Employment Letter',
                'template_type' => 'employee',
                'document_type' => 'employment_letter',
                'html_content' => '<h2>Employment Letter</h2><p>Dear {{employee_name}},</p><p>We are pleased to confirm your employment as <strong>{{job_title}}</strong> at {{company_name}}, commencing {{start_date}}.</p><p>Your annual salary will be {{salary}} payable {{pay_frequency}}.</p><p>Yours sincerely,<br>{{hr_manager_name}}<br>{{company_name}}</p>',
                'required_fields' => json_encode(['employee_name','job_title','company_name','start_date','salary','pay_frequency','hr_manager_name']),
                'is_active' => true,
                'is_global' => true,
                'company_id' => null,
                'created_by' => 1,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Service Contract',
                'template_type' => 'client',
                'document_type' => 'service_contract',
                'html_content' => '<h2>Service Agreement</h2><p>This Service Agreement is entered into between <strong>{{company_name}}</strong> ("Service Provider") and <strong>{{client_name}}</strong> ("Client") on {{contract_date}}.</p><h3>Services</h3><p>{{services_description}}</p><h3>Payment Terms</h3><p>The Client agrees to pay {{amount}} {{payment_terms}}.</p><p>Signed: ________________________<br>{{client_name}}</p>',
                'required_fields' => json_encode(['company_name','client_name','contract_date','services_description','amount','payment_terms']),
                'is_active' => true,
                'is_global' => true,
                'company_id' => null,
                'created_by' => 1,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Non-Disclosure Agreement',
                'template_type' => 'client',
                'document_type' => 'nda',
                'html_content' => '<h2>Non-Disclosure Agreement</h2><p>This NDA is made between <strong>{{company_name}}</strong> and <strong>{{recipient_name}}</strong> on {{date}}.</p><h3>Confidential Information</h3><p>Both parties agree to keep all confidential information strictly private and not to disclose it to third parties.</p><h3>Duration</h3><p>This agreement remains in effect for {{duration}} from the date signed.</p>',
                'required_fields' => json_encode(['company_name','recipient_name','date','duration']),
                'is_active' => true,
                'is_global' => true,
                'company_id' => null,
                'created_by' => 1,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Warning Letter',
                'template_type' => 'employee',
                'document_type' => 'warning_letter',
                'html_content' => '<h2>Warning Letter</h2><p>Dear {{employee_name}},</p><p>This letter serves as a formal warning regarding {{issue_description}}.</p><p>This incident occurred on {{incident_date}}. This behavior is a violation of company policy: {{policy_reference}}.</p><p>If this behavior continues, further disciplinary action may be taken, up to and including termination of employment.</p><p>Yours sincerely,<br>{{manager_name}}<br>{{company_name}}</p>',
                'required_fields' => json_encode(['employee_name','issue_description','incident_date','policy_reference','manager_name','company_name']),
                'is_active' => true,
                'is_global' => true,
                'company_id' => null,
                'created_by' => 1,
                'is_approved' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($templates as $template) {
            $exists = DB::table('document_templates')
                ->where('document_type', $template['document_type'])
                ->where('is_global', true)
                ->exists();
            if (!$exists) {
                DB::table('document_templates')->insert($template);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('document_templates')) {
            DB::table('document_templates')
                ->where('is_global', true)
                ->whereIn('document_type', ['employment_letter','service_contract','nda','warning_letter'])
                ->delete();
        }
    }
};
