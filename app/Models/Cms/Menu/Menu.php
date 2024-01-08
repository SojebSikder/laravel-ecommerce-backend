<?php

namespace App\Models\Cms\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url(config('constants.uploads.menu') . '/' . $this->image);
    }

    public function sublinks()
    {
        return $this->hasMany(Sublink::class)->with('sublinks')->orderBy('sort_order', 'ASC');
    }
}
