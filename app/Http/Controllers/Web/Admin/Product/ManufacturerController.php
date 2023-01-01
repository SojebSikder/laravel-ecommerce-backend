<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManufacturerController extends Controller
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

        $manufacturers = Manufacturer::query();

        if ($q) {
            $manufacturers = $manufacturers->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $manufacturers = $manufacturers->latest()->paginate(15);
        return view('backend.manufacturer.index', compact('manufacturers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_categories = Manufacturer::latest()->get();
        return view('backend.manufacturer.create', compact('parent_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories'
        ]);

        $name = $request->input('name');
        $slug = $request->input('slug');
        $file = $request->file('image');
        $description = $request->input('description');
        $meta_title = $request->input('meta_title');
        $meta_description = $request->input('meta_description');
        $meta_keyword = $request->input('meta_keyword');
        $status = $request->input('status') == 1 ? 1 : 0;

        $manufacturer = new Manufacturer();
        $manufacturer->name = $name;
        $manufacturer->slug = Str::slug($slug);

        if ($description) {
            $manufacturer->description = $description;
        }

        if ($request->hasFile('image')) {

            $file_name = time() . '-' . uniqid() . '.' . $file->extension();
            $file_path = config('constants.uploads.category') . "/" . $file_name;

            // resize image
            // $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 416, 236);
            // Storage::put($file_path, (string) $resizedimg->encode());

            $resizedimg = file_get_contents($file->getRealPath());
            Storage::put($file_path, (string) $resizedimg);

            $manufacturer->image = $file_name;
        }
        if ($meta_title) {
            $manufacturer->meta_title = $meta_title;
        }
        if ($meta_description) {
            $manufacturer->meta_description = $meta_description;
        }
        if ($meta_keyword) {
            $manufacturer->meta_keyword = $meta_keyword;
        }
        $manufacturer->status = $status;

        $manufacturer->save();

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
        $manufacturer = Manufacturer::findOrFail($id);
        return view('backend.manufacturer.edit', compact('manufacturer'));
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
        $request->validate([
            'name' => 'required'
        ]);

        $name = $request->input('name');
        $slug = $request->input('slug');
        $file = $request->file('image');
        $description = $request->input('description');
        $meta_title = $request->input('meta_title');
        $meta_description = $request->input('meta_description');
        $meta_keyword = $request->input('meta_keyword');
        $status = $request->input('status') == 1 ? 1 : 0;

        $manufacturer = Manufacturer::findOrFail($id);
        $manufacturer->name = $name;
        $manufacturer->slug = Str::slug($slug);
        if ($description) {
            $manufacturer->description = $description;
        }

        if ($request->hasFile('image')) {
            // remove previous image first
            if (Storage::exists(config('constants.uploads.manufacturer') . "/" . $manufacturer->image)) {
                Storage::delete(config('constants.uploads.manufacturer') . "/" . $manufacturer->image);
            }

            $file_name = time() . '-' . uniqid() . '.' . $file->extension();
            $file_path = config('constants.uploads.manufacturer') . "/" . $file_name;

            $resizedimg = file_get_contents($file->getRealPath());
            Storage::put($file_path, (string) $resizedimg);

            $manufacturer->image = $file_name;
        }
        if ($meta_title) {
            $manufacturer->meta_title = $meta_title;
        } else {
            $manufacturer->meta_title = null;
        }
        if ($meta_description) {
            $manufacturer->meta_description = $meta_description;
        } else {
            $manufacturer->meta_description = null;
        }
        if ($meta_keyword) {
            $manufacturer->meta_keyword = $meta_keyword;
        } else {
            $manufacturer->meta_keyword = null;
        }
        $manufacturer->status = $status;

        $manufacturer->save();

        return back()->with('success', 'Updated successfully');
    }

    public function status($id)
    {
        $manufacturer = Manufacturer::find($id);
        if ($manufacturer->status == '1') {
            $manufacturer->status = 0;
            $manufacturer->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $manufacturer->status = 1;
            $manufacturer->save();
            return back()->with('success', 'Enabled successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $manufacturer = Manufacturer::find($id);
            // remove image
            if (Storage::exists(config('constants.uploads.manufacturer') . "/" . $manufacturer->image)) {
                Storage::delete(config('constants.uploads.manufacturer') . "/" . $manufacturer->image);
            }
            $manufacturer->delete();
            return back()->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
