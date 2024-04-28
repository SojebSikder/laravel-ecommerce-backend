<?php

namespace App\Helper;

use App\Models\Setting\Currency\Currency;
use App\Models\Setting\General\GeneralSetting;
use App\Models\Setting\Setting\Setting;

/**
 * Setting Helper
 */
class SettingHelper
{
    /**
     * Get site name
     */
    public static function getSiteName($default = null)
    {
        $generalSetting = GeneralSetting::first();
        if ($generalSetting) {
            return $generalSetting->name;
        } else {
            return $default;
        }
    }


    /**
     * Get setting value
     */
    public static function get($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        if ($setting) {
            return $setting->value == null ? $default : $setting->value;
        } else {
            return $default;
        }
    }


    /**
     * show currency sign e.g. $
     */
    public static function currency_sign()
    {
        $currency = Currency::where('is_primary_store', 1)->first();
        if ($currency) {
            return $currency->currency_sign;
        } else {
            return self::get("currency_sign");
        }
    }

    /**
     * show currency code e.g. USD
     */
    public static function currency_code()
    {
        $currency = Currency::where('is_primary_store', 1)->first();
        if ($currency) {
            return $currency->currency_code;
        } else {
            return self::get("currency_code");
        }
    }
}
