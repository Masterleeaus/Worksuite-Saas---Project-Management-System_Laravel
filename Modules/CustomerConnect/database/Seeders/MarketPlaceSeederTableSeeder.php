<?php

namespace Modules\CustomerConnect\Database\Seeders;

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
        $data['product_main_heading'] = 'Customer Connect';
        $data['product_main_description'] = '<p>Instead of manually sending individual emails to each customer,vendor,and employee newsletters enable you to automate the process. You set up the criteria and content templates, and the system handles the distribution, saving you time and effort.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Customer Connect';
        $data['dedicated_theme_description'] = '<p>Applying filters helps narrow down the recipients to those who meet specific criteria, ensuring that the right message reaches the right audience.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Some Benefits of Using Customer Connect","dedicated_theme_section_description":"<p>Customer Connects allow you to segment your audience based on specific criteria, such as invoice status and due amounts. This segmentation ensures that you are sending relevant information to the right people, increasing the likelihood of engagement and response. Customer Connect systems automate the process of sending emails to the filtered recipients, saving time and effort.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":"null","description":null},"3":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"What is the use of History?","dedicated_theme_section_description":" <p>Customer Connects provide a history of email statuses, allowing you to see if emails were successfully sent or if there were any issues.Using the history of the newsletter, you can also see how many people have sent the mails and to which time.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":null,"description":null},"3":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Customer Connect"},{"screenshots":"","screenshots_heading":"Customer Connect"},{"screenshots":"","screenshots_heading":"Customer Connect"},{"screenshots":"","screenshots_heading":"Customer Connect"},{"screenshots":"","screenshots_heading":"Customer Connect"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Customer Connect')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Customer Connect'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
