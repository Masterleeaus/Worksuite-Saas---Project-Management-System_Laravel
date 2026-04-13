<?php

namespace Modules\FSMCore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\FSMCore\Models\FSMOrder;
use Modules\FSMCore\Models\FSMOrderAttachment;

/**
 * Handles file attachments (documents, photos, PDFs, etc.) for FSM job orders.
 *
 * Pattern adapted from FixitPro's UploadAttachmentComponent — every FSMOrder can
 * have an arbitrary number of document/file attachments uploaded by office staff
 * or field workers.
 */
class OrderAttachmentController extends Controller
{
    /**
     * Upload one file and attach it to an order.
     */
    public function store(Request $request, int $orderId)
    {
        $order = FSMOrder::findOrFail($orderId);

        $data = $request->validate([
            'file'        => 'required|file|max:20480', // 20 MB hard limit
            'description' => 'nullable|string|max:255',
        ]);

        $file     = $request->file('file');
        $path     = $file->store("fsm/orders/{$order->id}/attachments", 'public');
        $filename = $file->getClientOriginalName();

        FSMOrderAttachment::create([
            'fsm_order_id' => $order->id,
            'uploaded_by'  => Auth::id(),
            'filename'     => $filename,
            'path'         => $path,
            'mime_type'    => $file->getMimeType(),
            'size'         => $file->getSize(),
            'description'  => $data['description'] ?? null,
        ]);

        return redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', 'Attachment uploaded successfully.');
    }

    /**
     * Download (stream) a single attachment file.
     */
    public function download(int $orderId, int $attachmentId)
    {
        $order      = FSMOrder::findOrFail($orderId);
        $attachment = FSMOrderAttachment::where('fsm_order_id', $order->id)
            ->findOrFail($attachmentId);

        return Storage::disk('public')->download($attachment->path, $attachment->filename);
    }

    /**
     * Delete an attachment record and its stored file.
     */
    public function destroy(int $orderId, int $attachmentId)
    {
        $order      = FSMOrder::findOrFail($orderId);
        $attachment = FSMOrderAttachment::where('fsm_order_id', $order->id)
            ->findOrFail($attachmentId);

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return redirect()->route('fsmcore.orders.show', $order->id)
            ->with('success', 'Attachment deleted.');
    }
}
