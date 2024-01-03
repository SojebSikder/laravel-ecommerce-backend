<?php

namespace App\Models\OptionSet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSetElement extends Model
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

    public function getOptionValueAttribute($value)
    {
        return json_decode(json_decode($value));
    }

    public function getConditionAttribute($value)
    {
        return json_decode(json_decode($value));
    }

    public function items()
    {
        return $this->hasMany(OptionSetElementItem::class)->orderBy('sort_order', 'ASC');
    }
}
