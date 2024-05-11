<?php

namespace App\Models\Shipping;

use App\Helper\SettingHelper;
use App\Models\Address\Country;
use App\Models\Payment\PaymentProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
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

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'shipping_zone_addresses');
    }

    public function payment_providers()
    {
        return $this->belongsToMany(PaymentProvider::class, 'shipping_zone_payment_providers');
    }
}
