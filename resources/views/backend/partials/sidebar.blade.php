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
                        @can('order_management_read')
                            <li>
                                <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse" href="#sales"
                                    role="button" aria-expanded="false" aria-controls="sales">
                                    <span class="me-2"><i class="bi bi-cart3"></i></span>
                                    <span>Sales</span>
                                    <span class="right-icon ms-auto">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="collapse" id="sales">
                                    <div>
                                        <ul class="navbar-nav ps-3">
                                            <li>
                                                <a href="{{ route('order.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Orders</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('checkout.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Abandoned checkouts</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endcan

                        @can('product_management_read')
                            <li>
                                <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse" href="#products"
                                    role="button" aria-expanded="false" aria-controls="products">
                                    <span class="me-2"><i class="bi bi-journal-album"></i></span>
                                    <span>Products</span>
                                    <span class="right-icon ms-auto">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="collapse" id="products">
                                    <div>
                                        <ul class="navbar-nav ps-3">
                                            <li>
                                                <a href="{{ route('product.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Inventory</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('category.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Categories</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('manufacturer.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Manufacturers</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endcan

                        @can('user_management_read')
                            <li>
                                <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse" href="#customer"
                                    role="button" aria-expanded="false" aria-controls="customer">
                                    <span class="me-2"><i class="bi bi-people"></i></i></span>
                                    <span>Customers</span>
                                    <span class="right-icon ms-auto">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="collapse" id="customer">
                                    <div>
                                        <ul class="navbar-nav ps-3">
                                            <li>
                                                <a href="{{ route('customer.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Customers</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('role.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Customer roles</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endcan

                        @can('coupon_management_read')
                            <li>
                                <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse" href="#promotion"
                                    role="button" aria-expanded="false" aria-controls="promotion">
                                    <span class="me-2"><i class="bi bi-tags"></i></i></span>
                                    <span>Promotions</span>
                                    <span class="right-icon ms-auto">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="collapse" id="promotion">
                                    <div>
                                        <ul class="navbar-nav ps-3">
                                            <li>
                                                <a href="{{ route('coupon.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Coupons</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endcan

                        @can('page_management_read')
                            <li>
                                <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse" href="#cms"
                                    role="button" aria-expanded="false" aria-controls="cms">
                                    <span class="me-2"><i class="bi bi-body-text"></i></i></span>
                                    <span>Content management</span>
                                    <span class="right-icon ms-auto">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="collapse" id="cms">
                                    <div>
                                        <ul class="navbar-nav ps-3">
                                            <li>
                                                <a href="{{ route('page.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Pages</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('footer.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Footer</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endcan

                        @can('setting_management_read')
                            <li>
                                <a class="nav-link sidebar-link px-3" data-bs-toggle="collapse" href="#settings"
                                    role="button" aria-expanded="false" aria-controls="settings">
                                    <span class="me-2"><i class="bi bi-gear"></i></span>
                                    <span>Settings</span>
                                    <span class="right-icon ms-auto">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="collapse" id="settings">
                                    <div>
                                        <ul class="navbar-nav ps-3">
                                            <li>
                                                <a href="{{ route('setting.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Basic</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('shipping.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Shipping</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('payment-provider.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Payment method</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('status.index') }}" class="nav-link px-3">
                                                    <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                                    <span>Order status</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                        @endcan

                        <li>
                            <a class="nav-link sidebar-link px-3" target="__blank"
                                href="{{ env('CLIENT_APP_URL') }}" role="button">
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
