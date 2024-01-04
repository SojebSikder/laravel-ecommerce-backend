<?php

namespace App\Models\Setting\General;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GeneralSetting extends Model
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

    protected $appends = ['logo_url'];

    public function getImageUrlAttribute()
    {
        return Storage::url(config('constants.uploads.site') . '/' . $this->logo);
    }
}
