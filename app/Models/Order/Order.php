<?php

namespace App\Models\Order;

use App\Models\Shipping\ShippingZone;
use App\Models\User;
use App\Models\User\UserShippingAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['order_statuses', 'order_items', 'coupons', 'shipping_zone', 'user_shipping_address', 'user_billing_address'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->with('orders');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class)->with('product');
    }

    public function order_statuses()
    {
        return $this->hasMany(OrderStatus::class)->with('status');
    }

    public function coupons()
    {
        return $this->hasMany(OrderCoupon::class);
    }

    public function shipping_zone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    public function user_shipping_address()
    {
        return $this->belongsTo(UserShippingAddress::class, 'user_shipping_address_id')->with('country');
    }

    public function user_billing_address()
    {
        return $this->belongsTo(UserShippingAddress::class, 'user_billing_address_id')->with('country');
    }
}
