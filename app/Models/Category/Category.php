<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function sub_categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('status', 1);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->select('id', 'name');
    }
}
