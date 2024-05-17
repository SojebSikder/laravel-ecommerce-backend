<?php

namespace App\Http\Controllers\Api\App\Payment;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Helper\Payment\Method\StripeMethod;
use App\Helper\Payment\PaymentMethodHelper;
use App\Models\Order\Order;
use App\Models\Payment\PaymentProvider;
use App\Models\Payment\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Payment existing unpaid order
     */
    public function payment(Request $request)
    {
        try {
            $order_id = $request->input('order_id');
            $payment_provider_id = $request->input('payment_provider_id');

            $customerOrder = Order::find($order_id);
            $payment_provider = PaymentProvider::find($payment_provider_id);
            $res_status = null;
            $res_message = null;
            $res_client_secret = null;
            $res_transaction_id = null;
            $provider = null;

            if (!$customerOrder) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not exist',
                ]);
            }

            // make payment
            if ($payment_provider->name == "cod") {

                $res_status = 'success';
                // $res_message = 'Order placed successfully';
                $res_message = 'Your order #' . $customerOrder->order_id . ' is placed at ' . SettingHelper::get('name') . ' and details emailed you';
                $provider = 'cod';
            } else if ($payment_provider->name == StripeMethod::$provider_name) {
                $provider = $payment_provider->name;
                $redirect_url = $this->makePayment($customerOrder, $provider);

                if ($redirect_url['status'] == 'success') {
                    $res_status = 'success';
                    // $res_message = 'Order placed successfully';
                    $res_message = 'Your order #' . $customerOrder->order_id . ' is placed at ' . SettingHelper::get('name') . ' and details emailed you';
                    $res_client_secret = $redirect_url['client_secret'];
                    $res_transaction_id = $redirect_url['id'];
                } else {
                    $res_status = 'success';
                    // $res_message = 'Order placed successfully';
                    $res_message = 'Your order #' . $customerOrder->order_id . ' is placed at ' . SettingHelper::get('name') . ' and details emailed you';
                }
            }

            // response
            if ($provider == 'cod') {
                return response()->json([
                    'status' => $res_status,
                    'provider' => $provider,
                    'message' => $res_message,
                ]);
            } else if ($provider == StripeMethod::$provider_name) {
                if ($res_client_secret) {
                    return response()->json([
                        'status' => $res_status,
                        'provider' => $provider,
                        'message' => $res_message,
                        'client_secret' => $res_client_secret,
                        'transaction_id' => $res_transaction_id,
                        'order_details' => $customerOrder,
                    ]);
                }
            } else {
                return response()->json([
                    'status' => $res_status,
                    'message' => $res_message,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }


    public function payment_success(Request $request)
    {
        $payment_provider_id = $request->input('payment_provider_id');
        $order_id = $request->input('order_id');
        $transaction_id = $request->input('transaction_id');
        // this if for bsecure
        $provider_name = $request->input('provider_name');
        // bsecure id
        $order_ref = $request->input('order_ref');


        $loggedInUser = auth('api')->user();
        // ?order_ref=ddf07145-666f-45cd-90cd-2b900fc73949
        // get payment provider
        if ($payment_provider_id) {
            $payment_provider = PaymentProvider::find($payment_provider_id);
        } else if ($provider_name) {
            $payment_provider = PaymentProvider::where('name', $provider_name)->first();
        }

        if ($payment_provider->name == StripeMethod::$provider_name) {
            // get payment details
            $retrievePaymentIntent = new PaymentMethodHelper(new StripeMethod());
            $paymentDetails = $retrievePaymentIntent->retrieve($transaction_id);


            if ($paymentDetails->status == "succeeded") {
                // mark payment status to paid
                $order = Order::find($order_id);
                $order->payment_status = "paid";
                $order->save();

                // store transaction history
                $is_payment_transaction = PaymentTransaction::where('transaction_id', $transaction_id)
                    ->where('order_id', $order_id)->first();

                if (!$is_payment_transaction) {
                    $payment_transaction = new PaymentTransaction();
                    if ($loggedInUser) {
                        $payment_transaction->user_id = $loggedInUser->id;
                    }
                    $payment_transaction->order_id = $order_id;
                    $payment_transaction->transaction_id = $transaction_id;
                    $payment_transaction->transaction_provider = $payment_provider->name;
                    $payment_transaction->amount = $paymentDetails->amount != 0 ? ((float)$paymentDetails->amount / 100) : 0; // converting to dollar
                    $payment_transaction->currency = $paymentDetails->currency;
                    $payment_transaction->status = 'succeeded';
                    $payment_transaction->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => "Payment sucessful",
                    ]);
                }
                return response()->json([
                    'status' => 'success',
                    'message' => "Payment already paid before",
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Payment unsucessful",
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Payment provider not found :(",
            ]);
        }
    }

    /**
     * Pay existing unpaid order
     */
    public function makePayment($order, $paymentProvider = 'stripe')
    {
        try {
            $loggedInUser = auth('api')->user();
            if ($paymentProvider == StripeMethod::$provider_name) {
                // preparing data for stripe
                $items = [];
                $itemArray = collect($order->order_items)->toArray();

                foreach ($itemArray as $item) {
                    array_push($items, [
                        'price_data' => [
                            'currency' => SettingHelper::currency_code(),
                            'product_data' => [
                                'name' => $item['product']['name'],
                            ],
                            'unit_amount' => (float)$item['total_price'] * 100, // cent to dollar
                        ],
                        'quantity' => $item['quantity'],
                    ]);
                }


                // pay
                // $paymentMethod = new PaymentMethodHelper(new StripeMethod($order, $items));
                // $paymentUrl = $paymentMethod->store();
                $stripe = new StripeMethod($order, $items);
                $paymentUrl = $stripe->checkout();

                // return $paymentUrl;
                return [
                    'status' => 'success',
                    'payment_info' => $paymentUrl,
                ];
            }
        } catch (\Throwable $th) {
            return [
                'status' => 'error',
                // 'message' => "Something went wrong :(",
                'message' => $th->getMessage(),
            ];
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
