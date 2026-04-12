<?php

namespace App\Services\Common;

use App\Models\Menu;
use Illuminate\Support\Collection;

class MenuService
{
    /**
     * Generate the navigation tree from the core menus table.
     *
     * @param  bool  $includeSettingMenus  Include items flagged as setting_menu.
     * @return Collection<Menu>
     */
    public function generate(bool $includeSettingMenus = false): Collection
    {
        $query = Menu::query();

        if (!$includeSettingMenus) {
            $query->where(function ($q) {
                $q->whereNull('setting_menu')->orWhere('setting_menu', 0);
            });
        }

        // Build a two-level tree: top-level items with their children.
        // parent_id may not exist in all installations; guard accordingly.
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('menus');

        if (in_array('parent_id', $columns, true)) {
            $all = $query->orderBy('menu_name')->get();

            // Attach children to parents.
            $indexed  = $all->keyBy('id');
            $roots    = collect();

            foreach ($all as $item) {
                if (empty($item->parent_id)) {
                    $item->setRelation('children', collect());
                    $roots->push($item);
                } else {
                    $parent = $indexed->get($item->parent_id);
                    if ($parent) {
                        if (!$parent->relationLoaded('children')) {
                            $parent->setRelation('children', collect());
                        }
                        $parent->children->push($item);
                    } else {
                        // Orphaned item — treat as root.
                        $item->setRelation('children', collect());
                        $roots->push($item);
                    }
                }
            }

            return $roots;
        }

        return $query->orderBy('menu_name')->get();
    }

    /**
     * Delete a menu item, re-parenting any children to null
     * (orphans are preserved rather than cascade-deleted).
     */
    public function delete(Menu $menu): void
    {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('menus');

        if (in_array('parent_id', $columns, true)) {
            // Re-parent children so they become top-level items.
            Menu::where('parent_id', $menu->id)->update(['parent_id' => null]);
        }

        $menu->delete();
    }
}
