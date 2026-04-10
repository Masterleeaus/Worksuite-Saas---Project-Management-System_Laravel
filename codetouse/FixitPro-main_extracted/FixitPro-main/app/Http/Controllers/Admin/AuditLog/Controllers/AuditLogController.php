<?php

namespace App\Http\Controllers\Admin\AuditLog\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        if (!validatePermissions('audit-logs')) {
            abort(403, 'Unauthorized access');
        }

        $query = AuditLog::with('admin')->latest('created_at');

        // Filter by action
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filter by admin
        if ($request->has('admin_id') && $request->admin_id) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('model_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('admin_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(50);
        
        $actions = AuditLog::distinct()->pluck('action');
        $admins = \App\Models\Admin::select('id', 'name')->get();

        return view('admin.AuditLog.index', compact('logs', 'actions', 'admins'));
    }

    public function show(int $id): View
    {
        if (!validatePermissions('audit-logs')) {
            abort(403, 'Unauthorized access');
        }

        $log = AuditLog::with('admin')->findOrFail($id);
        
        return view('admin.AuditLog.show', compact('log'));
    }
}
