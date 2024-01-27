<?php

namespace App\Models\Product\Variant;

use App\Helper\SettingHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['new_price', 'currency_sign', 'currency_code', 'availability'];

    public function images()
    {
        return $this->hasMany(VariantImage::class);
    }

    public function variant_attributes()
    {
        return $this->hasMany(VariantAttribute::class, 'variant_id', 'id');
    }

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

    public function getNewPriceAttribute()
    {
        if ($this->is_sale) {
            $newPrice = 0;

            $newPrice = $this->price - ($this->price * $this->discount / 100);

            return $newPrice;
        } else {
            return $this->price;
        }
    }

    // custom availability attribute
    public function getAvailabilityAttribute()
    {
        $outOfStock = 'out of stock';
        $inStock = 'in stock';
        $totalQuantity = 0;

        if ($this->quantity > 0) {
            $totalQuantity +=  $this->quantity;
        }

        if ($totalQuantity > 0) {
            return $inStock;
        } else {
            return $outOfStock;
        }
    }

    // public function attributes()
    // {
    //     return $this->belongsToMany(Attribute::class, 'variant_attributes');
    // }

    // public function attribute_values()
    // {
    //     return $this->belongsToMany(AttributeValue::class, 'variant_attribute_values');
    // }
}
