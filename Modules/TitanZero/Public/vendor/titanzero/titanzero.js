(function () {
  function qs(sel){ return document.querySelector(sel); }
  function ce(tag, cls){ var el=document.createElement(tag); if(cls) el.className=cls; return el; }
  function csrf(){
    var m=document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute('content') : '';
  }
  function post(url, payload){
    return fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': csrf(),
        'X-Requested-With':'XMLHttpRequest'
      },
      body: JSON.stringify(payload||{})
    }).then(function(r){ return r.json(); });
  }

  function pageContext(){
    return {
      route: document.body.getAttribute('data-route') || null,
      page: document.title || null,
      url: location.href,
      module: document.body.getAttribute('data-module') || null,
      model: document.body.getAttribute('data-model') || null,
      fields: Array.prototype.slice.call(document.querySelectorAll('input[name],select[name],textarea[name]'))
        .map(function(el){ return el.name; })
        .filter(Boolean)
        .slice(0, 60)
    };
  }

  function mount(){
    if(qs('#tz-bubble')) return;

    var bubble=ce('div'); bubble.id='tz-bubble'; bubble.style.cssText='position:fixed;right:22px;bottom:22px;z-index:99999;';
    var btn=ce('button','btn btn-primary'); btn.type='button'; btn.textContent='Titan Zero';
    bubble.appendChild(btn);

    var panel=ce('div'); panel.id='tz-panel'; panel.style.cssText='display:none;position:absolute;right:0;bottom:52px;width:360px;max-width:92vw;background:#0f172a;color:#e5e7eb;border:1px solid rgba(255,255,255,.15);border-radius:12px;box-shadow:0 12px 30px rgba(0,0,0,.35);';
    panel.innerHTML = ''+
      '<div style="padding:12px 12px 10px;border-bottom:1px solid rgba(255,255,255,.12);display:flex;justify-content:space-between;align-items:center;">'+
        '<div style="font-weight:600;">Titan Zero</div>'+
        '<button type="button" class="btn btn-sm btn-outline-light" id="tz-close">×</button>'+
      '</div>'+
      '<div id="tz-log" style="padding:12px;max-height:320px;overflow:auto;font-size:13px;"></div>'+
      '<div style="padding:12px;border-top:1px solid rgba(255,255,255,.12);">'+
        '<textarea id="tz-text" class="form-control" rows="2" placeholder="Ask for help on this page…"></textarea>'+
        '<div style="display:flex;gap:8px;margin-top:10px;">'+
          '<button type="button" class="btn btn-sm btn-light" id="tz-send">Send</button>'+
          '<button type="button" class="btn btn-sm btn-outline-light" id="tz-explain">Explain page</button>'+
        '</div>'+
      '</div>';
    bubble.appendChild(panel);
    document.body.appendChild(bubble);

    function toggle(on){ panel.style.display = on ? 'block' : 'none'; }
    btn.addEventListener('click', function(){ toggle(panel.style.display==='none'); });
    qs('#tz-close').addEventListener('click', function(){ toggle(false); });

    function logCard(html){
      var log=qs('#tz-log');
      var card=ce('div'); card.style.cssText='padding:10px 10px;margin-bottom:10px;border-radius:10px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);';
      card.innerHTML = html;
      log.appendChild(card);
      log.scrollTop = log.scrollHeight;
      return card;
    }

    function renderResult(res){
      if(!res) return;
      if(res.next === 'confirm'){
        var it=res.intent||{};
        var card = logCard(
          '<div style="font-weight:600;margin-bottom:6px;">Confirm action</div>'+
          '<div>Intent: <b>'+it.intent+'</b> ('+it.confidence+'%) • risk: '+it.risk_level+'</div>'+
          '<div style="margin-top:8px;display:flex;gap:8px;flex-wrap:wrap;">'+
            '<button class="btn btn-sm btn-success" data-confirm="'+res.run_id+'">Confirm</button>'+
            '<button class="btn btn-sm btn-outline-light" data-cancel="1">Cancel</button>'+
          '</div>'
        );
        card.querySelector('[data-confirm]').addEventListener('click', function(){
          var runId=this.getAttribute('data-confirm');
          logCard('Executing…');
          post('/account/titan/zero/intent/confirm', { run_id: parseInt(runId,10), text: qs('#tz-text').value, page_context: pageContext() })
            .then(function(r){ renderResult(r); })
            .catch(function(){ logCard('Error executing'); });
        });
        return;
      }

      if(res.next === 'clarify'){
        logCard('I need more info to help. Try: “explain this page” or “help fill this form”.');
        return;
      }

      if(res.result){
        var r=res.result;
        if(r.type === 'summarize_standard'){
          var cits = (r.citations||[]).map(function(c){
            return '<li>'+ (c.document_title||('Doc '+c.document_id)) +' • chunk '+c.chunk_index+' • '+c.content_hash+'</li>';
          }).join('');
          logCard(
            '<div style="font-weight:600;margin-bottom:6px;">Standards retrieval</div>'+
            '<div style="opacity:.9">'+r.summary+'</div>'+
            (cits ? '<div style="margin-top:8px"><div style="font-weight:600;font-size:12px">Citations</div><ul style="margin:6px 0 0 16px">'+cits+'</ul></div>' : '')
          );
        } else {
          logCard('<pre style="white-space:pre-wrap;margin:0;">'+JSON.stringify(r,null,2)+'</pre>');
        }
        return;
      }

      if(res.intent){
        logCard('<pre style="white-space:pre-wrap;margin:0;">'+JSON.stringify(res.intent,null,2)+'</pre>');
      }
    }

    function send(text){
      if(!text) text = qs('#tz-text').value;
      logCard('<div style="opacity:.9">'+(text||'')+'</div>');
      post('/account/titan/zero/intent/route', { text: text, page_context: pageContext() })
        .then(function(res){ renderResult(res); })
        .catch(function(){ logCard('Titan Zero: request failed'); });
    }

    qs('#tz-send').addEventListener('click', function(){ send(); });
    qs('#tz-explain').addEventListener('click', function(){ qs('#tz-text').value='Explain this page'; send('Explain this page'); });
  }

  if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', mount);
  } else {
    mount();
  }
})();