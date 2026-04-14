<?php

namespace App\Http\Controllers;

use App\Models\ModuleDependency;
use App\Models\ModuleInstallLog;
use App\Models\ModuleTestResult;
use App\Services\CustomModules\Configuration\ConfigurationManager;
use App\Services\CustomModules\Monitoring\LogEntryRepository;
use App\Services\CustomModules\Monitoring\PerformanceMetricsCollector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ModuleInstallerDashboardController extends Controller
{
    public function __construct(
        protected PerformanceMetricsCollector $metricsCollector,
        protected LogEntryRepository $logRepository,
    ) {
    }

    public function index(): View
    {
        return view('custom-modules.dashboard.index', [
            'stats' => $this->getDashboardStats(),
            'recentInstallations' => $this->getRecentInstallations(5),
            'failedInstallations' => $this->getFailedInstallations(5),
            'performanceMetrics' => $this->getPerformanceMetrics(),
            'moduleStats' => $this->getModuleStats(),
            'testSummary' => $this->getTestSummary(),
            'dependencySummary' => $this->getDependencySummary(),
            'configurationSummary' => $this->getConfigurationSummary(),
            'compatibilitySummary' => $this->getCompatibilitySummary(),
            'readinessSummary' => $this->getReadinessSummary(),
        ]);
    }

    public function history(Request $request): View
    {
        $query = ModuleInstallLog::query();
        if ($request->filled('module')) {
            $query->where('module_name', $request->string('module'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return view('custom-modules.dashboard.history', [
            'installations' => $query->orderByDesc('installed_at')->paginate(20),
            'modules' => ModuleInstallLog::query()->distinct()->pluck('module_name'),
        ]);
    }

    public function show(ModuleInstallLog $install): View
    {
        return view('custom-modules.dashboard.show', [
            'installation' => $install,
            'logs' => $this->logRepository->getLogsForInstallation($install->id, 100),
            'metrics' => $this->metricsCollector->getInstallationMetrics($install->id),
            'tests' => $this->getInstallationTests($install->id),
        ]);
    }

    public function logs(Request $request): View
    {
        $filters = array_filter([
            'module_name' => $request->input('module'),
            'event_type' => $request->input('event_type'),
            'level' => $request->input('level'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        return view('custom-modules.dashboard.logs', [
            'logs' => $this->logRepository->search($filters, 50),
            'statistics' => $this->normalizeLogStatistics($this->logRepository->getStatistics(7)),
            'modules' => ModuleInstallLog::query()->distinct()->pluck('module_name'),
            'filters' => $filters,
        ]);
    }

    public function performance(Request $request): View
    {
        $moduleName = $request->input('module');
        $days = max(1, (int) $request->input('days', 7));
        $performanceReport = $moduleName
            ? $this->metricsCollector->generatePerformanceReport($moduleName, $days)
            : $this->getAllPerformanceMetrics($days);

        return view('custom-modules.dashboard.performance', [
            'performanceReport' => $performanceReport,
            'modules' => ModuleInstallLog::query()->distinct()->pluck('module_name'),
            'timeline' => $this->getPerformanceTimeline($days),
            'days' => $days,
        ]);
    }

    public function exportLogs(Request $request)
    {
        $moduleName = $request->input('module');
        $slug = $moduleName ?: 'all-modules';
        $filePath = storage_path('app/exports/' . $slug . '_logs_' . now()->format('Y-m-d_H-i-s') . '.csv');
        $this->logRepository->exportToCSV($moduleName, $filePath);
        return response()->download($filePath, basename($filePath))->deleteFileAfterSend();
    }

    protected function getDashboardStats(): array
    {
        $query = ModuleInstallLog::query();
        $total = $query->count();
        $hasStatus = Schema::hasColumn('module_install_logs', 'status');
        $hasInstalledAt = Schema::hasColumn('module_install_logs', 'installed_at');
        $successful = $hasStatus ? ModuleInstallLog::where('status', 'installed')->count() : 0;
        $failed = $hasStatus ? ModuleInstallLog::whereIn('status', ['failed', 'partial_failed', 'rollback_needed'])->count() : 0;
        return [
            'total_installations' => $total,
            'successful' => $successful,
            'success_rate' => $total > 0 ? round(($successful / $total) * 100, 2) : 0,
            'failed' => $failed,
            'failure_rate' => $total > 0 ? round(($failed / $total) * 100, 2) : 0,
            'rollback_available' => Schema::hasColumn('module_install_logs', 'can_rollback') ? ModuleInstallLog::where('can_rollback', true)->count() : 0,
            'last_installation' => ModuleInstallLog::query()->latest($hasInstalledAt ? 'installed_at' : 'created_at')->value($hasInstalledAt ? 'installed_at' : 'created_at'),
        ];
    }

    protected function getRecentInstallations(int $limit = 5)
    {
        return ModuleInstallLog::query()->orderByDesc(Schema::hasColumn('module_install_logs', 'installed_at') ? 'installed_at' : 'created_at')->limit($limit)->get();
    }

    protected function getFailedInstallations(int $limit = 5)
    {
        if (!Schema::hasColumn('module_install_logs', 'status')) { return collect(); }
        return ModuleInstallLog::query()->whereIn('status', ['failed', 'partial_failed', 'rollback_needed'])->orderByDesc(Schema::hasColumn('module_install_logs', 'installed_at') ? 'installed_at' : 'created_at')->limit($limit)->get();
    }

    protected function getPerformanceMetrics(): array
    {
        return [
            'average_installation_time' => $this->metricsCollector->getAverageInstallationTime(10),
            'slowest_installations' => ModuleInstallLog::query()->orderByDesc(Schema::hasColumn('module_install_logs', 'installed_at') ? 'installed_at' : 'created_at')->limit(5)->get()->map(function ($install) {
                return [
                    'module' => $install->module_name,
                    'duration' => data_get($install->repair_results, 'summary.total_duration_seconds', 0),
                ];
            })->toArray(),
        ];
    }

    protected function getModuleStats(): array
    {
        if (!Schema::hasColumn('module_install_logs', 'status')) { return []; }
        return ModuleInstallLog::query()->groupBy('module_name')->selectRaw('module_name, count(*) as total, sum(case when status = "installed" then 1 else 0 end) as successful')->get()->map(function ($stat) {
            return [
                'name' => $stat->module_name,
                'installations' => (int) $stat->total,
                'successful' => (int) $stat->successful,
                'success_rate' => $stat->total > 0 ? round(($stat->successful / $stat->total) * 100, 2) : 0,
            ];
        })->toArray();
    }

    protected function getAllPerformanceMetrics(int $days): array
    {
        $dateColumn = Schema::hasColumn('module_install_logs', 'installed_at') ? 'installed_at' : 'created_at';
        $installs = ModuleInstallLog::query()->where($dateColumn, '>=', now()->subDays($days))->get();
        return [
            'total_installations' => $installs->count(),
            'average_duration' => round($installs->avg(fn ($install) => data_get($install->repair_results, 'summary.total_duration_seconds', 0)), 2),
            'success_rate' => $installs->count() > 0 ? round(($installs->where('status', 'installed')->count() / $installs->count()) * 100, 2) : 0,
        ];
    }

    protected function getPerformanceTimeline(int $days): array
    {
        $dateColumn = Schema::hasColumn('module_install_logs', 'installed_at') ? 'installed_at' : 'created_at';
        return ModuleInstallLog::query()->where($dateColumn, '>=', now()->subDays($days))->get()->groupBy(fn ($install) => optional($install->{$dateColumn})->format('Y-m-d'))->map(function ($group, $date) {
            return [
                'date' => $date,
                'count' => $group->count(),
                'average_duration' => round($group->avg(fn ($install) => data_get($install->repair_results, 'summary.total_duration_seconds', 0)), 2),
            ];
        })->values()->toArray();
    }

    protected function getTestSummary(): array
    {
        if (!Schema::hasTable('module_test_results')) {
            return ['total' => 0, 'passed' => 0, 'failed' => 0, 'success_rate' => 0];
        }

        $total = ModuleTestResult::count();
        $passed = ModuleTestResult::where('success', true)->count();
        $failed = $total - $passed;

        return [
            'total' => $total,
            'passed' => $passed,
            'failed' => $failed,
            'success_rate' => $total > 0 ? round(($passed / $total) * 100, 2) : 0,
        ];
    }

    protected function getDependencySummary(): array
    {
        if (!Schema::hasTable('module_dependencies')) {
            return ['total' => 0, 'resolved' => 0, 'pending' => 0];
        }

        $total = ModuleDependency::count();
        $resolved = ModuleDependency::where('status', 'resolved')->count();

        return [
            'total' => $total,
            'resolved' => $resolved,
            'pending' => $total - $resolved,
        ];
    }
    protected function getCompatibilitySummary(): array
    {
        return [
            'manifest_required' => true,
            'legacy_config_supported' => true,
            'non_worksuite_manifest_rejected' => true,
            'nwidart_target' => '/Modules',
        ];
    }

    protected function getReadinessSummary(): array
    {
        if (!Schema::hasColumn('module_install_logs', 'readiness_score')) {
            return ['average_score' => 0, 'pass_count' => 0, 'warn_count' => 0, 'fail_count' => 0, 'fix_steps_pending' => 0];
        }

        $logs = ModuleInstallLog::query()->get();
        return [
            'average_score' => round((float) $logs->avg('readiness_score'), 2),
            'pass_count' => $logs->where('readiness_score', '>=', 90)->count(),
            'warn_count' => $logs->filter(fn ($log) => $log->readiness_score >= 65 && $log->readiness_score < 90)->count(),
            'fail_count' => $logs->where('readiness_score', '<', 65)->count(),
            'fix_steps_pending' => (int) $logs->sum(fn ($log) => is_array($log->fix_plan) ? count($log->fix_plan) : 0),
        ];
    }

    protected function getConfigurationSummary(): array
    {
        $config = ConfigurationManager::load();
        return [
            'security_validator' => (bool) data_get($config, 'validators.security.enabled', true),
            'permission_validator' => (bool) data_get($config, 'validators.permissions.enabled', true),
            'route_validator' => (bool) data_get($config, 'validators.routes.enabled', true),
            'cache_clear' => (bool) data_get($config, 'repairs.cache_clear.enabled', true),
        ];
    }

    protected function getInstallationTests(int $installId)
    {
        if (!Schema::hasTable('module_test_results')) {
            return collect();
        }

        return ModuleTestResult::where('install_id', $installId)->orderBy('test_category')->orderBy('test_name')->get();
    }

    protected function normalizeLogStatistics(array $statistics): array
    {
        return [
            'total_events' => $statistics['total_events'] ?? $statistics['total_logs'] ?? 0,
            'errors' => $statistics['errors'] ?? $statistics['errors_count'] ?? 0,
            'warnings' => $statistics['warnings'] ?? $statistics['warnings_count'] ?? 0,
            'by_level' => $statistics['by_level'] ?? [],
            'by_event_type' => $statistics['by_event_type'] ?? [],
            'by_module' => $statistics['by_module'] ?? [],
        ];
    }
}
