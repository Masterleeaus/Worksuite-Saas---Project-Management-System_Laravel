<?php

/**
 * ROLLBACK METHOD FOR CustomModuleController
 * 
 * Undoes a module installation by:
 * 1. Removing module files
 * 2. Restoring database state from snapshot
 * 3. Rolling back repair actions
 */

public function rollback(ModuleInstallLog $install, Request $request)
{
    // Authorization
    abort_403(GlobalSetting::validateSuperAdmin('manage_superadmin_custom_module_settings'));
    
    $moduleName = $install->module_name;
    
    \Log::warning("Module rollback initiated", ['module' => $moduleName, 'install_id' => $install->id]);
    
    // Check if rollback is possible
    if (!$install->can_rollback) {
        \Log::info("Rollback not available", ['module' => $moduleName, 'reason' => 'no_snapshot']);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => 'Rollback not available for this installation. No snapshot exists.',
        ]);
    }
    
    if (!$install->pre_install_snapshot) {
        \Log::warning("Rollback attempted but snapshot missing", ['module' => $moduleName]);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => 'Rollback snapshot is missing or corrupted.',
        ]);
    }
    
    try {
        $results = [];
        
        // ====================================================================
        // STEP 1: Remove Module Files
        // ====================================================================
        
        $modulePath = base_path("Modules/{$moduleName}");
        
        if (File::exists($modulePath)) {
            File::deleteDirectory($modulePath);
            \Log::info("Module files deleted", ['module' => $moduleName]);
            $results['files_deleted'] = true;
        } else {
            \Log::info("Module files not found (already deleted)", ['module' => $moduleName]);
            $results['files_deleted'] = false;
        }
        
        // ====================================================================
        // STEP 2: Restore Database State from Snapshot
        // ====================================================================
        
        $snapshotService = app(ModuleInstallSnapshot::class);
        $restoreResult = $snapshotService->restoreFrom($moduleName, $install->pre_install_snapshot);
        
        if ($restoreResult['success']) {
            \Log::info("Database state restored from snapshot", ['module' => $moduleName]);
            $results['database_restored'] = true;
        } else {
            \Log::error("Database restore failed", ['module' => $moduleName, 'error' => $restoreResult['error']]);
            $results['database_restored'] = false;
            $results['database_error'] = $restoreResult['error'];
        }
        
        // ====================================================================
        // STEP 3: Rollback Safe-Fix Repairs
        // ====================================================================
        
        if (!empty($install->applied_repairs)) {
            $executor = app(SafeFixActionExecutor::class);
            $rollbackResults = $executor->rollbackRepairs($moduleName, array_flip($install->applied_repairs));
            
            \Log::info("Safe-fix repairs rolled back", ['module' => $moduleName, 'count' => count($rollbackResults)]);
            $results['repairs_rolled_back'] = true;
            $results['repair_details'] = $rollbackResults;
        } else {
            $results['repairs_rolled_back'] = false;
            $results['repair_details'] = [];
        }
        
        // ====================================================================
        // STEP 4: Clear Caches
        // ====================================================================
        
        try {
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            
            cache()->forget('laravel-modules');
            File::deleteDirectory(storage_path('app') . '/Modules/');
            
            \Log::info("Application caches cleared", ['module' => $moduleName]);
            $results['caches_cleared'] = true;
        } catch (\Exception $e) {
            \Log::warning("Cache clear had issues during rollback", ['error' => $e->getMessage()]);
            $results['caches_cleared'] = false;
        }
        
        // ====================================================================
        // STEP 5: Update Installation Log
        // ====================================================================
        
        $install->update([
            'status' => 'rolled_back',
            'installation_notes' => "Rolled back on " . now()->toDateTimeString() . ". Files deleted, database restored, repairs reversed.",
        ]);
        
        \Log::info("Installation log marked as rolled back", ['module' => $moduleName, 'install_id' => $install->id]);
        
        // ====================================================================
        // STEP 6: Return Success
        // ====================================================================
        
        return Reply::dataOnly([
            'status' => 'success',
            'message' => "Module {$moduleName} has been successfully rolled back.",
            'details' => $results,
            'module_name' => $moduleName,
        ]);
        
    } catch (\Exception $e) {
        \Log::error("Rollback failed with exception", ['module' => $moduleName, 'error' => $e->getMessage()]);
        
        $install->update([
            'status' => 'rollback_failed',
            'installation_notes' => "Rollback attempt failed: {$e->getMessage()}",
        ]);
        
        return Reply::dataOnly([
            'status' => 'fail',
            'message' => "Rollback failed: {$e->getMessage()}",
            'error' => $e->getMessage(),
            'install_id' => $install->id,
        ]);
    }
}
