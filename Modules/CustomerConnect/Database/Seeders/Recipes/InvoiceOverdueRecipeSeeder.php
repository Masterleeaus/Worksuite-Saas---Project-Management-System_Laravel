<?php

namespace Modules\CustomerConnect\Database\Seeders\Recipes;

use Illuminate\Database\Seeder;

class InvoiceOverdueRecipeSeeder extends Seeder
{
    public function run(): void
    {
        // no-op
    }

    public static function recipe(): array
    {
        return [
            'key' => 'invoice_overdue',
            'name' => 'Invoice Overdue (Tradies)',
            'steps' => [
                ['name' => 'Reminder SMS', 'channel' => 'sms', 'delay_minutes' => 0, 'content' => "Hi {{name}}, friendly reminder your invoice is overdue. If you need anything, reply here."],
                ['name' => 'Reminder WhatsApp', 'channel' => 'whatsapp', 'delay_minutes' => 60*24*3, 'content' => "Hi {{name}}, just checking in — invoice is still outstanding. Happy to help if needed."],
                ['name' => 'Final Telegram (opt-in)', 'channel' => 'telegram', 'delay_minutes' => 60*24*7, 'content' => "Hi {{name}}, final reminder — invoice is overdue. Please let us know when payment is scheduled."],
            ],
        ];
    }
}
