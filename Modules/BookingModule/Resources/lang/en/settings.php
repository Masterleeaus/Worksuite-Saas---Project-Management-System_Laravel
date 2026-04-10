<?php

return [
    // Generic
    'save' => 'Save',
    'back' => 'Back',
    'saved' => 'Settings saved.',
    'updated' => 'Settings updated.',

    // Auto-assign
    'auto_assign_title' => 'Auto-assign settings',
    'enabled' => 'Enabled',
    'strategy' => 'Strategy',
    'strategy_schedule_match' => 'Avoid double-booking (recommended)',
    'strategy_least_busy' => 'Least busy on date',
    'strategy_round_robin' => 'Round robin',
    'require_permission' => 'Only assign to users who have a permission',
    'eligible_permission' => 'Eligible permission key',
    'eligible_permission_help' => 'Only users with this permission will be considered for auto assignment.',

    // Public spam
    'public_spam_title' => 'Public booking spam controls',
    'enable_honeypot' => 'Enable honeypot protection',
    'honeypot_min_seconds' => 'Minimum seconds before submit',
    'honeypot_help' => 'Blocks bots that submit too fast or fill hidden fields.',
    'rate_limit_per_minute' => 'Rate limit per minute',
    'rate_limit_help' => 'Applies to /public/{slug} booking endpoints.',

    // Notification preferences
    'notification_preferences' => 'Notification preferences',
    'channels' => 'Channels',
    'channel_email' => 'Email',
    'channel_in_app' => 'In-app notifications',
    'events' => 'Events',
    'notify_assigned' => 'Notify when a booking is assigned to me',
    'notify_reassigned' => 'Notify when a booking is reassigned',
    'notify_unassigned' => 'Notify when a booking is unassigned',
    'notify_rescheduled' => 'Notify when a booking is rescheduled',
    'notify_cancelled' => 'Notify when a booking is cancelled',
    'digest_and_quiet_hours' => 'Digest & quiet hours',
    'daily_digest' => 'Send me a daily digest',
    'quiet_hours_start' => 'Quiet hours start',
    'quiet_hours_end' => 'Quiet hours end',
];
