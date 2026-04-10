<?php

namespace Modules\CustomerConnect\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivacyExportController extends Controller
{
    /**
     * GDPR-style export for a Contact: threads + messages (CSV).
     * NOTE: This is NOT an inbox viewer; it's a compliance export endpoint.
     */
    public function exportContactCsv(Request $request, int $contactId): StreamedResponse
    {
        // Basic auth assumed via account middleware; add your own authorization policy as needed.
        $tenantId = optional($request->user())->company_id ?? null;

        $contact = DB::table('customerconnect_contacts')
            ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
            ->where('id', $contactId)
            ->first();

        abort_if(!$contact, 404);

        $filename = 'customerconnect_contact_'.$contactId.'_export.csv';

        return response()->streamDownload(function () use ($tenantId, $contactId) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['type', 'id', 'thread_id', 'direction', 'channel', 'created_at', 'status', 'body_text']);

            $threads = DB::table('customerconnect_threads')
                ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                ->where('contact_id', $contactId)
                ->select(['id', 'channel', 'status', 'subject', 'last_message_at'])
                ->get();

            foreach ($threads as $t) {
                fputcsv($out, ['thread', $t->id, $t->id, '', $t->channel, $t->last_message_at, $t->status, $t->subject ?? '']);
            }

            $messages = DB::table('customerconnect_messages')
                ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                ->whereIn('thread_id', $threads->pluck('id')->all())
                ->orderBy('created_at')
                ->select(['id','thread_id','direction','status','created_at','body_text'])
                ->cursor();

            foreach ($messages as $m) {
                fputcsv($out, ['message', $m->id, $m->thread_id, $m->direction, '', $m->created_at, $m->status, $m->body_text]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
