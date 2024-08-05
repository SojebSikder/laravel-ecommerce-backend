<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url(config('constants.uploads.category') . '/' . $this->image);
        } else {
            return null;
        }
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories')
            ->with(['images'])
            ->where('status', 1);
    }
    public function products_with_limit()
    {
        return $this->belongsToMany(Product::class, 'product_categories')
            ->with(['images'])
            ->where('status', 1)->limit(10);
    }

    public function sub_categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('status', 1)->with('sub_categories');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->select('id', 'name');
    }
}
