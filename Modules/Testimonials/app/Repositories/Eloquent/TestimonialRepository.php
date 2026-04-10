<?php

namespace Modules\Testimonials\app\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Testimonials\app\Models\Testimonial;
use Modules\Testimonials\app\Repositories\Contracts\TestimonialRepositoryInterface;

class TestimonialRepository implements TestimonialRepositoryInterface
{
    // -----------------------------------------------------------------------
    // List / read
    // -----------------------------------------------------------------------

    public function getAll($request)
    {
        $orderBy = $request->input('order_by', 'desc');
        $sortBy  = $request->input('sort_by', 'order_by');

        $query = Testimonial::orderBy($sortBy, $orderBy);

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->input('service_type'));
        }
        if ($request->filled('is_published')) {
            $query->where('is_published', (bool) $request->input('is_published'));
        }

        return $query->get()->map(function ($t) {
            $t->client_image = $t->client_image
                ? $t->file($t->client_image)
                : url('assets/img/user-default.jpg');
            return $t;
        });
    }

    public function getPublished(array $filters = [])
    {
        $query = Testimonial::where('is_published', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('star_rating')
            ->orderByDesc('created_at');

        if (!empty($filters['service_type'])) {
            $query->where('service_type', $filters['service_type']);
        }
        if (!empty($filters['min_rating'])) {
            $query->where('star_rating', '>=', (int) $filters['min_rating']);
        }
        if (isset($filters['featured_only']) && $filters['featured_only']) {
            $query->where('is_featured', true);
        }
        if (!empty($filters['limit'])) {
            $query->limit((int) $filters['limit']);
        }

        return $query->get()->map(function ($t) {
            $t->client_image = $t->client_image
                ? $t->file($t->client_image)
                : url('assets/img/user-default.jpg');
            return $t;
        });
    }

    // -----------------------------------------------------------------------
    // Create / update
    // -----------------------------------------------------------------------

    public function store(Request $request)
    {
        $data = $request->only([
            'client_name', 'position', 'description', 'status',
            'customer_name', 'suburb', 'service_type', 'content',
            'star_rating', 'video_url',
        ]);

        // Normalise: sync legacy <-> new fields
        if (empty($data['customer_name']) && !empty($data['client_name'])) {
            $data['customer_name'] = $data['client_name'];
        }
        if (empty($data['content']) && !empty($data['description'])) {
            $data['content'] = $data['description'];
        }

        $langCode = $request->input('language_code', 'en');

        if ($request->method === 'add') {
            $last = Testimonial::latest('order_by')->first();
            $data['order_by'] = $last ? $last->order_by + 1 : 1;
            $data['is_published'] = false;

            if ($request->hasFile('client_image')) {
                $data['client_image'] = $this->uploadImage($request->file('client_image'));
                $data['photo_path'] = $data['client_image'];
            }
            if ($request->hasFile('before_photo')) {
                $data['before_photo'] = $this->uploadImage($request->file('before_photo'));
            }
            if ($request->hasFile('after_photo')) {
                $data['after_photo'] = $this->uploadImage($request->file('after_photo'));
            }

            $data['source'] = $request->input('source', 'manual');

            Testimonial::create($data);
            return ['message' => __('testimonial_create_success', [], $langCode)];
        } else {
            $id = $request->input('id');
            $testimonial = Testimonial::find($id);
            if ($testimonial) {
                if ($request->hasFile('client_image')) {
                    $this->deleteImage($testimonial->client_image);
                    $data['client_image'] = $this->uploadImage($request->file('client_image'));
                    $data['photo_path'] = $data['client_image'];
                }
                if ($request->hasFile('before_photo')) {
                    $this->deleteImage($testimonial->before_photo);
                    $data['before_photo'] = $this->uploadImage($request->file('before_photo'));
                }
                if ($request->hasFile('after_photo')) {
                    $this->deleteImage($testimonial->after_photo);
                    $data['after_photo'] = $this->uploadImage($request->file('after_photo'));
                }
            }
            Testimonial::where('id', $id)->update($data);
            return ['message' => __('testimonial_update_success', [], $langCode)];
        }
    }

    // -----------------------------------------------------------------------
    // Delete
    // -----------------------------------------------------------------------

    public function destroy(Request $request)
    {
        $id       = $request->input('id');
        $langCode = $request->input('language_code', 'en');

        $testimonial = Testimonial::findOrFail($id);
        foreach (['client_image', 'before_photo', 'after_photo'] as $field) {
            $this->deleteImage($testimonial->$field);
        }
        $testimonial->delete();

        return ['message' => __('testimonial_delete_success', [], $langCode)];
    }

    // -----------------------------------------------------------------------
    // Status
    // -----------------------------------------------------------------------

    public function statusChange(Request $request)
    {
        $id       = $request->input('id');
        $status   = $request->input('status');
        $langCode = $request->input('language_code', 'en');

        Testimonial::where('id', $id)->update(['status' => $status]);

        return ['message' => __('testimonial_status_success', [], $langCode)];
    }

    // -----------------------------------------------------------------------
    // Publish / unpublish
    // -----------------------------------------------------------------------

    public function publish(int $id): array
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_published' => true]);
        return ['message' => __('Testimonial published successfully.')];
    }

    public function unpublish(int $id): array
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_published' => false]);
        return ['message' => __('Testimonial unpublished.')];
    }

    public function toggleFeatured(int $id): array
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_featured' => !$testimonial->is_featured]);
        $state = $testimonial->is_featured ? 'featured' : 'unfeatured';
        return ['message' => __("Testimonial {$state} successfully.")];
    }

    // -----------------------------------------------------------------------
    // Import from ReviewModule (5-star reviews)
    // -----------------------------------------------------------------------

    public function importFromReviews(): array
    {
        if (!class_exists(\Modules\ReviewModule\Entities\Review::class)) {
            return ['imported' => 0, 'message' => 'ReviewModule not available.'];
        }

        $imported = 0;

        try {
            $reviews = \Modules\ReviewModule\Entities\Review::where('review_rating', 5)
                ->where('is_active', 1)
                ->get();

            foreach ($reviews as $review) {
                $alreadyExists = Testimonial::where('source', 'review')
                    ->where('source_id', $review->id)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                $data = [
                    'source'       => 'review',
                    'source_id'    => $review->id,
                    'star_rating'  => 5,
                    'content'      => $review->review_comment ?? '',
                    'description'  => $review->review_comment ?? '',
                    'is_published' => false,
                    'status'       => true,
                    'order_by'     => (Testimonial::max('order_by') ?? 0) + 1,
                ];

                // Populate customer name if the Review has a customer relation
                if (isset($review->customer_id)) {
                    $customer = $review->customer ?? null;
                    if ($customer) {
                        $data['customer_name'] = $customer->f_name . ' ' . $customer->l_name;
                        $data['client_name']   = $data['customer_name'];
                    }
                }

                // Populate service type from the service relation
                if (method_exists($review, 'service') && $review->service) {
                    $data['service_type'] = $review->service->name ?? null;
                }

                if (isset($review->company_id)) {
                    $data['company_id'] = $review->company_id;
                }

                Testimonial::create($data);
                $imported++;
            }
        } catch (\Throwable $e) {
            Log::warning('[Testimonials] importFromReviews failed', ['err' => $e->getMessage()]);
        }

        return [
            'imported' => $imported,
            'message'  => "Imported {$imported} review(s) as testimonials.",
        ];
    }

    // -----------------------------------------------------------------------
    // Import from CustomerFeedback (positive feedback)
    // -----------------------------------------------------------------------

    public function importFromFeedback(): array
    {
        $feedbackClass = \Modules\CustomerFeedback\Models\FeedbackTicket::class;
        if (!class_exists($feedbackClass)) {
            // Try alternate namespace
            $feedbackClass = 'Modules\\CustomerFeedback\\Entities\\FeedbackTicket';
            if (!class_exists($feedbackClass)) {
                return ['imported' => 0, 'message' => 'CustomerFeedback module not available.'];
            }
        }

        $imported = 0;

        try {
            $feedbacks = $feedbackClass::where('feedback_type', 'feedback')
                ->where('status', 'resolved')
                ->get();

            foreach ($feedbacks as $fb) {
                $alreadyExists = Testimonial::where('source', 'feedback')
                    ->where('source_id', $fb->id)
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                $data = [
                    'source'       => 'feedback',
                    'source_id'    => $fb->id,
                    'content'      => $fb->description ?? '',
                    'description'  => $fb->description ?? '',
                    'star_rating'  => 5,
                    'is_published' => false,
                    'status'       => true,
                    'order_by'     => (Testimonial::max('order_by') ?? 0) + 1,
                ];

                if (isset($fb->company_id)) {
                    $data['company_id'] = $fb->company_id;
                }

                Testimonial::create($data);
                $imported++;
            }
        } catch (\Throwable $e) {
            Log::warning('[Testimonials] importFromFeedback failed', ['err' => $e->getMessage()]);
        }

        return [
            'imported' => $imported,
            'message'  => "Imported {$imported} feedback item(s) as testimonials.",
        ];
    }

    // -----------------------------------------------------------------------
    // File helpers
    // -----------------------------------------------------------------------

    protected function uploadImage($file): string
    {
        $filename = Str::uuid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('testimonials', $filename, 'public');
        return $filename;
    }

    protected function deleteImage(?string $filename): void
    {
        if ($filename && Storage::disk('public')->exists('testimonials/' . $filename)) {
            Storage::disk('public')->delete('testimonials/' . $filename);
        }
    }
}

