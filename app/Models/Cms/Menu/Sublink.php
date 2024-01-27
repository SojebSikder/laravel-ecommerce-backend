<?php

namespace App\Models\Cms\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sublink extends Model
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
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url(config('constants.uploads.sublink') . '/' . $this->image);
    }

    public function sublinks()
    {
        return $this->hasMany(Sublink::class, 'parent_id')->orderBy('sort_order', 'ASC');
    }

    public function parent()
    {
        return $this->belongsTo(Sublink::class, 'parent_id');
    }
}
