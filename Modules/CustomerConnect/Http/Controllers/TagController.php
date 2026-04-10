<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\CustomerConnect\Entities\Tag;

/**
 * Manages thread tags for the tenant.
 * FIX BUG 4: This controller was imported in web.php but did not exist.
 */
class TagController extends AccountBaseController
{
    public function index(Request $request)
    {
        $this->pageTitle = 'Customer Connect - Tags';

        $tags = Tag::query()
            ->where('company_id', company()->id)
            ->orderBy('name')
            ->paginate(50);

        return view('customerconnect::settings.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:80',
            'color' => 'nullable|string|max:20',
        ]);

        Tag::create([
            'company_id' => company()->id,
            'name'       => trim($request->name),
            'color'      => $request->color ?? '#6b7280',
        ]);

        return redirect()->back()->with('status', 'Tag created.');
    }

    public function update(Request $request, int $id)
    {
        $tag = Tag::where('company_id', company()->id)->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:80',
            'color' => 'nullable|string|max:20',
        ]);

        $tag->update([
            'name'  => trim($request->name),
            'color' => $request->color ?? $tag->color,
        ]);

        return redirect()->back()->with('status', 'Tag updated.');
    }

    public function destroy(int $id)
    {
        Tag::where('company_id', company()->id)->where('id', $id)->delete();

        return redirect()->back()->with('status', 'Tag deleted.');
    }
}
