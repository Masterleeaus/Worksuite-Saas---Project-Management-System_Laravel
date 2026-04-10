<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\WorkPermit;
use Modules\Security\Http\Requests\WorkPermitRequest;
use Modules\Security\Services\ApprovalWorkflowService;

class WorkPermitController extends AccountBaseController
{
    protected $approvalService;

    public function __construct(ApprovalWorkflowService $approvalService)
    {
        parent::__construct();
        $this->approvalService = $approvalService;
        $this->pageTitle = 'security::app.work_permits';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        $this->permits = WorkPermit::all();
        return view('security::work-permits.index', $this->data);
    }

    public function create()
    {
        return view('security::work-permits.create', $this->data);
    }

    public function store(WorkPermitRequest $request)
    {
        $permit = WorkPermit::create($request->validated());
        return Reply::successWithData(__('security::messages.work_permit_created'), [
            'redirectUrl' => route('security.work_permits.show', $permit->id)
        ]);
    }

    public function show($id)
    {
        $this->permit = WorkPermit::with('approvedBy', 'approvedByBm', 'validatedBy', 'files')->findOrFail($id);
        $this->approvalChain = $this->approvalService->getApprovalChain($this->permit);
        return view('security::work-permits.show', $this->data);
    }

    public function edit($id)
    {
        $this->permit = WorkPermit::findOrFail($id);
        return view('security::work-permits.edit', $this->data);
    }

    public function update(WorkPermitRequest $request, $id)
    {
        $permit = WorkPermit::findOrFail($id);
        $permit->update($request->validated());
        return Reply::success(__('security::messages.work_permit_updated'));
    }

    public function destroy($id)
    {
        WorkPermit::findOrFail($id)->delete();
        return Reply::success(__('security::messages.work_permit_deleted'));
    }

    public function approve($id)
    {
        $this->permit = WorkPermit::findOrFail($id);
        $this->approvalChain = $this->approvalService->getApprovalChain($this->permit);
        return view('security::work-permits.approve', $this->data);
    }

    public function processApproval(Request $request, $id)
    {
        $permit = WorkPermit::findOrFail($id);
        $level = $request->approval_level;

        if ($level === 'unit_owner') {
            $this->approvalService->approveByUnitOwner($permit, auth()->id());
        } elseif ($level === 'building_manager') {
            $this->approvalService->approveByBuildingManager($permit, auth()->id());
        } elseif ($level === 'security') {
            $this->approvalService->validateBySecurity($permit, auth()->id());
        }

        return Reply::success(__('security::messages.work_permit_approved'));
    }

    public function uploadFiles(Request $request, $id)
    {
        $permit = WorkPermit::findOrFail($id);
        $request->validate(['files.*' => 'file|mimes:pdf,doc,docx']);

        foreach ($request->file('files', []) as $file) {
            $path = $file->store('work-permits/' . $id, 'public');
            $permit->files()->create(['filename' => $path]);
        }

        return Reply::success(__('security::messages.files_uploaded'));
    }

    public function export()
    {
        return response()->json(['status' => 'success']);
    }

    public function download($id)
    {
        return response()->json(['status' => 'success']);
    }

    public function applyQuickAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        switch ($action) {
            case 'delete':
                WorkPermit::whereIn('id', $ids)->delete();
                return Reply::success(__('security::messages.work_permits_deleted'));
            default:
                return Reply::error(__('security::messages.action_not_found'));
        }
    }
}
