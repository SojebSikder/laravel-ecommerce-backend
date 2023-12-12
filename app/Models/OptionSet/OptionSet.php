<?php

namespace App\Models\OptionSet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSet extends Model
{
    use HasFactory;

    public function elements()
    {
        return $this->hasMany(OptionSetElement::class)->orderBy('sort_order', 'ASC');
    }
}
