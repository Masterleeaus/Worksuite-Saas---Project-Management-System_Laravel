(function () {
    'use strict';

    var script = document.currentScript || (function () {
        var scripts = document.getElementsByTagName('script');
        return scripts[scripts.length - 1];
    })();

    var chatbotId = script.getAttribute('data-chatbot-id');
    var apiUrl    = script.getAttribute('data-api-url') || '/api/chatbot';

    if (!chatbotId) {
        console.warn('[WorkSuite Chatbot] data-chatbot-id attribute is required.');
        return;
    }

    // Derive frame URL from apiUrl
    var frameBase = apiUrl.replace(/\/api\/chatbot$/, '');
    var frameUrl  = frameBase + '/chatbot-widget/' + chatbotId + '/frame';

    // Fetch chatbot config
    fetch(apiUrl + '/widget/' + chatbotId, {headers: {'Accept': 'application/json'}})
        .then(function (r) { return r.json(); })
        .then(function (config) { render(config); })
        .catch(function () { render({name: 'Chat', welcome_message: ''}); });

    function render(config) {
        var style = document.createElement('style');
        style.textContent = [
            '#ws-chatbot-bubble{position:fixed;bottom:20px;right:20px;z-index:2147483646;cursor:pointer;',
            'width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;',
            'box-shadow:0 4px 20px rgba(0,0,0,.25);background:#6366f1;transition:transform .2s,opacity .2s;}',
            '#ws-chatbot-bubble:hover{transform:scale(1.08);}',
            '#ws-chatbot-bubble svg{fill:none;stroke:#fff;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}',
            '#ws-chatbot-frame{position:fixed;bottom:86px;right:20px;z-index:2147483645;',
            'width:380px;height:560px;border:none;border-radius:12px;',
            'box-shadow:0 12px 48px rgba(0,0,0,.2);opacity:0;pointer-events:none;',
            'transform:translateY(10px) scale(.97);transition:opacity .25s,transform .25s;}',
            '#ws-chatbot-frame.open{opacity:1;pointer-events:all;transform:translateY(0) scale(1);}',
            '@media(max-width:480px){',
            '#ws-chatbot-frame{width:100vw!important;height:100dvh!important;right:0!important;bottom:0!important;border-radius:0!important;}',
            '}'
        ].join('');
        document.head.appendChild(style);

        var bubble = document.createElement('div');
        bubble.id = 'ws-chatbot-bubble';
        bubble.title = config.name || 'Chat';
        bubble.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';

        var frame = document.createElement('iframe');
        frame.id  = 'ws-chatbot-frame';
        frame.src = frameUrl;
        frame.setAttribute('allow', 'microphone');

        document.body.appendChild(frame);
        document.body.appendChild(bubble);

        var open = false;
        bubble.addEventListener('click', function () {
            open = !open;
            frame.classList.toggle('open', open);
            bubble.innerHTML = open
                ? '<svg width="20" height="20" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>'
                : '<svg width="24" height="24" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';
        });
    }
})();
