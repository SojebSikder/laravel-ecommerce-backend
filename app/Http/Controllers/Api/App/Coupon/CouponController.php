<?php

namespace App\Http\Controllers\Api\App\Coupon;

use App\Http\Controllers\Controller;
use App\Models\Cart\Cart;
use App\Models\Category\Category;
use App\Models\Coupon\Coupon;
use App\Models\Coupon\TempRedeem;
use App\Models\Order\OrderCoupon;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

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
        $user_id = auth('api')->user()->id;
        $voucherCode = $request->input('code');

        return $this->apply($user_id, $voucherCode);
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
     * Apply coupon to an item
     * @param int $redeemerId user id
     * @param string $voucherCode coupon code
     * @param int $itemId product id
     * @return Redeem $redemption
     */
    public static function apply($redeemerId, $voucherCode, $itemId = null)
    {
        // TODO: auto voucher at checkout
        // $autoVoucher = Coupon::where('method', "auto")->where('status', 1)->get();

        $voucher = Coupon::where('code', $voucherCode)->where('method', "code")->first();

        // Make sure voucher exist
        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon is not exists',
            ]);
        }

        if ($voucher->coupon_type == "product") {
            // Make sure product is exist
            $item = Product::where('id', $itemId)->first();
            if (!$item) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product is not available',
                ]);
            } else if ($item->status != 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product is active',
                ]);
            }
        } else if ($voucher->coupon_type == "category") {
            // Make sure category is exist
            $item = Category::where('id', $itemId)->first();
            if (!$item) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category is not available',
                ]);
            } else if ($item->status != 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category is not active',
                ]);
            }
        }


        // Make sure is voucher active, not expired and available.
        // if ($voucher->status == 0 || $voucher->isExpired() || !$voucher->isAvailable()) {
        //     return;
        // }
        if ($voucher->status == 0 || $voucher->isExpired()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon is not active or it has expired',
            ]);
        }

        // limit
        // Make sure voucher usage
        if ($voucher->max_uses != null) {
            $total_usages = OrderCoupon::where('coupon_id', $voucher->id)
                ->get()
                ->count();
            if ($total_usages >= $voucher->max_uses) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Coupon is exceeded maximum usage',
                ]);
            }
        }

        // Make sure voucher usage for single user
        if ($voucher->max_uses_user != null) {
            $user_usages = OrderCoupon::where('user_id', $redeemerId)
                ->where('coupon_id', $voucher->id)
                ->get()
                ->count();

            if ($user_usages >= $voucher->max_uses_user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Coupon is exceeded maximum usage of user',
                ]);
            }
        }
        // end limit

        //Minimum purchase requirements
        if ($voucher->min_type == "amount") {
            if (Cart::subtotal() < $voucher->min_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Coupon is not applied for minimum purchase amount',
                ]);
            }
        } else if ($voucher->min_type == "quantity") {
            $cartCount = Cart::where('user_id', $redeemerId)->get()->count();
            if ($cartCount < $voucher->min_qnty) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Coupon is not applied for minimum quantity of items',
                ]);
            }
        }

        // Apply voucher to project batch package (item)
        // store to redeem
        // $redemption = new Redeem();
        // $redemption->user_id = $redeemerId;
        // $redemption->coupon_id = $voucher->id;
        // // $redemption->item_id = $itemId;
        // $redemption->save();

        // store to virtual redeem
        $v_redemption = new TempRedeem();
        // $v_redemption->redeem_id = $redemption->id;
        $v_redemption->user_id = $redeemerId;
        $v_redemption->coupon_id = $voucher->id;
        $v_redemption->save();

        return response()->json([
            'status' => 'success',
            // 'data' => $redemption,
            'message' => 'Coupon applied',
        ]);
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
