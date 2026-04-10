<?php

namespace Modules\EvidenceVault\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\EvidenceVault\Entities\EvidenceSubmission;

/**
 * Admin-facing controller for browsing and reviewing evidence submissions.
 */
class EvidenceVaultController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Evidence Vault';
    }

    /**
     * List all submissions, with optional filtering by job, date range, and cleaner.
     */
    public function index(Request $request)
    {
        abort_403(!$this->user->permission('view_evidence_vault'));

        $query = EvidenceSubmission::with(['photos', 'submitter'])
            ->orderByDesc('created_at');

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->input('job_id'));
        }

        if ($request->filled('job_reference')) {
            $query->where('job_reference', 'like', '%' . $request->input('job_reference') . '%');
        }

        if ($request->filled('submitted_by')) {
            $query->where('submitted_by', $request->input('submitted_by'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $this->submissions = $query->paginate(20)->withQueryString();

        return view('evidence_vault::index', $this->data);
    }

    /**
     * Show a single evidence submission with all photos and signature.
     */
    public function show(int $id)
    {
        abort_403(!$this->user->permission('view_evidence_vault'));

        $this->submission = EvidenceSubmission::with(['photos', 'submitter'])
            ->findOrFail($id);

        return view('evidence_vault::show', $this->data);
    }

    /**
     * Delete a submission (and cascade-delete its photos via DB foreign key).
     */
    public function destroy(int $id)
    {
        abort_403(!$this->user->permission('delete_evidence_vault'));

        $submission = EvidenceSubmission::findOrFail($id);

        // Delete physical files.
        foreach ($submission->photos as $photo) {
            try {
                \Illuminate\Support\Facades\Storage::disk($photo->disk)
                    ->delete($photo->disk_path . '/' . $photo->disk_filename);
            } catch (\Exception $e) {
                // Non-fatal – log but continue.
                \Illuminate\Support\Facades\Log::warning('EvidenceVault: could not delete photo file', [
                    'photo_id' => $photo->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        $submission->delete();

        return Reply::success('Evidence submission deleted successfully.');
    }
}
