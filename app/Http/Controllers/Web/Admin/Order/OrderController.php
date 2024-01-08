<?php

namespace App\Http\Controllers\Web\Admin\Order;

use App\Helper\SettingHelper;
use App\Helper\StringHelper;
use App\Http\Controllers\Controller;
use App\Mail\User\Order\OrderFulfilled;
use App\Models\Address\Country;
use App\Models\Order\Order;
use App\Models\Order\OrderCoupon;
use App\Models\Order\OrderDraft\OrderDraft;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderShippingAddress;
use App\Models\Order\OrderStatus;
use App\Models\Order\OrderTimeline\OrderTimeline;
use App\Models\Order\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_management_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        // search query
        $q = $request->input('q');
        // filter
        // available request params: date, status, fulfillment_status, payment_status, customer_id
        // available values: today, last_7_days,last_30_days
        $date = $request->input('date');
        $status = $request->input('status');
        $fulfillment_status = $request->input('fulfillment_status');
        $payment_status = $request->input('payment_status');
        $customer_id = $request->input('customer_id');
        //

        $orders = Order::query();

        if ($q) {
            $orders = $orders->orWhere('email', 'like', '%' . $q . '%')
                ->orWhereHas('user_shipping_address', function ($query) use ($q) {
                    $query->where('name', 'like', '%' . $q . '%');
                })
                ->orWhere('id', 'like', '%' . $q . '%')
                ->orWhere('order_id', 'like', '%' . $q . '%')
                ->orWhere('payment_ref_id', 'like', '%' . $q . '%')
                ->orWhere('tracking_number', 'like', '%' . $q . '%')
                ->orWhere('phone_number', 'like', '%' . $q . '%');
        }
        if ($status) {
            $orders = $orders->where('status', $status);
        }
        if ($fulfillment_status) {
            $orders = $orders->where('fulfillment_status', $fulfillment_status);
        }
        if ($payment_status) {
            $orders = $orders->where('payment_status', $payment_status);
        }
        if ($customer_id) {
            $orders = $orders->where('user_id', $customer_id);
        }
        if ($date) {
            if ($date == 'today') {
                $orders = $orders->whereDate('created_at', Carbon::today());
            } else if ($date == 'last_7_days') {
                $orders = $orders->whereDate('created_at', '>=', Carbon::now()->subDays(7));
            } else if ($date == 'last_30_days') {
                $orders = $orders->whereDate('created_at', '>=', Carbon::now()->subDays(30));
            } else {
                $orders = $orders->whereDate('created_at', '>=', Carbon::parse($date));
            }
        }


        $orders = $orders->with('order_items')
            ->latest()
            ->paginate(15);
        return view('backend.order.index', compact('orders'));
    }


    public function view_invoice($id)
    {
        $order = Order::findOrFail($id);
        return view('backend.order.invoice', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('order_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // create order from draft
        $order_draft_id = $request->input('order_draft_id');
        $order_draft = OrderDraft::with('user', 'order_draft_items')->findOrFail($order_draft_id);

        //start the transaction
        DB::beginTransaction();

        // invoice id, user will use this for track order
        $latestOrder = Order::orderBy('created_at', 'DESC')->first();
        if ($latestOrder) {
            if (Order::where('invoice_number', $latestOrder->invoice_number + 1)->first()) {
                $invoice_id = str_pad((int)$latestOrder->invoice_number + 2, 4, "0", STR_PAD_RIGHT);
            } else {
                $invoice_id = str_pad((int)$latestOrder->invoice_number + 1, 4, "0", STR_PAD_RIGHT);
            }
        } else {
            $invoice_id = "1000";
        }

        // shipping address
        $shipping_address = new OrderShippingAddress();
        $shipping_address->save();

        // create order
        $order = new Order();
        $order->invoice_number = $invoice_id;
        $order->user_id = $order_draft->user_id;

        $order->fname = $order_draft->user->fname;
        $order->lname = $order_draft->user->lname;
        $order->phone_number = $order_draft->user->phone_number;
        $order->email = $order_draft->user->email;

        $order->comment = $order_draft->comment;

        $order->order_total = $order_draft->order_total;
        $order->payment_status = "unpaid";
        $order->status = "order_placed";
        $order->currency = SettingHelper::currency_code();
        $order->currency_sign = SettingHelper::currency_sign();
        $order->order_shipping_address_id = $shipping_address->id;

        $order->save();

        // create order items from draft items
        $order_draft_items = $order_draft->order_draft_items;
        foreach ($order_draft_items as $order_draft_item) {

            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->product_id = $order_draft_item->product_id;
            if ($order_draft_item->variant_id) {
                $order_item->variant_id = $order_draft_item->variant_id;
            }
            $order_item->attribute = $order_draft_item->attribute;
            $order_item->quantity = $order_draft_item->quantity;

            if ($order_draft_item->variant_id) {
                $total = 0.0;
                if ($order_draft_item->variant->is_sale == 1) {
                    $total += StringHelper::discount($order_draft_item->variant->price, $order_draft_item->variant->discount);
                } else {
                    $total += $order_draft_item->variant->price;
                }

                $order_item->weight = $order_draft_item->variant->weight;
                $order_item->weight_unit = $order_draft_item->variant->weight_unit;
                if ($order_draft_item->variant->is_sale) {
                    $order_item->discount = $order_draft_item->variant->discount;
                }
                $order_item->price = $order_draft_item->variant->price;
                $order_item->total_price = $total;
            } else {
                $total = 0.0;
                if ($order_draft_item->product->is_sale == 1) {
                    $total += StringHelper::discount($order_draft_item->product->price, $order_draft_item->product->discount);
                } else {
                    $total += $order_draft_item->product->price;
                }

                $order_item->weight = $order_draft_item->product->weight;
                $order_item->weight_unit = $order_draft_item->product->weight_unit;
                if ($order_draft_item->product->is_sale) {
                    $order_item->discount = $order_draft_item->product->discount;
                }
                $order_item->price = $order_draft_item->product->price;
                $order_item->total_price = $total;
            }

            $order_item->save();
        }

        // delete order draft
        $order_draft->delete();

        // log activity
        activity()
            ->performedOn($order)
            ->causedBy(auth()->user())
            ->withProperties(['order_id' => $order->id])
            ->log('order_created');


        //commit the transaction
        DB::commit();

        return redirect()->route('order.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('order_management_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::orderBy('name', 'asc')->get();
        $statuses = Status::orderBy('sort_order', 'asc')->get();
        $order = Order::with([
            'user',
            'order_items' => function ($query) {
                $query->with(['product', 'variant' => function ($variant) {
                    $variant->with(['variant_attributes' => function ($query) {
                        $query->with(['attribute', 'attribute_value']);
                    }, 'images']);
                }]);
            },
            'order_timelines' => function ($query) {
                $query->with('user')->latest();
            },
            'order_shipping_address',
            'order_billing_address',
            'order_statuses' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($id);

        return view('backend.order.show', compact('order', 'countries', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function saveUserShippingDetails(Request $request, $id)
    {
        abort_if(Gate::denies('order_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // user shipping address
        $shipping_fname = $request->input('shipping_fname');
        $shipping_lname = $request->input('shipping_lname');
        $shipping_country = $request->input('shipping_country');

        $shipping_address1 = $request->input('shipping_address1');
        $shipping_address2 = $request->input('shipping_address2');

        $shipping_city = $request->input('shipping_city');
        $shipping_state = $request->input('shipping_state');
        $shipping_zip = $request->input('shipping_zip');
        $shipping_phone_number = $request->input('shipping_phone_number');


        // store shipping address
        $shippingAddress = OrderShippingAddress::find($id);
        if ($shipping_fname) {
            $shippingAddress->fname = $shipping_fname;
        }
        if ($shipping_lname) {
            $shippingAddress->lname = $shipping_lname;
        }
        if ($shipping_country) {
            $shippingAddress->country_id = $shipping_country;
        }
        if ($shipping_address1) {
            $shippingAddress->shipping_address1 = $shipping_address1;
        }
        if ($shipping_address2) {
            $shippingAddress->shipping_address2 = $shipping_address2;
        }
        if ($shipping_city) {
            $shippingAddress->city = $shipping_city;
        }
        if ($shipping_state) {
            $shippingAddress->state = $shipping_state;
        }
        if ($shipping_zip) {
            $shippingAddress->zip = $shipping_zip;
        }
        if ($shipping_phone_number) {
            $shippingAddress->phone_number = $shipping_phone_number;
        }

        $shippingAddress->save();

        // log activity
        activity()
            ->performedOn($shippingAddress)
            ->causedBy(auth()->user())
            ->withProperties(['order_id' => $shippingAddress->id])
            ->log('order_shipping_address_updated');

        return back()->with('success', 'Changed successfully');
    }

    public function status(Request $request, $id)
    {
        abort_if(Gate::denies('order_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $status = $request->input('status');

            //start the transaction
            DB::beginTransaction();

            $db_status = Status::find($status);

            $order = Order::findOrFail($id);
            $order->status = $db_status->name;
            $order->save();

            $order_status = new OrderStatus();
            $order_status->order_id = $id;
            $order_status->status_id = $status;
            $order_status->save();

            // keep order status history
            $orderTimeline = new OrderTimeline();
            $orderTimeline->order_id = $id;
            $orderTimeline->type = "status";
            $orderTimeline->body = $db_status->label;
            $orderTimeline->save();

            // log activity
            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->withProperties(['order_id' => $order->id])
                ->log('order_status_changed');

            //commit the transaction
            DB::commit();
            return back()->with('success', 'Order status changed');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('success', $th->getMessage());
        }
    }

    public function destroyStatus($id)
    {
        abort_if(Gate::denies('order_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order_status = OrderStatus::find($id);
        $order_status->delete();

        // log activity
        activity()
            ->performedOn($order_status)
            ->causedBy(auth()->user())
            ->withProperties(['order_id' => $order_status->order_id])
            ->log('order_status_deleted');

        return back()->with('success', 'Deleted successfully');
    }

    public function paymentStatus(Request $request, $id)
    {
        abort_if(Gate::denies('order_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment_status = $request->input('payment_status');

        $order = Order::findOrFail($id);
        $order->payment_status = $payment_status;
        $order->save();

        // keep history
        $orderTimeline = new OrderTimeline();
        $orderTimeline->order_id = $id;
        $orderTimeline->type = "status";
        $orderTimeline->body = $payment_status;
        $orderTimeline->save();

        // log activity
        activity()
            ->performedOn($order)
            ->causedBy(auth()->user())
            ->withProperties(['order_id' => $order->id])
            ->log('order_payment_status_changed');

        return back()->with('success', 'Payment status changed');
    }

    public function fulfillmentStatus(Request $request, $id)
    {
        abort_if(Gate::denies('order_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $fulfillment_status = $request->input('fulfillment_status');
        $tracking_number = $request->input('tracking_number');
        $courier_provider = $request->input('courier_provider');
        $fulfill_mail = $request->input('fulfill_mail') == 1 ? 1 : 0;
        $inline_shipping = $request->input('inline_shipping') == 1 ? 1 : 0;

        $order = Order::findOrFail($id);
        if ($fulfillment_status) {
            $order->fulfillment_status = $fulfillment_status;
        }
        if ($tracking_number) {
            $order->tracking_number = $tracking_number;
        }
        if ($courier_provider) {
            $order->courier_provider = $courier_provider;
        }
        if ($fulfillment_status == 'fulfilled') {
            $order->status = 'order_processing';
        }
        $order->save();

        // keep history
        $orderTimeline = new OrderTimeline();
        $orderTimeline->order_id = $id;
        $orderTimeline->type = "status";
        $orderTimeline->body = $fulfillment_status;
        $orderTimeline->save();


        /**
         * Notification
         */
        if ($fulfill_mail) {

            //Send fulfillment mail to customer
            if ($order->user_id) {
                $user = User::find($order->user_id);
                if ($user) {
                    $data = new \stdClass();
                    $data->order = $order;
                    $data->inline_shipping = $inline_shipping;
                    // $data->courier_shipping = 'https://callcourier.com.pk/tracking/?tc=';
                    $data->courier_shipping = $tracking_number;

                    Mail::to($user)->send(new OrderFulfilled($data));
                }
            } else {
                $customerTo = [
                    [
                        'email' => $order->email,
                        'name' => $order->user_shipping_address && ($order->user_shipping_address->name ?? null),
                    ]
                ];
                $data = new \stdClass();
                $data->order = $order;
                $data->inline_shipping = $inline_shipping;
                // $data->courier_shipping = 'https://callcourier.com.pk/tracking/?tc=';
                $data->courier_shipping = $tracking_number;

                Mail::to($customerTo)->send(new OrderFulfilled($data));
            }
        }

        // log activity
        activity()
            ->performedOn($order)
            ->causedBy(auth()->user())
            ->withProperties(['order_id' => $order->id])
            ->log('order_fulfillment_status_changed');

        return back()->with('success', 'Fulfillment status changed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            abort_if(Gate::denies('order_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            //start the transaction
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            // remove order item
            OrderItem::where('order_id', $order->id)->delete();
            // remove order coupon
            OrderCoupon::where('order_id', $order->id)->delete();
            // remove order
            $order->delete();

            //commit the transaction
            DB::commit();
            return redirect('/order?date=today')->with('success', 'Order Deleted');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('warning', $th->getMessage());
        }
    }
}
