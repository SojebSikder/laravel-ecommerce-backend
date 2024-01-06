<?php

namespace App\Models\Product\Variant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    use HasFactory;

    public function attribute()
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function attribute_value()
    {
        return $this->hasOne(AttributeValue::class, 'id', 'attribute_value_id');
    }
}
