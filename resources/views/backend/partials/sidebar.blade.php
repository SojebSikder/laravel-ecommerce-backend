<?php
$menus = [
    [
        'label' => 'Sales',
        'name' => 'sales',
        'icon' => 'bi bi-cart3',
        'order' => 1,
        'permission' => 'order_management_read',
        'children' => [
            [
                'label' => 'Orders',
                'name' => 'orders',
                'icon' => 'bi bi-layout-split',
                'route' => 'order.index',
                'order' => 1,
                'parent' => 'sales',
            ],
            [
                'label' => 'Drafts',
                'name' => 'drafts',
                'icon' => 'bi bi-layout-split',
                'route' => 'order-draft.index',
                'order' => 2,
                'parent' => 'sales',
            ],
            [
                'label' => 'Abandoned checkouts',
                'name' => 'abandoned-checkouts',
                'icon' => 'bi bi-layout-split',
                'route' => 'checkout.index',
                'order' => 3,
                'parent' => 'sales',
            ],
        ],
    ],
    [
        'label' => 'Products',
        'name' => 'products',
        'icon' => 'bi bi-journal-album',
        'order' => 2,
        'permission' => 'product_management_read',
        'children' => [
            [
                'label' => 'Inventory',
                'name' => 'inventory',
                'icon' => 'bi bi-layout-split',
                'route' => 'product.index',
                'order' => 1,
                'parent' => 'products',
            ],
            [
                'label' => 'Categories',
                'name' => 'categories',
                'icon' => 'bi bi-layout-split',
                'route' => 'category.index',
                'order' => 2,
                'parent' => 'products',
            ],
            [
                'label' => 'Manufacturers',
                'name' => 'manufacturers',
                'icon' => 'bi bi-layout-split',
                'route' => 'manufacturer.index',
                'order' => 3,
                'parent' => 'products',
            ],
            [
                'label' => 'Review',
                'name' => 'review',
                'icon' => 'bi bi-layout-split',
                'route' => 'review.index',
                'order' => 4,
                'parent' => 'products',
            ],
            [
                'label' => 'Tags',
                'name' => 'tags',
                'icon' => 'bi bi-layout-split',
                'route' => 'tag.index',
                'order' => 5,
                'parent' => 'products',
            ],
            [
                'label' => 'Option sets',
                'name' => 'option-sets',
                'icon' => 'bi bi-layout-split',
                'route' => 'option-set.index',
                'order' => 6,
                'parent' => 'products',
            ],
        ],
    ],
    [
        'label' => 'Customers',
        'name' => 'customers',
        'icon' => 'bi bi-people',
        'order' => 3,
        'permission' => 'user_management_read',
        'children' => [
            [
                'label' => 'Customers',
                'name' => 'customers',
                'icon' => 'bi bi-layout-split',
                'route' => 'customer.index',
                'order' => 1,
                'parent' => 'customers',
            ],
            [
                'label' => 'Customer roles',
                'name' => 'customer-roles',
                'icon' => 'bi bi-layout-split',
                'route' => 'role.index',
                'order' => 2,
                'parent' => 'customers',
            ],
        ],
    ],
    [
        'label' => 'Promotions',
        'name' => 'promotions',
        'icon' => 'bi bi-tags',
        'order' => 4,
        'permission' => 'coupon_management_read',
        'children' => [
            [
                'label' => 'Coupons',
                'name' => 'coupons',
                'icon' => 'bi bi-layout-split',
                'route' => 'coupon.index',
                'order' => 1,
                'parent' => 'promotions',
            ],
            [
                'label' => 'Custom mail',
                'name' => 'custom-mail',
                'icon' => 'bi bi-layout-split',
                'route' => 'sendmail.create',
                'order' => 1,
                'parent' => 'promotions',
            ],
        ],
    ],
    [
        'label' => 'Content management',
        'name' => 'cms',
        'icon' => 'bi bi-body-text',
        'order' => 5,
        'permission' => 'page_management_read',
        'children' => [
            [
                'label' => 'Pages',
                'name' => 'pages',
                'icon' => 'bi bi-layout-split',
                'route' => 'page.index',
                'order' => 1,
                'parent' => 'cms',
            ],
            [
                'label' => 'Footer',
                'name' => 'footer',
                'icon' => 'bi bi-layout-split',
                'route' => 'footer.index',
                'order' => 2,
                'parent' => 'cms',
            ],
        ],
    ],
    [
        'label' => 'Settings',
        'name' => 'settings',
        'icon' => 'bi bi-gear',
        'order' => 6,
        'permission' => 'setting_management_read',
        'children' => [
            [
                'label' => 'General Settings',
                'name' => 'general-settings',
                'icon' => 'bi bi-layout-split',
                'route' => 'general-setting.index',
                'order' => 1,
                'parent' => 'settings',
            ],
            [
                'label' => 'Basic',
                'name' => 'basic',
                'icon' => 'bi bi-layout-split',
                'route' => 'setting.index',
                'order' => 2,
                'parent' => 'settings',
            ],
            [
                'label' => 'Shipping',
                'name' => 'shipping',
                'icon' => 'bi bi-layout-split',
                'route' => 'shipping.index',
                'order' => 3,
                'parent' => 'settings',
            ],
            [
                'label' => 'Payment method',
                'name' => 'payment-method',
                'icon' => 'bi bi-layout-split',
                'route' => 'payment-provider.index',
                'order' => 4,
                'parent' => 'settings',
            ],
            [
                'label' => 'Order status',
                'name' => 'order-status',
                'icon' => 'bi bi-layout-split',
                'route' => 'status.index',
                'order' => 5,
                'parent' => 'settings',
            ],
            [
                'label' => 'Currency',
                'name' => 'currency',
                'icon' => 'bi bi-layout-split',
                'route' => 'currency.index',
                'order' => 6,
                'parent' => 'settings',
            ],
            [
                'label' => 'Plugins',
                'name' => 'plugins',
                'icon' => 'bi bi-layout-split',
                'route' => 'plugin.index',
                'order' => 7,
                'parent' => 'settings',
            ],
        ],
    ],
];

// plugin menus
$pluginMenus = SojebPluginManager::getPluginMenus(true);
if ($pluginMenus) {
    // insert plugin menus into specific position based on parent and order
    foreach ($pluginMenus as $pluginMenu) {
        $parent = $pluginMenu['parent'];
        $order = $pluginMenu['order'];
        $index = 0;
        foreach ($menus as $menu) {
            if ($menu['name'] == $parent) {
                if (isset($menu['children'])) {
                    $menu['children'] = array_merge(array_slice($menu['children'], 0, $order - 1), [$pluginMenu], array_slice($menu['children'], $order - 1));
                } else {
                    $menu['children'] = [$pluginMenu];
                }
                $menus[$index] = $menu;
                break;
            }

            $index++;
        }

        // $menus = array_merge(array_slice($menus, 0, $index + 1), [$pluginMenu], array_slice($menus, $index + 1));
    }
}

// end plugin menus

?>

<div>
    @if (in_array(auth()->user()->type, ['admin', 'su_admin']))
        @php
            $prefix = Request::route()->getPrefix();
            $route = Route::current()->getName();
        @endphp
        <!-- Sidebar -->
        <div class="offcanvas offcanvas-start bg-dark sidebar-nav text-white" tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">

            <div class="offcanvas-body p-0">

                <nav class="navbar-dark">
                    <ul class="navbar-nav">
                        <li>
                            <div class="text-secondary small fw-bold text-uppercase px-3">
                                Dashboard
                            </div>
                        </li>
                        <li>
                            <a href="/dashboard" class="nav-link active px-3">
                                <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="my-4">
                            <hr />
                        </li>
                        <li>
                            <div class="text-secondary small fw-bold text-uppercase px-3">
                                Manage
                            </div>
                        </li>

                        {{-- <li>
                            <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse" href="#collapseExample"
                                role="button" aria-expanded="false" aria-controls="collapseExample">
                                <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                <span>Layouts</span>
                                <span class="right-icon ms-auto">
                                    <i class="bi bi-chevron-down"></i>
                                </span>
                            </a>
                            <div class="collapse" id="collapseExample">
                                <div>
                                    <ul class="navbar-nav ps-3">
                                        <li>
                                            <a href="#" class="nav-link px-3">
                                                <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                <span>Nested Link</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </li> --}}

                        @foreach ($menus as $item)
                            @can($item['permission'])
                                <li>
                                    <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse"
                                        href="#{{ $item['name'] }}" role="button" aria-expanded="false"
                                        aria-controls="{{ $item['name'] }}">
                                        <span class="me-2"><i class="{{ $item['icon'] }}"></i></span>
                                        <span>{{ $item['label'] }}</span>
                                        <span class="right-icon ms-auto">
                                            <i class="bi bi-chevron-down"></i>
                                        </span>
                                    </a>
                                    <div class="collapse" id="{{ $item['name'] }}">
                                        <div>
                                            <ul class="navbar-nav ps-3">
                                                @if (isset($item['children']) && count($item['children']) > 0)
                                                    @foreach ($item['children'] as $child)
                                                        {{-- @can($child['permission']) --}}
                                                        <li>
                                                            <a href="{{ route($child['route']) }}" class="nav-link px-3">
                                                                <span class="me-2"><i
                                                                        class="bi bi-layout-split"></i></span>
                                                                <span>{{ $child['label'] }}</span>
                                                            </a>
                                                        </li>
                                                        {{-- @endcan --}}
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endcan
                        @endforeach



                        <li>
                            <a class="nav-link sidebar-link px-3" target="__blank" href="{{ env('CLIENT_APP_URL') }}"
                                role="button">
                                <span class="me-2"><i class="bi bi-shop"></i></span>
                                <span>View store</span>
                                <span class="right-icon ms-auto">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </span>
                            </a>

                        </li>

                    </ul>
                </nav>

            </div>
        </div>
        <!-- End Sidebar -->
    @endif
</div>
