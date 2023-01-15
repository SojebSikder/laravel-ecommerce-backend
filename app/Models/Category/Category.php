<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url(config('constants.uploads.category') . '/' . $this->image);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories')->where('status', 1);
    }

    public function sub_categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('status', 1);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->select('id', 'name');
    }
}
