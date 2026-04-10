{{-- Prompts Tab --}}
<div class="row">
    <div class="col-lg-12 mb-3">
        <h5 class="mb-2">Prompts</h5>
        <p class="text-muted mb-0">Manage prompt templates (global registry).</p>
    </div>

    <div class="col-lg-12">
        <div class="ai-tool-card">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Namespace</th>
                            <th>Slug</th>
                            <th>Version</th>
                            <th>Locale</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prompts ?? [] as $p)
                            <tr>
                                <td>{{ $p->namespace }}</td>
                                <td>{{ $p->slug }}</td>
                                <td>{{ $p->version }}</td>
                                <td>{{ $p->locale }}</td>
                                <td>{{ $p->status }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No prompts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
