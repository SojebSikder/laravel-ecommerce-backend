<?php

namespace App\Models\Order\OrderDraft;

use App\Models\Product\Product;
use App\Models\Product\Variant\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDraftItem extends Model
{
    use HasFactory;

    public function getAttributeAttribute($value)
    {
        return json_decode($value);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}
