<?php

namespace App\Models\Product;

use App\Helper\SettingHelper;
use App\Models\Category\Category;
use App\Models\OptionSet\OptionSet;
use App\Models\Product\Variant\Variant;
use App\Models\Review\Review;
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
        // 'weight',
        // 'weight_unit',
        'track_quantity',
        'barcode',
        'sku',
        'status',
        'views',
        'created_at',
        'updated_at',
        'user_id',
    ];

    protected $appends = [
        'is_variant', 'new_price', 'currency_sign',
        'currency_code', 'availability', 'total_variant_quantity',
        'rating_meta'
    ];

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

    // custom rating meta attribute
    public function getRatingMetaAttribute()
    {
        $oneStar = 0;
        $twoStar = 0;
        $threeStar = 0;
        $fourStar = 0;
        $fiveStar = 0;

        $reviews = $this->reviews;
        $reviews_count = count($this->reviews);
        foreach ($reviews as $review) {
            if ($review->rating_value == 1) {
                $oneStar += 1;
            }
            if ($review->rating_value == 2) {
                $twoStar += 1;
            }
            if ($review->rating_value == 3) {
                $threeStar += 1;
            }
            if ($review->rating_value == 4) {
                $fourStar += 1;
            }
            if ($review->rating_value == 5) {
                $fiveStar += 1;
            }
        }
        /*
        *calculate avarage rating value
        * AR = 1*a+2*b+3*c+4*d+5*e/(R)
        * Where AR is the average rating
        * a is the number of 1-star ratings
        * b is the number of 2-star ratings
        * c is the number of 3-star ratings
        * d is the number of 4-star ratings
        * e is the number of 5-star ratings
        * R is the total number of ratings
        */
        // total number of rating
        $total_rating = $oneStar + $twoStar + $threeStar + $fourStar + $fiveStar;

        // avarage value of rating
        $avg_value = $total_rating == 0 ? 0 : ((1 * $oneStar + 2 * $twoStar + 3 * $threeStar + 4 * $fourStar + 5 * $fiveStar) / $total_rating);

        return [
            'value' => number_format($avg_value, 1, '.', ''),
            'reviews' => $reviews_count,
            'stars' => [
                'one' => $oneStar,
                'two' => $twoStar,
                'three' => $threeStar,
                'four' => $fourStar,
                'five' => $fiveStar,
            ],
        ];
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

    public function manufacturers()
    {
        return $this->belongsToMany(Manufacturer::class, 'product_manufacturers');
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
