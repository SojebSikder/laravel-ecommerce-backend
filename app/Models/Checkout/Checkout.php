<?php

namespace App\Models\Checkout;

use App\Helper\SettingHelper;
use App\Helper\StringHelper;
use App\Models\Coupon\TempRedeem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
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

    public function checkout_items()
    {
        return $this->hasMany(CheckoutItem::class)->with('product');
    }

    /**
     * Functions
     */
    // add extra value from option set
    private static function getOptionSetPrice($cart, $key, $value)
    {
        // get option set price
        $optionSetPrice = array_map(function ($element) use ($value) {
            if ($element['type'] == "select") {
                $elementPrice = array_map(function ($optionValue) use ($value) {
                    if ($optionValue->value == $value) {
                        return (float)$optionValue->price ?? 0.0;
                    }
                }, $element['option_value']);

                $elementPriceReduce =  array_reduce($elementPrice, function ($curr, $prev) {
                    return $curr + $prev;
                }, 0.0);
                return $elementPriceReduce;
            }
        }, $cart['variant']['product']['option_set']['elements']);

        $optionSetPriceReduce = array_reduce($optionSetPrice, function ($curr, $prev) {
            return $curr + $prev;
        });
        return $optionSetPriceReduce;
    }
    // returns all products subtotal (for client side)
    public static function subtotal($id)
    {
        $checkout = Checkout::with('checkout_items')->where('uuid', $id)->first();
        $carts = $checkout->checkout_items;


        $map = array_map(function ($cart) {
            $priceMap = array_map(
                function ($attribute) use ($cart) {
                    return static::getOptionSetPrice($cart, $attribute->name, $attribute->value);
                },
                $cart['attribute']
            );
            $price = array_reduce($priceMap, function ($prev, $curr) {
                return $prev + $curr;
            }, 0.0);

            if ($cart['variant']['product']['is_sale'] == "true") {
                return $price + StringHelper::discount($cart['variant']['price'], $cart['variant']['discount']);
            } else {
                return $price + (float) $cart['variant']['price'];
            }
        }, $carts->toArray());

        $total = array_reduce($map, function ($prev, $curr) {
            return (float)$prev + (float)$curr;
        }, 0.0);
        return $total;
    }

    // returns coupon discounted price
    public static function coupon_price($id)
    {
        $checkout = Checkout::with('checkout_items')->where('uuid', $id)->first();
        $user_id = $checkout->user_id;
        if ($user_id) {
            $temp_redeems = TempRedeem::where('user_id', $user_id)->get();

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
        } else {
            return [];
        }
    }

    // return total price of order (for client side)
    public static function order_total($id)
    {
        $coupon_prices = static::coupon_price($id);
        $subtotal = static::subtotal($id);

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
