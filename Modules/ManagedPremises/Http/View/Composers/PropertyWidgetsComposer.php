<?php
namespace Modules\ManagedPremises\Http\View\Composers;

use Illuminate\View\View;
use Modules\ManagedPremises\Entities\PropertyVisit;
use Modules\QualityControl\Entities\QcRecord;

class PropertyWidgetsComposer
{
    public function compose(View $view): void
    {
        if (!function_exists('company_id') || !company_id()) return;

        $companyId = company_id();

        $view->with('pm_next_visits', PropertyVisit::query()
            ->where('company_id', $companyId)
            ->where('status', 'scheduled')
            ->orderBy('scheduled_for')
            ->with('property')
            ->limit(10)
            ->get());

        if (class_exists(QcRecord::class)) {
            $view->with('pm_overdue_inspections', QcRecord::query()
                ->where('company_id', $companyId)
                ->whereIn('status', ['pending', 'fail', 'reclean_required'])
                ->whereNotNull('property_id')
                ->orderByDesc('inspected_at')
                ->limit(10)
                ->get());
        } else {
            $view->with('pm_overdue_inspections', collect());
        }
    }
}
