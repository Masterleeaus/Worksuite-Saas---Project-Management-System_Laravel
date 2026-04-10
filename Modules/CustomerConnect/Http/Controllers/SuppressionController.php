<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\Suppression;

class SuppressionController extends AccountBaseController
{
    public function index(Request $request)
    {
        $q = Suppression::query()->where('company_id', company()->id);

        if ($term = $request->get('q')) {
            $q->where(function ($w) use ($term) {
                $w->where('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('telegram_user_id', 'like', "%{$term}%");
            });
        }

        $items = $q->latest()->paginate(20);

        return view('customerconnect::settings.suppressions.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:50',
            'telegram_user_id' => 'nullable|string|max:191',
            'reason' => 'nullable|string|max:191',
        ]);

        Suppression::create([
            'company_id' => company()->id,
            'email' => $request->email,
            'phone' => $request->phone,
            'telegram_user_id' => $request->telegram_user_id,
            'reason' => $request->reason ?: 'manual',
        ]);

        return redirect()->back()->with('status', 'Suppression added.');
    }

    public function destroy($id)
    {
        Suppression::where('company_id', company()->id)->where('id', $id)->delete();
        return redirect()->back()->with('status', 'Suppression removed.');
    }
}
