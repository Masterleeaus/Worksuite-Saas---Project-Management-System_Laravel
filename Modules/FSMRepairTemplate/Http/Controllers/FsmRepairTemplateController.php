<?php

namespace Modules\FSMRepairTemplate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FsmRepairTemplateController extends Controller
{
    public function index()
    {
        return view('fsmrepairtemplate::index');
    }

    public function create()
    {
        return view('fsmrepairtemplate::create');
    }

    public function store(Request $request)
    {
        return redirect()->route('fsmrepairtemplate.index');
    }

    public function show($id)
    {
        return view('fsmrepairtemplate::show', compact('id'));
    }

    public function edit($id)
    {
        return view('fsmrepairtemplate::edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('fsmrepairtemplate.index');
    }

    public function destroy($id)
    {
        return redirect()->route('fsmrepairtemplate.index');
    }
}
