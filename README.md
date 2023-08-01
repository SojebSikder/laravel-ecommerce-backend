# jeckmarket-backend

Laravel E-commerce application backend with plugin architecture.

# Setup

Enable zip extension from php.ini

# Install

Install Laravel packages

```
composer install
```

# Running

```
php artisan serve
```

## Guide for plugin development

Create new plugin

-   Create a new directory under `plugins` directory. Directory name should be same as your `package` name. And `package` name should be unique.

    Example: plugins -> com.sojebsikder.demoplugin

-   Inside your plugin directory, create `index.php` file.

-   create class with convention like this: if your package name like this `com.sojebsikder.demoplugin` then your class name should be `Com_sojebsikder_demoplugin`
    just replace dot(.) with underscore(\_)
    Here is an example of simple plugin:

```php
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
            'name' => 'HelloWorld',
            'icon' => 'bi bi-layout-split',
            'route' => 'hello',
            'order' => 4,
            'parent' => 'sales',
        ];
        $this->addMenu($menu);
    }

    public function setupRoutes()
    {
        view()->addNamespace('my_views', __DIR__ . '/views');

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
```
