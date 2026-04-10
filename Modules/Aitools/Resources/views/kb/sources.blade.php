@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-3">@lang('aitools::app.ai_kb_sources')</h4>

            <form class="mb-4" method="POST" action="{{ route('ai-tools.kb.sources.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Source name" required>
                    </div>
                    <div class="col-md-3">
                        <select name="source_type" class="form-control" required>
                            <option value="upload">upload</option>
                            <option value="url">url</option>
                            <option value="api">api</option>
                            <option value="crawler">crawler</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="source_uri" class="form-control" placeholder="URL / identifier (optional)">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary w-100">@lang('app.save')</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>URI</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($sources as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->source_type }}</td>
                            <td style="max-width:380px; overflow:hidden; text-overflow:ellipsis;">{{ $s->source_uri }}</td>
                            <td>{{ $s->is_active ? 'active' : 'off' }}</td>
                            <td>{{ $s->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No sources yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $sources->links() }}
        </div>
    </div>
</div>
@endsection
