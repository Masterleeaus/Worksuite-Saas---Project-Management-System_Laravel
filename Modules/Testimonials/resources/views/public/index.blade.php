<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Customer Testimonials') }} — {{ config('app.name') }}</title>
    <meta name="description" content="{{ __('Read what our customers say about our cleaning services.') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Figtree', sans-serif; background: #f8f9fa; color: #212529; margin: 0; padding: 0; }
        .t-hero { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: #fff; padding: 3rem 1rem; text-align: center; }
        .t-hero h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 0.5rem; }
        .t-hero p { font-size: 1.1rem; opacity: .85; }
        .t-filters { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; padding: 1.5rem 1rem; background: #fff; border-bottom: 1px solid #dee2e6; }
        .t-filters a, .t-filters span { padding: .4rem .9rem; border-radius: 2rem; background: #e9ecef; text-decoration: none; color: #495057; font-size: .875rem; font-weight: 500; transition: background .15s; }
        .t-filters a.active, .t-filters a:hover { background: #0d6efd; color: #fff; }
        .t-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .t-card { background: #fff; border-radius: .75rem; box-shadow: 0 2px 8px rgba(0,0,0,.08); overflow: hidden; display: flex; flex-direction: column; }
        .t-card.featured { border: 2px solid #ffc107; }
        .t-card-before-after { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
        .t-card-before-after img { width: 100%; height: 160px; object-fit: cover; display: block; }
        .t-card-before-after .label { font-size: .7rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; padding: .25rem .5rem; }
        .t-card-before-after .label.before { background: #dc3545; color: #fff; }
        .t-card-before-after .label.after  { background: #198754; color: #fff; }
        .t-card-photo img { width: 100%; height: 200px; object-fit: cover; display: block; }
        .t-card-body { padding: 1.25rem; flex: 1; }
        .t-stars { color: #ffc107; font-size: 1.1rem; margin-bottom: .5rem; }
        .t-stars span { color: #dee2e6; }
        .t-quote { font-style: italic; color: #495057; font-size: .95rem; line-height: 1.6; margin-bottom: 1rem; }
        .t-meta { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
        .t-meta .name { font-weight: 700; color: #212529; }
        .t-meta .suburb { font-size: .82rem; color: #6c757d; }
        .t-meta .service { font-size: .78rem; background: #e0f0ff; color: #0d6efd; padding: .2rem .55rem; border-radius: 1rem; }
        .t-meta .source { font-size: .75rem; color: #adb5bd; }
        .t-featured-badge { display: inline-block; background: #ffc107; color: #000; font-size: .7rem; font-weight: 700; padding: .15rem .45rem; border-radius: .25rem; margin-bottom: .5rem; }
        .t-video { margin-top: .75rem; }
        .t-video iframe { width: 100%; border-radius: .5rem; border: none; height: 180px; }
        .t-empty { text-align: center; padding: 4rem 1rem; color: #6c757d; grid-column: 1/-1; }
        @media (max-width: 480px) { .t-grid { grid-template-columns: 1fr; padding: 1rem; } }
    </style>
</head>
<body>

<div class="t-hero">
    <h1>⭐ {{ __('What Our Customers Say') }}</h1>
    <p>{{ __('Real reviews from real customers across the local area.') }}</p>
</div>

{{-- Service type filters --}}
@if($serviceTypes->isNotEmpty())
<div class="t-filters">
    <a href="{{ route('testimonials.public.index') }}" class="{{ empty($filters['service_type'] ?? '') ? 'active' : '' }}">
        {{ __('All Services') }}
    </a>
    @foreach($serviceTypes as $type)
    <a href="{{ route('testimonials.public.index', ['service_type' => $type]) }}"
       class="{{ ($filters['service_type'] ?? '') === $type ? 'active' : '' }}">
        {{ ucfirst($type) }}
    </a>
    @endforeach
</div>
@endif

{{-- Featured testimonials (if any) --}}
@if($featured->isNotEmpty())
<div style="background:#fffbf0; padding:1.5rem 2rem; max-width:1200px; margin:0 auto;">
    <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem; color:#856404;">
        ⭐ {{ __('Featured Testimonials') }}
    </h2>
    <div class="t-grid" style="padding:0;">
        @foreach($featured as $t)
            @include('testimonials::public._card', ['t' => $t])
        @endforeach
    </div>
</div>
<hr style="margin:0;">
@endif

{{-- Main grid --}}
<div class="t-grid">
    @forelse($testimonials->where('is_featured', false) as $t)
        @include('testimonials::public._card', ['t' => $t])
    @empty
        <div class="t-empty">
            <p>{{ __('No testimonials found.') }}</p>
        </div>
    @endforelse
</div>

</body>
</html>
