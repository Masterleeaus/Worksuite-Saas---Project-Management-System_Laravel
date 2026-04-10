{{-- Providers & Models Tab --}}
<div class="row">
    <div class="col-lg-12 mb-3">
        <h5 class="mb-2">Providers</h5>
        <p class="text-muted mb-0">Manage AI providers and models (global registry).</p>
    </div>

    <div class="col-lg-12">
        <div class="ai-tool-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Providers</h6>
                <button class="btn btn-sm btn-primary" id="add-provider-btn">Add Provider</button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Driver</th>
                            <th>Base URL</th>
                            <th>Default</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($providers ?? [] as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->driver }}</td>
                                <td>{{ $p->base_url }}</td>
                                <td>{{ $p->is_default ? 'Yes' : 'No' }}</td>
                                <td>{{ $p->is_active ? 'Yes' : 'No' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No providers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ai-tool-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Models</h6>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Provider</th>
                            <th>Default</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($models ?? [] as $m)
                            <tr>
                                <td>{{ $m->name }}</td>
                                <td>{{ $m->model_type }}</td>
                                <td>{{ optional($m->provider)->name ?? $m->provider_id }}</td>
                                <td>{{ $m->is_default ? 'Yes' : 'No' }}</td>
                                <td>{{ $m->is_active ? 'Yes' : 'No' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No models yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <small class="text-muted">Advanced editing (create/update) stays via existing forms/actions; this tab ensures the page renders correctly.</small>
        </div>
    </div>
</div>
