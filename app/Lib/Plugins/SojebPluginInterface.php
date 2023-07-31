<?php

namespace App\Lib\Plugins;

interface SojebPluginInterface
{

    /**
     * Invoke when Init plugin
     */
    public static function onInit();

    /**
     * Invoke when Install plugin
     */
    public static function onInstall();
    /**
     * Invoke when Uninstall plugin
     */
    public static function onUninstall();

    /**
     * Invoke when Activate plugin
     */
    public static function onActivate();

    /**
     * Invoke when Deactivate plugin
     */
    public static function onDeactivate();
}
