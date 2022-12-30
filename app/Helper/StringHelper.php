<?php

namespace App\Helper;

/**
 * Image Helper
 */
class StringHelper
{
    /**
     * Text shorten
     */
    public static function textShorten($text, $limit = 400)
    {
        if (strlen($text) <= $limit) {
            return $text;
        }
        $text = $text . " ";
        $text = substr($text, 0, $limit);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text . ".....";
        return $text;
    }

    /**
     * return discounted price
     */
    public static function discount($price, $discount)
    {
        $result = 0;
        if ($discount == null) {
            $result = (float)$price;
        } else {
            $result = (float)$price - ((float)$price * (float)$discount) / 100;
        }
        return $result;
    }
}
