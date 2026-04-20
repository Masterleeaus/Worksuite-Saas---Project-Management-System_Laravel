<?php

namespace Modules\FSMEquipmentWarranty\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FSMEquipmentWarranty\Models\FSMWarrantyProfile;

class EquipmentWarrantyComputeController extends Controller
{
    public function compute(Request $request): JsonResponse
    {
        $data = $request->validate([
            'profile_id' => 'required|integer|exists:fsm_warranty_profiles,id',
            'start_date' => 'required|date',
        ]);

        $profile = FSMWarrantyProfile::findOrFail($data['profile_id']);
        $endDate = $profile->computeEndDate(Carbon::parse($data['start_date']));

        return response()->json(['warranty_end_date' => $endDate->toDateString()]);
    }
}
