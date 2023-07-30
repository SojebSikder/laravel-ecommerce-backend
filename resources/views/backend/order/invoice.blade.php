<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice </title>
    {{-- <link href="{{ asset('admin_assets') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> --}}
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .row {
            display: -webkit-box;
            /* wkhtmltopdf uses this one */
            display: flex;
            -webkit-box-pack: center;
            /* wkhtmltopdf uses this one */
            justify-content: center;
        }

        .row>div {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            flex: 1;
        }

        .row>div:last-child {
            margin-right: 0;
        }

        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col {
            ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;

            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .col-lg-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }



        table {
            border-collapse: collapse;
        }

        table {
            display: table;
            border-collapse: separate;
            box-sizing: border-box;
            text-indent: initial;
            border-spacing: 2px;
            border-color: grey;
        }

        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        div {
            display: block;
        }

        .table-hover {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table-hover:hover {}

        .table {

            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .justify-content-center {
            -ms-flex-pack: center !important;
            justify-content: center !important;
        }

        .justify-content-between {
            justify-content: space-between !important;
        }

        .align-items-center {
            -ms-flex-align: center !important;
            align-items: center !important;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center !important;
        }

        .header {
            font-size: 1.5625rem;
            line-height: 1.4;
            letter-spacing: -0.021rem;
            font-weight: 500;
        }

        .height {
            margin-top: 80px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center height">
            <div class="col col-lg-7">
                <div>
                    <div class="row justify-content-between">
                        <div>
                            <div class="header">
                                {{ SettingHelper::get('name') }}
                            </div>
                        </div>
                        <div>
                            <div>
                                Order #{{ $order->order_id }}
                            </div>
                            <div>
                                {{ date('d M Y', strtotime($order->created_at)) }}
                            </div>
                        </div>
                    </div>
                </div>


                {{-- address --}}
                <div class="row">
                    <div>
                        <div>
                            <label class="font-weight-bold" for="email">SHIP TO</label>
                        </div>
                        <div>
                            @if ($order->order_shipping_address)
                                {{-- <div>
                                    Phone: {{ $order->order_shipping_address->phone }}
                        </div> --}}
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

                    </div>
                    {{-- billing --}}
                    <div style="margin-left: 100px;">
                        <div>
                            <label class="font-weight-bold" for="email">Bill TO</label>
                        </div>

                        <div>
                            <div>
                                @if ($order->order_shipping_address != $order->order_billing_address)
                                    @if (isset($order->order_billing_address))
                                        <div>
                                            {{ $order->order_billing_address->name }}
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
                                    @if ($order->order_shipping_address)
                                        {{-- <div>
                                            Phone: {{ $order->order_shipping_address->phone }}
                                        </div> --}}
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- products --}}
                <div class="row">
                    <div class="col">
                        <table class="table-hover table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Items</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->order_items as $item)
                                    <tr>
                                        <td>
                                            <?php
                                            $file = Storage::get('product/' . $item->product->images[0]->image);
                                            $file = base64_encode($file);
                                            ?>
                                            @if (count($item->product->images) > 0)
                                                <a href="{{ $item->product->images[0]->image_url }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    <img style="width:50px; min-width: 50px;" class="img-thumbnail"
                                                        src="data:image/png;base64,{{ $file }}"
                                                        alt="{{ $item->product->images[0]->image }}"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Click to view large mode">
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a>{{ $item->product->name }}</a>

                                            {{-- product attribute --}}
                                            @if (isset($item->attribute) && count($item->attribute) > 0)
                                                <ul>
                                                    @foreach ($item->attribute as $attribute)
                                                        <li>{{ $attribute->name }}: {{ $attribute->value }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->qnty)
                                                {{ $item->qnty }}
                                            @else
                                                1
                                            @endif
                                        </td>
                                        <td>{{ $order->currency }}{{ $item->total_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <div>
                            <label>Shipping zone:</label>
                            <a>{{ $order->shipping_zone->name }}</a>
                        </div>

                    </div>
                </div>

                <div class="col">
                    <div class="row">
                        <table class="table-hover table">
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
                                    <td>{{ $order->currency }}{{ $order->sub_total }}</td>
                                    <td>{{ $order->currency }}{{ $order->shipping_charge }}</td>
                                    <td>
                                        <div>
                                            @if (count($order->coupons) > 0)
                                                @foreach ($order->coupons as $coupon)
                                                    <div>
                                                        {{ $coupon->amount }} @if ($coupon->amount_type == 'percentage')
                                                            %
                                                            ({{ $order->currency }}{{ ($order->sub_total * $coupon->amount) / 100 }})
                                                    </div>
                                                @endif
                                            @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $order->currency }}{{ $order->order_total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- end products --}}
                <hr>
                <div class="row justify-content-center text-center">
                    <div>
                        <div>
                            <label>Thank you for shopping with us</label>
                        </div>
                        <div>
                            <label class="font-weight-bold">{{ SettingHelper::get('name') }}</label>
                        </div>
                        <div>
                            <label>{{ SettingHelper::get('contact_email') }}</label>
                        </div>
                        <div>
                            <label>{{ env('CLIENT_APP_URL') }}</label>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
</body>

</html>
