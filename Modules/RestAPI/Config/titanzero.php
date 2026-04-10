<?php

return {
    'module': 'restapi',
    'capabilities': [
        {
            'key': 'restapi.help.explain_page',
            'label': 'RestAPI: Explain this page',
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
            'key': 'restapi.rest-api.send_push',
            'label': 'RestAPI: Send Push',
            'risk': 'low',
            'requires': [],
            'handler': 'rest-api.send_push',
            'voice_phrases': [
                'send push'
            ]
        },
        {
            'key': 'restapi.rest-api.test_push',
            'label': 'RestAPI: Test Push',
            'risk': 'low',
            'requires': [],
            'handler': 'rest-api.test_push',
            'voice_phrases': [
                'test push'
            ]
        }
    ],
    'go_enabled': true,
    'zero_enabled': true
};
