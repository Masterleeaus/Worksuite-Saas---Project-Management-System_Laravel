<?php

namespace Modules\Faq\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([]);
    }

    public function show($id): JsonResponse
    {
        return response()->json([]);
    }
}
