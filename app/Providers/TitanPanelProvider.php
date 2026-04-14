<?php

namespace App\Providers;

use App\Filament\Pages\AutomationQueue;
use App\Filament\Pages\CommandCentre;
use App\Filament\Resources\DocumentTemplateResource;
use App\Filament\Pages\ScoutStatus;
use App\Filament\Pages\SentinelApprovals;
use App\Filament\Pages\SignalLogs;
use App\Filament\Widgets\ActivityFeedWidget;
use App\Filament\Widgets\JobsTodayWidget;
use App\Filament\Widgets\RevenueWidget;
use App\Filament\Widgets\SystemSignalsWidget;
use App\Filament\Widgets\TitanChatWidget;
use App\Http\Middleware\FilamentAuthenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
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

            // ----------------------------------------------------------------
            // Authentication – reuse Worksuite's existing web guard / User model.
            // NO ->login() here: unauthenticated access is redirected to the
            // standard Worksuite login (/login) by the Authenticate middleware.
            // ----------------------------------------------------------------
            ->authGuard('web')

            // ----------------------------------------------------------------
            // Branding
            // ----------------------------------------------------------------
            ->brandName('Titan Command Centre')
            ->colors([
                'primary' => Color::Indigo,
            ])

            // ----------------------------------------------------------------
            // Navigation groups – auto-detected from Modules/* and Extensions/*
            // ----------------------------------------------------------------
            ->navigationGroups(
                array_map(
                    fn (string $name) => NavigationGroup::make($name),
                    self::getModuleNavigationGroups()
                )
            )

            // ----------------------------------------------------------------
            // Pages
            // ----------------------------------------------------------------
            ->pages([
                CommandCentre::class,
                AutomationQueue::class,
                ScoutStatus::class,
                SentinelApprovals::class,
                SignalLogs::class,
            ])

            // ----------------------------------------------------------------
            // Widgets
            // ----------------------------------------------------------------
            ->widgets([
                SystemSignalsWidget::class,
                JobsTodayWidget::class,
                RevenueWidget::class,
                ActivityFeedWidget::class,
                TitanChatWidget::class,
            ])
            ->resources([
                DocumentTemplateResource::class,
            ])

            // ----------------------------------------------------------------
            // Middleware – standard Filament stack using the existing session
            // ----------------------------------------------------------------
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
                \App\Http\Middleware\ApplyTitanTenantScope::class,
                \App\Http\Middleware\EnsureTitanPanelAccess::class,
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

        $isAdmin = method_exists($user, 'hasRole') && $user->hasRole('admin');

        $permission = method_exists($user, 'permission')
            ? $user->permission('titan_access')
            : false;

        $hasTitanPermission = !in_array($permission, [false, null, 'none'], true);

        return $isAdmin || $hasTitanPermission;
    }

    public static function resolveWorksuiteUser(): ?object
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return null;
        }

        if (is_object($authUser) && isset($authUser->company_id)) {
            return $authUser;
        }

        if (isset($authUser->user) && is_object($authUser->user)) {
            return $authUser->user;
        }

        if (function_exists('user')) {
            $sessionUser = user();

            if (is_object($sessionUser)) {
                return $sessionUser;
            }
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
        $groups = ['Titan'];

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
