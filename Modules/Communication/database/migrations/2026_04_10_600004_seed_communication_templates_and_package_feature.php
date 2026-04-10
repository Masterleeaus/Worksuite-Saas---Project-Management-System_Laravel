<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Seed pre-built communication templates for CleanSmartOS cleaning business
 * and register 'communication' as a subscription package feature.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Seed subscription package feature -----------------------------------------------
        if (Schema::hasTable('subscription_packages') && Schema::hasTable('subscription_package_features')) {
            $packages = DB::table('subscription_packages')->pluck('id');

            foreach ($packages as $packageId) {
                $exists = DB::table('subscription_package_features')
                    ->where('subscription_package_id', $packageId)
                    ->where('feature', 'communication')
                    ->exists();

                if (! $exists) {
                    DB::table('subscription_package_features')->insert([
                        'id'                      => (string) Str::uuid(),
                        'subscription_package_id' => $packageId,
                        'feature'                 => 'communication',
                        'company_id'              => null,
                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ]);
                }
            }
        }

        // Seed pre-built templates ---------------------------------------------------------
        if (! Schema::hasTable('communication_templates')) {
            return;
        }

        $templates = [
            [
                'slug'      => 'booking_confirmation',
                'name'      => 'Booking Confirmation',
                'type'      => 'email',
                'subject'   => 'Your Booking is Confirmed — {booking_date}',
                'body'      => "Hi {customer_name},\n\nYour cleaning appointment has been confirmed for {booking_date}.\n\nCleaner: {cleaner_name}\nService: {service_name}\nAddress: {address}\n\nThank you for choosing us!\n\n{company_name}",
                'variables' => json_encode(['customer_name', 'booking_date', 'cleaner_name', 'service_name', 'address', 'company_name']),
            ],
            [
                'slug'      => 'booking_reminder',
                'name'      => 'Booking Reminder',
                'type'      => 'email',
                'subject'   => 'Reminder: Cleaning Tomorrow — {booking_date}',
                'body'      => "Hi {customer_name},\n\nJust a friendly reminder that your cleaning is scheduled for tomorrow, {booking_date}.\n\nCleaner: {cleaner_name}\n\nSee you then!\n{company_name}",
                'variables' => json_encode(['customer_name', 'booking_date', 'cleaner_name', 'company_name']),
            ],
            [
                'slug'      => 'thank_you',
                'name'      => 'Thank You After Service',
                'type'      => 'email',
                'subject'   => 'Thank You for Choosing {company_name}!',
                'body'      => "Hi {customer_name},\n\nThank you for using our cleaning service on {booking_date}. We hope your home is sparkling!\n\nWe would love to hear your feedback. Please take a moment to leave us a review.\n\nWarm regards,\n{company_name}",
                'variables' => json_encode(['customer_name', 'booking_date', 'company_name']),
            ],
            [
                'slug'      => 're_engagement',
                'name'      => 'Re-Engagement',
                'type'      => 'email',
                'subject'   => 'We Miss You, {customer_name}!',
                'body'      => "Hi {customer_name},\n\nIt's been a while since your last cleaning with us. We'd love to have you back!\n\nBook your next appointment today and enjoy a fresh, clean home.\n\n{company_name}",
                'variables' => json_encode(['customer_name', 'company_name']),
            ],
            [
                'slug'      => 'sms_booking_confirmation',
                'name'      => 'SMS: Booking Confirmation',
                'type'      => 'sms',
                'subject'   => null,
                'body'      => "Hi {customer_name}, your cleaning on {booking_date} is confirmed. Cleaner: {cleaner_name}. - {company_name}",
                'variables' => json_encode(['customer_name', 'booking_date', 'cleaner_name', 'company_name']),
            ],
            [
                'slug'      => 'sms_booking_reminder',
                'name'      => 'SMS: Booking Reminder',
                'type'      => 'sms',
                'subject'   => null,
                'body'      => "Reminder: Your cleaning is tomorrow {booking_date}. See you then! - {company_name}",
                'variables' => json_encode(['booking_date', 'company_name']),
            ],
        ];

        foreach ($templates as $template) {
            $exists = DB::table('communication_templates')
                ->where('slug', $template['slug'])
                ->exists();

            if (! $exists) {
                DB::table('communication_templates')->insert(array_merge($template, [
                    'company_id' => null,
                    'status'     => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subscription_package_features')) {
            DB::table('subscription_package_features')
                ->where('feature', 'communication')
                ->delete();
        }

        if (Schema::hasTable('communication_templates')) {
            DB::table('communication_templates')
                ->whereNotNull('slug')
                ->whereIn('slug', [
                    'booking_confirmation', 'booking_reminder', 'thank_you',
                    're_engagement', 'sms_booking_confirmation', 'sms_booking_reminder',
                ])
                ->delete();
        }
    }
};
