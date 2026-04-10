<?php

namespace Modules\Testimonials\app\Repositories\Contracts;

use Illuminate\Http\Request;

interface TestimonialRepositoryInterface
{
    public function getAll(Request $request);
    public function store(Request $request);
    public function destroy(Request $request);
    public function statusChange(Request $request);

    /** Approve and make a testimonial publicly visible. */
    public function publish(int $id): array;

    /** Retract a published testimonial. */
    public function unpublish(int $id): array;

    /** Toggle the featured flag. */
    public function toggleFeatured(int $id): array;

    /** Return only published testimonials (optionally filtered). */
    public function getPublished(array $filters = []);

    /** Import 5-star reviews from ReviewModule as testimonials. */
    public function importFromReviews(): array;

    /** Import positive feedback from CustomerFeedback module. */
    public function importFromFeedback(): array;
}

