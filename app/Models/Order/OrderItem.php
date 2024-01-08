<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use App\Models\Product\Variant\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function getAttributeAttribute($value)
    {
        return json_decode($value);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with('images');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id')->with('images');
    }
}
