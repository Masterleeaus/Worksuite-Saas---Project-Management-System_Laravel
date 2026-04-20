<?php

namespace Modules\ManagedPremises\Http\Controllers;


use Modules\ManagedPremises\Http\Controllers\Concerns\EnsuresManagedPremisesPermissions;
use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\ManagedPremises\Entities\Property;
use Modules\ManagedPremises\Http\Requests\StorePropertyRequest;
use Modules\ManagedPremises\Http\Requests\UpdatePropertyRequest;
use Modules\ManagedPremises\Domain\Premises\Actions\CreatePremiseAction;
use Modules\ManagedPremises\Domain\Premises\Actions\UpdatePremiseAction;
use Modules\ManagedPremises\Domain\Premises\Actions\ArchivePremiseAction;

class PropertiesController extends AccountBaseController
{
    public function __construct(
        private readonly CreatePremiseAction $createPremiseAction,
        private readonly UpdatePremiseAction $updatePremiseAction,
        private readonly ArchivePremiseAction $archivePremiseAction
    )
    {
        $this->ensureCanViewManagedPremises();
        parent::__construct();
        $this->pageTitle = 'managedpremises::app.menu.properties';
        $this->pageIcon = 'ti-home';
    }
    use EnsuresManagedPremisesPermissions;

    public function index()
    {
        $this->ensureCanViewManagedPremises();
        $viewPermission = user()->permission('managedpremises.view');
        abort_403(!in_array($viewPermission, ['all', 'owned', 'both']));

        $this->properties = Property::orderByDesc('id')->paginate(25);
        return view('managedpremises::properties.index', $this->data);
    }

    public function create()
    {
        $this->ensureCanViewManagedPremises();
        $addPermission = user()->permission('managedpremises.create');
        abort_403(!in_array($addPermission, ['all']));

        $this->property = new Property();
        $this->view = 'managedpremises::properties.create';

        if (request()->ajax()) {
            $html = view('managedpremises::properties.ajax.form', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => __('managedpremises::app.menu.properties')]);
        }

        return view('managedpremises::properties.create', $this->data);
    }

    public function store(StorePropertyRequest $request)
    {
        $this->ensureCanViewManagedPremises();
        $property = $this->createPremiseAction->handle(array_merge(
            $request->validated(),
            ['created_by' => user()->id ?? null]
        ));

        $redirectUrl = route('managedpremises.properties.show', $property->id);
        return Reply::successWithData(__('messages.recordSaved'), ['redirectUrl' => $redirectUrl]);
    }

    public function show($id)
    {
        $this->ensureCanViewManagedPremises();
        $viewPermission = user()->permission('managedpremises.view');
        abort_403(!in_array($viewPermission, ['all', 'owned', 'both']));

        $this->property = Property::with(['units', 'contacts', 'jobs'])->findOrFail($id);

        if (request()->ajax()) {
            $html = view('managedpremises::properties.ajax.show', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->property->name ?? __('managedpremises::app.menu.properties')]);
        }

        return view('managedpremises::properties.show', $this->data);
    }

    public function edit($id)
    {
        $this->ensureCanViewManagedPremises();
        $editPermission = user()->permission('managedpremises.update');
        abort_403(!in_array($editPermission, ['all']));

        $this->property = Property::findOrFail($id);

        if (request()->ajax()) {
            $html = view('managedpremises::properties.ajax.form', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => __('app.edit')]);
        }

        return view('managedpremises::properties.edit', $this->data);
    }

    public function update(UpdatePropertyRequest $request, $id)
    {
        $this->ensureCanViewManagedPremises();
        $property = Property::findOrFail($id);
        $this->updatePremiseAction->handle($property, $request->validated());

        $redirectUrl = route('managedpremises.properties.show', $property->id);
        return Reply::successWithData(__('messages.recordUpdated'), ['redirectUrl' => $redirectUrl]);
    }

    public function destroy($id)
    {
        $this->ensureCanViewManagedPremises();
        $deletePermission = user()->permission('managedpremises.delete');
        abort_403(!in_array($deletePermission, ['all']));

        $this->archivePremiseAction->handle(Property::findOrFail($id));

        $redirectUrl = route('managedpremises.properties.index');
        return Reply::successWithData(__('messages.deleteSuccess'), ['redirectUrl' => $redirectUrl]);
    }
}
