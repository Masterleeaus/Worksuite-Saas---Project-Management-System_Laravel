<?php

namespace Modules\Suppliers\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        return view('suppliers::index');
    }

    public function list()
    {
        // Placeholder: in future fetch supplier data with pagination/search
        $suppliers = [];
        return view('suppliers::list', compact('suppliers'));
    }
}
