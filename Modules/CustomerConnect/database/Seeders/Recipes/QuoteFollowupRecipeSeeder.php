<?php

namespace Modules\CustomerConnect\Database\Seeders\Recipes;

use Illuminate\Database\Seeder;
use Modules\CustomerConnect\Services\Recipes\RecipeInstaller;

class QuoteFollowupRecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Intentionally no-op in generic seeder. Install per-tenant via UI/controller later.
    }

    public static function recipe(): array
    {
        return [
            'key' => 'quote_followup',
            'name' => 'Quote Follow-up (Tradies)',
            'steps' => [
                ['name' => 'Follow-up SMS', 'channel' => 'sms', 'delay_minutes' => 60*24*2, 'content' => "Hi {{name}}, just checking in on your quote. Happy to answer any questions."],
                ['name' => 'Follow-up Email', 'channel' => 'email', 'delay_minutes' => 60*24*5, 'content' => "Hi {{name}},\n\nFollowing up on the quote we sent through. If you'd like to proceed, reply to this email or book a time.\n\nThanks,\n{{company}}"],
            ],
        ];
    }
}
