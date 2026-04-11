<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanTheme\Models\MegaMenu;
use Modules\TitanTheme\Models\MegaMenuItem;

class MegaMenuItemController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.mega_menu');
    }

    /**
     * Store a new menu item.
     */
    public function store(Request $request, int $menuId)
    {
        abort_403(!$this->user->permission('manage_mega_menu'));

        MegaMenu::findOrFail($menuId);

        $data = $request->validate([
            'parent_id'       => 'nullable|integer|exists:titan_mega_menu_items,id',
            'label'           => 'required|string|max:255',
            'url'             => 'nullable|string|max:500',
            'route_name'      => 'nullable|string|max:200',
            'icon'            => 'nullable|string|max:100',
            'description'     => 'nullable|string|max:500',
            'thumbnail_path'  => 'nullable|string|max:500',
            'item_type'       => 'nullable|in:link,group,featured',
            'open_in_new_tab' => 'nullable|boolean',
            'is_active'       => 'nullable|boolean',
            'is_featured'     => 'nullable|boolean',
            'required_module' => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer|min:0',
            'column_span'     => 'nullable|integer|min:1|max:4',
        ]);

        $data['mega_menu_id']    = $menuId;
        $data['is_active']       = $request->boolean('is_active', true);
        $data['is_featured']     = $request->boolean('is_featured', false);
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab', false);

        MegaMenuItem::create($data);

        return Reply::successWithData(
            __('titantheme::titantheme.menu_item_created'),
            ['redirectUrl' => route('titantheme.mega-menu.edit', $menuId)]
        );
    }

    /**
     * Update an existing menu item.
     */
    public function update(Request $request, int $menuId, int $itemId)
    {
        abort_403(!$this->user->permission('manage_mega_menu'));

        $item = MegaMenuItem::where('mega_menu_id', $menuId)->findOrFail($itemId);

        $data = $request->validate([
            'parent_id'       => 'nullable|integer|exists:titan_mega_menu_items,id',
            'label'           => 'required|string|max:255',
            'url'             => 'nullable|string|max:500',
            'route_name'      => 'nullable|string|max:200',
            'icon'            => 'nullable|string|max:100',
            'description'     => 'nullable|string|max:500',
            'thumbnail_path'  => 'nullable|string|max:500',
            'item_type'       => 'nullable|in:link,group,featured',
            'open_in_new_tab' => 'nullable|boolean',
            'is_active'       => 'nullable|boolean',
            'is_featured'     => 'nullable|boolean',
            'required_module' => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer|min:0',
            'column_span'     => 'nullable|integer|min:1|max:4',
        ]);

        $data['is_active']       = $request->boolean('is_active', true);
        $data['is_featured']     = $request->boolean('is_featured', false);
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab', false);

        $item->update($data);

        return Reply::successWithData(
            __('titantheme::titantheme.menu_item_updated'),
            ['redirectUrl' => route('titantheme.mega-menu.edit', $menuId)]
        );
    }

    /**
     * Delete a menu item.
     */
    public function destroy(int $menuId, int $itemId)
    {
        abort_403(!$this->user->permission('manage_mega_menu'));

        MegaMenuItem::where('mega_menu_id', $menuId)->findOrFail($itemId)->delete();

        return Reply::success(__('titantheme::titantheme.menu_item_deleted'));
    }

    /**
     * Persist new sort order for items within a menu.
     */
    public function reorder(Request $request, int $menuId)
    {
        abort_403(!$this->user->permission('manage_mega_menu'));

        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];

        foreach ($ids as $position => $id) {
            MegaMenuItem::where('mega_menu_id', $menuId)
                ->where('id', $id)
                ->update(['sort_order' => $position]);
        }

        return Reply::success(__('titantheme::titantheme.order_saved'));
    }
}
