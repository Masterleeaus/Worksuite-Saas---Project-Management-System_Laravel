<?php

namespace Modules\CyberSecurity\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CyberSecurity\Entities\ComplianceChecklist;

class ComplianceDashboardController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'cybersecurity::app.compliance.title';
        $this->middleware(function ($request, $next) {
            abort_403(user()->permission('manage_compliance') == 'none');

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $framework = $request->get('framework', 'gdpr');

        $this->framework = $framework;
        $this->frameworks = [
            'gdpr'           => __('cybersecurity::app.compliance.gdpr'),
            'privacy_act_au' => __('cybersecurity::app.compliance.privacy_act_au'),
        ];

        $companyId = company()->id ?? null;

        // Load saved checklist items for this company + framework
        $savedItems = ComplianceChecklist::where('company_id', $companyId)
            ->where('framework', $framework)
            ->get()
            ->keyBy('item_key');

        $templateItems = ComplianceChecklist::$frameworkItems[$framework] ?? [];

        $this->checklistItems = collect($templateItems)->map(function ($item) use ($savedItems) {
            $saved = $savedItems->get($item['key']);

            return [
                'key'        => $item['key'],
                'label'      => $item['label'],
                'status'     => $saved?->status ?? 'pending',
                'notes'      => $saved?->notes ?? '',
                'id'         => $saved?->id ?? null,
                'reviewed_by' => $saved?->reviewed_by ?? null,
                'reviewed_at' => $saved?->reviewed_at?->format(company_date_format()) ?? null,
            ];
        });

        $this->compliantCount    = $this->checklistItems->where('status', 'compliant')->count();
        $this->nonCompliantCount = $this->checklistItems->where('status', 'non_compliant')->count();
        $this->totalCount        = $this->checklistItems->count();
        $this->compliancePercent = $this->totalCount > 0
            ? round(($this->compliantCount / $this->totalCount) * 100)
            : 0;

        return view('cybersecurity::compliance.index', $this->data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'framework'  => 'required|in:gdpr,privacy_act_au',
            'item_key'   => 'required|string',
            'status'     => 'required|in:pending,compliant,non_compliant,not_applicable',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $companyId = company()->id ?? null;

        ComplianceChecklist::updateOrCreate(
            [
                'company_id' => $companyId,
                'framework'  => $request->framework,
                'item_key'   => $request->item_key,
            ],
            [
                'item_label'  => $request->item_key,
                'status'      => $request->status,
                'notes'       => $request->notes,
                'reviewed_by' => user()->id,
                'reviewed_at' => now(),
            ]
        );

        return Reply::success(__('messages.updateSuccess'));
    }

}
