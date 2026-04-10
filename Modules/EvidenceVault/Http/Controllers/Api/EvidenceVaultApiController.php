<?php

namespace Modules\EvidenceVault\Http\Controllers\Api;

use App\Helper\Reply;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\EvidenceVault\Entities\EvidencePhoto;
use Modules\EvidenceVault\Entities\EvidenceSubmission;
use Modules\EvidenceVault\Http\Requests\StoreEvidenceRequest;

/**
 * API endpoints used by the PWA field-worker interface.
 *
 * All routes require the 'auth:sanctum' or 'auth:api' middleware
 * (configured in Routes/api.php).
 */
class EvidenceVaultApiController extends Controller
{
    /**
     * POST /api/evidence-vault/submit
     *
     * Accepts multipart/form-data with:
     *  - job_id           (optional integer)
     *  - job_reference    (optional string)
     *  - notes            (optional string)
     *  - signature_data   (optional base64 PNG data-URI)
     *  - client_signed    (optional bool)
     *  - photos[]         (required: one or more image files)
     *  - is_site_locked_photo[] (optional per-photo boolean flags, same index as photos[])
     */
    public function submit(StoreEvidenceRequest $request): JsonResponse
    {
        $disk     = config('evidence_vault.storage_disk', 'local');
        $basePath = config('evidence_vault.storage_path', 'evidence-vault');

        // Create the submission record first so we have an ID for the path.
        $submission = EvidenceSubmission::create([
            'company_id'     => auth()->user()->company_id ?? null,
            'job_id'         => $request->input('job_id'),
            'job_reference'  => $request->input('job_reference'),
            'submitted_by'   => auth()->id(),
            'signature_data' => $request->input('signature_data'),
            'client_signed'  => (bool) $request->input('client_signed', false),
            'notes'          => $request->input('notes'),
        ]);

        $lockFlags = $request->input('is_site_locked_photo', []);

        foreach ($request->file('photos', []) as $index => $file) {
            $diskFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $diskPath     = $basePath . '/' . $submission->id;

            Storage::disk($disk)->putFileAs($diskPath, $file, $diskFilename);

            EvidencePhoto::create([
                'submission_id'        => $submission->id,
                'original_filename'    => $file->getClientOriginalName(),
                'disk_filename'        => $diskFilename,
                'disk'                 => $disk,
                'disk_path'            => $diskPath,
                'mime_type'            => $file->getMimeType(),
                'file_size'            => $file->getSize(),
                'is_site_locked_photo' => (bool) ($lockFlags[$index] ?? false),
            ]);
        }

        $submission->load('photos');

        return response()->json(
            Reply::successWithData('Evidence submitted successfully.', [
                'submission_id' => $submission->id,
                'photo_count'   => $submission->photos->count(),
                'is_complete'   => $submission->isComplete(),
            ])
        );
    }

    /**
     * GET /api/evidence-vault/{submissionId}
     *
     * Return a summary of a submission (admin or owner only).
     */
    public function show(int $id): JsonResponse
    {
        $submission = EvidenceSubmission::with('photos')->findOrFail($id);

        // Only the submitter or an admin may read their own evidence via API.
        if (
            auth()->id() !== $submission->submitted_by
            && !auth()->user()->hasRole('admin')
        ) {
            abort(403);
        }

        return response()->json([
            'status'     => 'success',
            'submission' => $submission,
        ]);
    }
}
