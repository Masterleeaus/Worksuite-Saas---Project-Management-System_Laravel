<?php

return {
    'module': 'zoom',
    'capabilities': [
        {
            'key': 'zoom.help.explain_page',
            'label': 'Zoom: Explain this page',
            'risk': 'low',
            'requires': [],
            'handler': 'titanzero.intent.explain_page',
            'voice_phrases': [
                'what is this page',
                'explain this',
                'help me'
            ]
        },
        {
            'key': 'zoom.get-zoom-webhook',
            'label': 'Zoom: Get Zoom Webhook',
            'risk': 'low',
            'requires': [],
            'handler': 'get-zoom-webhook',
            'voice_phrases': [
                'get zoom webhook'
            ]
        },
        {
            'key': 'zoom.zoom-meetings.apply_quick_action',
            'label': 'Zoom: Apply Quick Action',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-meetings.apply_quick_action',
            'voice_phrases': [
                'apply quick action'
            ]
        },
        {
            'key': 'zoom.zoom-meetings.calendar',
            'label': 'Zoom: Calendar',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-meetings.calendar',
            'voice_phrases': [
                'calendar'
            ]
        },
        {
            'key': 'zoom.zoom-meetings.cancel_meeting',
            'label': 'Zoom: Cancel Meeting',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-meetings.cancel_meeting',
            'voice_phrases': [
                'cancel meeting'
            ]
        },
        {
            'key': 'zoom.zoom-meetings.end_meeting',
            'label': 'Zoom: End Meeting',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-meetings.end_meeting',
            'voice_phrases': [
                'end meeting'
            ]
        },
        {
            'key': 'zoom.zoom-meetings.start_meeting',
            'label': 'Zoom: Start Meeting',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-meetings.start_meeting',
            'voice_phrases': [
                'start meeting'
            ]
        },
        {
            'key': 'zoom.zoom-meetings.update_occurrence',
            'label': 'Zoom: Update Occurrence',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-meetings.update_occurrence',
            'voice_phrases': [
                'update occurrence'
            ]
        },
        {
            'key': 'zoom.zoom-settings.zoom-slack-settings',
            'label': 'Zoom: Zoom Slack Settings',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-settings.zoom-slack-settings',
            'voice_phrases': [
                'zoom slack settings'
            ]
        },
        {
            'key': 'zoom.zoom-settings.zoom-smtp-settings',
            'label': 'Zoom: Zoom Smtp Settings',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-settings.zoom-smtp-settings',
            'voice_phrases': [
                'zoom smtp settings'
            ]
        },
        {
            'key': 'zoom.zoom-webhook',
            'label': 'Zoom: Zoom Webhook',
            'risk': 'low',
            'requires': [],
            'handler': 'zoom-webhook',
            'voice_phrases': [
                'zoom webhook'
            ]
        }
    ],
    'go_enabled': true,
    'zero_enabled': true
};
