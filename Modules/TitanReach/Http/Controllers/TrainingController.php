<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachCampaignEmbedding;

class TrainingController extends Controller
{
    public function index()
    {
        $companyId  = auth()->user()?->company_id ?? null;
        $embeddings = ReachCampaignEmbedding::when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('titanreach::training.index', compact('embeddings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'source_type' => 'required|in:url,pdf,text,qa',
            'source_url'  => 'nullable|url',
            'content'     => 'required|string',
        ]);

        $data['company_id'] = auth()->user()?->company_id;

        ReachCampaignEmbedding::create($data);

        return back()->with('success', 'Training source added.');
    }

    public function destroy(int $id)
    {
        ReachCampaignEmbedding::findOrFail($id)->delete();

        return back()->with('success', 'Training source removed.');
    }
}
