<?php

namespace Modules\Report\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * DailyCleanerScheduleCommand
 *
 * Emails today's cleaner schedule to each cleaner in the morning.
 * Schedule: daily at 06:00.
 *
 * Usage: php artisan report:daily-cleaner-schedule
 */
class DailyCleanerScheduleCommand extends Command
{
    protected $signature = 'report:daily-cleaner-schedule';
    protected $description = 'Email today\'s booking schedule to each assigned cleaner';

    public function handle(): int
    {
        $today = Carbon::now()->toDateString();

        if (!DB::getSchemaBuilder()->hasTable('tasks')) {
            $this->warn('tasks table not found — skipping daily cleaner schedule.');
            return self::SUCCESS;
        }

        // Fetch today's bookings with their assigned cleaner (task_users.is_owner = 0)
        $bookings = DB::table('tasks as t')
            ->join('task_users as tu', 'tu.task_id', '=', 't.id')
            ->join('users as u', 'u.id', '=', 'tu.user_id')
            ->where('t.task_type', 'booking')
            ->whereRaw('DATE(t.due_date) = ?', [$today])
            ->where('tu.is_owner', 0)
            ->whereNotNull('u.email')
            ->select([
                'u.id as cleaner_id',
                'u.name as cleaner_name',
                'u.email as cleaner_email',
                't.id as booking_id',
                't.heading as booking_heading',
                't.due_date',
                't.booking_status',
                't.service_address',
                't.service_type',
            ])
            ->orderBy('u.name')
            ->orderBy('t.due_date')
            ->get();

        if ($bookings->isEmpty()) {
            $this->info("No bookings scheduled for {$today}.");
            return self::SUCCESS;
        }

        // Group by cleaner and send one email per cleaner
        $grouped = $bookings->groupBy('cleaner_id');
        $sent    = 0;

        foreach ($grouped as $cleanerId => $jobs) {
            $cleaner = $jobs->first();
            $lines   = ["Hi {$cleaner->cleaner_name},\n", "Your schedule for today ({$today}):\n"];

            foreach ($jobs as $job) {
                $lines[] = "  • [{$job->booking_status}] {$job->booking_heading}"
                    . ($job->service_address ? " @ {$job->service_address}" : '')
                    . " ({$job->service_type})";
            }

            $lines[] = "\nPlease log in to the portal for full details.";

            try {
                Mail::raw(implode("\n", $lines), function ($msg) use ($cleaner, $today) {
                    $msg->to($cleaner->cleaner_email)
                        ->subject("Your Cleaning Schedule — {$today}");
                });
                $sent++;
            } catch (\Throwable $e) {
                $this->error("Failed to send to {$cleaner->cleaner_email}: {$e->getMessage()}");
            }
        }

        $this->info("Daily schedule sent to {$sent} cleaner(s) for {$today}.");
        return self::SUCCESS;
    }
}
