<?php

namespace Modules\CustomerConnect\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'Customer Connect';
        $menu   = $event->menu;

        // Parent — FIX: route was 'customerconnect.index' (didn't exist),
        // corrected to 'customerconnect.dashboard.index'
        $menu->add([
            'title'      => 'Customer Connect',
            'icon'       => 'link',
            'name'       => 'customerconnect',
            'parent'     => null,
            'order'      => 1250,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.dashboard.index',
            'module'     => $module,
            'permission' => 'customerconnect.view',
        ]);

        $menu->add([
            'title'      => 'Inbox',
            'icon'       => '',
            'name'       => 'customerconnect_inbox',
            'parent'     => 'customerconnect',
            'order'      => 10,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.inbox.index',
            'module'     => $module,
            'permission' => 'customerconnect.inbox.view',
        ]);

        $menu->add([
            'title'      => 'Campaigns',
            'icon'       => '',
            'name'       => 'customerconnect_campaigns',
            'parent'     => 'customerconnect',
            'order'      => 20,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.campaigns.index',
            'module'     => $module,
            'permission' => 'customerconnect.campaigns',
        ]);

        $menu->add([
            'title'      => 'Audiences',
            'icon'       => '',
            'name'       => 'customerconnect_audiences',
            'parent'     => 'customerconnect',
            'order'      => 30,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.audiences.index',
            'module'     => $module,
            'permission' => 'customerconnect.audiences',
        ]);

        $menu->add([
            'title'      => 'Runs',
            'icon'       => '',
            'name'       => 'customerconnect_runs',
            'parent'     => 'customerconnect',
            'order'      => 40,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.runs.index',
            'module'     => $module,
            'permission' => 'customerconnect.runs',
        ]);

        $menu->add([
            'title'      => 'Deliveries',
            'icon'       => '',
            'name'       => 'customerconnect_deliveries',
            'parent'     => 'customerconnect',
            'order'      => 50,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.deliveries.index',
            'module'     => $module,
            'permission' => 'customerconnect.deliveries',
        ]);

        $menu->add([
            'title'      => 'Recipes',
            'icon'       => '',
            'name'       => 'customerconnect_recipes',
            'parent'     => 'customerconnect',
            'order'      => 60,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.recipes.index',
            'module'     => $module,
            'permission' => 'customerconnect.view',
        ]);

        $menu->add([
            'title'      => 'History',
            'icon'       => '',
            'name'       => 'customerconnect_history',
            'parent'     => 'customerconnect',
            'order'      => 65,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.history.index',
            'module'     => $module,
            'permission' => 'customerconnect.view',
        ]);

        $menu->add([
            'title'      => 'Suppression',
            'icon'       => '',
            'name'       => 'customerconnect_suppressions',
            'parent'     => 'customerconnect',
            'order'      => 70,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.settings.suppressions.index',
            'module'     => $module,
            'permission' => 'customerconnect.manage',
        ]);

        $menu->add([
            'title'      => 'Tags',
            'icon'       => '',
            'name'       => 'customerconnect_tags',
            'parent'     => 'customerconnect',
            'order'      => 80,
            'ignore_if'  => [],
            'depend_on'  => [],
            'route'      => 'customerconnect.settings.tags.index',
            'module'     => $module,
            'permission' => 'customerconnect.manage',
        ]);
    }
}
