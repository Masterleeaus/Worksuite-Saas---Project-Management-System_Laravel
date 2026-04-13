<?php

namespace Modules\TitanGo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanGo\Models\TitanGoIssue;

/**
 * IssueAdminController
 *
 * Lists and shows field-worker issue reports submitted via Titan Go.
 * Only accessible to admin/manager users via web middleware.
 *
 * Routes:
 *   GET  /account/titan-go/issues          — index
 *   GET  /account/titan-go/issues/{id}     — show
 *   POST /account/titan-go/issues/{id}     — update status (resolve/escalate)
 */
class IssueAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = TitanGoIssue::query()->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('worker_id')) {
            $query->where('worker_id', $request->worker_id);
        }

        $issues  = $query->paginate(50)->appends($request->query());
        $types   = TitanGoIssue::distinct()->pluck('type')->sort()->values();
        $filter  = $request->only(['type', 'status', 'worker_id']);

        return view('titango::admin.issues.index', compact('issues', 'types', 'filter'));
    }

    public function show(int $id)
    {
        $issue = TitanGoIssue::findOrFail($id);
        return view('titango::admin.issues.show', compact('issue'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:open,resolved,escalated',
        ]);

        TitanGoIssue::findOrFail($id)->update(['status' => $request->status]);

        return redirect()->route('titango.admin.issues.index')
            ->with('success', 'Issue status updated.');
    }
}
