<?php

namespace Modules\StaffCompliance\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\StaffCompliance\Entities\ComplianceDocumentType;
use Modules\StaffCompliance\Entities\WorkerComplianceDocument;
use App\Models\User;

class WorkerComplianceDocumentController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('view_compliance') == 'none', 403);

        $query = WorkerComplianceDocument::with(['worker', 'documentType', 'verifier'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('worker_id')) {
            $query->where('user_id', $request->worker_id);
        }

        $documents     = $query->paginate(20);
        $documentTypes = ComplianceDocumentType::orderBy('name')->get();
        $workers       = User::scopeOnlyEmployee(User::query())->orderBy('name')->get();

        return view('staffcompliance::documents.index', compact('documents', 'documentTypes', 'workers'));
    }

    public function show($id)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('view_compliance') == 'none', 403);

        $document = WorkerComplianceDocument::with(['worker', 'documentType', 'verifier'])->findOrFail($id);

        return view('staffcompliance::documents.show', compact('document'));
    }

    public function store(Request $request)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('manage_compliance') == 'none', 403);

        $data = $request->validate([
            'user_id'           => 'required|exists:users,id',
            'document_type_id'  => 'required|exists:compliance_document_types,id',
            'document_number'   => 'nullable|string|max:255',
            'issuing_authority' => 'nullable|string|max:255',
            'issue_date'        => 'required|date',
            'expiry_date'       => 'nullable|date|after_or_equal:issue_date',
            'notes'             => 'nullable|string',
            'file'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('compliance-documents', 'public');
        }

        unset($data['file']);
        $data['status'] = 'pending_review';

        WorkerComplianceDocument::create($data);

        return response()->json(['status' => 'success', 'message' => __('staffcompliance::compliance.document_uploaded')]);
    }

    public function verify(Request $request, $id)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('verify_compliance_documents') == 'none', 403);

        $document = WorkerComplianceDocument::findOrFail($id);
        $document->status      = 'verified';
        $document->verified_by = user()->id;
        $document->verified_at = now();
        $document->rejection_reason = null;
        $document->save();

        return response()->json(['status' => 'success', 'message' => __('staffcompliance::compliance.document_verified')]);
    }

    public function reject(Request $request, $id)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('verify_compliance_documents') == 'none', 403);

        $data = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $document = WorkerComplianceDocument::findOrFail($id);
        $document->status           = 'rejected';
        $document->rejection_reason = $data['rejection_reason'];
        $document->verified_by      = user()->id;
        $document->verified_at      = now();
        $document->save();

        return response()->json(['status' => 'success', 'message' => __('staffcompliance::compliance.document_rejected')]);
    }

    public function myDocuments(Request $request)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);

        $documents     = WorkerComplianceDocument::with(['documentType', 'verifier'])
            ->where('user_id', user()->id)
            ->latest()
            ->get();

        $documentTypes = ComplianceDocumentType::orderBy('name')->get();

        return view('staffcompliance::documents.my_documents', compact('documents', 'documentTypes'));
    }
}
