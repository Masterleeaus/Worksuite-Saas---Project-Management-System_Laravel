<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        if (Schema::hasTable('cleaner_locations') && Schema::hasTable('titan_go_location_pings')) {
            DB::statement("
                INSERT INTO titan_go_location_pings
                (company_id, worker_id, visit_id, latitude, longitude, accuracy, tracking_mode, created_at, updated_at)
                SELECT
                    cl.company_id,
                    cl.user_id,
                    CASE WHEN cl.booking_id REGEXP '^[0-9]+$' THEN cl.booking_id ELSE NULL END,
                    cl.lat,
                    cl.lng,
                    cl.accuracy,
                    'background',
                    COALESCE(cl.recorded_at, cl.created_at),
                    COALESCE(cl.recorded_at, cl.updated_at)
                FROM cleaner_locations cl
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM titan_go_location_pings t
                    WHERE t.worker_id = cl.user_id
                      AND t.latitude = cl.lat
                      AND t.longitude = cl.lng
                      AND t.created_at = COALESCE(cl.recorded_at, cl.created_at)
                )
            ");
        }

        if (Schema::hasTable('route_points') && Schema::hasTable('titan_go_route_trails')) {
            DB::statement("
                INSERT INTO titan_go_route_trails
                (company_id, worker_id, visit_id, booking_id, latitude, longitude, accuracy, sequence, recorded_at, created_at, updated_at)
                SELECT
                    rp.company_id,
                    rp.user_id,
                    CASE WHEN rp.booking_id REGEXP '^[0-9]+$' THEN rp.booking_id ELSE NULL END,
                    rp.booking_id,
                    rp.lat,
                    rp.lng,
                    rp.accuracy,
                    rp.sequence,
                    rp.recorded_at,
                    COALESCE(rp.recorded_at, rp.created_at),
                    COALESCE(rp.recorded_at, rp.updated_at)
                FROM route_points rp
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM titan_go_route_trails tr
                    WHERE tr.worker_id = rp.user_id
                      AND tr.booking_id <=> rp.booking_id
                      AND tr.sequence = rp.sequence
                      AND tr.recorded_at <=> rp.recorded_at
                )
            ");
        }

        if (Schema::hasTable('zone_check_ins') && Schema::hasTable('titan_go_worker_statuses')) {
            DB::statement("
                INSERT INTO titan_go_worker_statuses
                (company_id, worker_id, visit_id, status, notes, created_at, updated_at)
                SELECT
                    z.company_id,
                    z.user_id,
                    CASE WHEN z.booking_id REGEXP '^[0-9]+$' THEN z.booking_id ELSE NULL END,
                    'arrived',
                    z.notes,
                    COALESCE(z.checked_in_at, z.created_at),
                    COALESCE(z.checked_in_at, z.updated_at)
                FROM zone_check_ins z
                WHERE z.checked_in_at IS NOT NULL
                  AND NOT EXISTS (
                    SELECT 1
                    FROM titan_go_worker_statuses s
                    WHERE s.worker_id = z.user_id
                      AND s.created_at = COALESCE(z.checked_in_at, z.created_at)
                      AND s.status = 'arrived'
                )
            ");

            DB::statement("
                INSERT INTO titan_go_worker_statuses
                (company_id, worker_id, visit_id, status, notes, created_at, updated_at)
                SELECT
                    z.company_id,
                    z.user_id,
                    CASE WHEN z.booking_id REGEXP '^[0-9]+$' THEN z.booking_id ELSE NULL END,
                    'job_complete',
                    z.notes,
                    COALESCE(z.checked_out_at, z.updated_at),
                    COALESCE(z.checked_out_at, z.updated_at)
                FROM zone_check_ins z
                WHERE z.checked_out_at IS NOT NULL
                  AND NOT EXISTS (
                    SELECT 1
                    FROM titan_go_worker_statuses s
                    WHERE s.worker_id = z.user_id
                      AND s.created_at = COALESCE(z.checked_out_at, z.updated_at)
                      AND s.status = 'job_complete'
                )
            ");
        }
    }

    public function down(): void
    {
        // no destructive rollback for data backfill
    }
};
