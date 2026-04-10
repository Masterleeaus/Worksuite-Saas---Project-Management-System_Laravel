<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\Note;

class NoteController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'security::app.notes';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        $this->notes = Note::all();
        return view('security::notes.index', $this->data);
    }

    public function create()
    {
        return view('security::notes.create', $this->data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = Note::create($request->validated());
        return Reply::successWithData(__('security::messages.note_created'), [
            'redirectUrl' => route('security.notes.show', $note->id)
        ]);
    }

    public function show($id)
    {
        $this->note = Note::findOrFail($id);
        return view('security::notes.show', $this->data);
    }

    public function edit($id)
    {
        $this->note = Note::findOrFail($id);
        return view('security::notes.edit', $this->data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = Note::findOrFail($id);
        $note->update($request->validated());
        return Reply::success(__('security::messages.note_updated'));
    }

    public function destroy($id)
    {
        Note::findOrFail($id)->delete();
        return Reply::success(__('security::messages.note_deleted'));
    }

    public function export()
    {
        return response()->json(['status' => 'success']);
    }

    public function applyQuickAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        switch ($action) {
            case 'delete':
                Note::whereIn('id', $ids)->delete();
                return Reply::success(__('security::messages.notes_deleted'));
            default:
                return Reply::error(__('security::messages.action_not_found'));
        }
    }
}
