<?php

namespace Modules\BookingModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\BookingModule\Entities\AppointmentNotificationLog;
use Modules\BookingModule\Services\Notifications\InAppNotificationService;

class NotificationCenterController extends Controller
{
    public function index(Request $request)
    {
        // Worksuite typically uses permission strings; keep Gate/Policy optional.
        if (function_exists('user') && method_exists(user(), 'permission')) {
            abort_unless(user()->permission('appointment view notifications'), 403);
        }

        $q = trim((string)$request->get('q', ''));

        $rows = AppointmentNotificationLog::query()
            ->where('user_id', auth()->id())
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                        ->orWhere('message', 'like', "%{$q}%")
                        ->orWhere('event', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('sent_at')
            ->paginate(25);

        return view('bookingmodule::notifications.index', compact('rows'));
    }

    public function markRead(int $id, InAppNotificationService $svc)
    {
        $svc->markRead($id, auth()->id());
        return redirect()->back()->with('status', __('bookingmodule::notifications.marked'));
    }
}
