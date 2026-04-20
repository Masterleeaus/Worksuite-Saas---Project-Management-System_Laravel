<?php

namespace App\Providers;

use App\Http\Middleware\FilamentAuthenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * TitanPanelProvider
 *
 * Registers the Filament "titan" panel at /titan as a parallel UI layer
 * alongside the existing Worksuite dashboards.  It deliberately avoids
 * /dashboard, /home, /admin, and /account/* routes.
 *
 * Authentication: re-uses the existing Worksuite 'web' guard.  There is
 * deliberately NO ->login() call so that unauthenticated users are
 * redirected to the standard Worksuite login page rather than a
 * Filament-specific login form that would bypass Fortify/2FA.
 *
 * Tenant isolation is enforced via a global query scope that filters
 * all Filament resource queries by company_id = auth()->user()->company_id.
 */
class TitanPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titan')
            ->path('titan')
            ->authGuard('web')
            ->brandName('Titan Command Centre')
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                FilamentAuthenticate::class,
            ]);
    }

    public static function canAccess(): bool
    {
        $user = self::resolveWorksuiteUser();

        if (!$user) {
            return false;
        }

        if ((int) ($user->is_superadmin ?? 0) === 1) {
            return true;
        }

        if (empty($user->company_id)) {
            return false;
        }

        $hasInternalRole = method_exists($user, 'hasRole')
            && ($user->hasRole('admin') || $user->hasRole('employee') || $user->hasRole('superadmin'));

        $permission = method_exists($user, 'permission')
            ? $user->permission('titan_access')
            : false;

        $hasTitanPermission = !in_array($permission, [false, null, 'none'], true);

        return $hasInternalRole || $hasTitanPermission;
    }

    public static function resolveWorksuiteUser(): ?object
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return null;
        }

        if (isset($authUser->company_id) || isset($authUser->is_superadmin)) {
            return $authUser;
        }

        if (isset($authUser->user) && is_object($authUser->user)) {
            return $authUser->user;
        }

        return null;
    }

    /**
     * Auto-build navigation groups from detected Worksuite modules and extensions.
     *
     * Scans Modules/* and app/Extensions/* and returns a navigation group name
     * for each directory found.  The actual resources declare which group they
     * belong to via $navigationGroup = 'ModuleName'.
     *
     * @return array<string>
     */
    public static function getModuleNavigationGroups(): array
    {
        $groups = ['Command Centre', 'CRM', 'Sales', 'Projects', 'Finance', 'Team', 'Operations', 'Activity', 'Documents'];

        $modulesPath    = base_path('Modules');
        $extensionsPath = app_path('Extensions');

        if (is_dir($modulesPath)) {
            $dirs = array_filter(
                scandir($modulesPath),
                fn ($d) => $d !== '.' && $d !== '..' && is_dir($modulesPath . '/' . $d)
            );
            foreach (array_values($dirs) as $dir) {
                $groups[] = $dir;
            }
        }

        if (is_dir($extensionsPath)) {
            $dirs = array_filter(
                scandir($extensionsPath),
                fn ($d) => $d !== '.' && $d !== '..' && is_dir($extensionsPath . '/' . $d)
            );
            foreach (array_values($dirs) as $dir) {
                $groups[] = $dir;
            }
        }

        return array_unique($groups);
    }
}
