<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'name',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }
}
