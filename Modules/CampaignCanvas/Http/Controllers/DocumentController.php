<?php
namespace Modules\CampaignCanvas\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\CampaignCanvas\Entities\Document;
use Modules\CampaignCanvas\Http\Requests\StoreDocumentRequest;

class DocumentController extends AccountBaseController
{
    public function store(StoreDocumentRequest $request): JsonResponse
    {
        $doc = Document::create([
            'company_id' => company()->id,
            'user_id'    => auth()->id(),
            'uuid'       => (string) Str::uuid(),
            'name'       => $request->input('name', 'Untitled Design'),
            'payload'    => $request->input('payload'),
            'preview'    => $request->input('preview'),
        ]);

        return response()->json(['uuid' => $doc->uuid, 'id' => $doc->id], 201);
    }

    public function update(Request $request, string $uuid): JsonResponse
    {
        $doc = $this->findOwned($uuid);

        $doc->update(array_filter([
            'name'    => $request->input('name', $doc->name),
            'payload' => $request->input('payload', $doc->payload),
            'preview' => $request->input('preview', $doc->preview),
        ], fn($v) => $v !== null));

        $doc->refresh();

        return response()->json(['uuid' => $doc->uuid, 'updated_at' => $doc->updated_at]);
    }

    public function destroy(string $uuid): JsonResponse
    {
        $doc = $this->findOwned($uuid);

        // Remove preview file if stored locally
        if ($doc->preview && \Illuminate\Support\Facades\Storage::disk('public')->exists($doc->preview)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->preview);
        }

        $doc->delete();

        return response()->json(['deleted' => true]);
    }

    public function duplicate(string $uuid): JsonResponse
    {
        $original = $this->findOwned($uuid);

        $copy = $original->replicate(['uuid', 'created_at', 'updated_at']);
        $copy->uuid    = (string) Str::uuid();
        $copy->name    = $original->name . ' (copy)';
        $copy->preview = null;
        $copy->save();

        return response()->json(['uuid' => $copy->uuid, 'id' => $copy->id], 201);
    }

    public function rename(Request $request, string $uuid): JsonResponse
    {
        $request->validate(['name' => ['required', 'string', 'max:255']]);
        $doc = $this->findOwned($uuid);
        $doc->update(['name' => $request->input('name')]);

        return response()->json(['uuid' => $doc->uuid, 'name' => $doc->name]);
    }

    private function findOwned(string $uuid): Document
    {
        return Document::where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }
}
