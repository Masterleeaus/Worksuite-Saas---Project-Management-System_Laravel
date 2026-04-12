<?php

namespace Modules\TitanTheme\Services;

use Illuminate\Support\Collection;
use Modules\TitanTheme\Models\MegaMenu;
use Modules\TitanTheme\Models\NavItem;

class NavigationService
{
    /**
     * Return the sidebar navigation tree for the current company.
     * Items are filtered to only those whose required_module (if set) is loaded.
     *
     * @return Collection<NavItem>
     */
    public function sidebarTree(): Collection
    {
        return NavItem::active()
            ->forPanel(NavItem::PANEL_SIDEBAR)
            ->topLevel()
            ->with(['children' => function ($q) {
                $q->active()->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (NavItem $item) => $this->isVisible($item));
    }

    /**
     * Return the header mega menus with their nested items.
     *
     * @return Collection<MegaMenu>
     */
    public function headerMenus(): Collection
    {
        return MegaMenu::active()
            ->with(['items' => function ($q) {
                $q->active()->with(['children' => function ($q2) {
                    $q2->active()->orderBy('sort_order');
                }]);
            }])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (MegaMenu $menu) => $this->moduleIsLoaded($menu->required_module));
    }

    /**
     * Check whether a nav item should be shown to the current user.
     */
    public function isVisible(NavItem $item): bool
    {
        if (!$this->moduleIsLoaded($item->required_module)) {
            return false;
        }

        $roles = $item->visibleRoles();
        if ($roles === null) {
            return true;
        }

        $user = auth()->user();
        if (!$user) {
            return false;
        }

        // WorkSuite uses hasRole() from the User model.
        foreach ($roles as $role) {
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return a flat ordered list of all nav items for a given panel.
     *
     * @return Collection<NavItem>
     */
    public function flatList(string $panel = NavItem::PANEL_SIDEBAR): Collection
    {
        return NavItem::forPanel($panel)->orderBy('sort_order')->get();
    }

    /**
     * Persist a new sort order supplied as an array of item IDs.
     */
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $position => $id) {
            NavItem::where('id', $id)->update(['sort_order' => $position]);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    protected function moduleIsLoaded(?string $moduleName): bool
    {
        if (empty($moduleName)) {
            return true;
        }

        // Use nwidart/laravel-modules helper when available.
        if (function_exists('module_path')) {
            try {
                $module = app('modules')->find($moduleName);
                return $module !== null && $module->isEnabled();
            } catch (\Throwable $e) {
                // Fall through.
            }
        }

        return true;
    }
}
