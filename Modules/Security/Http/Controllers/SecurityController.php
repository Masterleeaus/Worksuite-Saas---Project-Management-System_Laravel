<?php

namespace Modules\Security\Http\Controllers;

use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountBaseController;
use Modules\Security\Entities\AccessCard;
use Modules\Security\Entities\InOutPermit;
use Modules\Security\Entities\WorkPermit;
use Modules\Security\Entities\Package;
use Modules\Security\Entities\Parking;
use Modules\Security\Entities\Note;
use Modules\Security\Entities\SecurityRecord;

class SecurityController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'security::app.security_management';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('security', $this->user->modules));
            return $next($request);
        });
    }

    public function dashboard()
    {
        $this->accessCards = AccessCard::count();
        $this->inOutPermits = InOutPermit::count();
        $this->workPermits = WorkPermit::count();
        $this->packages = Package::count();
        $this->parkingRecords = Parking::count();
        $this->notes = Note::count();
        $this->securityRecords = SecurityRecord::count();

        $this->pendingApprovals = InOutPermit::whereNull('approved_by')->count() +
                                  WorkPermit::whereNull('approved_by')->count();

        return view('security::dashboard', $this->data);
    }

    public function auditTrail()
    {
        $this->pageTitle = 'security::app.audit_trail';
        $this->records = SecurityRecord::orderBy('created_at', 'desc')->paginate(20);
        return view('security::audit-trail', $this->data);
    }

    public function approvalsQueue()
    {
        $this->pageTitle = 'security::app.pending_approvals';
        $this->inOutPermitsPending = InOutPermit::whereNull('approved_by')->get();
        $this->workPermitsPending = WorkPermit::whereNull('approved_by')->get();
        return view('security::approvals-queue', $this->data);
    }
}
