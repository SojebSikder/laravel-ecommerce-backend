<?php

namespace App\Helper;

use App\Models\Setting\Setting\Setting;

/**
 * Setting Helper
 */
class SettingHelper
{
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
        return self::get("currency_sign");
    }

    /**
     * show currency code e.g. USD
     */
    public static function currency_code()
    {
        return self::get("currency_code");
    }
}
