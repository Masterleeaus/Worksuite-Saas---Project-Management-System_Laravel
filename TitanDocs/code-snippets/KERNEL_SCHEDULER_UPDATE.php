<?php

/**
 * KERNEL SCHEDULER UPDATE
 * 
 * File: app/Console/Kernel.php
 * 
 * Add this to your schedule() method:
 */

protected function schedule(Schedule $schedule)
{
    // ... existing schedules ...
    
    // Clean up old module uploads daily at 3 AM
    $schedule->command('modules:cleanup-uploads --days=7')
        ->daily()
        ->at('03:00')
        ->onFailure(function () {
            \Log::error('Module upload cleanup failed');
        })
        ->onSuccess(function () {
            \Log::info('Module upload cleanup completed successfully');
        });
}
