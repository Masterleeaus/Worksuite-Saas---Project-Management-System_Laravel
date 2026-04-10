<?php

/*
|--------------------------------------------------------------------------
| CustomerConnect Automation Manifest
|--------------------------------------------------------------------------
| Declares signals (observable events) and actions (executable operations)
| for the TitanZero governance pipeline.
|
| Signal severity levels:
|   green  = informational, auto-approve
|   amber  = review recommended, bounded auto-execute with limits
|   red    = requires human approval
|
| Action risk levels:
|   green  = auto-execute within configured bounds
|   amber  = auto-execute with limits, log & alert on threshold breach
|   red    = queue for human approval, never auto-execute
*/

return [

    'signals' => [

        [
            'id'       => 'customerconnect.campaign_run_completed',
            'label'    => 'Campaign Run Completed',
            'severity' => 'green',
            'facts'    => ['run_id', 'campaign_id', 'company_id', 'sent_count', 'failed_count', 'skipped_count'],
        ],

        [
            'id'       => 'customerconnect.delivery_failed',
            'label'    => 'Delivery Failed',
            'severity' => 'amber',
            'facts'    => ['delivery_id', 'company_id', 'channel', 'error', 'provider', 'attempt_count'],
        ],

        [
            'id'       => 'customerconnect.daily_cap_reached',
            'label'    => 'Daily Sending Cap Reached',
            'severity' => 'amber',
            'facts'    => ['company_id', 'channel', 'cap_limit', 'period_start'],
        ],

        [
            'id'       => 'customerconnect.sla_breach',
            'label'    => 'SLA Breach — Thread Awaiting Response',
            'severity' => 'amber',
            'facts'    => ['thread_id', 'company_id', 'channel', 'minutes_awaiting', 'assigned_to_user_id'],
        ],

        [
            'id'       => 'customerconnect.inbound_message_received',
            'label'    => 'Inbound Message Received',
            'severity' => 'green',
            'facts'    => ['thread_id', 'message_id', 'company_id', 'channel', 'contact_id', 'provider'],
        ],

        [
            'id'       => 'customerconnect.quiet_hours_deferral',
            'label'    => 'Delivery Deferred (Quiet Hours)',
            'severity' => 'green',
            'facts'    => ['delivery_id', 'company_id', 'channel', 'rescheduled_for'],
        ],

        [
            'id'       => 'customerconnect.unsubscribe_received',
            'label'    => 'Contact Unsubscribed',
            'severity' => 'green',
            'facts'    => ['contact_id', 'company_id', 'channel', 'email', 'phone'],
        ],

    ],

    'actions' => [

        [
            'id'          => 'customerconnect.pause_campaign',
            'label'       => 'Pause Campaign',
            'description' => 'Sets campaign status to paused, halting future run scheduling.',
            'risk'        => 'amber',
            'parameters'  => ['campaign_id' => 'integer'],
        ],

        [
            'id'          => 'customerconnect.send_thread_message',
            'label'       => 'Send Thread Reply',
            'description' => 'Sends an outbound message into an existing inbox thread.',
            'risk'        => 'green',
            'parameters'  => ['thread_id' => 'integer', 'body' => 'string'],
        ],

        [
            'id'          => 'customerconnect.assign_thread',
            'label'       => 'Assign Thread',
            'description' => 'Assigns an inbox thread to a user.',
            'risk'        => 'green',
            'parameters'  => ['thread_id' => 'integer', 'user_id' => 'integer'],
        ],

        [
            'id'          => 'customerconnect.close_thread',
            'label'       => 'Close Thread',
            'description' => 'Marks an inbox thread as closed.',
            'risk'        => 'green',
            'parameters'  => ['thread_id' => 'integer'],
        ],

        [
            'id'          => 'customerconnect.add_suppression',
            'label'       => 'Suppress Contact',
            'description' => 'Adds a contact to the global suppression list.',
            'risk'        => 'amber',
            'parameters'  => ['company_id' => 'integer', 'email' => 'string|nullable', 'phone' => 'string|nullable', 'reason' => 'string'],
        ],

    ],

];
