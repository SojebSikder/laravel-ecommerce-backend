<?php

namespace App\Models\OptionSet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSet extends Model
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

    public function elements()
    {
        return $this->hasMany(OptionSetElement::class)->orderBy('sort_order', 'ASC');
    }
}
