<?php
// This file use for handle super admin setting page

namespace Modules\TitanZero\Http\Controllers\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanZero\Entities\TitanZeroUsage;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($settings)
    {
        $today = now()->startOfDay();

        $usages = TitanZeroUsage::where('created_at', '>=', $today)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $quota = config('aiassistant.quota', []);

        return view('titanzero::super-admin.settings.index', [
            'settings' => $settings,
            'usages'   => $usages,
            'quota'    => $quota,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
