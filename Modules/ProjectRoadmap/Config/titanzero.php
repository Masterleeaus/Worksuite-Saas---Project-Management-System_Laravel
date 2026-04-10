<?php

return {
    'module': 'projectroadmap',
    'capabilities': [
        {
            'key': 'projectroadmap.help.explain_page',
            'label': 'ProjectRoadmap: Explain this page',
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
