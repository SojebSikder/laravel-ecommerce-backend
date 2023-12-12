<?php

namespace App\Models\OptionSet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionSetElement extends Model
{
    use HasFactory;

    public function getOptionValueAttribute($value)
    {
        return json_decode(json_decode($value));
    }

    public function getConditionAttribute($value)
    {
        return json_decode(json_decode($value));
    }
}
