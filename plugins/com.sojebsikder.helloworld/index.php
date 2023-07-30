<?php

use App\Lib\Plugins\SojebPlugin;
use App\Lib\Plugins\SojebPluginInterface;

class ComsojebsikderHelloworld_Plugin extends SojebPlugin implements SojebPluginInterface
{
    public function __construct()
    {
        $this->package = "com.sojebsikder.helloworld";
        $this->name = "Hello World";
        $this->description = "Hello World Plugin";
        $this->version = "1.0";
    }

    public static function install()
    {
    }
    public static function uninstall()
    {
    }
}
