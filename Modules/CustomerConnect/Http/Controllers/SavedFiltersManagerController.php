<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\SavedFilter;

/**
 * Settings-page manager for a user's saved inbox filters.
 * FIX BUG 4: This controller was imported in web.php but did not exist.
 *
 * Contrast with SavedFiltersController (inline inbox quick-save).
 * This controller handles the dedicated settings page: list, set-default, delete.
 */
class SavedFiltersManagerController extends AccountBaseController
{
    public function index(Request $request)
    {
        $this->pageTitle = 'Customer Connect - Saved Filters';

        $filters = SavedFilter::query()
            ->where('user_id', user()->id)
            ->where('company_id', company()->id)
            ->orderBy('name')
            ->get();

        return view('customerconnect::settings.filters.index', compact('filters'));
    }

    public function setDefault(Request $request, int $id)
    {
        $filter = SavedFilter::where('user_id', user()->id)
            ->where('company_id', company()->id)
            ->findOrFail($id);

        // Clear any existing default for this user
        SavedFilter::where('user_id', user()->id)
            ->where('company_id', company()->id)
            ->update(['is_default' => false]);

        $filter->update(['is_default' => true]);

        return redirect()->back()->with('status', 'Default filter set.');
    }

    public function destroy(int $id)
    {
        SavedFilter::where('user_id', user()->id)
            ->where('company_id', company()->id)
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('status', 'Filter deleted.');
    }
}
