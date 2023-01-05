<?php

namespace App\Models\Cms\Footer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['items'];

    public function items()
    {
        return $this->hasMany(FooterItem::class)->orderBy('sort_order', 'asc');
    }
}
