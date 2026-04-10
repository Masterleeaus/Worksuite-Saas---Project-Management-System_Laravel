<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Modules\CustomerConnect\Entities\Audience;
use Modules\CustomerConnect\Http\Requests\StoreAudienceRequest;
use Modules\CustomerConnect\Http\Requests\UpdateAudienceRequest;

class AudienceController extends AccountBaseController
{
    public function index()
    {
        $this->pageTitle = 'Customer Connect - Audiences';

        $audiences = Audience::query()
            ->where('company_id', company()->id)
            ->latest()
            ->paginate(20);

        return view('customerconnect::customerconnect.audiences.index', compact('audiences'));
    }

    public function create()
    {
        return view('customerconnect::customerconnect.audiences.create');
    }

    public function store(StoreAudienceRequest $request)
    {
        $data               = $request->validated();
        $data['company_id'] = company()->id;

        $audience = Audience::create($data);

        return redirect()->route('customerconnect.audiences.edit', $audience)->with('success', 'Audience created');
    }

    public function edit(Audience $audience)
    {
        $this->authorizeCompany($audience);
        $audience->loadCount('members');
        return view('customerconnect::customerconnect.audiences.edit', compact('audience'));
    }

    public function update(UpdateAudienceRequest $request, Audience $audience)
    {
        $this->authorizeCompany($audience);
        $audience->update($request->validated());
        return back()->with('success', 'Audience updated');
    }

    public function destroy(Audience $audience)
    {
        $this->authorizeCompany($audience);
        $audience->delete();
        return redirect()->route('customerconnect.audiences.index')->with('success', 'Audience deleted');
    }

    private function authorizeCompany(Audience $audience): void
    {
        abort_unless((int)$audience->company_id === (int)company()->id, 404);
    }
}
