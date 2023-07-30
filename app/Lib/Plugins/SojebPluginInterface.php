<?php

namespace App\Lib\Plugins;

interface SojebPluginInterface
{
    /**
     * Invoke when Install plugin
     */
    public static function install();
    /**
     * Invoke when Uninstall plugin
     */
    public static function uninstall();
}
