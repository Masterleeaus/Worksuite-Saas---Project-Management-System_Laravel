<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Testimonials Widget') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; background: transparent; margin: 0; padding: .5rem; }
        .w-grid { display: flex; flex-direction: column; gap: .75rem; }
        .w-card { background: #fff; border-radius: .6rem; padding: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,.1); border-left: 4px solid #0d6efd; }
        .w-stars { color: #ffc107; font-size: 1rem; margin-bottom: .4rem; }
        .w-stars span { color: #dee2e6; }
        .w-quote { font-style: italic; font-size: .875rem; color: #495057; line-height: 1.55; margin: 0 0 .6rem; }
        .w-meta { font-size: .78rem; color: #6c757d; display: flex; flex-wrap: wrap; gap: .35rem; align-items: center; }
        .w-meta .name { font-weight: 600; color: #212529; }
        .w-meta .suburb::before { content: '📍 '; }
        .w-meta .service { background: #e0f0ff; color: #0d6efd; padding: .15rem .4rem; border-radius: 1rem; }
        .w-before-after { display: grid; grid-template-columns: 1fr 1fr; gap: .25rem; margin-bottom: .6rem; border-radius: .4rem; overflow: hidden; }
        .w-before-after img { width: 100%; height: 90px; object-fit: cover; display: block; }
        .w-before-after .lbl { font-size: .65rem; font-weight: 700; text-transform: uppercase; padding: .15rem .35rem; }
        .w-before-after .lbl.b { background: #dc3545; color: #fff; }
        .w-before-after .lbl.a { background: #198754; color: #fff; }
        .w-empty { text-align: center; color: #adb5bd; padding: 2rem; font-size: .9rem; }
        .w-branding { text-align: right; font-size: .7rem; color: #adb5bd; margin-top: .5rem; }
        .w-branding a { color: #adb5bd; text-decoration: none; }
    </style>
</head>
<body>

<div class="w-grid">
    @forelse($testimonials as $t)
    <div class="w-card">
        {{-- Before/After --}}
        @if($t->before_photo && $t->after_photo)
        <div class="w-before-after">
            <div>
                <img src="{{ $t->file($t->before_photo) }}" alt="Before">
                <div class="lbl b">{{ __('Before') }}</div>
            </div>
            <div>
                <img src="{{ $t->file($t->after_photo) }}" alt="After">
                <div class="lbl a">{{ __('After') }}</div>
            </div>
        </div>
        @endif

        {{-- Rating --}}
        <div class="w-stars">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= ($t->star_rating ?? 5))★@else<span>☆</span>@endif
            @endfor
        </div>

        {{-- Quote --}}
        <p class="w-quote">"{{ \Illuminate\Support\Str::limit($t->content ?: $t->description, 180) }}"</p>

        {{-- Meta --}}
        <div class="w-meta">
            <span class="name">{{ $t->customer_name ?: $t->client_name }}</span>
            @if($t->suburb)<span class="suburb">{{ $t->suburb }}</span>@endif
            @if($t->service_type)<span class="service">{{ ucfirst($t->service_type) }}</span>@endif
        </div>
    </div>
    @empty
    <div class="w-empty">{{ __('No testimonials yet.') }}</div>
    @endforelse
</div>

@if(isset($widgetModel))
<div class="w-branding">
    <a href="{{ route('testimonials.public.index') }}" target="_blank" rel="noopener">
        {{ __('More reviews →') }}
    </a>
</div>
@endif

</body>
</html>
