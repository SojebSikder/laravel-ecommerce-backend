<?php

namespace App\Http\Controllers\Web\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Setting\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // search query
        $q = $request->input('q');

        $settings = Setting::query();

        if ($q) {
            $settings = $settings->orWhere('label', 'like', '%' . $q . '%')
                ->orWhere('key', 'like', '%' . $q . '%');
        }

        $settings = $settings->orderBy('key', 'asc')->paginate(15);

        return view('backend.setting.index', compact('settings'));
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
        $label = $request->input('label');
        $key = $request->input('key');
        $value = $request->input('value');
        $value_type = $request->input('value_type');
        $size = $request->input('size');
        $description = $request->input('description');

        $setting = new Setting();
        $setting->label = $label;
        $setting->key = $key;
        $setting->value = $value;
        $setting->value_type = $value_type;
        if ($setting->value_type == "file") {
            $setting->size = $size;
        }
        $setting->description = $description;

        $setting->save();

        return back()->with('success', 'Created successfully');
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
        $value = $request->input('value');

        $setting = Setting::findOrFail($id);
        $setting->value = $value;
        $setting->save();

        return back()->with('success', 'Settings updated');
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
