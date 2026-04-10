<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachContactList;
use Modules\TitanReach\Models\ReachContact;

class ContactListController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()?->company_id ?? null;
        $lists = ReachContactList::withCount('contacts')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->paginate(20);

        return view('titanreach::lists.index', compact('lists'));
    }

    public function create()
    {
        return view('titanreach::lists.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['company_id'] = auth()->user()?->company_id;
        ReachContactList::create($data);

        return redirect()->route('titanreach.lists.index')->with('success', 'List created.');
    }

    public function show(int $id)
    {
        $list     = ReachContactList::with('contacts')->findOrFail($id);
        $contacts = $list->contacts()->paginate(20);

        return view('titanreach::lists.show', compact('list', 'contacts'));
    }

    public function addContact(Request $request, int $id)
    {
        $list = ReachContactList::findOrFail($id);
        $request->validate(['contact_id' => 'required|integer|exists:reach_contacts,id']);

        $list->contacts()->syncWithoutDetaching([$request->input('contact_id')]);

        return back()->with('success', 'Contact added to list.');
    }

    public function removeContact(int $id, int $contactId)
    {
        $list = ReachContactList::findOrFail($id);
        $list->contacts()->detach($contactId);

        return back()->with('success', 'Contact removed from list.');
    }

    public function destroy(int $id)
    {
        ReachContactList::findOrFail($id)->delete();

        return redirect()->route('titanreach.lists.index')->with('success', 'List deleted.');
    }
}
