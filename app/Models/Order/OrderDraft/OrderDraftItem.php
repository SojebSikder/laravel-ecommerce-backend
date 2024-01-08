<?php

namespace App\Models\Order\OrderDraft;

use App\Helper\StringHelper;
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

    public function getSubtotalAttribute()
    {
        if ($this->variant_id) {
            if ($this->product->option_set) {
                // if option set available
                $attributeData = collect($this->attribute)->toArray();
                $elementData = collect($this->product->option_set->elements)->toArray();

                // get option set price
                $totalMap = array_map(function ($attribute) use ($elementData) {
                    $optionSetPrice = array_map(function ($element) use ($attribute) {
                        if ($element['type'] == "select") {
                            $elementPrice = array_map(function ($optionValue) use ($attribute) {
                                if ($optionValue->value == $attribute->value) {
                                    return (float)$optionValue->price ?? 0.0;
                                }
                            }, $element['option_value']);

                            $elementPriceReduce =  array_reduce($elementPrice, function ($curr, $prev) {
                                return $curr + $prev;
                            }, 0.0);
                            return $elementPriceReduce;
                        }
                    }, $elementData);

                    $optionSetPriceReduce = array_reduce($optionSetPrice, function ($curr, $prev) {
                        return $curr + $prev;
                    });

                    return $optionSetPriceReduce;
                }, $attributeData);

                $totalOptionSetPrice = array_reduce($totalMap, function ($prev, $curr) {
                    return $prev + $curr;
                }, 0.0);

                $total = $totalOptionSetPrice;
                if ($this->variant->is_sale == 1) {
                    $total += StringHelper::discount($this->variant->price, $this->variant->discount);
                } else {
                    $total += $this->variant->price;
                }
            } else {
                // if not option set is available
                $total = 0.0;
                if ($this->variant->is_sale == 1) {
                    $total += StringHelper::discount($this->variant->price, $this->variant->discount);
                } else {
                    $total += $this->variant->price;
                }
            }
            return $total;
        } else {
            if ($this->product->option_set) {
                // if option set available
                $attributeData = collect($this->attribute)->toArray();
                $elementData = collect($this->product->option_set->elements)->toArray();

                // get option set price
                $totalMap = array_map(function ($attribute) use ($elementData) {
                    $optionSetPrice = array_map(function ($element) use ($attribute) {
                        if ($element['type'] == "select") {
                            $elementPrice = array_map(function ($optionValue) use ($attribute) {
                                if ($optionValue->value == $attribute->value) {
                                    return (float)$optionValue->price ?? 0.0;
                                }
                            }, $element['option_value']);

                            $elementPriceReduce =  array_reduce($elementPrice, function ($curr, $prev) {
                                return $curr + $prev;
                            }, 0.0);
                            return $elementPriceReduce;
                        }
                    }, $elementData);

                    $optionSetPriceReduce = array_reduce($optionSetPrice, function ($curr, $prev) {
                        return $curr + $prev;
                    });

                    return $optionSetPriceReduce;
                }, $attributeData);

                $totalOptionSetPrice = array_reduce($totalMap, function ($prev, $curr) {
                    return $prev + $curr;
                }, 0.0);

                $total = $totalOptionSetPrice;
                if ($this->product->is_sale == 1) {
                    $total += StringHelper::discount($this->product->price, $this->product->discount);
                } else {
                    $total += $this->product->price;
                }
            } else {
                // if not option set is available
                $total = 0.0;
                if ($this->product->is_sale == 1) {
                    $total += StringHelper::discount($this->product->price, $this->product->discount);
                } else {
                    $total += $this->product->price;
                }
            }
            return $total;
        }
    }
}
