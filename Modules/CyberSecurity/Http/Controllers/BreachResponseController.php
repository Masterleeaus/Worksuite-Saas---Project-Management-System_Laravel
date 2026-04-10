<?php

namespace Modules\CyberSecurity\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CyberSecurity\Entities\BreachReport;

class BreachResponseController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'cybersecurity::app.breach_response.title';
        $this->middleware(function ($request, $next) {
            abort_403(user()->permission('manage_compliance') == 'none');

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $companyId = company()->id ?? null;

        $this->breaches = BreachReport::where('company_id', $companyId)
            ->latest()
            ->paginate(15);

        $this->openCount       = BreachReport::where('company_id', $companyId)->where('status', 'open')->count();
        $this->investigatingCount = BreachReport::where('company_id', $companyId)->where('status', 'investigating')->count();
        $this->resolvedCount   = BreachReport::where('company_id', $companyId)->where('status', 'resolved')->count();

        return view('cybersecurity::breach-response.index', $this->data);
    }

    public function create()
    {
        return view('cybersecurity::breach-response.create', $this->data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:191',
            'description'         => 'required|string',
            'severity'            => 'required|in:low,medium,high,critical',
            'breach_detected_at'  => 'required|date',
            'affected_users_count' => 'nullable|integer|min:0',
            'affected_data_types' => 'nullable|string|max:1000',
        ]);

        $companyId = company()->id ?? null;

        // GDPR / NDB: notify within 72 hours of discovery
        $notificationDeadline = \Carbon\Carbon::parse($request->breach_detected_at)->addHours(72);

        BreachReport::create([
            'company_id'           => $companyId,
            'title'                => $request->title,
            'description'          => $request->description,
            'severity'             => $request->severity,
            'status'               => 'open',
            'breach_detected_at'   => $request->breach_detected_at,
            'notification_deadline' => $notificationDeadline,
            'affected_users_count' => $request->affected_users_count ?? 0,
            'affected_data_types'  => $request->affected_data_types,
            'reported_by'          => user()->id,
        ]);

        return Reply::success(__('messages.recordSaved'));
    }

    public function show($id)
    {
        $companyId = company()->id ?? null;

        $this->breach = BreachReport::where('company_id', $companyId)->findOrFail($id);

        return view('cybersecurity::breach-response.show', $this->data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status'              => 'required|in:open,investigating,notified,resolved',
            'remediation_steps'   => 'nullable|string|max:2000',
            'assigned_to'         => 'nullable|integer|exists:users,id',
        ]);

        $companyId = company()->id ?? null;

        $breach = BreachReport::where('company_id', $companyId)->findOrFail($id);
        $breach->status            = $request->status;
        $breach->remediation_steps = $request->remediation_steps ?? $breach->remediation_steps;
        $breach->assigned_to       = $request->assigned_to ?? $breach->assigned_to;

        if ($request->status === 'notified' && !$breach->notified_at) {
            $breach->notified_at = now();
        }

        $breach->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    public function destroy($id)
    {
        $companyId = company()->id ?? null;

        BreachReport::where('company_id', $companyId)->findOrFail($id)->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }

}
