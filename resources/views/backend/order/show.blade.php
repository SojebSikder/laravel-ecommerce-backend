@extends('backend.master')

@section('title')
    Orders · #{{ $order->invoice_number }} · {{ SettingHelper::get('name') }}
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
                    Orders
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="float-start">Manage order</h5>
                                <a href="{{ route('order.index') }}" class="btn btn-sm btn-primary float-end mr-4 mt-3">
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
                                    Order list
                                </a>

                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col">
                                        <div class="col">
                                            {{-- status bar --}}
                                            <div>
                                                <div class="d-flex">
                                                    <div class="m-1">
                                                        <h5>#{{ $order->invoice_number }}</h5>
                                                    </div>
                                                    <div class="m-1">
                                                        <span
                                                            class="badge @if ($order->payment_status != 'paid') bg-warning @else bg-secondary @endif">{{ $order->payment_status }}</span>
                                                    </div>
                                                    <div class="m-1">
                                                        <span
                                                            class="badge bg-secondary">{{ $order->payment_provider }}</span>
                                                    </div>
                                                    <div class="m-1">
                                                        <span
                                                            class="badge @if ($order->fulfillment_status != 'fulfilled') bg-warning @else bg-secondary @endif">{{ $order->fulfillment_status }}</span>
                                                    </div>
                                                    <div class="m-1">
                                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                                    </div>
                                                    {{-- printing controls --}}
                                                    <div class="m-1">
                                                        <button onclick="window.print()"
                                                            class="btn btn-outline-primary">Print order
                                                            page</button>
                                                    </div>
                                                    {{-- <div class="m-1">
                                                        <a href="{{ route('order.invoice.generate', $order->id) }}"
                                                            class="hide-print btn btn-outline-primary">Print packing
                                                            slips</a>
                                                    </div> --}}
                                                    <div class="m-1">
                                                        <a target="__blank"
                                                            href="{{ route('order.invoice.index', $order->id) }}"
                                                            class="hide-print btn btn-outline-primary">View packing
                                                            slips</a>
                                                    </div>
                                                </div>
                                                <div>
                                                    {{ date('d M Y', strtotime($order->created_at)) }} at
                                                    {{ date('h:i a', strtotime($order->created_at)) }}
                                                </div>
                                            </div>



                                            {{-- fullfillment modal --}}
                                            <div class="modal fade" id="fulfilmentEdit" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Fulfill
                                                                item</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                {{-- fulfillment status --}}
                                                                <div class="col">
                                                                    @if ($order->fulfillment_status != 'fulfilled')
                                                                        <form
                                                                            action="{{ route('fulfillment_status', $order->id) }}"
                                                                            id="fulfill" method="post">
                                                                            @csrf
                                                                            <input type="hidden" value="fulfilled"
                                                                                name="fulfillment_status">
                                                                            {{-- courier provider --}}
                                                                            <div class="form-group">
                                                                                <label for="courier_provider">Courier
                                                                                    Provider</label>
                                                                                <input class="form-control"
                                                                                    name="courier_provider"
                                                                                    id="courier_provider" type="text"
                                                                                    value="{{ $order->courier_provider }}"
                                                                                    placeholder="Courier Provider">
                                                                            </div>
                                                                            {{-- tracking number --}}
                                                                            <div class="form-group">
                                                                                <label for="tracking_number">Tracking
                                                                                    number</label>
                                                                                <input class="form-control"
                                                                                    name="tracking_number"
                                                                                    id="tracking_number" type="text"
                                                                                    value="{{ $order->tracking_number }}"
                                                                                    placeholder="Tracking number">
                                                                            </div>

                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" value="1" checked
                                                                                    name="inline_shipping"
                                                                                    id="inline_shipping">
                                                                                <label class="form-check-label"
                                                                                    for="inline_shipping">
                                                                                    Send website track order link
                                                                                </label>
                                                                            </div>

                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" value="1" checked
                                                                                    name="fulfill_mail" id="fulfill_mail">
                                                                                <label class="form-check-label"
                                                                                    for="fulfill_mail">
                                                                                    Send shipment details to your customer
                                                                                    now
                                                                                </label>
                                                                            </div>

                                                                            <div class="form-group mb-3">
                                                                                <button
                                                                                    onclick="event.preventDefault();document.getElementById('fulfill').submit()"
                                                                                    class="btn btn-primary mt-3">Fulfill
                                                                                    item</button>
                                                                            </div>
                                                                        </form>
                                                                    @else
                                                                        <form
                                                                            action="{{ route('fulfillment_status', $order->id) }}"
                                                                            id="unfulfill" method="post">
                                                                            @csrf

                                                                            <input type="hidden" value="unfulfilled"
                                                                                name="fulfillment_status">
                                                                            <div class="form-group mb-3">
                                                                                <button
                                                                                    onclick="event.preventDefault();document.getElementById('unfulfill').submit()"
                                                                                    class="btn btn-warning mt-3">Unfulfill
                                                                                    item</button>
                                                                            </div>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end fullfillment modal --}}

                                            {{-- tracking modal --}}
                                            <div class="modal fade" id="trackingEdit" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Edit
                                                                tracking</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </button>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                {{-- tracking status --}}
                                                                <div class="col">
                                                                    <form
                                                                        action="{{ route('fulfillment_status', $order->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        {{-- courier provider --}}
                                                                        <div class="form-group">
                                                                            <label for="courier_provider">Courier
                                                                                Provider</label>
                                                                            <input class="form-control"
                                                                                name="courier_provider"
                                                                                id="courier_provider" type="text"
                                                                                value="{{ $order->courier_provider }}"
                                                                                placeholder="Courier Provider">
                                                                        </div>
                                                                        {{-- tracking number --}}
                                                                        <div class="form-group">
                                                                            <label for="tracking_number">Tracking
                                                                                number</label>
                                                                            <input class="form-control"
                                                                                name="tracking_number"
                                                                                id="tracking_number" type="text"
                                                                                value="{{ $order->tracking_number }}"
                                                                                placeholder="Tracking number">
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="1" checked
                                                                                name="inline_shipping"
                                                                                id="inline_shipping">
                                                                            <label class="form-check-label"
                                                                                for="inline_shipping">
                                                                                Send website track order link
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="1" checked
                                                                                name="fulfill_mail" id="fulfill_mail">
                                                                            <label class="form-check-label"
                                                                                for="fulfill_mail">
                                                                                Send shipment details to your customer now
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-group mb-3">
                                                                            <button type="submit"
                                                                                class="btn btn-primary mt-3">Save</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end tracking modal --}}


                                            {{-- user shipping address modal edit --}}
                                            <div class="modal fade" id="shippingEdit" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Edit
                                                                shipping address</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('order.user.shipping.details', $order->order_shipping_address->id) }}"
                                                                method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="container">
                                                                    <div class="">

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="shipping_fname">First
                                                                                    Name</label>
                                                                                <input type="text"
                                                                                    placeholder="First Name"
                                                                                    value="{{ $order->order_shipping_address->fname }}"
                                                                                    class="form-control"
                                                                                    id="shipping_fname"
                                                                                    name="shipping_fname">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="shipping_lname">Last
                                                                                    Name</label>
                                                                                <input type="text"
                                                                                    placeholder="Last Name"
                                                                                    value="{{ $order->order_shipping_address->lname }}"
                                                                                    class="form-control"
                                                                                    id="shipping_lname"
                                                                                    name="shipping_lname">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="shipping_country">Country</label>
                                                                                <select class="form-control"
                                                                                    name="shipping_country"
                                                                                    id="shipping_country">
                                                                                    <option value="">Select country
                                                                                    </option>
                                                                                    @foreach ($countries as $country)
                                                                                        <option
                                                                                            value="{{ $country->id }}"
                                                                                            @if ($order->order_shipping_address->country && $order->order_shipping_address->country->id == $country->id) selected @endif>
                                                                                            {{ $country->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="shipping_address1">Address
                                                                                    1</label>
                                                                                <input type="text"
                                                                                    placeholder="Street address"
                                                                                    class="form-control"
                                                                                    id="shipping_address1"
                                                                                    name="shipping_address1"
                                                                                    value="{{ $order->order_shipping_address->address1 }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="shipping_address2">Address
                                                                                    2</label>
                                                                                <input type="text"
                                                                                    placeholder="building"
                                                                                    class="form-control"
                                                                                    id="shipping_address2"
                                                                                    name="shipping_address2"
                                                                                    value="{{ $order->order_shipping_address->address2 }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="shipping_city">City</label>
                                                                                <input type="text" placeholder="city"
                                                                                    class="form-control"
                                                                                    id="shipping_city"
                                                                                    name="shipping_city"
                                                                                    value="{{ $order->order_shipping_address->city }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="shipping_state">State</label>
                                                                                <input type="text" placeholder="state"
                                                                                    class="form-control"
                                                                                    id="shipping_state"
                                                                                    name="shipping_state"
                                                                                    value="{{ $order->order_shipping_address->state }}">
                                                                            </div>
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label for="shipping_zip">Zip</label>
                                                                                <input type="text" placeholder="zip"
                                                                                    class="form-control" id="shipping_zip"
                                                                                    name="shipping_zip"
                                                                                    value="{{ $order->order_shipping_address->zip }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="shipping_phone_number">Phone
                                                                                    Number</label>
                                                                                <input type="text" placeholder="Phone"
                                                                                    class="form-control"
                                                                                    id="shipping_phone_number"
                                                                                    name="shipping_phone_number"
                                                                                    value="{{ $order->order_shipping_address->phone_numebr }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <button type="submit"
                                                                    class="btn btn-sm btn-primary float-right">Save</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end user shipping address modal edit --}}


                                            {{-- user billing address modal edit --}}
                                            <div class="modal fade" id="billingEdit" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Edit
                                                                billing address</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if (isset($order->order_billing_address))
                                                                <form
                                                                    action="{{ route('order.user.shipping.details', $order->order_billing_address->id) }}"
                                                                    method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="container">
                                                                        <div class="">

                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="billing_fname">First
                                                                                        Name</label>
                                                                                    <input type="text"
                                                                                        placeholder="First Name"
                                                                                        value="{{ $order->order_billing_address->fname }}"
                                                                                        class="form-control"
                                                                                        id="billing_fname"
                                                                                        name="shipping_fname">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="billing_lname">Last
                                                                                        Name</label>
                                                                                    <input type="text"
                                                                                        placeholder="Last Name"
                                                                                        value="{{ $order->order_billing_address->lname }}"
                                                                                        class="form-control"
                                                                                        id="billing_lname"
                                                                                        name="shipping_lname">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="billing_country">Country</label>
                                                                                    <select class="form-control"
                                                                                        name="shipping_country"
                                                                                        id="billing_country">
                                                                                        <option value="">Select
                                                                                            country
                                                                                        </option>
                                                                                        @foreach ($countries as $country)
                                                                                            <option
                                                                                                value="{{ $country->id }}"
                                                                                                @if ($order->order_billing_address->country && $order->order_billing_address->country->id == $country->id) selected @endif>
                                                                                                {{ $country->name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="billing_address1">Address
                                                                                        1</label>
                                                                                    <input type="text"
                                                                                        placeholder="Street address"
                                                                                        class="form-control"
                                                                                        id="billing_address1"
                                                                                        name="shipping_address1"
                                                                                        value="{{ $order->order_billing_address->address1 }}">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="billing_address2">Address
                                                                                        2</label>
                                                                                    <input type="text"
                                                                                        placeholder="address2"
                                                                                        class="form-control"
                                                                                        id="billing_address2"
                                                                                        name="shipping_address2"
                                                                                        value="{{ $order->order_billing_address->address2 }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="billing_city">City</label>
                                                                                    <input type="text"
                                                                                        placeholder="city"
                                                                                        class="form-control"
                                                                                        id="billing_city"
                                                                                        name="shipping_city"
                                                                                        value="{{ $order->order_billing_address->city }}">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="billing_state">State</label>
                                                                                    <input type="text"
                                                                                        placeholder="state"
                                                                                        class="form-control"
                                                                                        id="billing_state"
                                                                                        name="shipping_state"
                                                                                        value="{{ $order->order_billing_address->state }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="billing_zip">Zip</label>
                                                                                    <input type="text"
                                                                                        placeholder="zip"
                                                                                        class="form-control"
                                                                                        id="billing_zip"
                                                                                        name="shipping_zip"
                                                                                        value="{{ $order->order_billing_address->zip }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-primary float-right">Save</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end user billing address modal edit --}}



                                            <div class="row">
                                                {{-- first section --}}
                                                <div class="col-sm-8">
                                                    {{-- order status --}}
                                                    <div class="statbox widget box box-shadow m-2">
                                                        <div class="widget-header">

                                                            <div class="row mb-3">
                                                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                                    <div class="m-1">
                                                                        <span
                                                                            class="badge @if ($order->fulfillment_status != 'fulfilled') bg-warning @else bg-secondary @endif">
                                                                            {{ $order->fulfillment_status }}
                                                                        </span>
                                                                    </div>
                                                                    @if ($order->tracking_number != null)
                                                                        <div class="m-1">
                                                                            Order tracking: <a target="__blank"
                                                                                href="https://callcourier.com.pk/tracking/?tc={{ $order->tracking_number }}">{{ $order->tracking_number }}</a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <table
                                                                        class="table-bordered table-hover table-striped table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>Product</th>
                                                                                <th>Quantity</th>
                                                                                <th>Price</th>
                                                                                <th>Discount(-) %</th>
                                                                                <th>Total price</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($order->order_items as $item)
                                                                                <tr>
                                                                                    <td>
                                                                                        @if ($item->variant_id)
                                                                                            @if (count($item->variant->images) > 0)
                                                                                                <a href="{{ $item->variant->images[0]->image_url }}"
                                                                                                    target="_blank"
                                                                                                    rel="noopener noreferrer">
                                                                                                    <img style="width:50px; min-width: 50px;"
                                                                                                        class="img-thumbnail"
                                                                                                        src="{{ $item->variant->images[0]->image_url }}"
                                                                                                        alt="{{ $item->variant->images[0]->image_url }}"
                                                                                                        data-bs-toggle="tooltip"
                                                                                                        data-placement="top"
                                                                                                        title="Click to view large mode">
                                                                                                </a>
                                                                                            @endif
                                                                                        @else
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
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($item->variant_id)
                                                                                            <a target="_blank"
                                                                                                href="{{ route('variant.edit', $item->variant_id) }}">
                                                                                                {{ $item->product->name }}

                                                                                                <div>
                                                                                                    <span
                                                                                                        class="badge bg-secondary text-start">
                                                                                                        @foreach ($item->variant->variant_attributes as $variant_attribute)
                                                                                                            {{ $variant_attribute->attribute->name }}:
                                                                                                            {{ $variant_attribute->attribute_value->name }}
                                                                                                            <br>
                                                                                                        @endforeach
                                                                                                    </span>
                                                                                                </div>

                                                                                            </a>
                                                                                        @else
                                                                                            <a target="_blank"
                                                                                                href="{{ route('product.edit', $item->product_id) }}">
                                                                                                {{ $item->product->name }}
                                                                                            </a>
                                                                                        @endif

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

                                                                                    <td>{{ $item->quantity }}</td>
                                                                                    <td>{{ $order->currency }}{{ $item->price }}
                                                                                    <td>{{ $item->discount }} </td>
                                                                                    <td>{{ $order->currency }}{{ $item->total_price }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>

                                                                    <hr>
                                                                    <div>
                                                                        <label>Shipping zone:</label>
                                                                        <br>
                                                                        @if ($order->shipping_zone)
                                                                            <a
                                                                                href="{{ route('shipping.edit', $order->shipping_zone->shipping_id) }}">
                                                                                {{ $order->shipping_zone->name }}
                                                                            </a>
                                                                        @else
                                                                            <a>
                                                                                {{ $order->shipping_zone_name }}
                                                                            </a>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <hr>

                                                            <div class="row">
                                                                {{-- fulfillment status --}}
                                                                <div class="col">
                                                                    @if ($order->fulfillment_status != 'fulfilled')
                                                                        <div class="form-group mb-3">
                                                                            <a href="javascript:void(0);"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#fulfilmentEdit"
                                                                                class="btn btn-sm btn-primary mt-3">Fulfill
                                                                                item</a>
                                                                        </div>
                                                                    @else
                                                                        <div class="form-group mb-3">
                                                                            <a data-bs-toggle="modal"
                                                                                data-bs-target="#fulfilmentEdit"
                                                                                class="btn btn-sm btn-warning mt-3">Unfulfill
                                                                                item</a>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                {{-- tracking number --}}
                                                                <div class="col">
                                                                    <div class="form-group">
                                                                        <a href="javascript:void(0);"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#trackingEdit"
                                                                            class="btn btn-sm btn-primary mt-3">Tracking
                                                                            number</a>
                                                                    </div>
                                                                </div>

                                                                <div class="col">
                                                                    <form action="{{ route('order_status', $order->id) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label for="order_status">Order status</label>
                                                                            <select class="form-control" id="order_status"
                                                                                name="status">
                                                                                <option value="">Select status
                                                                                </option>
                                                                                @foreach ($statuses as $status)
                                                                                    <option value="{{ $status->id }}"
                                                                                        @if ($order->status == $status->name) selected @endif>
                                                                                        {{ $status->label }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group mt-2">
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-primary">Update
                                                                                status</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>



                                                        </div>
                                                    </div>

                                                    {{-- payment status --}}
                                                    <div class="statbox widget box box-shadow m-2">
                                                        <div class="widget-header">
                                                            <div class="row mb-3">
                                                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                                    <div class="m-1">
                                                                        <h5>Payment status:
                                                                            <span
                                                                                class="badge @if ($order->payment_status != 'paid') bg-warning @else bg-secondary @endif">{{ $order->payment_status }}</span>
                                                                            <span
                                                                                class="badge bg-secondary">{{ $order->payment_provider }}</span>
                                                                        </h5>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <form action="{{ route('payment_status', $order->id) }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <?php
                                                                    $payment_status = [
                                                                        'unpaid' => 'Unpaid',
                                                                        'paid' => 'Paid',
                                                                        'failed' => 'Failed',
                                                                        'refunded' => 'Refunded',
                                                                    ];
                                                                    ?>
                                                                    <label for="payment_status">Payment status</label>
                                                                    <select class="form-control" id="payment_status"
                                                                        name="payment_status">
                                                                        <option value="">Select status</option>
                                                                        @foreach ($payment_status as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @if ($order->payment_status == $key) selected @endif>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group mt-2">
                                                                    <button type="submit" class="btn btn-primary">Update
                                                                        status</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <br>

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
                                                                                <td>{{ $order->currency }}{{ $order->sub_total }}
                                                                                </td>
                                                                                <td>{{ $order->currency }}{{ $order->shipping_charge }}
                                                                                </td>
                                                                                <td>
                                                                                    @if (count($order->coupons) > 0)
                                                                                        @foreach ($order->coupons as $coupon)
                                                                                            <div>
                                                                                                {{ $coupon->amount }}
                                                                                                @if ($coupon->amount_type == 'percentage')
                                                                                                    %
                                                                                                    ({{ $order->currency }}{{ ($order->sub_total * $coupon->amount) / 100 }})
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $order->currency }}{{ $order->order_total }}
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
                                                                            {{-- @if ($order->user)
                                                                                <a href="{{ route('customer.show', $order->user->id) }}">
                                                                                    {{ $order->user->fname }} {{ $order->user->lname }}
                                                                                </a>
                                                                            @else
                                                                                Information not available
                                                                            @endif --}}

                                                                            @if ($order->user)
                                                                                <a
                                                                                    href="{{ route('customer.show', $order->user->id) }}">
                                                                                    {{ $order->user->fname }}
                                                                                    {{ $order->user->lname }}
                                                                                </a>
                                                                            @elseif($order->order_shipping_address)
                                                                                {{ $order->order_shipping_address->name }}
                                                                            @else
                                                                                Information not available
                                                                            @endif

                                                                        </div>
                                                                        <div>
                                                                            @if ($order->user)
                                                                                <a
                                                                                    href="{{ route('order.index') }}?customer_id={{ $order->user->id }}">
                                                                                    {{ count($order->user->orders) }}
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
                                                                                href="{{ route('sendmail.create') }}?to_email={{ $order->email }}">{{ $order->email }}</a>
                                                                        </div>
                                                                        <div>
                                                                            <a
                                                                                href="tel:{{ $order->dial_code }}{{ $order->phone }}">{{ $order->dial_code }}{{ $order->phone }}</a>
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
                                                                        @if ($order->order_shipping_address)
                                                                            <div>
                                                                                Phone:
                                                                                {{ $order->order_shipping_address->phone }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order->order_shipping_address->name }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order->order_shipping_address->street_address }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order->order_shipping_address->building }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order->order_shipping_address->city }}
                                                                                {{ $order->order_shipping_address->zip }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $order->order_shipping_address->state }}
                                                                            </div>
                                                                            @if ($order->order_shipping_address->country)
                                                                                <div>
                                                                                    {{ $order->order_shipping_address->country->name }}
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
                                                                            @if ($order->order_shipping_address_id != $order->order_billing_address_id)
                                                                                @if (isset($order->order_billing_address))
                                                                                    <div>
                                                                                        {{ $order->order_billing_address->fname }}
                                                                                        {{ $order->order_billing_address->lname }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order->order_billing_address->street_address }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order->order_billing_address->building }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order->order_billing_address->city }}
                                                                                        {{ $order->order_billing_address->zip }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order->order_billing_address->state }}
                                                                                    </div>
                                                                                    <div>
                                                                                        {{ $order->order_billing_address->country->name }}
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

                                            <div class="d-flex">
                                                {{-- order timeline --}}
                                                <div class="col hide-print">
                                                    <h5>Timeline</h5>
                                                    <div class="card-body">
                                                        <form
                                                            action="{{ route('order-timeline.store') }}?order_id={{ $order->id }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col">

                                                                    <div class="col">
                                                                        <div class="form-group mb-3">
                                                                            <label for="body">Comment</label>
                                                                            <textarea id="body" placeholder="Leave a comment" class="form-control @error('name') is-invalid @enderror"
                                                                                value="{{ old('body') }}" name="body"></textarea>

                                                                        </div>
                                                                        @error('body')
                                                                            <div class="alert alert-danger">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <div class="form-group mb-3">
                                                                                <button id="submit" type="submit"
                                                                                    class="btn btn-primary mt-3">Save</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>


                                                    @if ($order->order_timelines)
                                                        @foreach ($order->order_timelines as $order_timeline)
                                                            <div class="p-2">
                                                                @if ($order_timeline->type == 'comment')
                                                                    <div>
                                                                        <div class="card" style="width: 18rem;">
                                                                            {{-- <img src="..." class="card-img-top" alt="..."> --}}
                                                                            <div class="card-body">
                                                                                <h5 class="card-title">
                                                                                    <div>
                                                                                        {{ $order_timeline->user ? $order_timeline->user->fname : '' }}
                                                                                        {{ $order_timeline->user ? $order_timeline->user->lname : '' }}
                                                                                    </div>

                                                                                    <div class="fw-light">
                                                                                        {{ date('d M Y', strtotime($order_timeline->created_at)) }}
                                                                                        at
                                                                                        {{ date('h:i a', strtotime($order_timeline->created_at)) }}
                                                                                    </div>
                                                                                </h5>
                                                                                <p class="card-text">
                                                                                    {{ $order_timeline->body }}
                                                                                </p>
                                                                                <div class="col">
                                                                                    <form id="delete_order_timeline"
                                                                                        action="{{ route('order-timeline.destroy', $order_timeline->id) }}"
                                                                                        method="post">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <div class="form-group">
                                                                                            <button
                                                                                                onclick="event.preventDefault();
                                                                                            if(confirm('Are you really want to delete status?')){
                                                                                                document.getElementById('delete_order_timeline').submit()
                                                                                            }"
                                                                                                class="btn btn-sm btn-warning">Delete</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div>
                                                                        <div class="fw-light">
                                                                            {{ date('d M Y', strtotime($order_timeline->created_at)) }}
                                                                            at
                                                                            {{ date('h:i a', strtotime($order_timeline->created_at)) }}
                                                                        </div>
                                                                        <div>
                                                                            {{ $order_timeline->body }}
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>

                                                {{-- order status activity --}}
                                                <div class="col hide-print">
                                                    <h5>Order status activity</h5>
                                                    @if ($order->order_statuses)
                                                        @foreach ($order->order_statuses as $status)
                                                            @if ($status->status)
                                                                <div class="p-2">
                                                                    <div>
                                                                        {{ $status->status->label }} -
                                                                        {{ date('d M Y', strtotime($status->created_at)) }}
                                                                        at
                                                                        {{ date('h:i a', strtotime($status->created_at)) }}
                                                                    </div>

                                                                    <div class="col">
                                                                        <form id="delete_order_status"
                                                                            action="{{ route('order_status.destroy', $status->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <div class="form-group">
                                                                                <button
                                                                                    onclick="event.preventDefault();
                                                                            if(confirm('Are you really want to delete status?')){
                                                                                document.getElementById('delete_order_status').submit()
                                                                            }"
                                                                                    class="btn btn-sm btn-warning">Delete</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>



                                            <hr>
                                            <div class="col">
                                                <form action="{{ route('order.destroy', $order->id) }}"
                                                    id="order_destroy" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group mb-3">
                                                        <button
                                                            onclick="event.preventDefault();
                                                            if(confirm('Are you really want to delete order, this cannot be undone ?')){
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
