<?php

namespace Modules\TitanIntegrations\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ZoneApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => [], 'message' => 'Zone management removed']);
    }
}
