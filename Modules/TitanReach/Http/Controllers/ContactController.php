<?php

namespace Modules\TitanReach\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanReach\Models\ReachContact;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()?->company_id ?? null;
        $query = ReachContact::when($companyId, fn ($q) => $q->where('company_id', $companyId));

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
            $query->where(fn ($q) => $q->where('name', 'like', $search)
                ->orWhere('phone', 'like', $search)
                ->orWhere('email', 'like', $search));
        }

        $contacts = $query->orderBy('name')->paginate(30);

        return view('titanreach::contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('titanreach::contacts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:30',
            'email'            => 'nullable|email|max:255',
            'whatsapp_number'  => 'nullable|string|max:30',
            'telegram_chat_id' => 'nullable|string|max:100',
            'tags'             => 'nullable|array',
        ]);

        $data['company_id'] = auth()->user()?->company_id;

        ReachContact::create($data);

        return redirect()->route('titanreach.contacts.index')->with('success', 'Contact created.');
    }

    public function edit(int $id)
    {
        $contact = ReachContact::findOrFail($id);
        return view('titanreach::contacts.create', compact('contact'));
    }

    public function update(Request $request, int $id)
    {
        $contact = ReachContact::findOrFail($id);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:30',
            'email'            => 'nullable|email|max:255',
            'whatsapp_number'  => 'nullable|string|max:30',
            'telegram_chat_id' => 'nullable|string|max:100',
            'tags'             => 'nullable|array',
        ]);

        $contact->update($data);

        return redirect()->route('titanreach.contacts.index')->with('success', 'Contact updated.');
    }

    public function destroy(int $id)
    {
        ReachContact::findOrFail($id)->delete();

        return redirect()->route('titanreach.contacts.index')->with('success', 'Contact deleted.');
    }
}
