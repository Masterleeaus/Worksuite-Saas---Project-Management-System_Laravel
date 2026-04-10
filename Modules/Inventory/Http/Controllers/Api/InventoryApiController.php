<?php

namespace Modules\Inventory\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryApiController extends Controller
{
    public function ping()
    {
        return response()->json(['ok' => true, 'module' => 'inventory']);
    }
}
