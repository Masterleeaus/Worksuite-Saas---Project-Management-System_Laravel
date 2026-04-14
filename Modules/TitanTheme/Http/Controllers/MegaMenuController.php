<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Modules\TitanTheme\Http\Requests\MegaMenuRequest;
use Modules\TitanTheme\Models\MegaMenu;

class MegaMenuController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.mega_menu');
    }

    /**
     * List all mega menus.
     */
    public function index()
    {
        abort_403(!$this->canViewMegaMenu());

        $this->menus = MegaMenu::with('items')->orderBy('sort_order')->paginate(10);

        return view('titantheme::mega-menu.index', $this->data);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        abort_403(!$this->canManageMegaMenu());

        return view('titantheme::mega-menu.create', $this->data);
    }

    /**
     * Store a new mega menu.
     */
    public function store(MegaMenuRequest $request)
    {
        abort_403(!$this->canManageMegaMenu());

        $data = $request->validated();

        $data['created_by'] = $this->user->id;
        $data['is_active']  = $request->boolean('is_active', true);

        MegaMenu::create($data);

        return Reply::successWithData(
            __('titantheme::titantheme.mega_menu_created'),
            ['redirectUrl' => route('titantheme.mega-menu.index')]
        );
    }

    /**
     * Show edit form.
     */
    public function edit(int $id)
    {
        abort_403(!$this->canManageMegaMenu());

        $this->menu = MegaMenu::with('allItems')->findOrFail($id);

        return view('titantheme::mega-menu.edit', $this->data);
    }

    /**
     * Update an existing mega menu.
     */
    public function update(MegaMenuRequest $request, int $id)
    {
        abort_403(!$this->canManageMegaMenu());

        $menu = MegaMenu::findOrFail($id);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $menu->update($data);

        return Reply::successWithData(
            __('titantheme::titantheme.mega_menu_updated'),
            ['redirectUrl' => route('titantheme.mega-menu.index')]
        );
    }

    /**
     * Delete a mega menu (cascades to items).
     */
    public function destroy(int $id)
    {
        abort_403(!$this->canManageMegaMenu());

        MegaMenu::findOrFail($id)->delete();

        return Reply::success(__('titantheme::titantheme.mega_menu_deleted'));
    }

    /**
     * Persist new sort order (array of IDs).
     */
    public function reorder(Request $request)
    {
        abort_403(!$this->canManageMegaMenu());

        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];

        foreach ($ids as $position => $id) {
            MegaMenu::where('id', $id)->update(['sort_order' => $position]);
        }

        return Reply::success(__('titantheme::titantheme.order_saved'));
    }

    protected function canViewMegaMenu(): bool
    {
        return in_array('titantheme', user_modules(), true)
            && in_array($this->user->permission('view_mega_menu'), ['all', 'added', 'owned', 'both'], true);
    }

    protected function canManageMegaMenu(): bool
    {
        return in_array('titantheme', user_modules(), true)
            && in_array($this->user->permission('manage_mega_menu'), ['all', 'added', 'owned', 'both'], true);
    }
}
