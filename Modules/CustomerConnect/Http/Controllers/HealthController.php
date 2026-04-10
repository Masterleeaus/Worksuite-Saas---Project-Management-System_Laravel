<?php

namespace Modules\CustomerConnect\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HealthController extends Controller
{
    public function index(Request $request)
    {
        // Zero-risk: avoid hard assumptions if tables are missing
        $tables = [
            'customerconnect_deliveries',
            'customerconnect_messages',
            'customerconnect_threads',
            'customerconnect_alerts',
            'customerconnect_delivery_events',
            'customerconnect_message_events',
        ];

        $exists = [];
        foreach ($tables as $t) {
            $exists[$t] = Schema::hasTable($t);
        }

        $kpis = [
            'deliveries_24h' => $exists['customerconnect_deliveries'] ? DB::table('customerconnect_deliveries')->where('created_at', '>=', now()->subDay())->count() : null,
            'failed_24h'     => $exists['customerconnect_deliveries'] ? DB::table('customerconnect_deliveries')->where('created_at', '>=', now()->subDay())->where('status', 'failed')->count() : null,
            'sent_24h'       => $exists['customerconnect_deliveries'] ? DB::table('customerconnect_deliveries')->where('created_at', '>=', now()->subDay())->where('status', 'sent')->count() : null,
            'inbound_24h'    => $exists['customerconnect_messages'] ? DB::table('customerconnect_messages')->where('created_at', '>=', now()->subDay())->where('direction', 'inbound')->count() : null,
            'open_threads'   => $exists['customerconnect_threads'] ? DB::table('customerconnect_threads')->where('status', 'open')->count() : null,
            'alerts_7d'      => $exists['customerconnect_alerts'] ? DB::table('customerconnect_alerts')->where('created_at', '>=', now()->subDays(7))->count() : null,
        ];

        $byChannel = null;
        if ($exists['customerconnect_deliveries'] && Schema::hasColumn('customerconnect_deliveries', 'channel')) {
            $byChannel = DB::table('customerconnect_deliveries')
                ->select('channel', DB::raw('count(*) as total'),
                    DB::raw("sum(case when status='failed' then 1 else 0 end) as failed"),
                    DB::raw("sum(case when status='sent' then 1 else 0 end) as sent"))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('channel')
                ->orderByDesc('total')
                ->get();
        }

        $recentAlerts = null;
        if ($exists['customerconnect_alerts']) {
            $recentAlerts = DB::table('customerconnect_alerts')->orderByDesc('id')->limit(25)->get();
        }

        return view('customerconnect::health.index', [
            'exists' => $exists,
            'kpis' => $kpis,
            'byChannel' => $byChannel,
            'recentAlerts' => $recentAlerts,
        ]);
    }
}
