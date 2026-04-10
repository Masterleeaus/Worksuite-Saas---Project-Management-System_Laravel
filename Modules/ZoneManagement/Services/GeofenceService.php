<?php

namespace Modules\ZoneManagement\Services;

use Modules\ZoneManagement\Entities\Zone;

class GeofenceService
{
    /**
     * Earth's mean radius in metres.
     */
    private const EARTH_RADIUS_M = 6371000;

    /**
     * Calculate the distance in metres between two GPS coordinates using
     * the Haversine formula.
     */
    public function haversineDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS_M * $c;
    }

    /**
     * Check whether a GPS coordinate is within the geofence of a Zone.
     *
     * For circle zones  – uses centre + radius.
     * For polygon zones – uses MySQL ST_Contains (requires spatial index).
     *
     * Returns an array with keys:
     *   - within  (bool)
     *   - distance_m (float|null) – metres from centre (circle zones only)
     */
    public function isWithinZone(Zone $zone, float $lat, float $lng): array
    {
        if ($zone->zone_type === 'circle' && $zone->center_lat && $zone->center_lng) {
            $distance = $this->haversineDistance(
                (float) $zone->center_lat,
                (float) $zone->center_lng,
                $lat,
                $lng
            );

            $radius = $zone->radius ?? 200;

            return [
                'within'     => $distance <= $radius,
                'distance_m' => round($distance, 1),
                'radius_m'   => $radius,
            ];
        }

        // Polygon fallback – use MySQL spatial query
        $result = \Illuminate\Support\Facades\DB::selectOne(
            "SELECT ST_Contains(
                (SELECT coordinates FROM zones WHERE id = ?),
                ST_GeomFromText(?)
             ) AS within",
            [$zone->id, "POINT({$lng} {$lat})"]
        );

        return [
            'within'     => (bool) ($result->within ?? false),
            'distance_m' => null,
            'radius_m'   => null,
        ];
    }

    /**
     * Given a list of cleaners with their last known location, return each one
     * sorted by distance from the target lat/lng, with distances attached.
     *
     * @param  array<array{user_id: int, lat: float, lng: float, name: string}>  $cleaners
     * @return array<array{user_id: int, name: string, distance_m: float, distance_km: string}>
     */
    public function sortCleanersByProximity(
        array $cleaners,
        float $targetLat,
        float $targetLng
    ): array {
        foreach ($cleaners as &$cleaner) {
            $cleaner['distance_m'] = $this->haversineDistance(
                (float) $cleaner['lat'],
                (float) $cleaner['lng'],
                $targetLat,
                $targetLng
            );
            $cleaner['distance_km'] = number_format($cleaner['distance_m'] / 1000, 2);
        }
        unset($cleaner);

        usort($cleaners, fn($a, $b) => $a['distance_m'] <=> $b['distance_m']);

        return $cleaners;
    }
}
