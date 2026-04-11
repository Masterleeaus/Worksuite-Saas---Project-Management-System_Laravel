{{--
    PromotionManagement — Sidebar partial.
    Pushed into the host application's sidebar layout via @stack('sidebar-modules').
    Only rendered when the PromotionManagement module is active.
--}}

@if(module_enabled('PromotionManagement'))
<li class="nav-item {{ Request::is('*admin/discount*') || Request::is('*admin/coupon*') || Request::is('*admin/campaign*') ? 'mm-active' : '' }}">
    <a href="#promotionSidebar" class="nav-link {{ Request::is('*admin/discount*') || Request::is('*admin/coupon*') || Request::is('*admin/campaign*') ? '' : 'collapsed' }}"
       data-toggle="collapse"
       aria-expanded="{{ Request::is('*admin/discount*') || Request::is('*admin/coupon*') || Request::is('*admin/campaign*') ? 'true' : 'false' }}">
        <span class="material-icons">local_offer</span>
        <span class="menu-title">{{ translate('Promotions') }}</span>
        <i class="arrow"></i>
    </a>
    <div id="promotionSidebar" class="collapse {{ Request::is('*admin/discount*') || Request::is('*admin/coupon*') || Request::is('*admin/campaign*') ? 'show' : '' }}">
        <ul class="sub-menu">
            @can('discount_view')
            <li class="nav-item {{ Request::is('*admin/discount*') ? 'mm-active' : '' }}">
                <a class="nav-link {{ Request::is('*admin/discount*') ? 'active' : '' }}"
                   href="{{ route('admin.discount.list') }}">
                    <span class="material-icons">discount</span>
                    {{ translate('Discounts') }}
                </a>
            </li>
            @endcan

            @can('discount_view')
            <li class="nav-item {{ Request::is('*admin/coupon*') ? 'mm-active' : '' }}">
                <a class="nav-link {{ Request::is('*admin/coupon*') ? 'active' : '' }}"
                   href="{{ route('admin.coupon.list') }}">
                    <span class="material-icons">confirmation_number</span>
                    {{ translate('Coupons') }}
                </a>
            </li>
            @endcan

            @can('discount_view')
            <li class="nav-item {{ Request::is('*admin/campaign*') ? 'mm-active' : '' }}">
                <a class="nav-link {{ Request::is('*admin/campaign*') ? 'active' : '' }}"
                   href="{{ route('admin.campaign.list') }}">
                    <span class="material-icons">campaign</span>
                    {{ translate('Campaigns') }}
                </a>
            </li>
            @endcan
        </ul>
    </div>
</li>
@endif
