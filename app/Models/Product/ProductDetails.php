<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
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
}
