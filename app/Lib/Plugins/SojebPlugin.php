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

    public $status = 0; // plugin status


    private $menus = [];
    private $routes = [];

    /// add menu
    public function addMenu($menu)
    {
        $this->menus[] = $menu;

        // sort menu
        usort($this->menus, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });
    }

    /// get menus
    public function getMenus()
    {
        return $this->menus;
    }

    // get menu by name
    public function getMenu($name)
    {
        foreach ($this->menus as $menu) {
            if ($menu['name'] == $name) {
                return $menu;
            }
        }
        return null;
    }

    // add route
    public function addRoute($route)
    {
        $this->routes[] = $route;
    }

    // get routes
    public function getRoutes()
    {
        return $this->routes;
    }
}
