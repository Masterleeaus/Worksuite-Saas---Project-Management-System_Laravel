@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-3">@lang('aitools::app.ai_kb_documents')</h4>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">@lang('aitools::app.addKnowledgeDocument')</h5>
                    <form method="POST" action="{{ route('ai-tools.kb.documents.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">@lang('aitools::app.source')</label>
                                <select name="source_id" class="form-control">
                                    <option value="">@lang('aitools::app.none')</option>
                                    @foreach($sources as $s)
                                        <option value="{{ $s->id }}">#{{ $s->id }} — {{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">@lang('aitools::app.documentTitle')</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">@lang('aitools::app.documentType')</label>
                                <select name="doc_type" class="form-control" required>
                                    <option value="text">text</option>
                                    <option value="policy">policy</option>
                                    <option value="faq">faq</option>
                                    <option value="swms">swms</option>
                                    <option value="notes">notes</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">@lang('aitools::app.documentContent')</label>
                            <textarea name="content" class="form-control" rows="8" required></textarea>
                            <small class="text-muted">@lang('aitools::app.kbContentHelp')</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="embed_now" value="1" id="embed_now" checked>
                                <label class="form-check-label" for="embed_now">@lang('aitools::app.embedNow')</label>
                            </div>
                            <button class="btn btn-primary">@lang('app.save')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">@lang('aitools::app.searchKnowledgeBase')</h5>
                    <div class="row">
                        <div class="col-md-10 mb-2">
                            <input type="text" id="kb_query" class="form-control" placeholder="@lang('aitools::app.searchPlaceholder')">
                        </div>
                        <div class="col-md-2 mb-2">
                            <button id="kb_search_btn" class="btn btn-outline-primary w-100">@lang('aitools::app.search')</button>
                        </div>
                    </div>
                    <div id="kb_results" class="mt-3"></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($documents as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td style="max-width:420px; overflow:hidden; text-overflow:ellipsis;">{{ $d->title }}</td>
                            <td>{{ $d->doc_type }}</td>
                            <td>{{ $d->status }}</td>
                            <td>{{ $d->source_id }}</td>
                            <td>{{ $d->created_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No documents yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $documents->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
(() => {
  const btn = document.getElementById('kb_search_btn');
  const q = document.getElementById('kb_query');
  const out = document.getElementById('kb_results');
  if (!btn || !q || !out) return;

  const render = (items) => {
    if (!items || !items.length) {
      out.innerHTML = `<div class="text-muted">@lang('aitools::app.noResults')</div>`;
      return;
    }
    out.innerHTML = items.map(i => {
      const score = (i.score ?? 0).toFixed(3);
      const text = (i.text ?? '').replace(/</g,'&lt;').replace(/>/g,'&gt;');
      return `
        <div class="border rounded p-3 mb-2">
          <div class="d-flex justify-content-between">
            <div><strong>#${i.document_id}</strong> <span class="text-muted">chunk ${i.chunk_index}</span></div>
            <div class="text-muted">score ${score}</div>
          </div>
          <div class="mt-2" style="white-space:pre-wrap">${text}</div>
        </div>
      `;
    }).join('');
  };

  const run = async () => {
    const query = (q.value || '').trim();
    if (!query) return;
    out.innerHTML = `<div class="text-muted">@lang('aitools::app.searching')</div>`;
    try {
      const res = await fetch(`{{ route('ai-tools.kb.search') }}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json'
        },
        body: JSON.stringify({query, limit: 5})
      });
      const data = await res.json();
      if (!res.ok || !data || data.status === 'fail') {
        out.innerHTML = `<div class="text-danger">${(data && data.message) ? data.message : '@lang('aitools::app.searchFailed')'}</div>`;
        return;
      }
      render((data && data.results) ? data.results : (data.data && data.data.results ? data.data.results : []));
    } catch (e) {
      out.innerHTML = `<div class="text-danger">@lang('aitools::app.searchFailed')</div>`;
    }
  };

  btn.addEventListener('click', run);
  q.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') { e.preventDefault(); run(); }
  });
})();
</script>
@endpush
@endsection
