<?php

namespace App\Models\Product;

use App\Helper\SettingHelper;
use App\Models\Category\Category;
use App\Models\OptionSet\OptionSet;
use App\Models\Product\Variant\Variant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'cost_per_item',
        'quantity',
        'weight',
        'weight_unit',
        'track_quantity',
        'barcode',
        'sku',
        'status',
        'views',
        'created_at',
        'updated_at',
    ];

    protected $appends = ['is_variant', 'new_price', 'currency_sign', 'currency_code', 'availability', 'total_variant_quantity'];

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

    public function getIsVariantAttribute()
    {
        if ($this->variants->count() > 0) {
            return true;
        } else {
            return false;
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

    public function getTotalVariantQuantityAttribute()
    {
        $totalQuantity = 0;

        foreach ($this->variants as $variant) {
            if ($variant->quantity > 0) {
                $totalQuantity +=  $variant->quantity;
            }
        }

        return $totalQuantity;
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function option_sets()
    {
        return $this->belongsToMany(OptionSet::class, 'product_option_sets');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function details()
    {
        return $this->hasMany(ProductDetails::class)->orderBy('sort_order', 'asc');
    }
}
