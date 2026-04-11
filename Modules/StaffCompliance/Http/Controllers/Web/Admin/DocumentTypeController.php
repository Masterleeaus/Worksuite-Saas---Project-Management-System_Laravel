<?php

namespace Modules\StaffCompliance\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\StaffCompliance\Entities\ComplianceDocumentType;

class DocumentTypeController extends Controller
{
    public function index()
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('manage_compliance_document_types') == 'none', 403);

        $types = ComplianceDocumentType::orderBy('name')->get();

        return view('staffcompliance::types.index', compact('types'));
    }

    public function store(Request $request)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('manage_compliance_document_types') == 'none', 403);

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'code'                  => 'required|string|max:100|unique:compliance_document_types,code',
            'vertical'              => 'nullable|array',
            'is_mandatory'          => 'boolean',
            'renewal_period_months' => 'nullable|integer|min:1',
            'description'           => 'nullable|string',
        ]);

        ComplianceDocumentType::create($data);

        return response()->json(['status' => 'success', 'message' => __('staffcompliance::compliance.document_type_created')]);
    }

    public function update(Request $request, $id)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('manage_compliance_document_types') == 'none', 403);

        $type = ComplianceDocumentType::findOrFail($id);

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'vertical'              => 'nullable|array',
            'is_mandatory'          => 'boolean',
            'renewal_period_months' => 'nullable|integer|min:1',
            'description'           => 'nullable|string',
        ]);

        $type->update($data);

        return response()->json(['status' => 'success', 'message' => __('staffcompliance::compliance.document_type_updated')]);
    }

    public function destroy($id)
    {
        abort_if(!in_array('staffcompliance', user_modules()), 403);
        abort_if(user()->permission('manage_compliance_document_types') == 'none', 403);

        ComplianceDocumentType::findOrFail($id)->delete();

        return response()->json(['status' => 'success', 'message' => __('staffcompliance::compliance.document_type_deleted')]);
    }
}
