<?php

namespace Modules\FSMCore\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMOrderPhoto;

/**
 * WorkerPhotoController
 *
 * Allows field workers to attach photos and capture customer signatures
 * against a work order directly from their mobile device.
 *
 * Photo types:
 *   - photo     : before/after site images
 *   - signature : customer sign-off on completion
 */
class WorkerPhotoController extends Controller
{
    /**
     * POST /api/fsm/v1/orders/{id}/photos
     *
     * Upload a photo or signature for an order.
     *
     * Multipart form fields:
     *   - file    (required) image file (jpeg/png/gif/webp, max 10 MB)
     *   - type    (optional) "photo" | "signature"  (default: "photo")
     *   - caption (optional) free-text caption
     */
    public function store(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $request->validate([
            'file'    => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'type'    => 'nullable|in:photo,signature',
            'caption' => 'nullable|string|max:1024',
        ]);

        $type = $request->input('type', 'photo');
        $disk = config('filesystems.default', 'local');

        $path = $request->file('file')->store(
            "fsm/orders/{$id}/{$type}s",
            $disk
        );

        $photo = FSMOrderPhoto::create([
            'fsm_order_id' => $id,
            'uploaded_by'  => $request->user()->id,
            'type'         => $type,
            'path'         => $path,
            'caption'      => $request->input('caption'),
        ]);

        return response()->json([
            'message' => ucfirst($type) . ' uploaded successfully.',
            'photo'   => array_merge($photo->toArray(), [
                'url' => Storage::disk($disk)->url($path),
            ]),
        ], 201);
    }

    /**
     * GET /api/fsm/v1/orders/{id}/photos
     *
     * List all photos/signatures attached to an order.
     * The worker must be assigned to the order.
     */
    public function index(Request $request, int $id): JsonResponse
    {
        $order = FSMOrder::findOrFail($id);
        $this->authorizeWorker($request, $order);

        $disk   = config('filesystems.default', 'local');
        $photos = $order->photos()->with('worker')->orderBy('created_at')->get()
            ->map(fn(FSMOrderPhoto $p) => array_merge($p->toArray(), [
                'url' => Storage::disk($disk)->url($p->path),
            ]));

        return response()->json(['data' => $photos]);
    }

    /**
     * DELETE /api/fsm/v1/orders/{order_id}/photos/{photo_id}
     *
     * Remove a photo. Only the uploading worker may delete their own photo.
     */
    public function destroy(Request $request, int $orderId, int $photoId): JsonResponse
    {
        $order = FSMOrder::findOrFail($orderId);
        $this->authorizeWorker($request, $order);

        $photo = FSMOrderPhoto::where('fsm_order_id', $orderId)
            ->where('id', $photoId)
            ->firstOrFail();

        if ((int) $photo->uploaded_by !== (int) $request->user()->id) {
            return response()->json(['message' => 'You may only delete photos you uploaded.'], 403);
        }

        $disk = config('filesystems.default', 'local');
        Storage::disk($disk)->delete($photo->path);
        $photo->delete();

        return response()->json(['message' => 'Photo deleted.']);
    }

    /**
     * Ensure the authenticated worker is assigned to the order.
     */
    private function authorizeWorker(Request $request, FSMOrder $order): void
    {
        if ((int) $order->person_id !== (int) $request->user()->id) {
            abort(403, 'You are not assigned to this order.');
        }
    }
}
