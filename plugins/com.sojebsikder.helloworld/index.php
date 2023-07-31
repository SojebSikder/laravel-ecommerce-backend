<?php

use App\Lib\Plugins\SojebPlugin;
use App\Lib\Plugins\SojebPluginInterface;
use Illuminate\Support\Facades\Route;

class Com_sojebsikder_helloworld_plugin extends SojebPlugin implements SojebPluginInterface
{
    public function __construct()
    {
        $this->package = "com.sojebsikder.helloworld";
        $this->name = "Hello World";
        $this->description = "Hello World Plugin";
        $this->version = "1.0";
    }

    public function onInit()
    {
        // add menu
        $menu = [
            'name' => 'HelloWorld',
            'icon' => 'fa fa-home',
            'route' => 'hello-world',
            'order' => 1,
            'parent' => 'Sales',
        ];
        $this->addMenu($menu);
    }

    public function setupRoutes()
    {
        Route::get('hello-world', function () {
            return "Hello World";
        });
    }

    public function onInstall()
    {
    }

    public function onUninstall()
    {
    }

    public function onActivate()
    {
    }

    public function onDeactivate()
    {
    }
}
