<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use App\Models\Menu;
use App\Services\Common\MenuService;

class MenuController extends AccountBaseController
{
    public function __construct(protected MenuService $menuService)
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.menu_builder');
    }

    /**
     * Display the sidebar navigation builder.
     */
    public function index()
    {
        abort_403(!in_array($this->user->permission('manage_navigation'), ['all', 'added']));

        $this->menus = $this->menuService->generate(false);

        return view('titantheme::menu.index', $this->data);
    }

    /**
     * Remove a menu item.
     * Children are re-parented to null (orphaned) rather than cascade-deleted.
     */
    public function delete(Menu $menu)
    {
        abort_403(!in_array($this->user->permission('manage_navigation'), ['all', 'added']));

        if (env('APP_ENV') === 'demo') {
            return Reply::error(__('messages.demoModeNotAllowed'));
        }

        $this->menuService->delete($menu);

        return Reply::success(__('titantheme::titantheme.menu_item_deleted'));
    }
}
