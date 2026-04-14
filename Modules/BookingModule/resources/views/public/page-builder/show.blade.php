<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['meta_title'] }}</title>
    <meta name="description" content="{{ $page['meta_description'] }}">
    <style>
        :root { --accent: {{ data_get($page, 'theme.accent', '#2563eb') }}; --surface: {{ data_get($page, 'theme.surface', '#0f172a') }}; --soft: {{ data_get($page, 'theme.soft', '#e2e8f0') }}; --text:#0f172a; --muted:#475569; }
        *{box-sizing:border-box} body{margin:0;font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;color:var(--text);background:#fff} .hero{background:linear-gradient(135deg,var(--surface),#1e293b);color:#fff;padding:88px 20px 72px}.container{width:min(1120px,calc(100% - 32px));margin:0 auto}.badge{display:inline-flex;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,.12);font-size:13px;margin-bottom:16px}.lead{font-size:1.1rem;color:rgba(255,255,255,.82);max-width:760px}.actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:46px;padding:0 20px;border-radius:12px;text-decoration:none;font-weight:600;border:0;cursor:pointer}.btn-primary{background:var(--accent);color:#fff}.btn-secondary{background:#fff;color:var(--surface)} .section{padding:56px 20px}.grid{display:grid;gap:20px}.grid-3{grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}.card{border:1px solid #e2e8f0;border-radius:20px;padding:24px;background:#fff;box-shadow:0 10px 30px rgba(15,23,42,.05)} .muted{color:var(--muted)} .faq{display:grid;gap:16px}.preview{position:fixed;right:16px;bottom:16px;background:#111827;color:#fff;padding:10px 14px;border-radius:999px;font-size:12px}.footer-cta{position:sticky;bottom:0;background:rgba(255,255,255,.95);backdrop-filter:blur(8px);border-top:1px solid #e2e8f0;padding:12px 0}.footer-cta .wrap{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}.form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px}.field label{display:block;font-weight:600;margin-bottom:6px}.field input,.field textarea,.field select{width:100%;padding:12px 14px;border:1px solid #cbd5e1;border-radius:12px;background:#fff} .field textarea{min-height:120px}.notice{padding:14px 16px;border-radius:14px;background:#ecfeff;color:#155e75;border:1px solid #a5f3fc;margin-bottom:18px} .error{padding:14px 16px;border-radius:14px;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;margin-bottom:18px} h1{font-size:clamp(2rem,5vw,4rem);line-height:1.05;margin:0 0 16px;max-width:900px}.slot-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-top:16px}.slot{border:1px solid #dbeafe;background:#eff6ff;color:#1d4ed8;padding:12px;border-radius:14px;font-size:14px}.inline-list{display:flex;flex-wrap:wrap;gap:8px;margin:0;padding:0;list-style:none}.pill{display:inline-flex;padding:8px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:999px}
    </style>
</head>
<body>
<section class="hero"><div class="container">@if(!empty($page['hero_badge']))<div class="badge">{{ $page['hero_badge'] }}</div>@endif<h1>{{ $page['headline'] }}</h1>@if(!empty($page['subheadline']))<p class="lead">{{ $page['subheadline'] }}</p>@endif<div class="actions"><a href="#booking-request" class="btn btn-primary">{{ $page['primary_button_label'] }}</a>@if(!empty($page['secondary_button_label']) && !empty($page['secondary_button_url']))<a href="{{ $page['secondary_button_url'] }}" class="btn btn-secondary">{{ $page['secondary_button_label'] }}</a>@endif<a href="{{ route('booking.status.show') }}" class="btn btn-secondary">Track booking</a></div></div></section>
<section class="section"><div class="container"><div class="card"><h2 style="margin-top:0">Service selector block-ready data</h2><p class="muted">This page now consumes the same public Booking APIs Smart Pages blocks will use.</p><ul class="inline-list" id="service-selector-fallback">@foreach($page['services'] as $service)<li class="pill">{{ $service }}</li>@endforeach</ul><div id="service-selector-live" class="slot-grid" style="display:none"></div></div></div></section>
<section class="section" style="background:#f8fafc;"><div class="container"><div class="grid" style="grid-template-columns:1.2fr .8fr;align-items:start;gap:24px;"><div class="card" id="booking-request"><h2 style="margin-top:0">Send a booking request</h2>@if(session('success'))<div class="notice">{{ session('success') }}</div>@endif@if($errors->any())<div class="error">{{ $errors->first() }}</div>@endif<form method="POST" action="{{ route('booking.pages.request.store', $page['slug']) }}">@csrf<input type="hidden" name="request_type" value="booking_request"><div class="form-grid"><div class="field"><label>Name</label><input name="customer_name" value="{{ old('customer_name') }}" required></div><div class="field"><label>Phone</label><input name="phone" value="{{ old('phone') }}" required></div><div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}"></div><div class="field"><label>Service</label><select name="service_name" id="service-select"><option value="">Select a service</option>@foreach($page['services'] as $service)<option value="{{ $service }}" @selected(old('service_name')===$service)>{{ $service }}</option>@endforeach</select></div><div class="field"><label>Preferred date</label><input type="date" name="preferred_date" value="{{ old('preferred_date') }}"></div><div class="field"><label>Arrival window</label><select name="preferred_window"><option value="">Choose a window</option><option @selected(old('preferred_window')==='Morning')>Morning</option><option @selected(old('preferred_window')==='Midday')>Midday</option><option @selected(old('preferred_window')==='Afternoon')>Afternoon</option></select></div><div class="field"><label>Postcode</label><input name="postcode" value="{{ old('postcode') }}"></div><div class="field" style="grid-column:1/-1"><label>Notes</label><textarea name="notes">{{ old('notes') }}</textarea></div></div><button class="btn btn-primary" type="submit">Submit booking request</button></form></div><div class="grid" style="gap:24px;"><div class="card"><h2 style="margin-top:0">Availability preview</h2><p class="muted">Live slots from the public booking availability API.</p><div id="availability-grid" class="slot-grid"><div class="slot">Loading slots…</div></div></div><div class="card"><h2 style="margin-top:0">Portal + dispatch ready</h2><ul><li>Public booking pages for Smart Pages embeds</li><li>Dispatch triage via booking page requests queue</li><li>Status tracking page for customer self-serve updates</li><li>Worksuite-ready admin dispatch board</li></ul><p class="muted">Use this page now, then swap the form block into Smart Pages once its booking blocks are wired.</p><a href="{{ route('booking.status.show') }}" class="btn btn-secondary">Open status tracker</a></div></div></div></div></section>
<section class="section" style="background:#f8fafc;"><div class="container"><h2 style="margin-top:0;">Why customers book through this page</h2><div class="grid grid-3" style="margin-top:20px;">@foreach($page['trust'] as $bullet)<div class="card"><strong>{{ $bullet }}</strong></div>@endforeach</div></div></section>
@if(!empty($page['faq']))<section class="section"><div class="container"><h2 style="margin-top:0;">Frequently asked questions</h2><div class="faq" style="margin-top:20px;">@foreach($page['faq'] as $item)<div class="card"><h3 style="margin-top:0;">{{ $item['question'] }}</h3><p class="muted" style="margin-bottom:0;">{{ $item['answer'] }}</p></div>@endforeach</div></div></section>@endif
<div class="footer-cta"><div class="container wrap"><div><strong>{{ $page['title'] }}</strong><div class="muted">Premium booking surface powered by Worksuite + BookingModule.</div></div><a href="#booking-request" class="btn btn-primary">{{ $page['primary_button_label'] }}</a></div></div>@if(!empty($isPreview))<div class="preview">Preview mode</div>@endif
<script>
(async function () {
    const slug = @json($page['slug']);
    const serviceGrid = document.getElementById('service-selector-live');
    const serviceFallback = document.getElementById('service-selector-fallback');
    const serviceSelect = document.getElementById('service-select');
    const availabilityGrid = document.getElementById('availability-grid');

    try {
        const servicesResponse = await fetch(`{{ route('booking.widgets.services') }}?slug=${encodeURIComponent(slug)}`);
        if (servicesResponse.ok) {
            const payload = await servicesResponse.json();
            if (Array.isArray(payload.data) && payload.data.length) {
                serviceGrid.innerHTML = payload.data.map(item => `<div class="slot">${item.name}</div>`).join('');
                serviceGrid.style.display = 'grid';
                serviceFallback.style.display = 'none';
                if (serviceSelect) {
                    const current = serviceSelect.value;
                    serviceSelect.innerHTML = '<option value="">Select a service</option>' + payload.data.map(item => `<option value="${item.name}">${item.name}</option>`).join('');
                    serviceSelect.value = current;
                }
            }
        }
    } catch (e) {}

    try {
        const availabilityResponse = await fetch(`{{ route('booking.widgets.availability') }}?days=6`);
        if (availabilityResponse.ok) {
            const payload = await availabilityResponse.json();
            if (Array.isArray(payload.data) && payload.data.length) {
                availabilityGrid.innerHTML = payload.data.map(slot => `<div class="slot"><strong>${slot.label}</strong><br><span>${slot.windows.join(' • ')}</span></div>`).join('');
            } else {
                availabilityGrid.innerHTML = '<div class="slot">No slots available.</div>';
            }
        }
    } catch (e) {
        availabilityGrid.innerHTML = '<div class="slot">Availability preview unavailable.</div>';
    }
})();
</script>
</body></html>
