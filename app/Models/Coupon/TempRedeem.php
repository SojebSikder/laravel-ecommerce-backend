<?php

namespace App\Models\Coupon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempRedeem extends Model
{
    use HasFactory;

     /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['coupon'];


    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
