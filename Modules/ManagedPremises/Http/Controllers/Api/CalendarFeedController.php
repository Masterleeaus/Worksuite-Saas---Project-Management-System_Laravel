<?php
namespace Modules\ManagedPremises\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\ManagedPremises\Http\Requests\CalendarFeedRequest;
use Modules\ManagedPremises\Entities\PropertyVisit;
use Modules\QualityControl\Entities\QcRecord;
use Modules\ManagedPremises\Http\Resources\CalendarItemResource;
use Modules\ManagedPremises\Http\Controllers\Concerns\EnsuresManagedPremisesPermissions;

class CalendarFeedController extends Controller
{
    
    use EnsuresManagedPremisesPermissions;

    public function index(CalendarFeedRequest $request)
    {
        $this->ensureCanViewManagedPremises();
        abort_unless(function_exists('company_id'), 403);
        $companyId = company_id();

        $from = $request->get('from') ?: now()->startOfMonth()->toDateString();
        $to   = $request->get('to') ?: now()->endOfMonth()->toDateString();

        $visits = PropertyVisit::query()
            ->where('company_id', $companyId)
            ->whereBetween('scheduled_for', [$from, $to])
            ->with('property')
            ->get()
            ->map(fn($v) => [
                'type' => 'visit',
                'id' => $v->id,
                'property_id' => $v->property_id,
                'title' => ($v->visit_type ?: 'Visit') . ' — ' . ($v->property?->name ?? ('#'.$v->property_id)),
                'start' => optional($v->scheduled_for)->toIso8601String(),
                'status' => $v->status,
            ]);

        $inspections = class_exists(QcRecord::class)
            ? QcRecord::query()
                ->where('company_id', $companyId)
                ->whereBetween('inspected_at', [$from, $to])
                ->whereNotNull('property_id')
                ->get()
                ->map(fn($i) => [
                    'type' => 'inspection',
                    'id' => $i->id,
                    'property_id' => $i->property_id,
                    'title' => 'QC Inspection — #' . $i->property_id,
                    'start' => optional($i->inspected_at)->toIso8601String(),
                    'status' => $i->status,
                ])
            : collect();

        return CalendarItemResource::collection($visits->merge($inspections)->values());
    }
}
