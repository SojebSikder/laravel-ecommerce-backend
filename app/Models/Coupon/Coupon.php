<?php

namespace App\Models\Coupon;

use App\Models\Category\Category;
use App\Models\Order\OrderCoupon;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'coupon_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'coupon_id');
    }

    public function getUsesAttribute()
    {
        $redeems = OrderCoupon::where('coupon_id', $this->id)->get()->count();
        return $redeems;
    }

    /**
     * Functions
     */
    // is coupon expired
    public function isExpired()
    {
        if ($this->expires_at != null) {
            if ($this->expires_at >= Carbon::now()->toDateString()) {
                // not expired
                return false;
            } else {
                // is expired
                return true;
            }
        } else {
            return false;
        }
    }
    // is coupon available
    public function isAvailable()
    {
        if ($this->starts_at != null) {
            if ($this->starts_at > Carbon::now()->toDateString()) {
                // not available
                return false;
            } else {
                // is available
                return true;
            }
        } else {
            return true;
        }
    }
}
