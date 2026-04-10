<?php

namespace Modules\Testimonials\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Testimonials\app\Models\Testimonial;
use Modules\Testimonials\app\Models\TestimonialWidget;
use Modules\Testimonials\app\Repositories\Contracts\TestimonialRepositoryInterface;

class AdminTestimonialsController extends Controller
{
    protected TestimonialRepositoryInterface $testimonialRepo;

    public function __construct(TestimonialRepositoryInterface $testimonialRepo)
    {
        $this->testimonialRepo = $testimonialRepo;
    }

    /**
     * Admin testimonials management page.
     */
    public function index(Request $request): View
    {
        $serviceTypes = Testimonial::select('service_type')
            ->whereNotNull('service_type')
            ->distinct()
            ->pluck('service_type');

        return view('testimonials::testimonials.testimonials', compact('serviceTypes'));
    }

    /**
     * Admin widget management page.
     */
    public function widgets(Request $request): View
    {
        $widgets = TestimonialWidget::orderByDesc('created_at')->get();
        return view('testimonials::widgets.index', compact('widgets'));
    }

    /**
     * Store a new widget.
     */
    public function storeWidget(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'settings_json' => 'nullable|array',
        ]);

        $widget = TestimonialWidget::create($data);
        $widget->update(['embed_code' => $widget->generateEmbedCode()]);

        return redirect()->route('admin.testimonials.widgets')
            ->with('success', __('Widget created successfully.'));
    }

    /**
     * Delete a widget.
     */
    public function destroyWidget(int $id)
    {
        TestimonialWidget::findOrFail($id)->delete();
        return redirect()->route('admin.testimonials.widgets')
            ->with('success', __('Widget deleted.'));
    }
}
