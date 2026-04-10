<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\SavedFilter;

class SavedFiltersController extends AccountBaseController
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'criteria' => 'required|array',
        ]);

        SavedFilter::create([
            'user_id' => user()->id,
            'name' => $request->name,
            'criteria' => $request->criteria,
        ]);

        return redirect()->back()->with('status', 'Saved filter created.');
    }

    public function delete($id)
    {
        SavedFilter::where('id', $id)->where('user_id', user()->id)->delete();
        return redirect()->back()->with('status', 'Saved filter deleted.');
    }
}
