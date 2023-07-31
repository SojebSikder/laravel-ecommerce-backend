<?php

namespace App\Lib\Plugins;

abstract class SojebPlugin
{
    /**
     * Plugin package name (Should be unique)
     */
    public $package = "com.sojebsikder.demo-plugin"; // plugin name
    public $name = "Demo plugin"; // plugin name
    public $description; // plugin description
    public $version = "1.0"; // plugin version
    public $author; // plugin author
    public $website; // plugin website
    public $copyRight; // plugin copy right
    public $license; // plugin license
    public $help; // plugin help
    public $icon; // plugin icon
    // temporary
    public $id = 1; // plugin id
    public $status = 0; // plugin status


    private static $menus = [];

    /// add menu
    public static function addMenu($menu)
    {
        self::$menus[] = $menu;

        // sort menu
        usort(self::$menus, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });
    }

    /// get menus
    public static function getMenus()
    {
        return self::$menus;
    }

    /// get menu by name
    public static function getMenu($name)
    {
        foreach (self::$menus as $menu) {
            if ($menu['name'] == $name) {
                return $menu;
            }
        }
        return null;
    }
}
