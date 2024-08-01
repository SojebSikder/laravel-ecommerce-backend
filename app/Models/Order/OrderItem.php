<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use App\Models\Product\Variant\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $appends = ['total_price'];

    public function getAttributeAttribute($value)
    {
        return json_decode($value);
    }

    // public function getTotalPriceAttribute()
    // {
    //     // if ($this->product->is_sale == 1) {
    //     //     $total -= $this->discount;
    //     // }


    //     $total = $this->price * $this->quantity;
        
    //     return number_format($total, 2);
    // }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with('images');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id')->with('images');
    }
}
