<?php

namespace Modules\BookingModule\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\BookingModule\Entities\BookingPage;
use Modules\BookingModule\Services\PageCreator\BookingPageTemplateService;

class BookingPageController extends Controller
{
    public function __construct(private readonly BookingPageTemplateService $templates)
    {
    }

    public function index(Request $request): Renderable
    {
        $pages = BookingPage::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest('id')
            ->paginate(config('app.pagination', 15));

        return view('bookingmodule::admin.page-builder.index', [
            'pages' => $pages,
            'templateOptions' => $this->templates->templates(),
            'statuses' => ['draft', 'published', 'archived'],
        ]);
    }

    public function create(Request $request): Renderable
    {
        $templateKey = $request->get('template', 'premium-home-cleaning');
        $template = $this->templates->resolve($templateKey);

        $page = new BookingPage([
            'status' => 'draft',
            'template' => $templateKey,
            'title' => $template['name'] ?? 'Booking Page',
            'headline' => $template['headline'] ?? '',
            'subheadline' => $template['subheadline'] ?? '',
            'hero_badge' => $template['hero_badge'] ?? '',
            'primary_button_label' => $template['primary_button_label'] ?? 'Book now',
            'primary_button_url' => $template['primary_button_url'] ?? '/account/admin/booking/list',
            'secondary_button_label' => $template['secondary_button_label'] ?? 'Get a quote',
            'secondary_button_url' => $template['secondary_button_url'] ?? '/account/admin/booking/check',
            'service_lines' => implode(PHP_EOL, $template['service_lines'] ?? []),
            'trust_lines' => implode(PHP_EOL, $template['trust_lines'] ?? []),
            'faq_lines' => implode(PHP_EOL, $template['faq_lines'] ?? []),
            'theme' => $template['theme'] ?? [],
        ]);

        return view('bookingmodule::admin.page-builder.form', [
            'page' => $page,
            'templateOptions' => $this->templates->templates(),
            'formAction' => route('admin.booking.pages.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title']);
        $data['created_by'] = Auth::id();
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        BookingPage::create($data);

        return redirect()->route('admin.booking.pages.index')->with('success', 'Booking page created successfully.');
    }

    public function edit(BookingPage $page): Renderable
    {
        $page->service_lines = is_array($page->service_lines ?? null) ? implode(PHP_EOL, $page->service_lines) : (string) ($page->service_lines ?? '');
        $page->trust_lines = is_array($page->trust_lines ?? null) ? implode(PHP_EOL, $page->trust_lines) : (string) ($page->trust_lines ?? '');
        $page->faq_lines = is_array($page->faq_lines ?? null) ? implode(PHP_EOL, collect($page->faq_lines)->map(fn ($item) => ($item['question'] ?? '').' | '.($item['answer'] ?? ''))->all()) : (string) ($page->faq_lines ?? '');

        return view('bookingmodule::admin.page-builder.form', [
            'page' => $page,
            'templateOptions' => $this->templates->templates(),
            'formAction' => route('admin.booking.pages.update', $page),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, BookingPage $page): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $page->id);
        $data['published_at'] = $data['status'] === 'published'
            ? ($page->published_at ?: now())
            : null;

        $page->update($data);

        return redirect()->route('admin.booking.pages.index')->with('success', 'Booking page updated successfully.');
    }

    public function preview(BookingPage $page): Renderable
    {
        return view('bookingmodule::public.page-builder.show', [
            'page' => $this->buildPagePayload($page),
            'isPreview' => true,
        ]);
    }

    public function destroy(BookingPage $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.booking.pages.index')->with('success', 'Booking page deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'nullable|string|max:191',
            'status' => 'required|in:draft,published,archived',
            'template' => 'required|string|max:100',
            'headline' => 'required|string|max:255',
            'subheadline' => 'nullable|string|max:1000',
            'hero_badge' => 'nullable|string|max:100',
            'primary_button_label' => 'required|string|max:100',
            'primary_button_url' => 'required|string|max:255',
            'secondary_button_label' => 'nullable|string|max:100',
            'secondary_button_url' => 'nullable|string|max:255',
            'service_lines' => 'nullable|string',
            'trust_lines' => 'nullable|string',
            'faq_lines' => 'nullable|string',
            'meta_title' => 'nullable|string|max:191',
            'meta_description' => 'nullable|string|max:255',
            'accent_color' => 'nullable|string|max:20',
            'surface_color' => 'nullable|string|max:20',
            'soft_color' => 'nullable|string|max:20',
        ]);

        $validated['theme'] = [
            'accent' => $validated['accent_color'] ?? '#2563eb',
            'surface' => $validated['surface_color'] ?? '#0f172a',
            'soft' => $validated['soft_color'] ?? '#e2e8f0',
        ];
        unset($validated['accent_color'], $validated['surface_color'], $validated['soft_color']);

        $validated['settings'] = [
            'services' => $this->templates->lineStringToArray($validated['service_lines'] ?? ''),
            'trust' => $this->templates->lineStringToArray($validated['trust_lines'] ?? ''),
            'faq' => $this->templates->faqStringToArray($validated['faq_lines'] ?? ''),
        ];

        return $validated;
    }

    private function uniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source ?: 'booking-page');
        $slug = $base;
        $index = 2;

        while (BookingPage::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$index++;
        }

        return $slug;
    }

    private function buildPagePayload(BookingPage $page): array
    {
        return [
            'title' => $page->title,
            'slug' => $page->slug,
            'headline' => $page->headline,
            'subheadline' => $page->subheadline,
            'hero_badge' => $page->hero_badge,
            'primary_button_label' => $page->primary_button_label,
            'primary_button_url' => $page->primary_button_url,
            'secondary_button_label' => $page->secondary_button_label,
            'secondary_button_url' => $page->secondary_button_url,
            'meta_title' => $page->meta_title ?: $page->title,
            'meta_description' => $page->meta_description ?: $page->subheadline,
            'services' => $page->settings['services'] ?? [],
            'trust' => $page->settings['trust'] ?? [],
            'faq' => $page->settings['faq'] ?? [],
            'theme' => $page->theme ?? [],
        ];
    }
}
