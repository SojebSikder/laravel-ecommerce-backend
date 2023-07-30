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
        //
    }
}
