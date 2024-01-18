<?php

namespace App\Models\Product\Variant;

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

    public function images()
    {
        return $this->hasMany(VariantImage::class);
    }

    public function variant_attributes()
    {
        return $this->hasMany(VariantAttribute::class, 'variant_id', 'id');
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
