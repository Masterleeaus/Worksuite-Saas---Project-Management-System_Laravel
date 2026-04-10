@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:640px;">

    <div class="card shadow-sm">
        <div class="card-header bg-warning bg-opacity-10 border-bottom-0">
            <h4 class="mb-0"><i class="ti-star me-2 text-warning"></i>Rate Your Clean</h4>
        </div>
        <div class="card-body">

            {{-- Job summary --}}
            <div class="mb-4 p-3 bg-light rounded">
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted d-block">Job Reference</small>
                        <strong>{{ $order->name }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Date</small>
                        <strong>
                            {{ $order->scheduled_date_start
                                ? $order->scheduled_date_start->format('d M Y')
                                : ($order->date_end ? $order->date_end->format('d M Y') : '—') }}
                        </strong>
                    </div>
                    @if($order->person)
                        <div class="col-12 mt-2">
                            <small class="text-muted d-block">Cleaner</small>
                            <strong>{{ $order->person->name }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            @if($existing)
                <div class="alert alert-info">
                    You already rated this job
                    @for($s = 1; $s <= 5; $s++)
                        <i class="ti-star{{ $s <= $existing->stars ? '' : '-o' }} text-warning"></i>
                    @endfor
                    — you can update it below.
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('clientpulse.portal.rating.store', $order->id) }}">
                @csrf

                {{-- Star rating widget --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Your rating <span class="text-danger">*</span></label>
                    <div class="star-rating d-flex gap-2" id="starRating">
                        @for($s = 1; $s <= 5; $s++)
                            <button type="button"
                                    class="btn btn-lg star-btn p-1 {{ ($existing && $existing->stars >= $s) ? 'text-warning' : 'text-muted' }}"
                                    data-value="{{ $s }}"
                                    aria-label="{{ $s }} star{{ $s > 1 ? 's' : '' }}">
                                <i class="ti-star fs-3"></i>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="stars" id="starsInput" value="{{ old('stars', $existing?->stars ?? '') }}" required>
                    @error('stars')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Optional comment --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="comment">Comments <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea id="comment" name="comment" class="form-control @error('comment') is-invalid @enderror"
                              rows="4" placeholder="Tell us what went well or how we can improve…">{{ old('comment', $existing?->comment ?? '') }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning px-4">
                        <i class="ti-check me-1"></i>Submit Rating
                    </button>
                    <a href="{{ route('clientpulse.portal.history.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const btns = document.querySelectorAll('.star-btn');
    const input = document.getElementById('starsInput');
    let selected = parseInt(input.value) || 0;

    function render(hovered) {
        btns.forEach((btn, i) => {
            const v = i + 1;
            const active = v <= (hovered || selected);
            btn.querySelector('i').className = 'ti-star fs-3';
            btn.classList.toggle('text-warning', active);
            btn.classList.toggle('text-muted', !active);
        });
    }

    btns.forEach((btn) => {
        btn.addEventListener('mouseenter', () => render(parseInt(btn.dataset.value)));
        btn.addEventListener('mouseleave', () => render(0));
        btn.addEventListener('click', () => {
            selected = parseInt(btn.dataset.value);
            input.value = selected;
            render(0);
        });
    });

    render(0);
})();
</script>
@endpush
@endsection
