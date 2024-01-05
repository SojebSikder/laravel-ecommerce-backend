<?php

namespace App\Models\Order;

use App\Models\Shipping\ShippingZone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory;
    // LogsActivity;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()->logOnly(['*'])->logOnlyDirty();
    // }

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['order_statuses', 'order_items', 'coupons', 'shipping_zone', 'order_shipping_address', 'order_billing_address'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function order_shipping_address()
    {
        return $this->belongsTo(OrderShippingAddress::class, 'order_shipping_address_id')->with('country');
    }

    public function order_billing_address()
    {
        return $this->belongsTo(OrderShippingAddress::class, 'order_billing_address_id')->with('country');
    }
}
