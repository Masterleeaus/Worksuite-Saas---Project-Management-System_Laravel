@extends('fsmsalerecurringagreement::layouts.master')

@section('fsm_content')
<h2 class="mb-3">Link Agreement to Recurring: {{ $recurring->name }}</h2>

<form method="POST" action="{{ route('fsmsalerecurringagreement.link', $recurring->id) }}">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Service Agreement <span class="text-danger">*</span></label>
        <select name="agreement_id" class="form-select @error('agreement_id') is-invalid @enderror" required>
            <option value="">— Select Agreement —</option>
            @foreach($agreements as $agreement)
                <option value="{{ $agreement->id }}" @selected(old('agreement_id') == $agreement->id)>
                    {{ $agreement->name }}
                </option>
            @endforeach
        </select>
        @error('agreement_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-primary">Link Agreement</button>
    <a href="{{ route('fsmsalerecurringagreement.index') }}" class="btn btn-secondary ms-1">Cancel</a>
</form>
@endsection
