<?php

namespace Modules\Testimonials\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Testimonials\app\Http\Requests\TestimonialRequest;
use Modules\Testimonials\app\Repositories\Contracts\TestimonialRepositoryInterface;

class TestimonialsController extends Controller
{
    protected TestimonialRepositoryInterface $testimonialRepo;

    public function __construct(TestimonialRepositoryInterface $testimonialRepo)
    {
        $this->testimonialRepo = $testimonialRepo;
    }

    // -----------------------------------------------------------------------
    // Admin CRUD (API / AJAX)
    // -----------------------------------------------------------------------

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->testimonialRepo->getAll($request);
            return response()->json([
                'code'    => 200,
                'message' => __('Testimonials details retrieved successfully.'),
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => 500,
                'message' => __('An error occurred while retrieving testimonials.'),
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function store(TestimonialRequest $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->store($request);
            return response()->json([
                'code'    => 200,
                'message' => $result['message'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => 500,
                'message' => __('testimonial_save_error', [], $request->input('language_code', 'en')),
                'error'   => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->destroy($request);
            return response()->json([
                'code'    => 200,
                'success' => true,
                'message' => $result['message'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => 500,
                'success' => false,
                'message' => __('testimonial_delete_error', [], $request->input('language_code', 'en')),
            ]);
        }
    }

    public function statusChange(Request $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->statusChange($request);
            return response()->json([
                'code'    => 200,
                'message' => $result['message'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code'    => 500,
                'message' => __('testimonial_status_error', [], $request->input('language_code', 'en')),
            ]);
        }
    }

    // -----------------------------------------------------------------------
    // Publish / Unpublish / Featured
    // -----------------------------------------------------------------------

    public function publish(Request $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->publish((int) $request->input('id'));
            return response()->json(['code' => 200, 'message' => $result['message']]);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function unpublish(Request $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->unpublish((int) $request->input('id'));
            return response()->json(['code' => 200, 'message' => $result['message']]);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function toggleFeatured(Request $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->toggleFeatured((int) $request->input('id'));
            return response()->json(['code' => 200, 'message' => $result['message']]);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }

    // -----------------------------------------------------------------------
    // Import
    // -----------------------------------------------------------------------

    public function importFromReviews(Request $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->importFromReviews();
            return response()->json(['code' => 200, 'message' => $result['message'], 'imported' => $result['imported']]);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function importFromFeedback(Request $request): JsonResponse
    {
        try {
            $result = $this->testimonialRepo->importFromFeedback();
            return response()->json(['code' => 200, 'message' => $result['message'], 'imported' => $result['imported']]);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }

    // -----------------------------------------------------------------------
    // Public (no auth required)
    // -----------------------------------------------------------------------

    public function publicList(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['service_type', 'min_rating', 'featured_only', 'limit']);
            $data    = $this->testimonialRepo->getPublished($filters);
            return response()->json(['code' => 200, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => $e->getMessage()]);
        }
    }
}
