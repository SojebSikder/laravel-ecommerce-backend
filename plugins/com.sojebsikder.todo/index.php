<?php

use App\Lib\Plugins\SojebPlugin;
use App\Lib\Plugins\SojebPluginInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Com_sojebsikder_todo_plugin extends SojebPlugin implements SojebPluginInterface
{
    public function __construct()
    {
        $this->package = "com.sojebsikder.todo";
        $this->name = "Todo";
        $this->description = "Manage your todo list";
        $this->version = "1.0";
    }

    public function onInit()
    {
        // add menu
        $menu = [
            'label' => 'Todo',
            'name' => 'todo',
            'icon' => 'bi bi-layout-split',
            'route' => 'todo',
            'order' => 1,
            'parent' => 'cms',
        ];
        $this->addMenu($menu);
    }

    public function setupRoutes()
    {
        view()->addNamespace('todo_views', __DIR__ . '/views');

        Route::get('todo', function () {
            $todos = $this->kv()->getAll();

            return view('todo_views::index', compact('todos'));
        })->name('todo');

        Route::post('todo', function (Request $request) {
            $title = $request->input('title');
            $content = $request->input('content');


            $id = uniqid();

            $data = new stdClass();
            $data->id = $id;
            $data->title = $title;
            $data->content = $content;

            $this->kv()->set($id, $data);

            return back()->with('success', 'Todo saved successfully');
        })->name('todo.store');

        Route::get('todo/{id}', function ($id) {
            $this->kv()->delete($id);

            return back()->with('success', 'Todo deleted successfully');
        })->name('todo.destroy');
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
