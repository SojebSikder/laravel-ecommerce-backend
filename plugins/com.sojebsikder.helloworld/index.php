<?php

use App\Lib\Plugins\SojebPlugin;
use App\Lib\Plugins\SojebPluginInterface;
use Illuminate\Http\Request;
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
        view()->addNamespace('my_views', __DIR__ . '/views');

        Route::get('hello-world', function () {
            return "Hello World";
        });
        Route::get('hello', function () {
            return view('my_views::index');
        })->name('hello');
        Route::post('hello', function (Request $request) {
            $num1 = $request->input('num1');
            $num2 = $request->input('num2');
            $calc = $num1 + $num2;

            return back()->with('result', 'The sum is ' . $calc);
        })->name('hello');
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
