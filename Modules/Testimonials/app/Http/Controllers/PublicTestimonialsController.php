<?php

namespace Modules\Testimonials\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Testimonials\app\Models\Testimonial;
use Modules\Testimonials\app\Models\TestimonialWidget;
use Modules\Testimonials\app\Repositories\Contracts\TestimonialRepositoryInterface;

class PublicTestimonialsController extends Controller
{
    protected TestimonialRepositoryInterface $testimonialRepo;

    public function __construct(TestimonialRepositoryInterface $testimonialRepo)
    {
        $this->testimonialRepo = $testimonialRepo;
    }

    /**
     * Public testimonials gallery page.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['service_type', 'min_rating']);

        $testimonials = $this->testimonialRepo->getPublished($filters);

        $serviceTypes = Testimonial::where('is_published', true)
            ->whereNotNull('service_type')
            ->distinct()
            ->pluck('service_type');

        $featured = $testimonials->filter(fn ($t) => $t->is_featured)->take(3);

        return view('testimonials::public.index', compact('testimonials', 'serviceTypes', 'featured', 'filters'));
    }

    /**
     * Embeddable widget iframe view.
     */
    public function widget(Request $request, int $widget): View
    {
        $widgetModel = TestimonialWidget::where('is_active', true)->findOrFail($widget);
        $settings    = $widgetModel->settings_json ?? [];

        $filters = [];
        if (!empty($settings['service_type'])) {
            $filters['service_type'] = $settings['service_type'];
        }
        if (!empty($settings['min_rating'])) {
            $filters['min_rating'] = $settings['min_rating'];
        }
        if (!empty($settings['limit'])) {
            $filters['limit'] = $settings['limit'];
        }

        $testimonials = $this->testimonialRepo->getPublished($filters);

        return view('testimonials::public.widget', compact('testimonials', 'widgetModel', 'settings'));
    }
}
