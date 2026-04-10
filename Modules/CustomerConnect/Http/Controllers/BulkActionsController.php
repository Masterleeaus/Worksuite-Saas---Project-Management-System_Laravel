<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkActionsController extends AccountBaseController
{
    public function apply(Request $request)
    {
        $request->validate([
            'thread_ids' => 'required|array',
            'thread_ids.*' => 'integer',
            'action' => 'required|string',
        ]);

        $threadIds = $request->thread_ids;
        $action = $request->action;

        switch ($action) {
            case 'close':
                DB::table('customerconnect_threads')->whereIn('id', $threadIds)->update(['status' => 'closed']);
                break;
            case 'open':
                DB::table('customerconnect_threads')->whereIn('id', $threadIds)->update(['status' => 'open']);
                break;
            case 'assign':
                $request->validate(['assigned_to_user_id' => 'required|integer']);
                DB::table('customerconnect_threads')->whereIn('id', $threadIds)->update(['assigned_to_user_id' => (int)$request->assigned_to_user_id]);
                break;
            case 'tag':
                $request->validate(['tag_id' => 'required|integer']);
                foreach ($threadIds as $tid) {
                    DB::table('customerconnect_thread_tags')->updateOrInsert(
                        ['thread_id' => $tid, 'tag_id' => (int)$request->tag_id],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
                break;
        }

        return redirect()->back()->with('status', 'Bulk action applied.');
    }
}
