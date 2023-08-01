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
            'label' => 'Calculator (Plugin example)',
            'name' => 'Calculator',
            'icon' => 'bi bi-layout-split',
            'route' => 'calculator',
            'order' => 4,
            'parent' => 'sales',
        ];
        $this->addMenu($menu);
    }

    public function setupRoutes()
    {
        view()->addNamespace('my_views', __DIR__ . '/views');

        Route::get('calculator', function () {
            return view('my_views::index');
        })->name('calculator');

        Route::post('calculate', function (Request $request) {
            $num1 = $request->input('num1');
            $num2 = $request->input('num2');
            $calc = $num1 + $num2;

            return back()->with('result', 'The sum is ' . $calc);
        })->name('calculate');
    }

    public function onDelete()
    {
    }


    public function onActivate()
    {
    }

    public function onDeactivate()
    {
    }
}
