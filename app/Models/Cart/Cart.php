<?php

namespace App\Models\Cart;

use App\Helper\SettingHelper;
use App\Helper\StringHelper;
use App\Models\Coupon\TempRedeem;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $appends = ['currency_sign', 'currency_code', 'subtotal'];

    // custom currency attribute
    public function getCurrencySignAttribute()
    {
        return SettingHelper::currency_sign();
    }

    // custom currency code attribute
    public function getCurrencyCodeAttribute()
    {
        return SettingHelper::currency_code();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function getSubtotalAttribute()
    {
        $total = 0.0;
        if ($this->product->is_sale == 1) {
            $total += StringHelper::discount($this->product->price, $this->product->discount);
        } else {
            $total += $this->product->price;
        }

        return $total;
    }

    // returns all products subtotal (for client side)
    public static function subtotal()
    {
        $carts = Cart::with('product')->where('user_id', auth('api')->user()->id)->get();


        $map = array_map(function ($cart) {

            $price = 0.0;

            if ($cart['product']['is_sale'] == 1) {
                return $price + StringHelper::discount($cart['product']['price'], $cart['product']['discount']);
            } else {
                return $price + (float) $cart['product']['price'];
            }
        }, $carts->toArray());

        $total = array_reduce($map, function ($prev, $curr) {
            return (float)$prev + (float)$curr;
        }, 0.0);
        return $total;
    }

    // check cart if amount or quantity less or exmpty
    public function checkCouponOnCart()
    {
        $temp_redeems = TempRedeem::where('user_id', auth('api')->user()->id)->get();

        if ($temp_redeems) {
            foreach ($temp_redeems as $temp_redeem) {
                if ($temp_redeem->coupon->method == "code") {
                    //Minimum purchase requirements
                    if ($temp_redeem->coupon->min_type == "amount") {
                        if (Cart::subtotal() < $temp_redeem->coupon->min_amount) {
                            TempRedeem::where('id', $temp_redeem->id)->first()->delete();
                        }
                    } else if ($temp_redeem->coupon->min_type == "quantity") {
                        $cartCount = Cart::where('user_id', auth('api')->user()->id)->get()->count();
                        if ($cartCount < $temp_redeem->coupon->min_qnty) {
                            TempRedeem::where('id', $temp_redeem->id)->first()->delete();
                        }
                    }
                }
            }
        }
        return;
    }
    // returns coupon discounted price
    public static function coupon_price()
    {
        $temp_redeems = TempRedeem::where('user_id', auth('api')->user()->id)->get();

        $amountArray = [];

        foreach ($temp_redeems as $temp_redeem) {
            if ($temp_redeem->coupon->method == "code") {
                array_push($amountArray, [
                    'amount' => (float) $temp_redeem->coupon->amount,
                    'amount_type' => $temp_redeem->coupon->amount_type
                ]);
            }
        }

        return $amountArray;
    }

    // return total price of order (for client side)
    public static function order_total()
    {
        $coupon_prices = static::coupon_price();
        $subtotal = static::subtotal();

        if ($coupon_prices) {
            $total = 0.0;
            foreach ($coupon_prices as $coupon_price) {
                if ($coupon_price['amount_type'] == 'percentage') {
                    // $total - (($request->input('price') * $discount) / 100);
                    $total += ($subtotal * (float) $coupon_price['amount']) / 100;
                } else {
                    $total += (float) $coupon_price['amount'];
                }
            }
            return number_format($subtotal - $total, 1, '.', '');
        } else {
            return $subtotal;
        }
    }
}
