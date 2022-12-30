<?php

namespace App\Helper;

use App\Models\Setting\Setting;

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
     * show currency sign
     */
    public static function currency()
    {
        return self::get("currency");
    }

    /**
     * show currency code
     */
    public static function currency_code()
    {
        return self::get("currency_code");
    }
}
