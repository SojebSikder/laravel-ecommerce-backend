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
            $provider = null;

            if (!$customerOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not exist',
                ]);
            }

            // make payment
            if ($payment_provider->name == "cod") {

                $res_status = true;
                // $res_message = 'Order placed successfully';
                $res_message = 'Your order #' . $customerOrder->invoice_number . ' is placed at ' . SettingHelper::get('name') . ' and details emailed you';
                $provider = 'cod';
            } else if ($payment_provider->name == StripeMethod::$provider_name) {
                $provider = $payment_provider->name;
                $redirect_url = $this->makePayment($customerOrder, $provider);

                if ($redirect_url['success'] == true) {

                    // save payment transaction id
                    $customerOrder->payment_ref_id = $redirect_url['payment_info']['id'];
                    $customerOrder->save();


                    // save payment transaction id
                    $customerOrder->payment_ref_id = $redirect_url['payment_info']['id'];
                    $customerOrder->save();

                    $res_status = true;
                    // $res_message = 'Order placed successfully';
                    $res_message = 'Your order #' . $customerOrder->invoice_number . ' is placed at ' . SettingHelper::get('name') . ' and details emailed you';
                } else {
                    $res_status = true;
                    // $res_message = 'Order placed successfully';
                    $res_message = 'Your order #' . $customerOrder->invoice_number . ' is placed at ' . SettingHelper::get('name') . ' and details emailed you';
                }
            }

            // response
            if ($provider == 'cod') {
                return response()->json([
                    'success' => true,
                    'provider' => $provider,
                    'message' => $res_message,
                ]);
            } else if ($provider == StripeMethod::$provider_name) {
                if ($redirect_url['success'] == true) {
                    return response()->json([
                        'redirect_url' => $redirect_url['payment_info']['url'],
                        'redirect' => true,
                        'success' => true,
                        'message' => 'Order placed successfully, proceeding to payment.',
                    ]);
                } else {
                    return response()->json([
                        'redirect' => false,
                        'success' => false,
                        'message' => $redirect_url['message'],
                    ]);
                }
            } else {
                return response()->json([
                    'success' => $res_status,
                    'message' => $res_message,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }


    public function stripe_webhook(Request $request)
    {
        try {
            $payload = json_decode($request->getContent(), true);
            $event = $payload['type'];
            $data = $payload['data']['object'];

            $transaction_id = $data['id'];

            // get payment details
            $retrieveCheckout = new StripeMethod();
            $paymentDetails = $retrieveCheckout->retrieveCheckout($transaction_id);

            $amount_received = $paymentDetails->amount_received != 0 ? ((float)$paymentDetails->amount_received / 100) : 0; // converting to dollar
            $currency = $paymentDetails->currency;
            $status = $paymentDetails->status;
            $payment_status = $paymentDetails->payment_status;

            // mark payment status to paid
            $order = Order::where('payment_ref_id', $transaction_id)->first();

            $order->payment_status = $payment_status;
            $order->paid_amount = $amount_received;
            $order->paid_currency = $currency;
            $order->save();

            // store transaction history
            $payment_transaction = new PaymentTransaction();
            $payment_transaction->user_id = $order->user_id;

            $payment_transaction->order_id = $order->id;
            $payment_transaction->transaction_id = $transaction_id;
            $payment_transaction->transaction_provider = $order->payment_provider;
            $payment_transaction->amount = $amount_received;
            $payment_transaction->currency = $currency;
            $payment_transaction->status = $payment_status;
            $payment_transaction->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated',
            ]);
        } catch (\Throwable $th) {
            throw $th;
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

                // shipping charge
                if ((float)$order->shipping_charge > 0) {
                    array_push($items, [
                        'price_data' => [
                            'currency' => SettingHelper::currency_code(),
                            'product_data' => [
                                'name' => 'Shipping Charge',
                            ],
                            'unit_amount' => (float)$order->shipping_charge * 100, // cent to dollar
                        ],
                        'quantity' => 1,
                    ]);
                }

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
                    'success' => true,
                    'payment_info' => $paymentUrl,
                ];
            }
        } catch (\Throwable $th) {
            return [
                'success' => false,
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
