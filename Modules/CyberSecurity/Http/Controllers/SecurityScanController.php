<?php

namespace Modules\CyberSecurity\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;

class SecurityScanController extends AccountBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'cybersecurity::app.security_scan.title';
        $this->middleware(function ($request, $next) {
            abort_403(user()->permission('run_security_scan') == 'none');

            return $next($request);
        });
    }

    public function index()
    {
        $this->lastScanResults = session('last_scan_results');

        return view('cybersecurity::security-scan.index', $this->data);
    }

    public function run(Request $request)
    {
        $results = [];

        // Check HTTPS enforcement
        $results[] = [
            'check'   => __('cybersecurity::app.security_scan.checks.https'),
            'status'  => request()->isSecure() || app()->environment('local') ? 'pass' : 'fail',
            'details' => request()->isSecure()
                ? __('cybersecurity::app.security_scan.checks.https_pass')
                : __('cybersecurity::app.security_scan.checks.https_fail'),
        ];

        // Check APP_DEBUG
        $results[] = [
            'check'   => __('cybersecurity::app.security_scan.checks.debug_mode'),
            'status'  => config('app.debug') ? 'fail' : 'pass',
            'details' => config('app.debug')
                ? __('cybersecurity::app.security_scan.checks.debug_mode_fail')
                : __('cybersecurity::app.security_scan.checks.debug_mode_pass'),
        ];

        // Check APP_ENV
        $results[] = [
            'check'   => __('cybersecurity::app.security_scan.checks.env'),
            'status'  => config('app.env') === 'production' ? 'pass' : 'warn',
            'details' => config('app.env') === 'production'
                ? __('cybersecurity::app.security_scan.checks.env_pass')
                : __('cybersecurity::app.security_scan.checks.env_warn', ['env' => config('app.env')]),
        ];

        // Check .env file exposure
        $envExposed = false;

        try {
            $response = @file_get_contents(config('app.url') . '/.env');
            $envExposed = $response !== false && strlen($response) > 10;
        } catch (\Throwable $e) {
            $envExposed = false;
        }

        $results[] = [
            'check'   => __('cybersecurity::app.security_scan.checks.env_exposure'),
            'status'  => $envExposed ? 'fail' : 'pass',
            'details' => $envExposed
                ? __('cybersecurity::app.security_scan.checks.env_exposure_fail')
                : __('cybersecurity::app.security_scan.checks.env_exposure_pass'),
        ];

        // Check session security config
        $results[] = [
            'check'   => __('cybersecurity::app.security_scan.checks.session_secure'),
            'status'  => config('session.secure') ? 'pass' : 'warn',
            'details' => config('session.secure')
                ? __('cybersecurity::app.security_scan.checks.session_secure_pass')
                : __('cybersecurity::app.security_scan.checks.session_secure_warn'),
        ];

        // Check session httponly
        $results[] = [
            'check'   => __('cybersecurity::app.security_scan.checks.session_httponly'),
            'status'  => config('session.http_only') ? 'pass' : 'fail',
            'details' => config('session.http_only')
                ? __('cybersecurity::app.security_scan.checks.session_httponly_pass')
                : __('cybersecurity::app.security_scan.checks.session_httponly_fail'),
        ];

        // Check storage public symlink doesn't expose sensitive paths
        $storagePublic = public_path('storage');
        $results[] = [
            'check'   => __('cybersecurity::app.security_scan.checks.storage_link'),
            'status'  => is_link($storagePublic) ? 'pass' : 'warn',
            'details' => is_link($storagePublic)
                ? __('cybersecurity::app.security_scan.checks.storage_link_pass')
                : __('cybersecurity::app.security_scan.checks.storage_link_warn'),
        ];

        $passCount = collect($results)->where('status', 'pass')->count();
        $failCount = collect($results)->where('status', 'fail')->count();
        $warnCount = collect($results)->where('status', 'warn')->count();

        $scanSummary = [
            'run_at'     => now()->format(company_date_format() . ' H:i'),
            'pass'       => $passCount,
            'fail'       => $failCount,
            'warn'       => $warnCount,
            'total'      => count($results),
            'results'    => $results,
            'score'      => count($results) > 0
                ? round(($passCount / count($results)) * 100)
                : 0,
        ];

        session(['last_scan_results' => $scanSummary]);

        if ($request->ajax()) {
            $html = view('cybersecurity::security-scan.ajax.results', ['scan' => $scanSummary])->render();

            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'scan' => $scanSummary]);
        }

        return redirect()->route('cybersecurity.security-scan.index');
    }

}
