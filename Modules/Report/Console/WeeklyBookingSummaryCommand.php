<?php

namespace Modules\Report\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * WeeklyBookingSummaryCommand
 *
 * Sends a weekly booking summary email to all company admins.
 * Schedule: every Monday morning.
 *
 * Usage: php artisan report:weekly-booking-summary
 */
class WeeklyBookingSummaryCommand extends Command
{
    protected $signature = 'report:weekly-booking-summary';
    protected $description = 'Email weekly booking summary to admins (runs every Monday)';

    public function handle(): int
    {
        $endDate   = Carbon::now()->subDay()->toDateString();
        $startDate = Carbon::now()->subDays(7)->toDateString();

        if (!DB::getSchemaBuilder()->hasTable('tasks')) {
            $this->warn('tasks table not found — skipping weekly booking summary.');
            return self::SUCCESS;
        }

        $stats = DB::table('tasks')
            ->where('task_type', 'booking')
            ->whereBetween(DB::raw('DATE(due_date)'), [$startDate, $endDate])
            ->select([
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN booking_status='completed' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN booking_status='cancelled' THEN 1 ELSE 0 END) as cancelled"),
                DB::raw("SUM(CASE WHEN booking_status='reclean'   THEN 1 ELSE 0 END) as reclean"),
            ])
            ->first();

        // Retrieve admin emails company by company (multi-tenant safe).
        $admins = DB::table('users')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'admin')
            ->whereNotNull('users.email')
            ->pluck('users.email')
            ->unique();

        foreach ($admins as $email) {
            try {
                Mail::raw(
                    "Weekly Booking Summary ({$startDate} → {$endDate})\n\n"
                    . "Total bookings : {$stats->total}\n"
                    . "Completed      : {$stats->completed}\n"
                    . "Cancelled      : {$stats->cancelled}\n"
                    . "Recleans       : {$stats->reclean}\n",
                    function ($msg) use ($email, $startDate, $endDate) {
                        $msg->to($email)
                            ->subject("Weekly Booking Summary: {$startDate} — {$endDate}");
                    }
                );
            } catch (\Throwable $e) {
                $this->error("Failed to send to {$email}: {$e->getMessage()}");
            }
        }

        $this->info("Weekly booking summary sent to {$admins->count()} admin(s). Period: {$startDate} → {$endDate}.");
        return self::SUCCESS;
    }
}
