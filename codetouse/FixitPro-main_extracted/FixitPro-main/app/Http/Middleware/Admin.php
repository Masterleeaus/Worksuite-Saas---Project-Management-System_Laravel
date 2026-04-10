<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\HasPermissionsTrait;
use Illuminate\Support\Facades\Auth;

class Admin
{
    use HasPermissionsTrait;

    public function handle($request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        // Permissions Validation based on URI
        // Skip check for dashboard or basic routes if needed, but per blueprint:
        $currentUri = \Route::getFacadeRoot()->current()->uri();
        
        // Allow dashboard for all authenticated admins by default or check specifically
        if ($currentUri == 'admin/dashboard') {
            return $next($request);
        }

        $response = $this->getModulesPermissions();
        if (!$response) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 0, 
                    'msg' => 'Access Denied: You do not have permission to access this module.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Access Denied: You do not have permission to access this module.');
        }

        return $next($request);
    }
}
