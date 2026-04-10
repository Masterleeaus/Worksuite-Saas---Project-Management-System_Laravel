<?php

return {
    'module': 'qrcode',
    'capabilities': [
        {
            'key': 'qrcode.help.explain_page',
            'label': 'QRCode: Explain this page',
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
            'key': 'qrcode.download',
            'label': 'QRCode: Download',
            'risk': 'low',
            'requires': [],
            'handler': 'download',
            'voice_phrases': [
                'download'
            ]
        },
        {
            'key': 'qrcode.fields',
            'label': 'QRCode: Fields',
            'risk': 'low',
            'requires': [],
            'handler': 'fields',
            'voice_phrases': [
                'fields'
            ]
        },
        {
            'key': 'qrcode.preview',
            'label': 'QRCode: Preview',
            'risk': 'low',
            'requires': [],
            'handler': 'preview',
            'voice_phrases': [
                'preview'
            ]
        }
    ],
    'go_enabled': true,
    'zero_enabled': true
};
