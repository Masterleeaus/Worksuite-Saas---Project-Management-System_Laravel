<?php
namespace Modules\CampaignCanvas\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Modules\CampaignCanvas\Http\Requests\UploadImageRequest;

class ImageUploadController extends AccountBaseController
{
    public function store(UploadImageRequest $request): JsonResponse
    {
        $path = $request->file('image')->store(
            config('campaigncanvas.upload_path', 'campaign-canvas/uploads'),
            'public'
        );

        return response()->json([
            'url'  => Storage::disk('public')->url($path),
            'path' => $path,
        ], 201);
    }
}
