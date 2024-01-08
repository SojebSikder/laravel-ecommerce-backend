<?php

namespace App\Http\Controllers\Api\App\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart\Cart;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // user id
        $user_id = auth("api")->user()->id;

        $carts = Cart::with('product', 'variant')->where('user_id', $user_id)->get();

        return response()->json([
            'coupon_discounted' => Cart::coupon_price(),
            'order_total' => Cart::order_total(),
            'subtotal' => Cart::subtotal(),
            'success' => true,
            'data' => $carts,
        ]);
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
            $product_id = $request->input('product_id');
            $variant_id = $request->input('variant_id');
            $quantity = $request->input('quantity');
            // user id
            $user_id = auth("api")->user()->id;


            // check if product is available
            $product = Product::where('id', $product_id)->first();
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ]);
            }

            // check if product is variant
            if ($variant_id) {
                $variant = $product->variants()->where('id', $variant_id)->first();
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product variant not found',
                    ]);
                }
            }

            // check product quantity
            if ($variant_id) {
                if ($variant->quantity < $quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product quantity not available',
                    ]);
                }
            } else {
                if ($product->quantity < $quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product quantity not available',
                    ]);
                }
            }

            if ($variant_id) {
                // check if product is already in cart
                $cart = Cart::where('user_id', $user_id)
                    ->where('product_id', $product_id)
                    ->where('variant_id', $variant_id)
                    ->first();
                if ($cart) {
                    $cart->quantity += $quantity;
                    $cart->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Added to cart successfully',
                    ]);
                }
            } else {
                // check if product is already in cart
                $cart = Cart::where('user_id', $user_id)
                    ->where('product_id', $product_id)
                    ->first();
                if ($cart) {
                    $cart->quantity += $quantity;
                    $cart->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Added to cart successfully',
                    ]);
                }
            }


            // add to cart
            $cart = new Cart();
            $cart->user_id = $user_id;
            $cart->product_id = $product_id;
            if ($variant_id) {
                $cart->variant_id = $variant_id;
            }
            $cart->quantity = $quantity;
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Added to cart successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ]);
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
        try {
            $quantity = $request->input('quantity');
            // user id
            $user_id = auth("api")->user()->id;

            $cart = Cart::with('product', 'variant')->where('user_id', $user_id)
                ->where('id', $id)
                ->first();

            if ($quantity <= 0) {
                $cart->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Cart Item deleted',
                ]);
            } else {
                // check if product quantity
                if ($cart->variant_id) {
                    if ($cart->variant->quantity < $quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Product quantity not available',
                        ]);
                    }
                } else {
                    if ($cart->product->quantity < $quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Product quantity not available',
                        ]);
                    }
                }

                $cart->quantity = $quantity;
                $cart->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Cart Item updated successfully',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ]);
        }
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
            // user id
            $user_id = auth("api")->user()->id;

            $cart = Cart::where('user_id', $user_id)
                ->where('id', $id)
                ->first();
            $cart->delete();
            $cart->checkCouponOnCart();

            return response()->json([
                'success' => true,
                'message' => 'Cart item deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ]);
        }
    }
}
