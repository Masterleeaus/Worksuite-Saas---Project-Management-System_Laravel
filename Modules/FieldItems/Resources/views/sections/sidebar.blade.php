@if (in_array('items', user_modules()))
    <li class="{{ request()->routeIs('items.*') || request()->routeIs('itemCategory.*') || request()->routeIs('itemSubCategory.*') || request()->routeIs('task-items.*') ? 'active' : '' }}">
        <a href="{{ route('items.index') }}">
            <i class="fa fa-box"></i>
            <span>@lang('fielditems::app.menu.items')</span>
        </a>
        <ul class="sub-menu">
            <li class="{{ request()->routeIs('items.*') ? 'active' : '' }}">
                <a href="{{ route('items.index') }}">
                    <i class="fa fa-list mr-1"></i>
                    @lang('fielditems::app.menu.items')
                </a>
            </li>
            <li class="{{ request()->routeIs('itemCategory.*') ? 'active' : '' }}">
                <a href="{{ route('itemCategory.create') }}">
                    <i class="fa fa-tags mr-1"></i>
                    @lang('fielditems::app.menu.categories')
                </a>
            </li>
            <li class="{{ request()->routeIs('task-items.*') ? 'active' : '' }}">
                <a href="{{ request()->route('task_id') ? route('task-items.index', request()->route('task_id')) : route('items.index') }}">
                    <i class="fa fa-clipboard-list mr-1"></i>
                    @lang('fielditems::app.menu.jobConsumption')
                </a>
            </li>
        </ul>
    </li>
@endif
