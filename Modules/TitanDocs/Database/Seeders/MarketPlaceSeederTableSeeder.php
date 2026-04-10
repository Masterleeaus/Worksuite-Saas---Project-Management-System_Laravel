<?php

namespace Modules\TitanDocs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LandingPage\Entities\MarketplacePageSetting;

class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'AI Document';
        $data['product_main_description'] = '<p>AI Document harnesses the power of OpenAI AI technology to generate unique content, images, and code while ensuring it is free from plagiarism. It enhances and creates captivating content in multiple languages. Users can effortlessly generate images by providing descriptions, and the Speech to Text feature facilitates real-time conversations. The admin panel empowers administrators to customize OpenAI model access for different user groups. Unlock the potential of your business with AI Document by crafting personalized subscription plans that align with your objectives. Embrace this flexible SaaS solution and propel your business toward profitability.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'AI WRITING ASSISTANT AND CONTENT GENERATOR TOOL';
        $data['dedicated_theme_description'] = '<p>AI Document can quickly produce high-quality content, saving significant time compared to manual writing processes.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Increased Productivity","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Time-Saving","description":"With AI Document ability to generate content at scale, businesses can produce a large volume of articles, blog posts, social media content, and more, increasing productivity and content output."},"2":{"title":"Cost-Effective","description":"AI Document eliminates the need to hire or outsource content writers, reducing costs associated with content creation."},"3":{"title":"Consistency","description":"AI Document ensures consistency in tone, style, and brand voice throughout the generated content, maintaining a cohesive brand image."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Multilingual Capabilities & Plagiarism Prevention","dedicated_theme_section_description":" <p>AI Document can create content in multiple languages, facilitating global reach and localization efforts.AI Document can detect and avoid plagiarism, generating original and unique content that meets ethical standards.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":null,"description":null},"3":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"AIImage"},{"screenshots":"","screenshots_heading":"AIImage"},{"screenshots":"","screenshots_heading":"AIImage"},{"screenshots":"","screenshots_heading":"AIImage"},{"screenshots":"","screenshots_heading":"AIImage"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'TitanDocs')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'TitanDocs'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
