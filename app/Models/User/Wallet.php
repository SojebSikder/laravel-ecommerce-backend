<?php

namespace App\Models\User;

use App\Helper\SettingHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $appends = ['currency_sign', 'currency_code'];

    // custom currency attribute
    public function getCurrencySignAttribute()
    {
        return SettingHelper::currency_sign();
    }

    // custom currency code attribute
    public function getCurrencyCodeAttribute()
    {
        return SettingHelper::currency_code();
    }
}
