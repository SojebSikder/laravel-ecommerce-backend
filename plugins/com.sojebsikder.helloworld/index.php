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
            'name' => 'Hello World',
            'icon' => 'fa fa-home',
            'route' => 'hello-world',
            'order' => 1,
            'parent' => 'dashboard',
        ];
        $this->addMenu($menu);

        // add route
        $route = [
            'name' => 'hello-world',
            'route' => 'hello-world',
            'method' => 'get',
            'callback' => function () {
                return "Hello World";
                // return view('com.sojebsikder.helloworld::index');
            },
        ];
        $this->addRoute($route);
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
