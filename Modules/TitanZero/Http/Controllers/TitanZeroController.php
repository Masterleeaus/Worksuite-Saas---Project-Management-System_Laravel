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


    public function ping()
    {
        return response()->json(['status' => 'ok', 'module' => 'titanzero', 'pass' => 3]);
    }
}
