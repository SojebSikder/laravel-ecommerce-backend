<?php

namespace App\Helper\Payment\Method;

use App\Helper\SettingHelper;
use Stripe\Stripe;

/**
 * Stripe Payment method
 */
class StripeMethod implements IMethod
{
    protected $order;
    protected $items;
    protected $coupon_id;
    public static $provider_name = 'stripe';

    /**
     * @param mixed $order Required if storing new payment
     * @param mixed $items Required if storing new payment
     */
    public function __construct($order = null, $items = null)
    {
        $this->order = $order;
        $this->items = $items;
        return $this;
    }

    public function createCoupon($couponInfo)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // $coupon = \Stripe\Coupon::create([
            //     'amount_off' => 30,
            //     'currency' => SettingHelper::currency_code(),
            //     'duration' => 'once',
            //     'id' => '30OFF',
            // ]);
            $coupon = \Stripe\Coupon::create($couponInfo);
            return $coupon;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Make payment using stripe built in checkout
     */
    public function checkout($coupon_id = null)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $stripeData = [
                // 'line_items' => [
                //     [
                //         'price_data' => [
                //             'currency' => 'usd',
                //             'product_data' => [
                //                 'name' => 'T-shirt',
                //             ],
                //             'unit_amount' => 2000,
                //         ],
                //         'quantity' => 1,
                //     ]
                // ],
                'line_items' => $this->items,
                // 'discounts' => [['coupon' => $coupon_id]],
                'mode' => 'payment',
                'success_url' => env('CLIENT_APP_URL') . '/payment/success',
                'cancel_url' => env('CLIENT_APP_URL') . '/payment/cancel',
            ];

            if ($coupon_id) {
                $stripeData['discounts'] = [['coupon' => $coupon_id]];
            }



            $session = \Stripe\Checkout\Session::create($stripeData);
            // return $session->url;
            return $session;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Make payment using stripe payment intent for custom ui
     */
    public function storePaymentIntent()
    {
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            $intent = $stripe->paymentIntents->create(
                [
                    // 'amount' => 1099,
                    'amount' => (float)$this->order['order_total'] * 100,
                    'currency' => SettingHelper::currency_code(),
                    'automatic_payment_methods' => ['enabled' => true],
                ]
            );
            // return $intent->client_secret;
            return $intent;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Retrieve payment using stripe built in checkout
     */
    public function retrieveCheckout($id)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = \Stripe\Checkout\Session::retrieve($id);
            return $session;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Make payment using stripe payment intent for custom ui
     */
    public function retrievePaymentIntent($id)
    {
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $intent = $stripe->paymentIntents->retrieve(
                $id,
                []
            );
            return $intent;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Execute payment
     */
    public function store()
    {
        return $this->storePaymentIntent();
    }

    /**
     * retrieve payment
     */
    public function retrieve($id)
    {
        return $this->retrievePaymentIntent($id);
    }
}
