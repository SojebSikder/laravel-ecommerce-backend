<?php

namespace App\Http\Controllers\Web\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\General\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get general settings
        $generalSetting = GeneralSetting::firstOrCreate();

        return view('backend.setting.general.edit', compact('generalSetting'));
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
        $name = $request->input('name');

        $meta_title = $request->input('meta_title');
        $meta_description = $request->input('meta_description');
        $meta_keyword = $request->input('meta_keyword');

        $robots_txt = $request->input('robots_txt');
        $file = $request->file('logo');

        $generalSetting = GeneralSetting::findOrFail($id);
        $generalSetting->name = $name;

        $generalSetting->meta_title = $meta_title;
        $generalSetting->meta_description = $meta_description;
        $generalSetting->meta_keyword = $meta_keyword;

        $generalSetting->robots_txt = $robots_txt;

        if ($request->hasFile('logo')) {

            $file_name = time() . '-' . uniqid() . '.' . $file->extension();
            $file_path = config('constants.uploads.site') . "/" . $file_name;

            // resize image
            // $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 416, 236);
            // Storage::put($file_path, (string) $resizedimg->encode());

            $resizedimg = file_get_contents($file->getRealPath());
            Storage::put($file_path, (string) $resizedimg);

            $generalSetting->logo = $file_name;
        }

        $generalSetting->save();

        return back()->with('success', 'Updated successfully');
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
