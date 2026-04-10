<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{

    public function register()
    {
        try {
            $pusherSetting = DB::table('pusher_settings')->first();

            if ($pusherSetting) {

                if (!in_array(config('app.env'), ['demo', 'development'])) {

                    // Prefer Reverb when configured; fall back to external Pusher.
                    $useReverb = config('reverb.apps.apps.0.key') !== null
                        && env('REVERB_APP_KEY') !== null;

                    if ($useReverb) {
                        Config::set('broadcasting.default', 'reverb');
                    } else {
                        $driver = ($pusherSetting->status == 1) ? 'pusher' : 'null';
                        Config::set('broadcasting.default', $driver);
                        Config::set('broadcasting.connections.pusher.key', $pusherSetting->pusher_app_key);
                        Config::set('broadcasting.connections.pusher.secret', $pusherSetting->pusher_app_secret);
                        Config::set('broadcasting.connections.pusher.app_id', $pusherSetting->pusher_app_id);
                        Config::set('broadcasting.connections.pusher.options.host', 'api-'.$pusherSetting->pusher_cluster.'.pusher.com');
                    }
                }
            }
        }
        // @codingStandardsIgnoreLine
        catch (\Exception $e) {
        } // phpcs:ignore
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['auth:sanctum,web']]);

        require base_path('routes/channels.php');
    }

}
