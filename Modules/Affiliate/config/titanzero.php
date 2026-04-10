<?php

return {
    'module': 'affiliate',
    'capabilities': [
        {
            'key': 'affiliate.help.explain_page',
            'label': 'Affiliate: Explain this page',
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
            'key': 'affiliate.redirectReferral',
            'label': 'Affiliate: Redirectreferral',
            'risk': 'low',
            'requires': [],
            'handler': 'affiliate.redirectReferral',
            'voice_phrases': [
                'redirectreferral'
            ]
        },
        {
            'key': 'affiliate.affiliates.change_status',
            'label': 'Affiliate: Change Status',
            'risk': 'low',
            'requires': [],
            'handler': 'affiliates.change_status',
            'voice_phrases': [
                'change status'
            ]
        },
        {
            'key': 'affiliate.affiliates.get_affiliates',
            'label': 'Affiliate: Get Affiliates',
            'risk': 'low',
            'requires': [],
            'handler': 'affiliates.get_affiliates',
            'voice_phrases': [
                'get affiliates'
            ]
        },
        {
            'key': 'affiliate.payouts.change_status',
            'label': 'Affiliate: Change Status',
            'risk': 'low',
            'requires': [],
            'handler': 'payouts.change_status',
            'voice_phrases': [
                'change status'
            ]
        },
        {
            'key': 'affiliate.payouts.confirm_paid',
            'label': 'Affiliate: Confirm Paid',
            'risk': 'low',
            'requires': [],
            'handler': 'payouts.confirm_paid',
            'voice_phrases': [
                'confirm paid'
            ]
        }
    ],
    'go_enabled': true,
    'zero_enabled': true
};
