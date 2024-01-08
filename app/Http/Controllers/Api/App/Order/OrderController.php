<?php

namespace App\Http\Controllers\Api\App\Order;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Mail\Admin\Order\AdminOrderConfirm;
use App\Mail\User\Order\OrderConfirm;
use App\Models\Address\Country;
use App\Models\Cart\Cart;
use App\Models\Checkout\Checkout;
use App\Models\Coupon\TempRedeem;
use App\Models\Order\Order;
use App\Models\Order\OrderCoupon;
use App\Models\Order\OrderItem;
use App\Models\Order\OrderShippingAddress;
use App\Models\Order\OrderStatus;
use App\Models\Order\OrderTimeline\OrderTimeline;
use App\Models\Order\Status;
use App\Models\Payment\PaymentProvider;
use App\Models\Product\Product;
use App\Models\Shipping\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $loggedInUser = auth('api')->user();
            $orders = Order::where('user_id', $loggedInUser->id)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => 'Something went wrong :(',
            ]);
        }
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
        try {
            $customMessages = [
                'required' => 'The :attribute field is required.'
            ];
            $rules = [
                'shipping_fname' => 'required',
                'shipping_lname' => 'required',
                // 'shipping_phone' => 'required',
                'email' => 'required|string|email|max:255',
                'shipping_city' => 'required',
                'shipping_address1' => 'required',
                'shipping_country' => 'required',
                'shipping_zone_id' => 'required',
                'payment_provider_id' => 'required',
            ];

            $this->validate($request, $rules, $customMessages);
            /**
             * request params
             */
            // $cart_data = $request->input('cart_data');
            $checkout_id = $request->input('checkout_id');
            // user shipping address
            $shipping_fname = $request->input('shipping_fname');
            $shipping_lname = $request->input('shipping_lname');
            $shipping_country = $request->input('shipping_country');
            $shipping_address1 = $request->input('shipping_address1');
            $shipping_address2 = $request->input('shipping_address2');
            $shipping_city = $request->input('shipping_city');
            $shipping_state = $request->input('shipping_state');
            $shipping_zip = $request->input('shipping_zip');
            $email = $request->input('email');
            $shipping_phone = $request->input('shipping_phone');
            // user billing address
            $billing = $request->input('billing');
            $billing_fname = $request->input('billing_fname');
            $billing_lname = $request->input('billing_lname');
            $billing_country = $request->input('billing_country');
            $billing_address1 = $request->input('billing_address1');
            $billing_address2 = $request->input('billing_address2');
            $billing_city = $request->input('billing_city');
            $billing_state = $request->input('billing_state');
            $billing_zip = $request->input('billing_zip');
            // payment
            $payment_provider_id = $request->input('payment_provider_id');

            // check if user logged in
            $loggedInUser = auth('api')->user();

            //start the transaction
            DB::beginTransaction();

            if ($loggedInUser) {
                $updateCheckout = Checkout::with('checkout_items')
                    ->where('uuid', $checkout_id)
                    ->first();
                $updateCheckout->user_id =  $loggedInUser;
                $updateCheckout->save();
            }

            $checkout = Checkout::with('checkout_items')
                ->where('uuid', $checkout_id)
                ->first();

            $cart_data = $checkout->checkout_items;

            $shipping_zone_id = $checkout->shipping_zone_id;

            /**
             * Store to db
             */
            // store shipping address
            $shippingAddress = new OrderShippingAddress();
            if ($shipping_fname) {
                $shippingAddress->fname = $shipping_fname;
            }
            if ($shipping_lname) {
                $shippingAddress->lname = $shipping_lname;
            }
            if ($shipping_country) {
                $shippingCountryInfo = Country::find($shipping_country);
                $shippingAddress->country = $shippingCountryInfo->name;

                $shippingAddress->country_id = $shipping_country;
            }
            if ($shipping_address1) {
                $shippingAddress->address1 = $shipping_address1;
            }
            if ($shipping_address2) {
                $shippingAddress->address2 = $shipping_address2;
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
            if ($shipping_phone) {
                $shippingAddress->phone_number = $shipping_phone;
            }
            if ($email) {
                $shippingAddress->email = $email;
            }
            $shippingAddress->save();
            // store billing address
            if ($billing) {
                $billingAddress = new OrderShippingAddress();
                if ($billing_fname) {
                    $billingAddress->fname = $billing_fname;
                }
                if ($billing_lname) {
                    $billingAddress->lname = $billing_lname;
                }
                if ($billing_country) {
                    $billingCountryInfo = Country::find($shipping_country);
                    $billingAddress->country = $billingCountryInfo->name;

                    $billingAddress->country_id = $billing_country;
                }
                if ($billing_address1) {
                    $billingAddress->address1 = $billing_address1;
                }
                if ($billing_address2) {
                    $billingAddress->address2 = $billing_address2;
                }
                if ($billing_city) {
                    $billingAddress->city = $billing_city;
                }
                if ($billing_state) {
                    $billingAddress->state = $billing_state;
                }
                if ($billing_zip) {
                    $billingAddress->zip = $billing_zip;
                }
                if ($shipping_phone) {
                    $billingAddress->phone_number = $shipping_phone;
                }
                $billingAddress->save();
            }

            // store order
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
            //
            $shipping_zone_data = ShippingZone::where('id', $shipping_zone_id)->first();
            $shipping_charge = (float) $shipping_zone_data->price;
            $order_total = Checkout::order_total($checkout_id) + (float) $shipping_charge;
            $payment_provider = PaymentProvider::find($payment_provider_id);
            // $status_info = Status::where('default', 1)->first();
            $status_info = Status::where('status', 1)->first();

            $order = new Order();
            if ($checkout->user_id) {
                $order->user_id = $checkout->user_id;
            }
            $order->invoice_number = $invoice_id;
            $order->sub_total = Checkout::subtotal($checkout_id);
            $order->shipping_zone_id = $shipping_zone_id;
            $order->shipping_zone_name = $shipping_zone_data->name;
            $order->shipping_charge = $shipping_charge;
            // store order total with = subtotal + coupon discount + shipping charge
            $order->order_total = $order_total;
            $order->payment_provider_id = $payment_provider_id;
            $order->payment_provider = $payment_provider->name;
            $order->payment_status = "unpaid";
            // contact
            if ($checkout->fname) {
                $order->fname = $checkout->fname;
            }
            if ($checkout->lname) {
                $order->lname = $checkout->lname;
            }
            $order->phone_number = $shipping_phone;
            $order->email = $email;
            $order->order_shipping_address_id = $shippingAddress->id;
            if ($billing) {
                $order->order_billing_address_id = $billingAddress->id;
            } else {
                $order->order_billing_address_id = $shippingAddress->id;
            }


            if ($status_info) {
                $order->status = $status_info->name;
            } else {
                $order->status = "order_placed";
            }
            $order->currency = SettingHelper::currency_code();
            $order->currency_sign = SettingHelper::currency_sign();
            $order->save();

            // add order status history
            if ($status_info) {
                $order_status = new OrderStatus();
                $order_status->order_id = $order->id;
                $order_status->status_id = $status_info->id;
                $order_status->save();

                // keep order status history
                $orderTimeline = new OrderTimeline();
                $orderTimeline->order_id = $order->id;
                $orderTimeline->type = "status";
                $orderTimeline->body = $status_info->label;
                $orderTimeline->save();
            }

            if ($checkout->user_id) {
                // store coupon history to order_coupon
                $temp_redeems = TempRedeem::where('user_id', $checkout->user_id)->get();
                if (count($temp_redeems)) {
                    foreach ($temp_redeems as $temp_redeem) {
                        $orderCoupon = new OrderCoupon();
                        $orderCoupon->order_id = $order->id;
                        $orderCoupon->user_id = $checkout->user_id;
                        $orderCoupon->coupon_id = $temp_redeem->coupon_id;
                        $orderCoupon->coupon_type = $temp_redeem->coupon->coupon_type;
                        $orderCoupon->method = $temp_redeem->coupon->method;
                        $orderCoupon->code = $temp_redeem->coupon->code;
                        $orderCoupon->amount_type = $temp_redeem->coupon->amount_type;
                        $orderCoupon->amount = $temp_redeem->coupon->amount;
                        $orderCoupon->save();

                        // remove temp_redeem data
                        TempRedeem::where('id', $temp_redeem->id)->first()->delete();
                    }
                }
            }

            // add order item and remove cart data
            if ($checkout) {
                foreach ($checkout->checkout_items as $checkout_item) {
                    // insert cart data to order product
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;

                    $orderItem->product_id = $checkout_item->product_id;
                    if ($checkout_item->variant_id) {
                        $orderItem->variant_id = $checkout_item->variant_id;
                    }
                    if ($checkout_item->product->is_sale == 1) {
                        $orderItem->discount = $checkout_item->product->discount;
                    }
                    $orderItem->quantity = $checkout_item->quantity;
                    $orderItem->price = $checkout_item->product->price;
                    $orderItem->total_price = $checkout_item->subtotal;
                    $orderItem->attribute = json_encode($checkout_item->attribute);

                    $orderItem->save();

                    // decrease product quantity
                    $updateProduct = Product::find($checkout_item->product_id);
                    if ($updateProduct) {
                        $updateProduct->quantity = (int)$updateProduct->quantity - (int)$checkout_item->quantity;
                        $updateProduct->save();
                    }

                    if ($checkout_item->cart_id) {
                        // remove cart data
                        Cart::where('id', $checkout_item->cart_id)->first()->delete();
                    }
                }
                // delete checkout with checkout items
                $checkout->delete();
            }


            $customerOrder = Order::find($order->id);
            // TODO make payment


            /**
             * Notification
             */
            $customerData = new \stdClass();
            $customerData->order = $customerOrder;
            //Send confirmation mail to customer
            $customerTo = [
                [
                    'email' => $email,
                    'name' => $shipping_fname . ' ' . $shipping_lname,
                ]
            ];
            Mail::to($customerTo)->send(new OrderConfirm($customerData));
            // Send confirmation mail to admin
            $to = [
                [
                    'email' => SettingHelper::get('contact_email'),
                    'name' => SettingHelper::get('name'),
                ]
            ];
            $data = new \stdClass();
            $data->order = $customerOrder;
            Mail::to($to)->send(new AdminOrderConfirm($data));
            // end Notification

            //commit the transaction
            DB::commit();

            // response
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $order = Order::where('id', $id)
                ->where('user_id', auth('api')->user()->id)
                ->first();

            if ($order) {
                return response()->json([
                    'success' => true,
                    'data' => $order,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Not found.",
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => 'Something went wrong.',
            ]);
        }
    }

    /**
     * Show order status
     */
    public function orderStatus(Request $request)
    {
        try {
            $order_number = $request->input('order_number');
            $email = $request->input('email');

            $order_number = ltrim($order_number, '#');

            $statuses = Status::orderBy('sort_order', 'asc')->where('on_shipping_status', 1)->get();
            $order = Order::where('order_id', $order_number)
                ->where('email', $email)
                ->first();

            if ($order) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'order_status' => $statuses,
                        'order_data' => $order,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong :(',
            ]);
        }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
