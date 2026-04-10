<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\AccessCard;
use Modules\Security\Http\Requests\AccessCardRequest;
use Yajra\DataTables\Facades\DataTables;

class AccessCardController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'security::app.access_cards';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        $this->cards = AccessCard::all();
        return view('security::access-cards.index', $this->data);
    }

    public function create()
    {
        return view('security::access-cards.create', $this->data);
    }

    public function store(AccessCardRequest $request)
    {
        $card = AccessCard::create($request->validated());
        return Reply::successWithData(__('security::messages.access_card_created'), [
            'redirectUrl' => route('security.access_cards.show', $card->id)
        ]);
    }

    public function show($id)
    {
        $this->card = AccessCard::findOrFail($id);
        return view('security::access-cards.show', $this->data);
    }

    public function edit($id)
    {
        $this->card = AccessCard::findOrFail($id);
        return view('security::access-cards.edit', $this->data);
    }

    public function update(AccessCardRequest $request, $id)
    {
        $card = AccessCard::findOrFail($id);
        $card->update($request->validated());
        return Reply::success(__('security::messages.access_card_updated'));
    }

    public function destroy($id)
    {
        AccessCard::findOrFail($id)->delete();
        return Reply::success(__('security::messages.access_card_deleted'));
    }

    public function export()
    {
        return response()->json(['status' => 'success', 'message' => 'Export not yet implemented']);
    }

    public function download($id)
    {
        return response()->json(['status' => 'success', 'message' => 'Download not yet implemented']);
    }

    public function applyQuickAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        switch ($action) {
            case 'delete':
                AccessCard::whereIn('id', $ids)->delete();
                return Reply::success(__('security::messages.access_cards_deleted'));
            default:
                return Reply::error(__('security::messages.action_not_found'));
        }
    }
}
