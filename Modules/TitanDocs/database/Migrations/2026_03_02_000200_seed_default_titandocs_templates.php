<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('ai_template_categories') || !Schema::hasTable('ai_template_languages') || !Schema::hasTable('ai_templates')) {
            return;
        }

        // 1) Seed categories (only if empty). IDs are important because controller expects 1..7.
        if (DB::table('ai_template_categories')->count() == 0) {
            DB::table('ai_template_categories')->insert([
                ['id'=>1,'name'=>'Content','status'=>1,'created_by'=>'0','created_at'=>now(),'updated_at'=>now()],
                ['id'=>2,'name'=>'Blog','status'=>1,'created_by'=>'0','created_at'=>now(),'updated_at'=>now()],
                ['id'=>3,'name'=>'Website','status'=>1,'created_by'=>'0','created_at'=>now(),'updated_at'=>now()],
                ['id'=>4,'name'=>'Social Media','status'=>1,'created_by'=>'0','created_at'=>now(),'updated_at'=>now()],
                ['id'=>5,'name'=>'Video','status'=>1,'created_by'=>'0','created_at'=>now(),'updated_at'=>now()],
                ['id'=>6,'name'=>'Email','status'=>1,'created_by'=>'0','created_at'=>now(),'updated_at'=>now()],
                ['id'=>7,'name'=>'Other','status'=>1,'created_by'=>'0','created_at'=>now(),'updated_at'=>now()],
            ]);
        }

        // 2) Seed languages (only if empty)
        if (DB::table('ai_template_languages')->count() == 0) {
            DB::table('ai_template_languages')->insert([
                ['language'=>'English','code'=>'en','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Arabic','code'=>'ar','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Chinese (Simplified)','code'=>'zh','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'French','code'=>'fr','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'German','code'=>'de','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Hindi','code'=>'hi','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Indonesian','code'=>'id','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Italian','code'=>'it','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Japanese','code'=>'ja','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Korean','code'=>'ko','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Portuguese','code'=>'pt','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Russian','code'=>'ru','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Spanish','code'=>'es','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Thai','code'=>'th','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
                ['language'=>'Vietnamese','code'=>'vi','flag'=>null,'status'=>1,'created_at'=>now(),'updated_at'=>now()],
            ]);
        }

        // 3) Seed templates by slug (idempotent: insert missing slugs)
        $templates = [
            // Table-style examples matching your screenshots
            ['name'=>'YouTube Video Script','slug'=>'youtube_video_script','template_code'=>'youtube_video_script','description'=>'Generate a YouTube video script with hook, outline, and CTA.','category_id'=>5,'is_tone'=>1],
            ['name'=>'Blog Post Article','slug'=>'blog_post_article','template_code'=>'blog_post_article','description'=>'Generate an SEO blog post with headings, FAQs, and conclusion.','category_id'=>2,'is_tone'=>1],
            ['name'=>'Social Media Post','slug'=>'social_media_post','template_code'=>'social_media_post','description'=>'Generate multiple social posts tailored to the platform and audience.','category_id'=>4,'is_tone'=>1],
            ['name'=>'Product Description','slug'=>'product_description','template_code'=>'product_description','description'=>'Generate persuasive product descriptions optimized for keywords.','category_id'=>3,'is_tone'=>1],
            ['name'=>'Email Newsletter','slug'=>'email_newsletter','template_code'=>'email_newsletter','description'=>'Generate an email newsletter with subject lines and sections.','category_id'=>6,'is_tone'=>1],
            ['name'=>'Press Release','slug'=>'press_release','template_code'=>'press_release','description'=>'Generate a press release with quote blocks and boilerplate.','category_id'=>1,'is_tone'=>1],
            ['name'=>'Landing Page Copy','slug'=>'landing_page_copy','template_code'=>'landing_page_copy','description'=>'Generate landing page copy: hero, features, proof, CTA.','category_id'=>3,'is_tone'=>1],
            ['name'=>'Technical Documentation','slug'=>'technical_documentation','template_code'=>'technical_documentation','description'=>'Generate technical documentation with steps, examples, and troubleshooting.','category_id'=>7,'is_tone'=>1],

            // Service-business oriented (Titan)
            ['name'=>'SWMS - Cleaning (Chemicals + PPE)','slug'=>'swms_cleaning','template_code'=>'swms_cleaning','description'=>'Generate a Safe Work Method Statement for cleaning jobs.','category_id'=>7,'is_tone'=>1],
            ['name'=>'Risk Assessment - Site Visit','slug'=>'risk_assessment_site_visit','template_code'=>'risk_assessment_site_visit','description'=>'Generate a structured risk assessment for a site visit.','category_id'=>7,'is_tone'=>1],
            ['name'=>'Service Agreement','slug'=>'service_agreement','template_code'=>'service_agreement','description'=>'Generate a client service agreement with scope, pricing, and terms.','category_id'=>7,'is_tone'=>1],
            ['name'=>'Quote / Proposal','slug'=>'quote_proposal','template_code'=>'quote_proposal','description'=>'Generate a quote/proposal with inclusions, exclusions, and next steps.','category_id'=>7,'is_tone'=>1],
            ['name'=>'Invoice Follow-up','slug'=>'invoice_followup','template_code'=>'invoice_followup','description'=>'Generate polite-to-firm invoice follow-up messages.','category_id'=>6,'is_tone'=>1],
        ];

        foreach ($templates as $t) {
            $exists = DB::table('ai_templates')->where('slug', $t['slug'])->exists();
            if ($exists) {
                continue;
            }

            $formFields = json_encode([
                ['name'=>'language','type'=>'select','label'=>'Language','required'=>true],
                ['name'=>'tone','type'=>'select','label'=>'Tone','required'=>false],
                ['name'=>'title','type'=>'text','label'=>'Title / Topic','required'=>true],
                ['name'=>'keyword','type'=>'text','label'=>'Focus Keyword','required'=>false],
                ['name'=>'max_result_length','type'=>'number','label'=>'Max Result Length','required'=>false],
                ['name'=>'results','type'=>'number','label'=>'Number of Results','required'=>false],
                ['name'=>'creativity','type'=>'select','label'=>'Creativity','required'=>false],
                ['name'=>'description','type'=>'textarea','label'=>'Extra Instructions','required'=>false],
            ]);

            DB::table('ai_templates')->insert([
                'name' => $t['name'],
                'icon' => null,
                'description' => $t['description'],
                'template_code' => $t['template_code'],
                'status' => 1,
                'professional' => 1,
                'slug' => $t['slug'],
                'category_id' => (string)$t['category_id'],
                'type' => '1',
                'form_fields' => $formFields,
                'is_tone' => (int)$t['is_tone'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // no-op
    }
};
