<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\InOutPermit;
use Modules\Security\Http\Requests\InOutPermitRequest;
use Modules\Security\Services\ApprovalWorkflowService;

class InOutPermitController extends AccountBaseController
{
    protected $approvalService;

    public function __construct(ApprovalWorkflowService $approvalService)
    {
        parent::__construct();
        $this->approvalService = $approvalService;
        $this->pageTitle = 'security::app.inout_permits';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        $this->permits = InOutPermit::all();
        return view('security::inout-permits.index', $this->data);
    }

    public function create()
    {
        return view('security::inout-permits.create', $this->data);
    }

    public function store(InOutPermitRequest $request)
    {
        $permit = InOutPermit::create($request->validated());
        return Reply::successWithData(__('security::messages.permit_created'), [
            'redirectUrl' => route('security.inout_permits.show', $permit->id)
        ]);
    }

    public function show($id)
    {
        $this->permit = InOutPermit::with('approvedBy', 'approvedByBm', 'validatedBy')->findOrFail($id);
        $this->approvalChain = $this->approvalService->getApprovalChain($this->permit);
        return view('security::inout-permits.show', $this->data);
    }

    public function edit($id)
    {
        $this->permit = InOutPermit::findOrFail($id);
        return view('security::inout-permits.edit', $this->data);
    }

    public function update(InOutPermitRequest $request, $id)
    {
        $permit = InOutPermit::findOrFail($id);
        $permit->update($request->validated());
        return Reply::success(__('security::messages.permit_updated'));
    }

    public function destroy($id)
    {
        InOutPermit::findOrFail($id)->delete();
        return Reply::success(__('security::messages.permit_deleted'));
    }

    public function approve($id)
    {
        $this->permit = InOutPermit::findOrFail($id);
        $this->approvalChain = $this->approvalService->getApprovalChain($this->permit);
        return view('security::inout-permits.approve', $this->data);
    }

    public function processApproval(Request $request, $id)
    {
        $permit = InOutPermit::findOrFail($id);
        $level = $request->approval_level; // 'unit_owner', 'building_manager', 'security'

        if ($level === 'unit_owner') {
            $this->approvalService->approveByUnitOwner($permit, auth()->id());
        } elseif ($level === 'building_manager') {
            $this->approvalService->approveByBuildingManager($permit, auth()->id());
        } elseif ($level === 'security') {
            $this->approvalService->validateBySecurity($permit, auth()->id());
        }

        return Reply::success(__('security::messages.permit_approved'));
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
                InOutPermit::whereIn('id', $ids)->delete();
                return Reply::success(__('security::messages.permits_deleted'));
            default:
                return Reply::error(__('security::messages.action_not_found'));
        }
    }
}
