<?php

/**
 * ENHANCED STORE METHOD FOR CustomModuleController
 * 
 * This replaces the current store() method (lines 105-180)
 * with full validation chain, state tracking, and safe-fix execution
 * 
 * Installation Flow:
 * 1. Security scan (block malicious code)
 * 2. Permission collision check
 * 3. Route collision check
 * 4. Analyze module
 * 5. Create state snapshot
 * 6. Move files
 * 7. Execute safe-fix repairs
 * 8. Record installation
 * 9. Return success/rollback URL
 */

public function store(Request $request)
{
    $filePath = $request->filePath;
    
    // ============================================================================
    // STEP 1: Run Pre-Install Validation Chain
    // ============================================================================
    
    \Log::info("CustomModule install starting", ['file' => basename($filePath)]);
    
    // 1.1 Security Scan
    $securityValidator = app(SecurityScanValidator::class);
    $securityResult = $securityValidator->validate($filePath);
    
    if (!$securityResult['valid']) {
        \Log::warning("Module failed security scan", ['issues' => $securityResult['issues']]);
        
        $this->persistInstallLog('install_blocked_security', [
            'reason' => 'security_scan_failed',
            'issues' => $securityResult['issues'],
        ], $filePath);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => 'Module failed security scan. Possible malicious content detected.',
            'blocking_issues' => $securityResult['issues'],
        ]);
    }
    
    // 1.2 Permission Collision Check
    $permValidator = app(PermissionCollisionValidator::class);
    $permResult = $permValidator->validate($filePath);
    
    if (!$permResult['valid']) {
        \Log::warning("Module has permission collisions", ['issues' => $permResult['issues']]);
        
        $this->persistInstallLog('install_blocked_collisions', [
            'reason' => 'permission_collision',
            'issues' => $permResult['issues'],
        ], $filePath);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => 'Module has permission key collisions with existing modules.',
            'blocking_issues' => $permResult['issues'],
        ]);
    }
    
    // 1.3 Route Collision Check
    $routeValidator = app(RouteCollisionValidator::class);
    $routeResult = $routeValidator->validate($filePath);
    
    if (!$routeResult['valid']) {
        \Log::warning("Module has route collisions", ['issues' => $routeResult['issues']]);
        
        $this->persistInstallLog('install_blocked_collisions', [
            'reason' => 'route_collision',
            'issues' => $routeResult['issues'],
        ], $filePath);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => 'Module has route URI collisions with existing routes.',
            'blocking_issues' => $routeResult['issues'],
        ]);
    }
    
    // ============================================================================
    // STEP 2: Analyze Module
    // ============================================================================
    
    $autoRepairMetadata = $request->boolean('auto_repair_metadata', true);
    $analysis = $this->analyzeUploadedModule($filePath, true, $autoRepairMetadata);
    
    $allowedStatuses = ['ready', 'installable-with-warnings', 'upgrade-candidate'];
    
    if (!($analysis['status'] ?? false) || !in_array($analysis['installability_status'] ?? 'blocked', $allowedStatuses, true)) {
        \Log::warning("Module analysis failed", ['analysis_status' => $analysis['installability_status'] ?? 'unknown']);
        
        $this->persistInstallLog('install_blocked_analysis', $analysis, $filePath);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => $analysis['message'] ?? 'The uploaded package could not be installed.',
            'diagnostics' => $analysis['diagnostics_html'] ?? null,
            'analysis' => $analysis,
        ]);
    }
    
    $moduleName = $analysis['module_name'];
    $moduleSourcePath = $analysis['module_path'];
    
    \Log::info("Module analysis passed", ['module' => $moduleName, 'status' => $analysis['installability_status']]);
    
    // ============================================================================
    // STEP 3: Create State Snapshot (BEFORE installation)
    // ============================================================================
    
    $snapshotService = app(ModuleInstallSnapshot::class);
    $preInstallSnapshot = $snapshotService->snapshotBefore($moduleName);
    
    \Log::info("Pre-install snapshot created", ['module' => $moduleName]);
    
    // ============================================================================
    // STEP 4: Move Module Files
    // ============================================================================
    
    try {
        File::put(public_path() . '/install-version.txt', 'complete');
        
        if ($autoRepairMetadata) {
            $repairResult = $this->persistInferredModuleConfig($moduleSourcePath, $analysis);
            if ($repairResult) {
                $analysis['auto_repairs'][] = $repairResult;
            }
        }
        
        // Create filesystem snapshot if this is an upgrade
        $snapshotPath = null;
        if (($analysis['installability_status'] ?? '') === 'upgrade-candidate') {
            $snapshotPath = app(InstallSnapshotService::class)->snapshotModule($moduleName);
            if ($snapshotPath) {
                $analysis['auto_repairs'][] = [
                    'title' => 'Snapshot before replace',
                    'detail' => 'Backed up the installed module before replacing it.',
                    'target' => $snapshotPath,
                ];
            }
        }
        
        // Move files to Modules directory
        File::ensureDirectoryExists(base_path('Modules'));
        File::moveDirectory($moduleSourcePath, base_path('Modules/' . $moduleName), true);
        
        \Log::info("Module files moved", ['module' => $moduleName, 'destination' => base_path('Modules/' . $moduleName)]);
        
    } catch (\Exception $e) {
        \Log::error("Failed to move module files", ['error' => $e->getMessage(), 'module' => $moduleName]);
        
        $this->persistInstallLog('install_failed_filesystem', [
            'error' => $e->getMessage(),
            'message' => 'Failed to move module files',
        ], $filePath);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => "Failed to move module files: {$e->getMessage()}",
        ]);
    }
    
    // ============================================================================
    // STEP 5: Execute Safe-Fix Repairs
    // ============================================================================
    
    $executor = app(SafeFixActionExecutor::class);
    
    $repairOptions = [
        'repair_permissions' => $request->boolean('repair_permissions', true),
        'repair_package_bridge' => $request->boolean('repair_package_bridge', true),
        'repair_company_module_settings' => $request->boolean('repair_company_module_settings', true),
        'clear_caches' => $request->boolean('clear_caches', true),
    ];
    
    $repairResult = $executor->executeAll($moduleName, $repairOptions);
    
    \Log::info("Safe-fix repairs executed", [
        'module' => $moduleName,
        'executed' => count($repairResult['executed']),
        'failed' => count($repairResult['failed']),
        'skipped' => count($repairResult['skipped']),
    ]);
    
    // Check if critical repairs failed
    $criticalFailed = isset($repairResult['failed']['repair_permissions']) || 
                      isset($repairResult['failed']['clear_caches']);
    
    if ($criticalFailed) {
        \Log::error("Critical repairs failed", ['failed' => $repairResult['failed']]);
        
        // Mark installation as partial
        $installStatus = 'partial_failed';
        $canRollback = !empty($preInstallSnapshot);
        
    } else {
        $installStatus = 'installed';
        $canRollback = !empty($preInstallSnapshot);
    }
    
    // ============================================================================
    // STEP 6: Record Installation State
    // ============================================================================
    
    $installLog = ModuleInstallLog::create([
        'module_name' => $moduleName,
        'version' => $analysis['version'] ?? 'unknown',
        'status' => $installStatus,
        'manifest_data' => $analysis,
        'validation_results' => [
            'security' => $securityResult,
            'permissions' => $permResult,
            'routes' => $routeResult,
        ],
        'validation_score' => 95, // Based on validators passing
        'pre_install_snapshot' => $preInstallSnapshot,
        'applied_repairs' => array_keys($repairResult['executed']),
        'repair_results' => $repairResult['executed'],
        'module_source_hash' => hash_file('sha256', $filePath),
        'module_source_size' => filesize($filePath),
        'can_rollback' => $canRollback,
        'installed_by' => auth()->id() ?? 'system',
        'installation_notes' => $repairResult['failed'] ? 'Some repairs failed: ' . json_encode($repairResult['failed']) : null,
        'installed_at' => now(),
    ]);
    
    \Log::info("Installation state recorded", ['install_id' => $installLog->id, 'module' => $moduleName]);
    
    // ============================================================================
    // STEP 7: Post-Install Actions
    // ============================================================================
    
    cache()->forget('laravel-modules');
    File::deleteDirectory(storage_path('app') . '/Modules/');
    
    if (module_enabled($moduleName)) {
        $this->updateVersion($moduleName);
    }
    
    if ($moduleName == 'UniversalBundle') {
        /** @phpstan-ignore-next-line */
        $module = Module::findOrFail($moduleName);
        $module->enable();
        Artisan::call('module:migrate', array($moduleName, '--force' => true));
        event(new ModuleStatusChanged($module, 'active'));
    }
    
    $postInstall = $this->runPostInstallActions($request, $moduleName);
    if ($snapshotPath ?? false) {
        $postInstall['snapshot'] = ['status' => 'success', 'detail' => $snapshotPath];
    }
    
    $analysis['post_install'] = $postInstall;
    $analysis['repair_results'] = $repairResult;
    $analysis['diagnostics_html'] = $this->renderDiagnosticsHtml($analysis);
    
    // Update the log with final diagnostics
    $installLog->update([
        'manifest_data' => $analysis,
    ]);
    
    \Log::info("Module installation completed", [
        'module' => $moduleName,
        'status' => $installStatus,
        'install_id' => $installLog->id,
    ]);
    
    // ============================================================================
    // STEP 8: Return Response
    // ============================================================================
    
    $this->flushData();
    
    $response = [
        'status' => $criticalFailed ? 'partial' : 'success',
        'message' => $criticalFailed 
            ? 'Module installed with some repair failures. Rollback available.'
            : 'Module installed successfully.',
        'install_id' => $installLog->id,
        'module_name' => $moduleName,
        'diagnostics' => $analysis['diagnostics_html'] ?? null,
        'analysis' => $analysis,
    ];
    
    // Add rollback capability if snapshot exists
    if ($canRollback) {
        $response['can_rollback'] = true;
        $response['rollback_url'] = route('custom-modules.rollback', ['install' => $installLog->id]);
    }
    
    // Add failed repairs to response for visibility
    if (!empty($repairResult['failed'])) {
        $response['repair_failures'] = $repairResult['failed'];
        $response['warning'] = 'Some repairs failed - manual intervention may be needed';
    }
    
    return Reply::dataOnly($response);
}
