<?php

namespace Modules\FSMKanban\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FsmKanbanController extends Controller
{
    public function index()
    {
        return view('fsmkanban::index');
    }

    public function create()
    {
        return view('fsmkanban::create');
    }

    public function store(Request $request)
    {
        return redirect()->route('fsmkanban.index');
    }

    public function show($id)
    {
        return view('fsmkanban::show', compact('id'));
    }

    public function edit($id)
    {
        return view('fsmkanban::edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('fsmkanban.index');
    }

    public function destroy($id)
    {
        return redirect()->route('fsmkanban.index');
    }
}
