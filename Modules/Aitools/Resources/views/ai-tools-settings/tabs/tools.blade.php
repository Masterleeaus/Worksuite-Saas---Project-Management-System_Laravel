{{-- Tools Tab --}}
<div class="row">
    <div class="col-lg-12 mb-3">
        <h5 class="mb-2">Tools</h5>
        <p class="text-muted mb-0">Manage registered tools and enable/disable them.</p>
    </div>

    <div class="col-lg-12">
        <div class="ai-tool-card">
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Tool Name</th>
                            <th>Title</th>
                            <th>Risk</th>
                            <th>Enabled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tools ?? [] as $t)
                            <tr>
                                <td>{{ $t->tool_name }}</td>
                                <td>{{ $t->title }}</td>
                                <td>{{ $t->risk_level }}</td>
                                <td>{{ $t->is_enabled ? 'Yes' : 'No' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No tools registered yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <small class="text-muted">If you still see a 500 here after this patch, it will be in the dispatcher or model; check laravel.log.</small>
        </div>
    </div>
</div>
