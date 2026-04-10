<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function index()
    {
        return view('inventory::index');
    }
}
