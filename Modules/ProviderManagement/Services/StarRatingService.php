<?php

namespace Modules\ProviderManagement\Services;

use App\Models\EmployeeDetails;

class StarRatingService
{
    /**
     * Recalculate and persist the star_rating for a given employee (user_id).
     * Reads from ReviewModule if available; no-op otherwise.
     */
    public function recalculate(int $userId): ?float
    {
        if (!class_exists(\Modules\ReviewModule\Entities\Review::class)) {
            return null;
        }

        /** @var \Illuminate\Database\Eloquent\Model $reviewClass */
        $reviewClass = \Modules\ReviewModule\Entities\Review::class;

        $avg = null;

        if (\Illuminate\Support\Facades\Schema::hasColumn((new $reviewClass)->getTable(), 'provider_user_id')) {
            $avg = $reviewClass::where('provider_user_id', $userId)
                ->whereNotNull('review_rating')
                ->avg('review_rating');
        }

        if ($avg !== null) {
            EmployeeDetails::where('user_id', $userId)
                ->update(['star_rating' => round((float) $avg, 2)]);
        }

        return $avg ? round((float) $avg, 2) : null;
    }
}
