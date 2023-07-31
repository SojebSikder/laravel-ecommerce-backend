<?php

namespace App\Lib\Plugins;

class SojebPluginManager
{
    // save plugin information
    public static function savePluginInfo($plugin, $data = [])
    {
        $pluginPath = base_path('plugins') . DIRECTORY_SEPARATOR . $plugin->package;
        $pluginInfoFile = $pluginPath . DIRECTORY_SEPARATOR . 'plugin.json';
        // if (file_exists($pluginInfoFile)) {
        // $pluginInfo = json_decode(file_get_contents($pluginInfoFile));
        $pluginInfo = new \stdClass();
        foreach ($data as $key => $value) {
            $pluginInfo->$key = $value;
        }
        file_put_contents($pluginInfoFile, json_encode($pluginInfo));
        // }
    }

    // get plugin information
    public static function getPluginInfo($package)
    {
        $pluginPath = base_path('plugins') . DIRECTORY_SEPARATOR . $package;
        $pluginInfoFile = $pluginPath . DIRECTORY_SEPARATOR . 'plugin.json';
        if (file_exists($pluginInfoFile)) {
            $pluginInfo = json_decode(file_get_contents($pluginInfoFile));
            return $pluginInfo;
        }
        return null;
    }

    // install plugin
    public static function installPlugin($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            $plugin->onInstall();

            // save plugin information
            $pluginInfo = [
                'status' => 1,
            ];
            self::savePluginInfo($plugin, $pluginInfo);
        }
    }

    // uninstall plugin
    public static function uninstallPlugin($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            $plugin->onUninstall();

            // save plugin information
            $pluginInfo = [
                'status' => 1,
            ];
            self::savePluginInfo($plugin, $pluginInfo);
        }
    }

    // check if plugin is installed
    public static function isPluginInstalled($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            // return $plugin->isInstalled();
            $pluginInfo = self::getPluginInfo($package);
            if ($pluginInfo) {
                return $pluginInfo->status == 1;
            }
        }
        return false;
    }

    // check if plugin is active
    public static function isPluginActive($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            // return $plugin->isActive();
            $pluginInfo = self::getPluginInfo($package);
            if ($pluginInfo) {
                return $pluginInfo->status == 1;
            }
        }
        return 0;
    }

    // activate plugin
    public static function activatePlugin($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            // return $plugin->activate();
            // $pluginInfo = self::getPluginInfo($package);
            // if ($pluginInfo) {
            //     $pluginInfo->status = 1;
            //     self::savePluginInfo($plugin, $pluginInfo);
            // }

            // save plugin information
            $pluginInfo = [
                'status' => 1,
            ];
            self::savePluginInfo($plugin, $pluginInfo);

            $plugin->onActivate();
        }
    }

    // deactivate plugin
    public static function deactivatePlugin($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            // return $plugin->deactivate();
            // $pluginInfo = self::getPluginInfo($package);
            // if ($pluginInfo) {
            //     $pluginInfo->status = 0;
            //     self::savePluginInfo($plugin, $pluginInfo);
            // }

            // save plugin information
            $pluginInfo = [
                'status' => 0,
            ];
            self::savePluginInfo($plugin, $pluginInfo);

            $plugin->onDeactivate();
        }
    }

    // get all plugins
    public static function getPlugins()
    {
        $plugins = [];
        $pluginPath = base_path('plugins');
        $pluginDirs = array_diff(scandir($pluginPath), ['.', '..']);
        foreach ($pluginDirs as $pluginDir) {
            $pluginFile = $pluginPath . DIRECTORY_SEPARATOR . $pluginDir . DIRECTORY_SEPARATOR . 'index.php';
            if (file_exists($pluginFile)) {
                require_once $pluginFile;
                // replace '.' with '' in plugin name
                $pluginDir = str_replace('.', '_', $pluginDir);

                $pluginClass = ucfirst($pluginDir) . '_plugin';
                $plugin = new $pluginClass();

                // get plugin information
                $pluginInfo = self::getPluginInfo($plugin->package);
                if ($pluginInfo) {
                    $plugin->status = $pluginInfo->status;
                }

                $plugins[] = $plugin;
            }
        }
        return $plugins;
    }

    // get plugin
    public static function getPlugin($package)
    {
        $plugins = self::getPlugins();
        foreach ($plugins as $plugin) {
            if ($plugin->package == $package) {
                return $plugin;
            }
        }
        return null;
    }

    // get plugin path
    public static function getPluginPath($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            return base_path('plugins') . DIRECTORY_SEPARATOR . $plugin->package;
        }
        return null;
    }

    // get plugin view path
    public static function getPluginViewPath($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            return base_path('plugins') . DIRECTORY_SEPARATOR . $plugin->package . DIRECTORY_SEPARATOR . 'views';
        }
        return null;
    }

    // get plugin public path
    public static function getPluginPublicPath($package)
    {
        $plugin = self::getPlugin($package);
        if ($plugin) {
            return base_path('plugins') . DIRECTORY_SEPARATOR . $plugin->package . DIRECTORY_SEPARATOR . 'public';
        }
        return null;
    }

    public static function initPlugin()
    {
        $plugins = self::getPlugins();

        foreach ($plugins as $plugin) {
            if ($plugin->status == 1) {
                $plugin->onInit();

                // add plugin menus
                // $menus = $plugin->getMenus();
                // $routes = $plugin->getRoutes();
            }
        }
    }

    public static function initRoutes()
    {
        $plugins = self::getPlugins();

        foreach ($plugins as $plugin) {
            if ($plugin->status == 1) {
                $plugin->setupRoutes();
            }
        }
    }
}
