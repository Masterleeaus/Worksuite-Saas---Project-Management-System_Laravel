<?php

namespace Modules\BookingModule\Http\Controllers\Web\Public;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\BookingModule\Entities\BookingPage;

class BookingPageController extends Controller
{
    public function show(string $slug): Renderable
    {
        $page = BookingPage::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('bookingmodule::public.page-builder.show', [
            'page' => [
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
            ],
            'isPreview' => false,
        ]);
    }
}
