<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\CustomerConnect\Entities\Delivery;

class ExportController extends AccountBaseController
{
    public function deliveries(Request $request): StreamedResponse
    {
        $companyId = company()->id;

        $fileName = 'customerconnect_deliveries_' . now()->format('Ymd_His') . '.csv';

        $query = Delivery::query()->where('company_id', $companyId)->latest();

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id','campaign_id','run_id','step_id','channel','status','to','provider','provider_message_id','error','scheduled_for','created_at']);

            $query->chunk(1000, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->campaign_id,
                        $r->run_id,
                        $r->step_id,
                        $r->channel,
                        $r->status,
                        $r->to ?? ($r->email ?? $r->phone ?? $r->telegram_user_id),
                        $r->provider,
                        $r->provider_message_id,
                        $r->error,
                        $r->scheduled_for,
                        $r->created_at,
                    ]);
                }
            });

            fclose($out);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
