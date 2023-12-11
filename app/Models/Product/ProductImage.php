<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'status',
    ];


    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url(config('constants.uploads.product') . '/' . $this->image);
    }
}
