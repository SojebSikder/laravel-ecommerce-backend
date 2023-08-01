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

    /**
     * Key value store
     */
    public function kv(): SojebKV
    {
        return new SojebKV(base_path('plugins/' . $this->package . '/' . $this->package . '.json'));
    }
}


/**
 * Key value store in json file
 * @example
 * $kv = new SojebKV(base_path('plugins/' . $this->package . '.json'));
 * $kv->set('title', 'Hello world');
 * echo $kv->get('title');
 */
class SojebKV
{
    private $file;
    private $data = [];

    public function __construct($file)
    {
        $this->file = $file;
        $this->load();
    }

    public function load()
    {
        if (file_exists($this->file)) {
            $this->data = json_decode(file_get_contents($this->file), true);
        }
    }

    public function save()
    {
        file_put_contents($this->file, json_encode($this->data));
    }

    public function get($key, $default = null)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return $default;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function delete($key)
    {
        unset($this->data[$key]);
        $this->save();
    }

    public function clear()
    {
        $this->data = [];
        $this->save();
    }

    public function getAll()
    {
        return $this->data;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
        $this->save();
    }
}
