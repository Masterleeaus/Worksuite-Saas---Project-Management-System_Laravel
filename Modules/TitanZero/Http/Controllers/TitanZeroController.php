<?php

namespace Modules\TitanZero\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;

class TitanZeroController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('titanzero::app.menu.titanZero') ?? 'Titan Zero';
    }

    public function index()
    {
        return view('titanzero::pages.dashboard');
    }

    public function help()
    {
        return view('titanzero::pages.help');
    }

    public function chat()
    {
        return view('titanzero::pages.chat');
    }

    public function generators()
    {
        return view('titanzero::pages.generators', [
            'items' => config('titanzero.generators', []),
        ]);
    }

    public function templates()
    {
        return view('titanzero::pages.templates', [
            'items' => config('titanzero.templates', []),
        ]);
    }


    public function settings()
    {
        return view('titanzero::pages.settings', [
            'features' => config('titanzero.cleaning_features', []),
        ]);
    }

    public function saveSettings(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'enabled_features' => ['nullable', 'array'],
        ]);

        // Store per-company settings (simple JSON blob in app settings or cache)
        $features = $request->input('enabled_features', []);
        \Illuminate\Support\Facades\Cache::put(
            'titanzero.features.' . (auth()->user()->company_id ?? 0),
            $features,
            now()->addDays(30)
        );

        return back()->with('success', __('TitanZero settings saved.'));
    }

    public function ping()
    {
        return response()->json(['status' => 'ok', 'module' => 'titanzero', 'pass' => 3]);
    }
}
