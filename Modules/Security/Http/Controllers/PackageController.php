<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\Package;
use Modules\Security\Http\Requests\PackageRequest;

class PackageController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'security::app.packages';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    public function index()
    {
        $this->packages = Package::all();
        return view('security::packages.index', $this->data);
    }

    public function create()
    {
        return view('security::packages.create', $this->data);
    }

    public function store(PackageRequest $request)
    {
        $package = Package::create($request->validated());
        return Reply::successWithData(__('security::messages.package_created'), [
            'redirectUrl' => route('security.packages.show', $package->id)
        ]);
    }

    public function show($id)
    {
        $this->package = Package::with('unit', 'courier', 'type', 'items')->findOrFail($id);
        return view('security::packages.show', $this->data);
    }

    public function edit($id)
    {
        $this->package = Package::findOrFail($id);
        return view('security::packages.edit', $this->data);
    }

    public function update(PackageRequest $request, $id)
    {
        $package = Package::findOrFail($id);
        $package->update($request->validated());
        return Reply::success(__('security::messages.package_updated'));
    }

    public function destroy($id)
    {
        Package::findOrFail($id)->delete();
        return Reply::success(__('security::messages.package_deleted'));
    }

    public function markReceived(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $package->update([
            'status' => 'received',
            'received_by' => $request->received_by,
            'received_date' => now(),
        ]);
        return Reply::success(__('security::messages.package_marked_received'));
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
                Package::whereIn('id', $ids)->delete();
                return Reply::success(__('security::messages.packages_deleted'));
            default:
                return Reply::error(__('security::messages.action_not_found'));
        }
    }
}
