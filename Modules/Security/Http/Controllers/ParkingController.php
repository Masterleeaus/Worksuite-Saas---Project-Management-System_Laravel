<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\Parking;
use Modules\Security\Http\Requests\ParkingRequest;

class ParkingController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'security::app.parking';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        $this->parkings = Parking::all();
        return view('security::parking.index', $this->data);
    }

    public function create()
    {
        return view('security::parking.create', $this->data);
    }

    public function store(ParkingRequest $request)
    {
        $parking = Parking::create($request->validated());
        return Reply::successWithData(__('security::messages.parking_created'), [
            'redirectUrl' => route('security.parking.show', $parking->id)
        ]);
    }

    public function show($id)
    {
        $this->parking = Parking::with('unit', 'items')->findOrFail($id);
        return view('security::parking.show', $this->data);
    }

    public function edit($id)
    {
        $this->parking = Parking::findOrFail($id);
        return view('security::parking.edit', $this->data);
    }

    public function update(ParkingRequest $request, $id)
    {
        $parking = Parking::findOrFail($id);
        $parking->update($request->validated());
        return Reply::success(__('security::messages.parking_updated'));
    }

    public function destroy($id)
    {
        Parking::findOrFail($id)->delete();
        return Reply::success(__('security::messages.parking_deleted'));
    }

    public function export()
    {
        return response()->json(['status' => 'success']);
    }

    public function download($id)
    {
        return response()->json(['status' => 'success']);
    }

    public function applyQuickAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        switch ($action) {
            case 'delete':
                Parking::whereIn('id', $ids)->delete();
                return Reply::success(__('security::messages.parkings_deleted'));
            default:
                return Reply::error(__('security::messages.action_not_found'));
        }
    }
}
