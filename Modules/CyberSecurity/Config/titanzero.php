<?php

return {
    'module': 'cybersecurity',
    'capabilities': [
        {
            'key': 'cybersecurity.help.explain_page',
            'label': 'CyberSecurity: Explain this page',
            'risk': 'low',
            'requires': [],
            'handler': 'titanzero.intent.explain_page',
            'voice_phrases': [
                'what is this page',
                'explain this',
                'help me'
            ]
        }
    ],
    'go_enabled': true,
    'zero_enabled': true
};
