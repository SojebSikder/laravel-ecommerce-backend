<?php

namespace App\Lib\Plugins;

interface SojebPluginInterface
{

    /**
     * Invoke when Init plugin, in sidebar menu
     */
    public function onInit();

    /**
     * Invoke when delete plugin
     */
    public function onDelete();

    /**
     * Invoke when Activate plugin
     */
    public function onActivate();

    /**
     * Invoke when Deactivate plugin
     */
    public function onDeactivate();

    /**
     * Setup routes
     * Invoke when plugin setup routes in web.php
     */
    public function setupRoutes();
}
