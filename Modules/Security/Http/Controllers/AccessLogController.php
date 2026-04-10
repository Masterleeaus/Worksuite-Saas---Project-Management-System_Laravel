<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\AccessLog;
use Modules\Security\Services\AccessLogService;
use Yajra\DataTables\Facades\DataTables;

class AccessLogController extends AccountBaseController
{
    protected $accessLogService;

    public function __construct(AccessLogService $accessLogService)
    {
        parent::__construct();
        $this->accessLogService = $accessLogService;
        $this->pageTitle = 'security::app.access_logs';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    /**
     * Display access logs list
     */
    public function index()
    {
        $this->pageTitle = 'security::app.access_logs';
        $this->activitySummary = $this->accessLogService->getActivitySummary(company()->id, 7);
        return view('security::access-logs.index', $this->data);
    }

    /**
     * Get access logs for DataTable
     */
    public function data(Request $request)
    {
        $query = AccessLog::with(['user', 'unit', 'accessCard', 'inOutPermit', 'workPermit', 'parking']);

        // Filters
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('timestamp', '<=', $request->date_to);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        return DataTables::of($query)
            ->addColumn('timestamp', function ($log) {
                return $log->timestamp->format('Y-m-d H:i:s');
            })
            ->addColumn('event', function ($log) {
                $colors = [
                    AccessLog::EVENT_BADGE_SWIPE => 'primary',
                    AccessLog::EVENT_ENTRY_GRANTED => 'success',
                    AccessLog::EVENT_ENTRY_DENIED => 'danger',
                    AccessLog::EVENT_EXIT => 'warning',
                    AccessLog::EVENT_VEHICLE_ENTRY => 'info',
                    AccessLog::EVENT_PERMIT_PRESENTED => 'secondary',
                ];
                $color = $colors[$log->event_type] ?? 'secondary';
                return "<span class='badge bg-label-{$color}'>".str_replace('_', ' ', ucfirst($log->event_type)).'</span>';
            })
            ->addColumn('status', function ($log) {
                $colors = [
                    AccessLog::STATUS_GRANTED => 'success',
                    AccessLog::STATUS_DENIED => 'danger',
                    AccessLog::STATUS_ALERT => 'warning',
                    AccessLog::STATUS_PENDING => 'secondary',
                ];
                $color = $colors[$log->status] ?? 'secondary';
                return "<span class='badge bg-label-{$color}'>".ucfirst($log->status).'</span>';
            })
            ->addColumn('entity', function ($log) {
                if ($log->accessCard) {
                    return "Access Card #{$log->accessCard->card_number}";
                } elseif ($log->inOutPermit) {
                    return "In/Out Permit #{$log->inOutPermit->id}";
                } elseif ($log->workPermit) {
                    return "Work Permit #{$log->workPermit->id}";
                } elseif ($log->parking) {
                    return "Vehicle {$log->parking->vehicle_plate}";
                }
                return 'N/A';
            })
            ->addColumn('user', function ($log) {
                return $log->user ? $log->user->name : 'System';
            })
            ->addColumn('actions', function ($log) {
                return "<a href='#' onclick='viewAccessLogDetails({$log->id})' class='btn btn-sm btn-info'>
                    <i class='bx bx-show'></i> View
                </a>";
            })
            ->rawColumns(['event', 'status', 'actions'])
            ->make(true);
    }

    /**
     * Show access log details
     */
    public function show($id)
    {
        $this->log = AccessLog::with(['user', 'unit', 'accessCard', 'inOutPermit', 'workPermit', 'parking'])->findOrFail($id);
        $this->pageTitle = 'security::app.access_log_details';
        return view('security::access-logs.show', $this->data);
    }

    /**
     * Get denied access attempts
     */
    public function deniedAttempts()
    {
        $this->pageTitle = 'security::app.denied_access_attempts';
        $this->deniedLogs = $this->accessLogService->getDeniedAttempts(company()->id, 50);
        return view('security::access-logs.denied-attempts', $this->data);
    }

    /**
     * Get access trail for specific entity
     */
    public function trail(Request $request)
    {
        $request->validate([
            'entity_type' => 'required|in:access_card,permit,work_permit,parking',
            'entity_id' => 'required|integer',
        ]);

        $trail = AccessLog::query();

        switch ($request->entity_type) {
            case 'access_card':
                $trail->where('access_card_id', $request->entity_id);
                break;
            case 'permit':
                $trail->where('inout_permit_id', $request->entity_id);
                break;
            case 'work_permit':
                $trail->where('work_permit_id', $request->entity_id);
                break;
            case 'parking':
                $trail->where('parking_id', $request->entity_id);
                break;
        }

        return Reply::dataOnly([
            'status' => 'success',
            'data' => $trail->recent()->get(),
        ]);
    }

    /**
     * Activity summary for dashboard
     */
    public function summary()
    {
        return Reply::dataOnly([
            'status' => 'success',
            'data' => $this->accessLogService->getActivitySummary(company()->id, 7),
        ]);
    }

    /**
     * Export access logs
     */
    public function export(Request $request)
    {
        $logs = AccessLog::query();

        if ($request->filled('location')) {
            $logs->where('location', $request->location);
        }

        if ($request->filled('date_from')) {
            $logs->whereDate('timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $logs->whereDate('timestamp', '<=', $request->date_to);
        }

        $logs = $logs->orderBy('timestamp', 'desc')->get();

        return response()->json(['status' => 'success', 'message' => 'Export not yet implemented']);
    }

    /**
     * Delete old access logs
     */
    public function cleanup(Request $request)
    {
        $request->validate(['days_to_keep' => 'required|integer|min:1']);

        $deleted = $this->accessLogService->cleanupOldLogs($request->days_to_keep);

        return Reply::success(__('security::messages.access_logs_cleaned', ['count' => $deleted]));
    }
}
