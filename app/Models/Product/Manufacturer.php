<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Manufacturer extends Model
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
            return Storage::url(config('constants.uploads.manufacturer') . '/' . $this->image);
        } else {
            return null;
        }
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_manufacturers')
            ->with(['images'])
            ->where('status', 1);
    }
}
