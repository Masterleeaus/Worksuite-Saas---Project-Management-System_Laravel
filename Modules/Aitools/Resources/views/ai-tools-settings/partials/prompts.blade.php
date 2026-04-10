<div class="ai-tool-card">
    <div class="ai-tool-header">
        <div class="ai-tool-title">
            <div class="ai-tool-icon"><i class="fa fa-file-alt"></i></div>
            <h5>Prompt Library</h5>
        </div>
    </div>

    <form method="POST" action="{{ route('ai-tools.prompts.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-3 form-group">
                <label>Namespace</label>
                <input name="namespace" class="form-control" value="aitools" required>
            </div>
            <div class="col-md-3 form-group">
                <label>Slug</label>
                <input name="slug" class="form-control" placeholder="rephrase" required>
            </div>
            <div class="col-md-2 form-group">
                <label>Version</label>
                <input name="version" class="form-control" type="number" min="1" value="1" required>
            </div>
            <div class="col-md-2 form-group">
                <label>Locale</label>
                <input name="locale" class="form-control" value="en" required>
            </div>
            <div class="col-md-2 form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active">active</option>
                    <option value="draft">draft</option>
                    <option value="archived">archived</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Title</label>
            <input name="title" class="form-control" placeholder="Rephrase Prompt v1">
        </div>

        <div class="form-group">
            <label>Prompt Body</label>
            <textarea name="prompt_body" class="form-control" rows="6" placeholder="Write the prompt here..." required></textarea>
        </div>

        <div class="form-group">
            <label>Meta (JSON, optional)</label>
            <textarea name="meta" class="form-control" rows="2" placeholder='{"notes":"..."}'></textarea>
        </div>

        <button class="btn btn-primary">Save Prompt</button>
    </form>

    <hr>

    <div class="ai-tool-card" style="border:1px dashed #ddd;padding:12px;margin-bottom:16px;">
        <h6 class="mb-2">Run a Prompt (Quick Test)</h6>
        <div class="row">
            <div class="col-md-3 form-group">
                <label>Namespace</label>
                <input id="aitools_run_namespace" class="form-control" value="aitools">
            </div>
            <div class="col-md-3 form-group">
                <label>Slug</label>
                <input id="aitools_run_slug" class="form-control" placeholder="rephrase">
            </div>
            <div class="col-md-2 form-group">
                <label>Version</label>
                <input id="aitools_run_version" class="form-control" type="number" min="1" value="1">
            </div>
            <div class="col-md-2 form-group">
                <label>Locale</label>
                <input id="aitools_run_locale" class="form-control" value="en">
            </div>
            <div class="col-md-2 form-group">
                <label>Temperature</label>
                <input id="aitools_run_temp" class="form-control" type="number" step="0.1" min="0" max="2" value="0.3">
            </div>
        </div>
        <div class="form-group">
            <label>Vars (JSON)</label>
            <textarea id="aitools_run_vars" class="form-control" rows="3" placeholder='{"text":"Hello"}'></textarea>
        </div>
        <div class="form-group">
            <label>System (optional)</label>
            <textarea id="aitools_run_system" class="form-control" rows="2" placeholder="You are a helpful assistant."></textarea>
        </div>
        <button type="button" class="btn btn-outline-primary" onclick="aitoolsRunPrompt()">Run Prompt</button>
        <div id="aitools_run_status" class="mt-2" style="font-size:12px;color:#666;"></div>
        <pre id="aitools_run_output" class="mt-2" style="white-space:pre-wrap;background:#f7f7f7;padding:10px;border-radius:6px;display:none;"></pre>
    </div>

    <h6 class="mb-2">Current Prompts</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Namespace</th><th>Slug</th><th>Version</th><th>Locale</th><th>Status</th><th>Title</th></tr></thead>
            <tbody>
                @foreach(($prompts ?? []) as $p)
                    <tr>
                        <td>{{ $p->namespace }}</td>
                        <td>{{ $p->slug }}</td>
                        <td>{{ $p->version }}</td>
                        <td>{{ $p->locale }}</td>
                        <td>{{ $p->status }}</td>
                        <td>{{ $p->title }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function aitoolsRunPrompt() {
  const statusEl = document.getElementById('aitools_run_status');
  const outEl = document.getElementById('aitools_run_output');
  statusEl.textContent = 'Running...';
  outEl.style.display = 'none';
  outEl.textContent = '';

  const payload = new FormData();
  payload.append('_token', '{{ csrf_token() }}');
  payload.append('namespace', document.getElementById('aitools_run_namespace').value);
  payload.append('slug', document.getElementById('aitools_run_slug').value);
  payload.append('version', document.getElementById('aitools_run_version').value);
  payload.append('locale', document.getElementById('aitools_run_locale').value);
  payload.append('temperature', document.getElementById('aitools_run_temp').value);
  payload.append('vars', document.getElementById('aitools_run_vars').value);
  payload.append('system', document.getElementById('aitools_run_system').value);

  fetch('{{ route('ai-tools.prompts.run') }}', {
    method: 'POST',
    body: payload,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  }).then(r => r.json()).then(j => {
    if (j.status === 'success' || j.status === 'data_only') {
      const output = (j.output || (j.data && j.data.output) || '');
      outEl.textContent = output;
      outEl.style.display = 'block';
      statusEl.textContent = 'Done.';
    } else {
      statusEl.textContent = (j.message || 'Error');
    }
  }).catch(e => {
    statusEl.textContent = 'Error: ' + (e && e.message ? e.message : 'Unknown');
  });
}
</script>
