<?php

namespace App\Http\Controllers\Api\App\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Cart\Cart;
use App\Models\Checkout\Checkout;
use App\Models\Checkout\CheckoutItem;
use App\Models\Shipping\ShippingZone;
use App\Services\Newsletter\MailingListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
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
            $shipping_zone_id = $request->input('shipping_zone_id');
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $cart_data = $request->input('cart_data');
            $mailing = $request->input('mailing');

            $loggedInUser = auth('api')->user();
            $uuid = uniqid(more_entropy: true);


            //start the transaction
            DB::beginTransaction();

            $shippingZone = ShippingZone::find($shipping_zone_id);

            $checkout = new Checkout();
            $checkout->uuid = $uuid;
            $checkout->fname = $fname;
            $checkout->lname = $lname;
            $checkout->email = $email;
            if ($shippingZone) {
                $checkout->shipping_zone_id = $shippingZone->id;
            }
            if ($loggedInUser) {
                $checkout->user_id = $loggedInUser->id;
            }
            $checkout->save();

            // add order item and remove cart data
            if ($loggedInUser) {
                $carts = Cart::where('user_id', $loggedInUser->id)->get();
            } else {
                $carts = $cart_data;
            }
            foreach ($carts as $cart) {
                // insert cart data to checkout_items
                $checkoutItem = new CheckoutItem();
                $checkoutItem->checkout_id = $checkout->id;
                if ($loggedInUser) {
                    $checkoutItem->cart_id = $cart->id;
                }

                if ($loggedInUser) {
                    $checkoutItem->product_id = $cart->product_id;
                    if ($cart->product->is_sale == 1) {
                        $checkoutItem->discount = $cart->product->discount;
                    }
                    $checkoutItem->quantity = $cart->quantity;
                    $checkoutItem->attribute = isset($cart->attribute) ? json_encode($cart->attribute) : null;
                } else {
                    $checkoutItem->product_id = $cart['product_id'];
                    if ($cart['product']['is_sale'] == 1) {
                        $checkoutItem->discount = $cart['product']['discount'];
                    }
                    $checkoutItem->quantity = $cart['quantity'];
                    $checkoutItem->attribute = isset($cart['attribute']) ? json_encode($cart['attribute']) : null;
                }

                $checkoutItem->save();
            }

            // add into mailing list
            if ($mailing) {
                $userId = null;
                if ($loggedInUser) {
                    $userId = $loggedInUser->id;
                }
                (new MailingListService())->store(
                    fname: $fname,
                    lname: $lname,
                    email: $email,
                    user_id: $userId
                );
            }

            //commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'checkout_id' => $checkout->uuid,
                ]
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            // return response()->json([
            //     'error' => true,
            //     'message' => "Something went wrong :(",
            // ]);
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
        $checkout = Checkout::with('checkout_items')->where('uuid', $id)->first();
        if ($checkout) {
            return response()->json([
                'coupon_discounted' => Checkout::coupon_price($id),
                'order_total' => Checkout::order_total($id),
                'subtotal' => Checkout::subtotal($id),
                'success' => true,
                'data' => $checkout,
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Not found :(',
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
