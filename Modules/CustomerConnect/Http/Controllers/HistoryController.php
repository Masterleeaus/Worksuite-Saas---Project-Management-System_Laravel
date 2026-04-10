<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Modules\CustomerConnect\Entities\Newsletters;

/**
 * Newsletter send history (legacy newsletter module history).
 * FIX BUG 5: Routes were defined but this controller had wrong view namespace
 * and referenced a non-existent route name. Both corrected here.
 */
class HistoryController extends AccountBaseController
{
    public function index()
    {
        $this->pageTitle = 'Customer Connect - History';

        if (!\Auth::user()->isAbleTo('newsletter history manage')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $newsletters = Newsletters::query()
            ->where('workspace_id', getActiveWorkSpace())
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('customerconnect::historys.index', compact('newsletters'));
    }

    public function show(int $id)
    {
        if (!\Auth::user()->isAbleTo('newsletter history show')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        $newsletter = Newsletters::findOrFail($id);

        return view('customerconnect::historys.view', compact('newsletter'));
    }

    public function destroy(int $id)
    {
        if (!\Auth::user()->isAbleTo('newsletter history delete')) {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

        Newsletters::findOrFail($id)->delete();

        // FIX: corrected route name from non-existent 'newsletter.customerconnect.history.index'
        return redirect()->route('customerconnect.history.index')
            ->with('success', __('History deleted successfully'));
    }
}
