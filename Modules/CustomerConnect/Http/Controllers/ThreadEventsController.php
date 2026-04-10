<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CustomerConnect\Services\Premium\SlaService;

class ThreadEventsController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Thread Events';
    }

    public function index($threadId, SlaService $sla)
    {
        $events = DB::table('customerconnect_message_events')
            ->join('customerconnect_messages', 'customerconnect_messages.id', '=', 'customerconnect_message_events.message_id')
            ->where('customerconnect_messages.thread_id', $threadId)
            ->orderBy('customerconnect_message_events.created_at', 'desc')
            ->select('customerconnect_message_events.*', 'customerconnect_messages.direction', 'customerconnect_messages.provider', 'customerconnect_messages.provider_message_id')
            ->paginate(50);

        $slaMetrics = $sla->computeForThread((int)$threadId);

        return view('customerconnect::inbox.thread_events', [
            'threadId' => $threadId,
            'events' => $events,
            'sla' => $slaMetrics,
        ]);
    }
}
