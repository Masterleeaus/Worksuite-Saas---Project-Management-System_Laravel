<div class="ai-tool-card">
    <div class="ai-tool-header">
        <div class="ai-tool-title">
            <div class="ai-tool-icon"><i class="fa fa-plug"></i></div>
            <h5>Providers & Models</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h6 class="mb-2">Add / Update Provider</h6>
            <form method="POST" action="{{ route('ai-tools.providers.store') }}">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" class="form-control" placeholder="OpenAI" required>
                </div>
                <div class="form-group">
                    <label>Driver</label>
                    <input name="driver" class="form-control" placeholder="openai">
                </div>
                <div class="form-group">
                    <label>Base URL</label>
                    <input name="base_url" class="form-control" placeholder="https://api.openai.com/v1">
                </div>
                <div class="form-group">
                    <label>API Key</label>
                    <input name="api_key" class="form-control" placeholder="sk-..." autocomplete="off">
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
                    <label class="ml-3"><input type="checkbox" name="is_default" value="1"> Default</label>
                </div>
                <button class="btn btn-primary">Save Provider</button>
            </form>
        </div>

        <div class="col-md-6">
            <h6 class="mb-2">Add / Update Model</h6>
            <form method="POST" action="{{ route('ai-tools.models.store') }}">
                @csrf
                <div class="form-group">
                    <label>Provider</label>
                    <select name="provider_id" class="form-control">
                        <option value="">(global)</option>
                        @foreach(($providers ?? []) as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Model Name</label>
                    <input name="name" class="form-control" placeholder="gpt-4o-mini" required>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="model_type" class="form-control" required>
                        <option value="chat">chat</option>
                        <option value="embedding">embedding</option>
                        <option value="image">image</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Max Output Tokens</label>
                    <input name="max_output_tokens" class="form-control" type="number" min="1">
                </div>
                <div class="form-group">
                    <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
                    <label class="ml-3"><input type="checkbox" name="is_default" value="1"> Default</label>
                </div>
                <button class="btn btn-primary">Save Model</button>
            </form>
        </div>
    </div>

    <hr>

    <h6 class="mb-2">Current Providers</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Name</th><th>Driver</th><th>Base URL</th><th>Default</th><th>Active</th></tr></thead>
            <tbody>
                @foreach(($providers ?? []) as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->driver }}</td>
                        <td>{{ $p->base_url }}</td>
                        <td>{{ $p->is_default ? 'Yes' : 'No' }}</td>
                        <td>{{ $p->is_active ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h6 class="mt-4 mb-2">Current Models</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead><tr><th>Name</th><th>Type</th><th>Provider</th><th>Default</th><th>Active</th></tr></thead>
            <tbody>
                @foreach(($models ?? []) as $m)
                    <tr>
                        <td>{{ $m->name }}</td>
                        <td>{{ $m->model_type }}</td>
                        <td>{{ optional($m->provider)->name }}</td>
                        <td>{{ $m->is_default ? 'Yes' : 'No' }}</td>
                        <td>{{ $m->is_active ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
