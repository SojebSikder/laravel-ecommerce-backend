@extends('backend.master')

@section('title')
    Draft order · {{ SettingHelper::get('name') }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/select2/css/select2.min.css">
    <style>
        /* hide elements when printing */
        @media print {
            button {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Main section -->
    <main class="mt-5 pt-3">
        {{-- display error message --}}
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="
           margin: 10px 5px 10px 5px;">
                <strong>{{ Session::get('success') }}</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (Session::has('warning'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="
           margin: 10px 5px 10px 5px;">
                <strong>{{ Session::get('warning') }}</strong>.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- //display error message --}}

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 fw-bold fs-3">
                    Drafts
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Manage draft</h5>
                                <a href="{{ route('order-draft.index') }}"
                                    class="btn btn-sm btn-primary float-end mr-4 mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-list">
                                        <line x1="8" y1="6" x2="21" y2="6"></line>
                                        <line x1="8" y1="12" x2="21" y2="12"></line>
                                        <line x1="8" y1="18" x2="21" y2="18"></line>
                                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                    </svg>
                                    Order draft list
                                </a>

                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col">
                                        <div class="col">
                                            {{-- status bar --}}
                                            <div>
                                                <div class="d-flex"></div>
                                                <div>
                                                    {{ date('d M Y', strtotime($order_draft->created_at)) }} at
                                                    {{ date('h:i a', strtotime($order_draft->created_at)) }}
                                                </div>
                                            </div>


                                            <div class="row">
                                                {{-- first section --}}
                                                <div class="col-sm-8">
                                                    {{-- order status --}}
                                                    <div class="statbox widget box box-shadow m-2">
                                                        <div class="col">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="d-flex">
                                                                        <div class="mb-2">
                                                                            <a href="{{ route('order-draft.product.create', $order_draft->id) }}"
                                                                                class="btn btn-sm btn-primary">Add
                                                                                products</a>
                                                                        </div>
                                                                    </div>

                                                                    <table
                                                                        class="table-bordered table-hover table-striped table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>Product</th>
                                                                                <th>Price</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($order_draft->order_draft_items as $item)
                                                                                <tr>
                                                                                    <td>
                                                                                        @if (count($item->product->images) > 0)
                                                                                            <a href="{{ $item->product->images[0]->image_url }}"
                                                                                                target="_blank"
                                                                                                rel="noopener noreferrer">
                                                                                                <img style="width:50px; min-width: 50px;"
                                                                                                    class="img-thumbnail"
                                                                                                    src="{{ $item->product->images[0]->image_url }}"
                                                                                                    alt="{{ $item->product->images[0]->image_url }}"
                                                                                                    data-bs-toggle="tooltip"
                                                                                                    data-placement="top"
                                                                                                    title="Click to view large mode">
                                                                                            </a>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        <a
                                                                                            href="{{ route('product.edit', $item->product->id) }}">
                                                                                            {{ $item->product->name }}
                                                                                        </a>

                                                                                        {{-- product attribute --}}
                                                                                        @if (isset($item->attribute) && count($item->attribute) > 0)
                                                                                            <ul>
                                                                                                @foreach ($item->attribute as $attribute)
                                                                                                    <li>{{ $attribute->name }}:
                                                                                                        {{ $attribute->value }}
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ $order_draft->currency }}{{ $item->total_price }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                            <hr>

                                                            <div class="row"></div>

                                                        </div>
                                                    </div>

                                                    {{-- payment status --}}
                                                    <div class="statbox widget box box-shadow m-2">
                                                        <div class="col">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <table
                                                                        class="table-bordered table-hover table-striped table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Subtotal</th>
                                                                                <th>Shipping</th>
                                                                                <th>Discount(-)</th>
                                                                                <th>Total</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>{{ $order_draft->currency }}{{ $order_draft->sub_total }}
                                                                                </td>
                                                                                <td>{{ $order_draft->currency }}{{ $order_draft->shipping_charge }}
                                                                                </td>
                                                                                <td>
                                                                                    @if (isset($order_draft->coupons) && count($order_draft->coupons) > 0)
                                                                                        @foreach ($order_draft->coupons as $coupon)
                                                                                            <div>
                                                                                                {{ $coupon->amount }}
                                                                                                @if ($coupon->amount_type == 'percentage')
                                                                                                    %
                                                                                                    ({{ $order_draft->currency }}{{ ($order_draft->sub_total * $coupon->amount) / 100 }})
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $order_draft->currency }}{{ $order_draft->order_total }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                {{--  second section --}}
                                                <div class="col-sm-4">
                                                    {{-- customer information --}}
                                                    <div class="statbox widget box box-shadow m-2">
                                                        <div class="widget-header">
                                                            <div class="row mb-3">
                                                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                                    <h5>Customer</h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="offset-1 col-xl-10 col-md-10 col-sm-10 col-10">

                                                            <div class="row">
                                                                <div class="col">
                                                                    <div>
                                                                        <div>
                                                                            {{-- @if ($order_draft->user)
                                                                                <a href="{{ route('customer.show', $order_draft->user->id) }}">
                                                                                    {{ $order_draft->user->fname }} {{ $order_draft->user->lname }}
                                                                                </a>
                                                                            @else
                                                                                Information not available
                                                                            @endif --}}

                                                                            @if ($order_draft->user)
                                                                                <a
                                                                                    href="{{ route('customer.show', $order_draft->user->id) }}">
                                                                                    {{ $order_draft->user->fname }}
                                                                                    {{ $order_draft->user->lname }}
                                                                                </a>
                                                                            @elseif($order_draft->order_shipping_address)
                                                                                {{ $order_draft->order_shipping_address->name }}
                                                                            @else
                                                                                Information not available
                                                                            @endif

                                                                        </div>
                                                                        <div>
                                                                            @if ($order_draft->user)
                                                                                <a
                                                                                    href="{{ route('order.index') }}?customer_id={{ $order_draft->user->id }}">
                                                                                    {{ count($order_draft->user->orders) }}
                                                                                    orders
                                                                                </a>
                                                                            @else
                                                                                Information not available
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div>
                                                                        <label>Contact information</label>
                                                                        <div>
                                                                            <a
                                                                                href="{{ route('sendmail.create') }}?to_email={{ $order_draft->email }}">{{ $order_draft->email }}</a>
                                                                        </div>
                                                                        <div>
                                                                            <a
                                                                                href="tel:{{ $order_draft->dial_code }}{{ $order_draft->phone }}">{{ $order_draft->dial_code }}{{ $order_draft->phone }}</a>
                                                                        </div>

                                                                    </div>
                                                                    <hr>
                                                                    <div>
                                                                        <label>Shipping address</label>
                                                                        <a href="javascript:void(0);"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#shippingEdit"
                                                                            class="hide-print bs-tooltip"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top" title=""
                                                                            data-bs-title="Edit">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-edit-2 br-6 mb-1 p-1">
                                                                                <path
                                                                                    d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                                </path>
                                                                            </svg>
                                                                        </a>
                                                                        @if ($order_draft->order_shipping_address)
                                                                            <div>
                                                                                Phone:
                                                                                {{ $order_draft->order_shipping_address->phone }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order_draft->order_shipping_address->name }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order_draft->order_shipping_address->street_address }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order_draft->order_shipping_address->building }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order_draft->order_shipping_address->city }}
                                                                                {{ $order_draft->order_shipping_address->zip }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order_draft->order_shipping_address->state }}
                                                                            </div>
                                                                            @if ($order_draft->order_shipping_address->country)
                                                                                <div>
                                                                                    {{ $order_draft->order_shipping_address->country->name }}
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <hr>
                                                                    <div>
                                                                        <label>Billing address</label>
                                                                        <a href="javascript:void(0);"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#billingEdit"
                                                                            class="hide-print bs-tooltip"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top" title=""
                                                                            data-bs-title="Edit">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-edit-2 br-6 mb-1 p-1">
                                                                                <path
                                                                                    d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                                </path>
                                                                            </svg>
                                                                        </a>
                                                                        <div>
                                                                            @if ($order_draft->order_shipping_address_id != $order_draft->order_billing_address_id)
                                                                                @if (isset($order_draft->order_billing_address))
                                                                                    <div>
                                                                                        {{ $order_draft->order_billing_address->fname }}
                                                                                        {{ $order_draft->order_billing_address->lname }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order_draft->order_billing_address->street_address }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order_draft->order_billing_address->building }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order_draft->order_billing_address->city }}
                                                                                        {{ $order_draft->order_billing_address->zip }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order_draft->order_billing_address->state }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order_draft->order_billing_address->country->name }}
                                                                                    </div>
                                                                                @endif
                                                                            @else
                                                                                Same as shipping address
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <hr>
                                            <div class="col">
                                                <form action="{{ route('order-draft.destroy', $order_draft->id) }}"
                                                    id="order_destroy" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group mb-3">
                                                        <button
                                                            onclick="event.preventDefault();
                                                            if(confirm('Are you really want to delete, this cannot be undone ?')){
                                                                document.getElementById('order_destroy').submit()
                                                            }"
                                                            class="btn btn-outline-danger mt-3">Delete</button>
                                                    </div>
                                                </form>
                                            </div>



                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
    <!-- End Main section -->
@endsection

@section('script')
    <script src="{{ asset('assets') }}/select2/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".tags").select2({
                placeholder: "Select country",
                allowClear: true,
                tags: true
            });
        });
    </script>
@endsection
