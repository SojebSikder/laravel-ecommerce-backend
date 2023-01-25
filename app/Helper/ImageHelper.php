<?php

namespace App\Helper;

use Intervention\Image\ImageManagerStatic as Image;

/**
 * Setting Helper
 */
class ImageHelper
{
    /**
     * Image helper make function
     */
    public static function make($data)
    {
        $image = Image::make($data);
        return $image;
    }
    /**
     * Resize image with given size
     */
    public static function resize($data, $width = null, $height = null)
    {
        $resizedimg = Image::make($data)->resize($width, $height);
        return $resizedimg;
    }
}
