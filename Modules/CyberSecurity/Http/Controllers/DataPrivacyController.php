<?php

namespace Modules\CyberSecurity\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CyberSecurity\Entities\DataPrivacyRequest;

class DataPrivacyController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'cybersecurity::app.data_privacy.title';
        $this->middleware(function ($request, $next) {
            abort_403(user()->permission('manage_data_privacy') == 'none');

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $companyId = company()->id ?? null;

        $this->requests = DataPrivacyRequest::where('company_id', $companyId)
            ->latest()
            ->paginate(15);

        $this->pendingCount     = DataPrivacyRequest::where('company_id', $companyId)->where('status', 'pending')->count();
        $this->inProgressCount  = DataPrivacyRequest::where('company_id', $companyId)->where('status', 'in_progress')->count();
        $this->completedCount   = DataPrivacyRequest::where('company_id', $companyId)->where('status', 'completed')->count();

        return view('cybersecurity::data-privacy.index', $this->data);
    }

    public function create()
    {
        return view('cybersecurity::data-privacy.create', $this->data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'             => 'required|in:access,deletion,rectification,portability',
            'requester_name'   => 'required|string|max:191',
            'requester_email'  => 'required|email|max:191',
            'notes'            => 'nullable|string|max:2000',
        ]);

        $companyId = company()->id ?? null;

        // Right of access / GDPR deadline: 30 days; deletion: 30 days
        $dueDate = now()->addDays(30);

        DataPrivacyRequest::create([
            'company_id'      => $companyId,
            'type'            => $request->type,
            'status'          => 'pending',
            'requester_name'  => $request->requester_name,
            'requester_email' => $request->requester_email,
            'notes'           => $request->notes,
            'due_date'        => $dueDate,
            'handled_by'      => user()->id,
        ]);

        return Reply::success(__('messages.recordSaved'));
    }

    public function show($id)
    {
        $companyId = company()->id ?? null;

        $this->privacyRequest = DataPrivacyRequest::where('company_id', $companyId)->findOrFail($id);

        return view('cybersecurity::data-privacy.show', $this->data);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,rejected',
            'notes'  => 'nullable|string|max:2000',
        ]);

        $companyId = company()->id ?? null;

        $privacyRequest = DataPrivacyRequest::where('company_id', $companyId)->findOrFail($id);
        $privacyRequest->status = $request->status;

        if ($request->notes) {
            $privacyRequest->notes = $request->notes;
        }

        if ($request->status === 'completed') {
            $privacyRequest->completed_at = now();
        }

        $privacyRequest->handled_by = user()->id;
        $privacyRequest->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    public function destroy($id)
    {
        $companyId = company()->id ?? null;

        DataPrivacyRequest::where('company_id', $companyId)->findOrFail($id)->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

}
