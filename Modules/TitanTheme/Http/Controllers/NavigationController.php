<?php

namespace Modules\TitanTheme\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\AccountBaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\TitanTheme\Models\NavItem;
use Modules\TitanTheme\Services\NavigationService;

class NavigationController extends AccountBaseController
{
    public function __construct(protected NavigationService $navigationService)
    {
        parent::__construct();
        $this->pageTitle = __('titantheme::titantheme.navigation');
    }

    /**
     * Show the sidebar navigation builder.
     */
    public function index()
    {
        abort_403(!$this->canViewNavigation());

        $this->sidebarItems = $this->navigationService->flatList(NavItem::PANEL_SIDEBAR);
        $this->headerItems  = $this->navigationService->flatList(NavItem::PANEL_HEADER);

        return view('titantheme::navigation.index', $this->data);
    }

    /**
     * Store a new nav item.
     */
    public function store(Request $request)
    {
        abort_403(!$this->canManageNavigation());

        $data = $request->validate([
            'parent_id'       => [
                'nullable',
                'integer',
                Rule::exists('titan_nav_items', 'id')->where(function ($query) use ($request) {
                    $query->where('company_id', $this->user->company_id)
                        ->where('panel', $request->input('panel'));
                }),
            ],
            'label'           => 'required|string|max:255',
            'url'             => 'nullable|string|max:500',
            'route_name'      => 'nullable|string|max:200',
            'icon'            => 'nullable|string|max:100',
            'panel'           => 'required|in:sidebar,header',
            'item_type'       => 'nullable|in:link,separator,heading',
            'is_active'       => 'nullable|boolean',
            'open_in_new_tab' => 'nullable|boolean',
            'visible_to_roles'=> 'nullable|string|max:500',
            'required_module' => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer|min:0',
        ]);

        $data['created_by']      = $this->user->id;
        $data['is_active']       = $request->boolean('is_active', true);
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab', false);

        NavItem::create($data);

        return Reply::successWithData(
            __('titantheme::titantheme.nav_item_created'),
            ['redirectUrl' => route('titantheme.navigation.index')]
        );
    }

    /**
     * Update an existing nav item.
     */
    public function update(Request $request, int $id)
    {
        abort_403(!$this->canManageNavigation());

        $item = NavItem::findOrFail($id);

        $data = $request->validate([
            'parent_id'       => [
                'nullable',
                'integer',
                Rule::exists('titan_nav_items', 'id')->where(function ($query) use ($request) {
                    $query->where('company_id', $this->user->company_id)
                        ->where('panel', $request->input('panel'));
                }),
            ],
            'label'           => 'required|string|max:255',
            'url'             => 'nullable|string|max:500',
            'route_name'      => 'nullable|string|max:200',
            'icon'            => 'nullable|string|max:100',
            'panel'           => 'required|in:sidebar,header',
            'item_type'       => 'nullable|in:link,separator,heading',
            'is_active'       => 'nullable|boolean',
            'open_in_new_tab' => 'nullable|boolean',
            'visible_to_roles'=> 'nullable|string|max:500',
            'required_module' => 'nullable|string|max:100',
            'sort_order'      => 'nullable|integer|min:0',
        ]);

        $data['is_active']       = $request->boolean('is_active', true);
        $data['open_in_new_tab'] = $request->boolean('open_in_new_tab', false);
        $data['parent_id'] = ($data['parent_id'] ?? null) == $item->id ? null : ($data['parent_id'] ?? null);

        $item->update($data);

        return Reply::successWithData(
            __('titantheme::titantheme.nav_item_updated'),
            ['redirectUrl' => route('titantheme.navigation.index')]
        );
    }

    /**
     * Delete a nav item.
     */
    public function destroy(int $id)
    {
        abort_403(!$this->canManageNavigation());

        NavItem::findOrFail($id)->delete();

        return Reply::success(__('titantheme::titantheme.nav_item_deleted'));
    }

    /**
     * Persist new sort order (array of IDs).
     */
    public function reorder(Request $request)
    {
        abort_403(!$this->canManageNavigation());

        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];

        $this->navigationService->reorder($ids);

        return Reply::success(__('titantheme::titantheme.order_saved'));
    }

    protected function canViewNavigation(): bool
    {
        return in_array('titantheme', user_modules(), true)
            && in_array($this->user->permission('view_navigation'), ['all', 'added', 'owned', 'both'], true);
    }

    protected function canManageNavigation(): bool
    {
        return in_array('titantheme', user_modules(), true)
            && in_array($this->user->permission('manage_navigation'), ['all', 'added', 'owned', 'both'], true);
    }
}
