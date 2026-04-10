<?php

namespace Modules\TitanDocs\Listeners;

use App\Events\CompanyMenuEvent;

class CompanyMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(CompanyMenuEvent $event): void
    {
        $module = 'TitanDocs';
        $menu = $event->menu;

        if (!in_array('AIImage', $event->menu->modules)) {
            $menu->add([
                'title' => 'AI',
                'icon' => 'brand-gitlab',
                'name' => 'ai',
                'parent' => null,
                'order' => 715,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => '',
                'module' => $module,
                'permission' => 'sidebar ai manage'
            ]);

            $menu->add([
                'title' => __('History'),
                'icon' => 'history',
                'name' => 'ai-history',
                'parent' => 'ai',
                'order' => 20,
                'ignore_if' => [],
                'depend_on' => [],
                'route' => '',
                'module' => $module,
                'permission' => null
            ]);
        }

        // Main AI Document entry (show an icon so the sidebar doesn't look "empty")
        $menu->add([
            'title' => __('AI Document'),
            'icon' => 'file',
            'name' => 'ai-document',
            'parent' => 'ai',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'aidocument.index',
            'module' => $module,
            'permission' => 'ai document manage'
        ]);

        $menu->add([
            'title' => __('AI Document'),
            'icon' => 'history',
            'name' => 'history-ai-document',
            'parent' => 'ai-history',
            'order' => 10,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'aidocument.document.history',
            'module' => $module,
            'permission' => 'document history manage'
        ]);
    }
}
