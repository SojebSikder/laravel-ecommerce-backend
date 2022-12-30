<div class="sidebar-wrapper sidebar-theme">

    {{-- @if (auth()->user()->type == 'admin') --}}
    @if (in_array(auth()->user()->type, ['admin', 'su_admin']))
        @php
            $prefix = Request::route()->getPrefix();
            $route = Route::current()->getName();
        @endphp

        <nav id="sidebar">
            <div class="shadow-bottom"></div>
            <ul class="list-unstyled menu-categories" id="accordionExample">
                {{-- Dashboard --}}
                <li class="menu">
                    <a href="#dashboard" data-active="false" data-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Dashboard</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul {{-- class="collapse submenu list-unstyled"  --}}
                        class="{{ $route == 'dashboard' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="dashboard" data-parent="#accordionExample">
                        <li class="{{ $route == 'dashboard' ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}"> Dashboard</a>
                        </li>

                        <li class="{{ $route == 'header-slider.index' ? 'active' : '' }}">
                            <a href="{{ route('header-slider.index') }}"> Header Slider</a>
                        </li>
                        <li class="{{ $route == 'banner.index' ? 'active' : '' }}">
                            <a href="{{ route('banner.index') }}"> Banner List</a>
                        </li>
                        <li class="{{ $route == 'menu.index' ? 'active' : '' }}">
                            <a href="{{ route('menu.index') }}"> Menu</a>
                        </li>
                    </ul>
                </li>

                {{-- Order --}}
                <li class="menu">
                    <a href="#order" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg version="1.1" stroke="currentColor" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 476.944 476.944" style="enable-background:new 0 0 476.944 476.944;"
                                xml:space="preserve">
                                <g>
                                    <path
                                        d="M84.176,379.875c-26.762,0-48.535,21.772-48.535,48.534s21.772,48.534,48.535,48.534c26.762,0,48.534-21.772,48.534-48.534
                                        S110.938,379.875,84.176,379.875z M84.176,446.944c-10.22,0-18.535-8.314-18.535-18.534s8.314-18.534,18.535-18.534
                                        c10.22,0,18.534,8.314,18.534,18.534S94.396,446.944,84.176,446.944z" />
                                    <path
                                        d="M342.707,379.875c-26.762,0-48.534,21.772-48.534,48.534s21.772,48.534,48.534,48.534
		                                c26.762,0,48.535-21.772,48.535-48.534S369.469,379.875,342.707,379.875z M342.707,446.944c-10.22,0-18.534-8.314-18.534-18.534
		                                s8.314-18.534,18.534-18.534c10.22,0,18.535,8.314,18.535,18.534S352.927,446.944,342.707,446.944z" />
                                    <path
                                        d="M413.322,0l-9.835,60H1.999l28.736,175.88c4.044,24.67,26.794,43.995,51.794,43.995h284.917l-6.557,40H50.642v30h335.73
		                                L438.804,30h36.141V0H413.322z M372.363,249.875H82.529c-10.174,0-20.543-8.808-22.188-18.841L37.298,90h361.271L372.363,249.875z" />
                            </svg>
                            <span>Order</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="{{ $route == 'order.index' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="order" data-parent="#accordionExample">
                        <li class="{{ $route == 'order.index' ? 'active' : '' }}">
                            <a href="{{ route('order.index') }}?date=today"> Orders</a>
                        </li>
                        <li class="{{ $route == 'cart.index' ? 'active' : '' }}">
                            <a href="{{ route('cart.index') }}"> Abandoned carts</a>
                        </li>
                    </ul>
                </li>

                {{-- Products --}}
                <li class="menu">
                    <a href="#product" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24"
                                height="24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 46.549 46.549"
                                style="enable-background:new 0 0 46.549 46.549;" xml:space="preserve">
                                <g>
                                    <g>
                                        <path
                                            d="M45.84,1.661c-0.025-0.515-0.437-0.927-0.952-0.951L30.04,0.002c-0.283-0.021-0.555,0.092-0.754,0.292L0.294,29.286
                                                c-0.188,0.188-0.293,0.441-0.293,0.707s0.105,0.52,0.293,0.707L15.85,46.256c0.195,0.195,0.451,0.293,0.707,0.293
                                                s0.512-0.098,0.707-0.293l28.991-28.991c0.199-0.199,0.305-0.474,0.292-0.755L45.84,1.661z M16.557,44.135L2.415,29.993
                                                L30.387,2.021l13.499,0.644l0.643,13.499L16.557,44.135z" />
                                        <path
                                            d="M32.113,8.78c-0.755,0.756-1.171,1.76-1.171,2.828s0.416,2.073,1.171,2.828c0.756,0.756,1.76,1.172,2.829,1.172
                                                s2.073-0.416,2.828-1.172c0.755-0.755,1.172-1.759,1.172-2.828c0-1.068-0.416-2.072-1.172-2.828
                                                C36.259,7.268,33.624,7.268,32.113,8.78z M36.356,13.022c-0.756,0.756-2.073,0.756-2.829,0c-0.377-0.378-0.585-0.88-0.585-1.414
                                                s0.208-1.036,0.585-1.414c0.378-0.378,0.88-0.586,1.415-0.586s1.036,0.208,1.414,0.586s0.586,0.88,0.586,1.414
                                                S36.734,12.644,36.356,13.022z" />
                                    </g>
                                </g>
                            </svg>
                            <span>Products</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="{{ $route == 'product.index' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="product" data-parent="#accordionExample">
                        <li class="{{ $route == 'product.index' ? 'active' : '' }}">
                            <a href="{{ route('product.index') }}">Inventory</a>
                        </li>
                        <li class="{{ $route == 'extra-product.index' ? 'active' : '' }}">
                            <a href="{{ route('extra-product.index') }}"> Product suggestion</a>
                        </li>
                        <li class="{{ $route == 'option-set.index' ? 'active' : '' }}">
                            <a href="{{ route('option-set.index') }}"> Option sets</a>
                        </li>
                        <li class="{{ $route == 'category.index' ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}"> Categories</a>
                        </li>
                        <li class="{{ $route == 'tags.index' ? 'active' : '' }}">
                            <a href="{{ route('tags.index') }}">Tags</a>
                        </li>
                        <li class="{{ $route == 'material.index' ? 'active' : '' }}">
                            <a href="{{ route('material.index') }}"> Materials</a>
                        </li>
                    </ul>
                </li>

                {{-- Customer --}}
                <li class="menu">
                    <a href="{{ route('customer.index') }}"
                        aria-expanded="{{ $route == 'customer.index' ? 'true' : 'false' }}"
                        class="{{ $route == 'customer.index' ? 'dropdown-toggle' : 'dropdown-toggle collapsed' }}">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>Customers</span>
                        </div>
                    </a>
                </li>

                {{-- Marketing --}}
                <li class="menu">
                    <a href="#marketing" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-target" id="Layer_1"
                                style="enable-background:new 0 0 24 24;" version="1.1" xml:space="preserve"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <path
                                    d="M12,2C6.49,2,2,6.49,2,12c0,1.56,0.35,3.06,1.04,4.45c0.25,0.49,0.84,0.7,1.34,0.45c0.49-0.25,0.7-0.85,0.45-1.34  C4.28,14.45,4,13.25,4,12c0-4.41,3.59-8,8-8s8,3.59,8,8s-3.59,8-8,8c-1.25,0-2.45-0.28-3.56-0.83c-0.5-0.25-1.09-0.04-1.34,0.45  s-0.04,1.09,0.45,1.34C8.94,21.65,10.44,22,12,22c5.51,0,10-4.49,10-10C22,6.49,17.51,2,12,2z" />
                                <path
                                    d="M10.33,16.72c-0.52-0.18-1.09,0.09-1.28,0.61c-0.18,0.52,0.09,1.09,0.61,1.28C10.41,18.87,11.2,19,12,19c3.86,0,7-3.14,7-7  c0-3.86-3.14-7-7-7s-7,3.14-7,7c0,0.8,0.13,1.58,0.4,2.33c0.18,0.52,0.76,0.79,1.28,0.61c0.52-0.18,0.79-0.75,0.61-1.28  C7.1,13.13,7,12.57,7,12c0-2.76,2.24-5,5-5s5,2.24,5,5s-2.24,5-5,5C11.43,17,10.87,16.9,10.33,16.72z" />
                                <path
                                    d="M3,18c-0.55,0-1,0.45-1,1s0.45,1,1,1h1v1c0,0.55,0.45,1,1,1s1-0.45,1-1v-1.59l6.71-6.71c0.39-0.39,0.39-1.02,0-1.41  s-1.02-0.39-1.41,0L4.59,18H3z" />
                                <path
                                    d="M12.67,13.89c-0.52,0.18-0.79,0.75-0.61,1.28c0.15,0.41,0.53,0.67,0.94,0.67c0.11,0,0.22-0.02,0.33-0.06  C14.93,15.21,16,13.69,16,12c0-2.21-1.79-4-4-4c-1.69,0-3.21,1.07-3.77,2.67c-0.18,0.52,0.09,1.09,0.61,1.28  c0.52,0.19,1.09-0.09,1.28-0.61C10.4,10.54,11.15,10,12,10c1.1,0,2,0.9,2,2C14,12.85,13.46,13.6,12.67,13.89z" />
                            </svg>


                            <span>Marketing</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="{{ $route == 'sendmail.create' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="marketing" data-parent="#accordionExample">
                        <li>
                            <a href="{{ route('newsletter.index') }}"> Newsletter</a>
                        </li>
                        <li>
                            <a href="{{ route('sendmail.create') }}"> Send mail</a>
                        </li>
                        <li>
                            <a href="{{ route('mailing-list.index') }}"> Mailing list</a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('mail-automation.index') }}"> Mail automation</a>
                        </li> --}}
                    </ul>
                </li>

                {{-- Coupon --}}
                <li class="menu">
                    <a href="#coupon" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-target">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>

                            <span>Coupon</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="{{ $route == 'coupon.index' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="coupon" data-parent="#accordionExample">
                        <li>
                            <a href="{{ route('coupon.index') }}"> Coupon</a>
                        </li>
                        <li>
                            <a href="{{ route('coupon-campaign.index') }}"> Coupon Campaign</a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="menu">
                    <a href="{{ route('coupon.index') }}"
                        aria-expanded="{{ $route == 'coupon.index' ? 'true' : 'false' }}"
                        class="{{ $route == 'coupon.index' ? 'dropdown-toggle' : 'dropdown-toggle collapsed' }}">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-target">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                            <span>Coupon</span>
                        </div>
                    </a>
                </li> --}}

                {{-- payment transaction --}}
                <li class="menu">
                    <a href="{{ route('payment-transaction.index') }}"
                        aria-expanded="{{ $route == 'payment-transaction.index' ? 'true' : 'false' }}"
                        class="{{ $route == 'payment-transaction.index' ? 'dropdown-toggle' : 'dropdown-toggle collapsed' }}">
                        <div class="">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-layout" viewBox="0 0 60 60"
                                style="enable-background:new 0 0 60 60;" xml:space="preserve">
                                <g>
                                    <path
                                        d="M48,14c-1.276,0-2.469,0.349-3.5,0.947C43.469,14.349,42.276,14,41,14c-3.859,0-7,3.14-7,7s3.141,7,7,7
                                        c1.276,0,2.469-0.349,3.5-0.947C45.531,27.651,46.724,28,48,28c3.859,0,7-3.14,7-7S51.859,14,48,14z M46,21
                                        c0,1.394-0.576,2.654-1.5,3.562C43.576,23.654,43,22.394,43,21s0.576-2.654,1.5-3.562C45.424,18.346,46,19.606,46,21z M36,21
                                        c0-2.757,2.243-5,5-5c0.631,0,1.23,0.13,1.787,0.345C41.68,17.583,41,19.212,41,21s0.68,3.417,1.787,4.655
                                        C42.23,25.87,41.631,26,41,26C38.243,26,36,23.757,36,21z M48,26c-0.631,0-1.23-0.13-1.787-0.345C47.32,24.417,48,22.788,48,21
                                        s-0.68-3.417-1.787-4.655C46.77,16.13,47.369,16,48,16c2.757,0,5,2.243,5,5S50.757,26,48,26z" />
                                    <path
                                        d="M55.783,8H4.217C1.892,8,0,9.892,0,12.217v35.566C0,50.108,1.892,52,4.217,52h51.566C58.108,52,60,50.108,60,47.783V12.217
                                        C60,9.892,58.108,8,55.783,8z M58,47.783C58,49.005,57.006,50,55.783,50H4.217C2.994,50,2,49.005,2,47.783V12.217
                                        C2,10.995,2.994,10,4.217,10h51.566C57.006,10,58,10.995,58,12.217V47.783z" />
                                    <path
                                        d="M6,18h9c0.553,0,1-0.448,1-1s-0.447-1-1-1H6c-0.553,0-1,0.448-1,1S5.447,18,6,18z" />
                                    <path
                                        d="M28,16h-9c-0.553,0-1,0.448-1,1s0.447,1,1,1h9c0.553,0,1-0.448,1-1S28.553,16,28,16z" />
                                    <path
                                        d="M6,23h1c0.553,0,1-0.448,1-1s-0.447-1-1-1H6c-0.553,0-1,0.448-1,1S5.447,23,6,23z" />
                                    <path
                                        d="M11,21c-0.553,0-1,0.448-1,1s0.447,1,1,1h2c0.553,0,1-0.448,1-1s-0.447-1-1-1H11z" />
                                    <path
                                        d="M19,22c0-0.552-0.447-1-1-1h-1c-0.553,0-1,0.448-1,1s0.447,1,1,1h1C18.553,23,19,22.552,19,22z" />
                                    <path
                                        d="M24,23c0.553,0,1-0.448,1-1s-0.447-1-1-1h-2c-0.553,0-1,0.448-1,1s0.447,1,1,1H24z" />
                                    <path
                                        d="M27.3,21.29C27.109,21.48,27,21.73,27,22s0.109,0.52,0.29,0.71C27.479,22.89,27.74,23,28,23s0.52-0.11,0.71-0.29
		                                    C28.89,22.52,29,22.26,29,22c0-0.26-0.11-0.52-0.29-0.7C28.34,20.92,27.66,20.92,27.3,21.29z" />
                                    <path d="M5,45h11v-8H5V45z M7,39h7v4H7V39z" />
                                    <path d="M18,45h11v-8H18V45z M20,39h7v4h-7V39z" />
                                    <path d="M31,45h11v-8H31V45z M33,39h7v4h-7V39z" />
                                    <path d="M44,45h11v-8H44V45z M46,39h7v4h-7V39z" />
                                </g>
                            </svg>

                            <span>Transaction</span>
                        </div>
                    </a>
                </li>


                {{-- Blog --}}
                <li class="menu">
                    <a href="#blog" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg version="1.1" id="Layer_1" stroke="currentColor"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                x="0px" y="0px" viewBox="0 0 512 512"
                                style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                <g>
                                    <g>
                                        <path
                                            d="M477.166,194.626h-26.54c-19.818,0-35.94-16.122-35.94-35.94v-17.693C414.687,63.249,351.438,0,273.693,0h-132.7
                                   C63.249,0,0,63.249,0,140.994v185.78c0,4.581,3.713,8.294,8.294,8.294c4.58,0,8.294-3.712,8.294-8.294v-185.78
                                   c0-68.597,55.808-124.406,124.406-124.406h132.7c68.597,0,124.406,55.809,124.406,124.406v17.693
                                   c0,28.963,23.564,52.527,52.527,52.527h26.54c10.062,0,18.246,8.184,18.246,18.246v150.393
                                   c0,63.719-51.84,115.559-115.559,115.559H132.147c-63.72,0-115.559-51.84-115.559-115.559V362.16c0-4.581-3.713-8.294-8.294-8.294
                                   c-4.58,0-8.294,3.712-8.294,8.294v17.693C0,452.72,59.28,512,132.147,512h247.706C452.72,512,512,452.72,512,379.853V229.46
                                   C512,210.253,496.373,194.626,477.166,194.626z" />
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M264.847,132.7h-106.16c-19.207,0-34.834,15.627-34.834,34.834c0,19.207,15.626,34.834,34.834,34.834h106.16
                                   c19.207,0,34.834-15.627,34.834-34.834C299.68,148.326,284.054,132.7,264.847,132.7z M264.847,185.78h-106.16
                                   c-10.061,0-18.246-8.184-18.246-18.246c0-10.062,8.185-18.246,18.246-18.246h106.16c10.062,0,18.246,8.184,18.246,18.246
                                   C283.093,177.595,274.909,185.78,264.847,185.78z" />
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M344.466,309.633h-44.233c-4.581,0-8.294,3.712-8.294,8.294c0,4.581,3.712,8.294,8.294,8.294h44.233
                                   c10.062,0,18.246,8.184,18.246,18.246c0,10.062-8.184,18.246-18.246,18.246h-185.78c-10.061,0-18.246-8.184-18.246-18.246
                                   c0-10.062,8.185-18.246,18.246-18.246h106.16c4.581,0,8.294-3.712,8.294-8.294c0-4.581-3.712-8.294-8.294-8.294h-106.16
                                   c-19.207,0-34.834,15.627-34.834,34.834c0,19.207,15.626,34.834,34.834,34.834h185.78c19.207,0,34.834-15.627,34.834-34.834
                                   C379.3,325.259,363.674,309.633,344.466,309.633z" />
                                    </g>
                                </g>
                            </svg>
                            <span>Blog posts</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="{{ $route == 'blog.index' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="blog" data-parent="#accordionExample">
                        <li>
                            <a href="{{ route('blog.index') }}"> Blogs List</a>
                        </li>
                    </ul>
                </li>


                {{-- Page --}}
                <li class="menu">
                    <a href="#page" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout">
                                <rect x="3" y="3" width="18" height="18" rx="2"
                                    ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="9" y1="21" x2="9" y2="9"></line>
                            </svg>

                            <span>Pages</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="{{ $route == 'page-builder.index' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="page" data-parent="#accordionExample">
                        <li>
                            <a href="{{ route('page-builder.index') }}"> Page Builder</a>
                        </li>
                        <li>
                            <a href="{{ route('footer.index') }}"> Footer</a>
                        </li>
                        <li>
                            <a href="{{ route('topic.index') }}"> Help center</a>
                        </li>
                        <li>
                            <a href="{{ route('contact-topic.index') }}"> Contact topic</a>
                        </li>
                        <li>
                            <a href="{{ route('shipping-info.index') }}"> Shipping info</a>
                        </li>
                    </ul>
                </li>

                {{-- Attachment --}}
                <li class="menu">
                    <a href="{{ route('attachment.index') }}"
                        aria-expanded="{{ $route == 'attachment.index' ? 'true' : 'false' }}"
                        class="{{ $route == 'attachment.index' ? 'dropdown-toggle' : 'dropdown-toggle collapsed' }}">
                        <div class="">
                            <svg width="15px" height="15px" viewBox="0 0 15 15" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-layout"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0.5 0V4.5C0.5 5.60457 1.39543 6.5 2.5 6.5C3.60457 6.5 4.5 5.60457 4.5 4.5V1.5C4.5 0.947715 4.05228 0.5 3.5 0.5C2.94772 0.5 2.5 0.947715 2.5 1.5V5M6 0.5H12.5C13.0523 0.5 13.5 0.947715 13.5 1.5V13.5C13.5 14.0523 13.0523 14.5 12.5 14.5H2.5C1.94772 14.5 1.5 14.0523 1.5 13.5V8M11 4.5H7M11 7.5H7M11 10.5H4"
                                    stroke="black" />
                            </svg>
                            <span>Attachment</span>
                        </div>
                    </a>
                </li>

                {{-- Review --}}
                <li class="menu">
                    <a href="{{ route('review.index') }}"
                        aria-expanded="{{ $route == 'review.index' ? 'true' : 'false' }}"
                        class="{{ $route == 'review.index' ? 'dropdown-toggle' : 'dropdown-toggle collapsed' }}">
                        <div class="">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                xmlns:xlink="http://www.w3.org/1999/xlink" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"
                                enable-background="new 0 0 512 512">
                                <g>
                                    <g>
                                        <path
                                            d="m199.9,307.1h-83.3c-56.3,0-101,44.8-102.1,100.1-3.1,16.7-7.3,45.9 3.1,56.3 28.1,27.1 91.7,37.5 140.6,37.5 50,0 112.5-10.4 140.6-37.5 10.4-10.4 6.3-39.6 3.1-56.3-0.9-55.3-46.7-100.1-102-100.1zm85.5,140.7c-19.8,19.8-68.8,32.3-126,32.3-57.3,0-107.3-12.5-126-31.3-2.1-4.2-12.1-117.3 83.3-122 21.7-1.1 84.4,0 84.4,0 93.5,0.1 86.4,117.9 84.3,121z" />
                                        <path
                                            d="m157.2,288.3c45.8,0 83.3-37.5 82.3-82.4 0-45.9-36.5-82.4-82.3-82.4-44.8,0-82.3,37.5-82.3,82.4 1.42109e-14,45.9 36.5,82.4 82.3,82.4zm0-143.9c34.4,0 62.5,28.1 62.5,61.5s-28.1,61.5-62.5,61.5c-34.4,0-62.5-28.1-62.5-61.5s28.2-61.5 62.5-61.5z" />
                                        <path
                                            d="M253.1,11v151.2h32.3v74l74-74H501V11H253.1z M481.2,142.4H352l-45.8,45.9v-45.9h-32.3V31.9h207.3V142.4z" />
                                        <path
                                            d="m375,61c1-2.1 3.1-3.1 5.2-3.1 2.1,0 4.2,1 4.2,1 1,2.1 2.1,4.2 2.1,6.3 0,3.1-1,5.2-2.1,6.3l-8.3,9.4c-2.1,3.1-4.2,5.2-5.2,7.3-1,2.1-1,5.2-1,8.3v9.4h18.8v-8.3c0-2.1 0-4.2 1-5.2 1-1 2.1-2.1 4.2-4.2 2.1-2.1 4.2-4.2 6.3-6.3 0-1 1-2.1 2.1-3.1 1-1 2.1-2.1 2.1-3.1 1,0 1-1 2.1-3.1 1-1 1-4.2 1-7.3 0-7.3-2.1-13.6-7.3-17.7-5.2-4.2-11.5-6.3-19.8-6.3-7.3,0-14.6,2.1-19.8,7.3-5.2,4.2-8.3,11.5-9.4,19.8h20.8c0.9-3.2 1.9-5.3 3-7.4z" />
                                        <path
                                            d="m380.2,110c-3.1,0-6.3,1-8.3,3.1-2.1,2.1-3.1,5.2-3.1,8.3 0,3.1 1,6.3 3.1,8.3 2.1,2.1 4.2,3.1 8.3,3.1 3.1,0 6.3-1 8.3-3.1 2.1-2.1 3.1-5.2 3.1-8.3 0-3.1-1-6.3-3.1-8.3-2.1-2-5.2-3.1-8.3-3.1z" />
                                    </g>
                                </g>
                            </svg>

                            <span>Reviews</span>
                        </div>
                    </a>
                </li>

                {{-- Setting --}}
                <li class="menu">
                    <a href="#setting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20.8067 7.62361L20.1842 6.54352C19.6577 5.6296 18.4907 5.31432 17.5755 5.83872V5.83872C17.1399 6.09534 16.6201 6.16815 16.1307 6.04109C15.6413 5.91402 15.2226 5.59752 14.9668 5.16137C14.8023 4.88415 14.7139 4.56839 14.7105 4.24604V4.24604C14.7254 3.72922 14.5304 3.2284 14.17 2.85767C13.8096 2.48694 13.3145 2.27786 12.7975 2.27808H11.5435C11.037 2.27807 10.5513 2.47991 10.194 2.83895C9.83669 3.19798 9.63717 3.68459 9.63961 4.19112V4.19112C9.6246 5.23693 8.77248 6.07681 7.72657 6.0767C7.40421 6.07336 7.08846 5.98494 6.81123 5.82041V5.82041C5.89606 5.29601 4.72911 5.61129 4.20254 6.52522L3.53435 7.62361C3.00841 8.53639 3.3194 9.70261 4.23 10.2323V10.2323C4.8219 10.574 5.18653 11.2056 5.18653 11.8891C5.18653 12.5725 4.8219 13.2041 4.23 13.5458V13.5458C3.32056 14.0719 3.00923 15.2353 3.53435 16.1454V16.1454L4.16593 17.2346C4.41265 17.6798 4.8266 18.0083 5.31619 18.1474C5.80578 18.2866 6.33064 18.2249 6.77462 17.976V17.976C7.21108 17.7213 7.73119 17.6516 8.21934 17.7822C8.70749 17.9128 9.12324 18.233 9.37416 18.6717C9.5387 18.9489 9.62711 19.2646 9.63046 19.587V19.587C9.63046 20.6435 10.487 21.5 11.5435 21.5H12.7975C13.8505 21.5001 14.7055 20.6491 14.7105 19.5962V19.5962C14.7081 19.088 14.9089 18.6 15.2682 18.2407C15.6275 17.8814 16.1155 17.6807 16.6236 17.6831C16.9452 17.6917 17.2596 17.7798 17.5389 17.9394V17.9394C18.4517 18.4653 19.6179 18.1544 20.1476 17.2438V17.2438L20.8067 16.1454C21.0618 15.7075 21.1318 15.186 21.0012 14.6964C20.8706 14.2067 20.5502 13.7894 20.111 13.5367V13.5367C19.6718 13.284 19.3514 12.8666 19.2208 12.3769C19.0902 11.8873 19.1603 11.3658 19.4154 10.928C19.5812 10.6383 19.8214 10.3982 20.111 10.2323V10.2323C21.0161 9.70289 21.3264 8.54349 20.8067 7.63277V7.63277V7.62361Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <circle opacity="0.4" cx="12.175" cy="11.8891" r="2.63616"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                            <span>Settings</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="{{ $route == 'setting.index' ? 'submenu list-unstyled collapse show' : 'collapse submenu list-unstyled' }}"
                        id="setting" data-parent="#accordionExample">
                        <li>
                            <a href="{{ route('setting.index') }}"> Basic</a>
                        </li>
                        <li>
                            <a href="{{ route('shipping.index') }}"> Shipping</a>
                        </li>
                        <li>
                            <a href="{{ route('payment-provider.index') }}"> Payment method</a>
                        </li>
                        <li>
                            <a href="{{ route('status.index') }}"> Order status</a>
                        </li>
                        <li>
                            <a href="{{ route('font.index') }}"> Fonts</a>
                        </li>
                    </ul>
                </li>

                {{-- View store --}}
                <li class="menu">
                    <a href="{{ env('CLIENT_APP_URL') }}" target="__blank__" class='dropdown-toggle collapsed'>
                        <div class="">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                width="24" height="24" stroke-linecap="round" stroke-linejoin="round"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 455 455"
                                style="enable-background:new 0 0 455 455;" xml:space="preserve">
                                <g>
                                    <polygon points="413.698,90.221 41.302,90.221 18.974,137.595 436.027,137.595 	" />
                                    <path
                                        d="M321.723,167.595c6.728,27.226,31.323,47.474,60.563,47.474c29.24,0,53.834-20.249,60.562-47.474H321.723z" />
                                    <path
                                        d="M227.5,215.069c29.24,0,53.834-20.249,60.562-47.474H166.938C173.665,194.82,198.26,215.069,227.5,215.069z" />
                                    <path
                                        d="M133.277,167.595H12.151c6.728,27.226,31.323,47.474,60.563,47.474S126.549,194.82,133.277,167.595z" />
                                    <path
                                        d="M304.893,203.057c-16.509,25.275-45.035,42.012-77.393,42.012s-60.885-16.737-77.393-42.012
                                        c-16.508,25.275-45.036,42.012-77.393,42.012c-9.862,0-19.363-1.563-28.281-4.44V455h103.398V283.793h159.338V455h103.398V240.628
                                        c-8.918,2.877-18.419,4.44-28.281,4.44C349.929,245.069,321.402,228.332,304.893,203.057z" />
                                    <rect x="89.916" width="275.169" height="60.221" />
                                    <rect x="177.831" y="313.793" width="99.338" height="141.207" />
                                </g>
                            </svg>

                            <span>View store</span>
                        </div>

                        <div>
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                stroke="currentColor" stroke-width="2" xmlns:xlink="http://www.w3.org/1999/xlink"
                                enable-background="new 0 0 512 512">
                                <g>
                                    <g>
                                        <path
                                            d="m251.6,185.7c-36.9,0-67,31.5-67,70.3 0,38.7 30,70.3 67,70.3 36.9,0 67-31.5 67-70.3 0-38.7-30.1-70.3-67-70.3z" />
                                        <path
                                            d="m251.6,367.1c-59.4,0-107.8-49.8-107.8-111.1 0-61.3 48.4-111.1 107.8-111.1s107.8,49.8 107.8,111.1c0,61.3-48.4,111.1-107.8,111.1zm246.3-121.9c-63.8-102.4-149.8-158.8-241.9-158.8-92.1,0-178.1,56.4-241.9,158.8-4.1,6.6-4.1,15 0,21.6 63.8,102.4 149.8,158.8 241.9,158.8 92.1,0 178-56.4 241.9-158.8 4.1-6.6 4.1-15 0-21.6z" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </a>
                </li>

            </ul>

        </nav>
    @endif
</div>
