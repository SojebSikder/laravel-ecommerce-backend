<?php

namespace App\Http\Controllers\Web\Admin\Plugin;

use App\Http\Controllers\Controller;
use App\Lib\Plugins\SojebPluginManager;
use Illuminate\Http\Request;

class PluginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plugins = SojebPluginManager::getPlugins();

        return view('backend.plugin.index', compact('plugins'));
    }

    public function install($package)
    {
        $plugin = SojebPluginManager::getPlugin($package);
        if ($plugin) {
            $plugin->install();
            return back()->with('success', 'Plugin installed successfully');
        }
        return back()->with('error', 'Plugin not found');
    }

    public function uninstall($package)
    {
        $plugin = SojebPluginManager::getPlugin($package);
        if ($plugin) {
            $plugin->uninstall();
            return back()->with('success', 'Plugin uninstalled successfully');
        }
        return back()->with('error', 'Plugin not found');
    }

    public function activate($package)
    {
        $plugin = SojebPluginManager::activatePlugin($package);
        if ($plugin) {
            return back()->with('success', 'Plugin activated successfully');
        }
        return back()->with('error', 'Plugin not found');
    }

    public function deactivate($package)
    {
        $plugin = SojebPluginManager::deactivatePlugin($package);

        if ($plugin) {
            return back()->with('success', 'Plugin deactivated successfully');
        }
        return back()->with('error', 'Plugin not found');
    }

    public function settings($package)
    {
        $plugin = SojebPluginManager::getPlugin($package);
        if ($plugin) {
            return view('backend.plugin.settings', compact('plugin'));
        }
        return back()->with('error', 'Plugin not found');
    }

    public function saveSettings(Request $request, $package)
    {
        $plugin = SojebPluginManager::getPlugin($package);
        if ($plugin) {
            $plugin->saveSettings($request);
            return back()->with('success', 'Settings saved successfully');
        }
        return back()->with('error', 'Plugin not found');
    }


    public function upload(Request $request)
    {
        try {
            $request->validate([
                'plugin' => 'required|mimes:zip'
            ]);

            $file = $request->file('plugin');

            $plugin = SojebPluginManager::uploadPlugin($file);

            return back()->with('success', 'Plugin uploaded successfully');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function status($id)
    {
        // $category = Category::find($id);
        // if ($category->status == 1) {
        //     $category->status = 0;
        //     $category->save();
        //     return back()->with('success', 'Disabled successfully');
        // } else {
        //     $category->status = 1;
        //     $category->save();
        //     return back()->with('success', 'Enabled successfully');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete plugin
        try {
            $plugin = SojebPluginManager::deletePlugin($id);
            return back()->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
