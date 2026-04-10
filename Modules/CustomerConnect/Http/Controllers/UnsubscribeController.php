<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\Unsubscribe;

class UnsubscribeController extends AccountBaseController
{
    public function index(Request $request)
    {
        $q = Unsubscribe::query()->where('company_id', company()->id);

        if ($term = $request->get('q')) {
            $q->where(function ($w) use ($term) {
                $w->where('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%")
                  ->orWhere('channel', 'like', "%{$term}%");
            });
        }

        $items = $q->latest()->paginate(20);

        return view('customerconnect::settings.unsubscribes.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:50',
            'channel' => 'required|string|max:30',
            'reason' => 'nullable|string|max:191',
        ]);

        Unsubscribe::create([
            'company_id' => company()->id,
            'email' => $request->email,
            'phone' => $request->phone,
            'channel' => $request->channel,
            'reason' => $request->reason ?: 'manual',
        ]);

        return redirect()->back()->with('status', 'Unsubscribe added.');
    }

    public function destroy($id)
    {
        Unsubscribe::where('company_id', company()->id)->where('id', $id)->delete();
        return redirect()->back()->with('status', 'Unsubscribe removed.');
    }
}
