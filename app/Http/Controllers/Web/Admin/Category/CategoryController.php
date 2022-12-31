<?php

namespace App\Http\Controllers\Web\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
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

        $categories = Category::query();

        if ($q) {
            $categories = $categories->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $categories = $categories->with('sub_categories')
            ->whereNull('parent_id')
            ->latest()
            ->paginate(15);
        return view('backend.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_categories = Category::latest()->get();
        return view('backend.category.create', compact('parent_categories'));
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
        $parent_category_id = $request->input('parent_category_id');
        $file = $request->file('image');
        $description = $request->input('description');
        $meta_title = $request->input('meta_title');
        $meta_description = $request->input('meta_description');
        $meta_keyword = $request->input('meta_keyword');
        $status = $request->input('status') == 1 ? 1 : 0;

        $category = new Category();
        $category->name = $name;
        // $category->slug = Str::slug($name);
        $category->slug = Str::slug($slug);
        if ($parent_category_id) {
            $category->parent_id = $parent_category_id;
        }
        if ($description) {
            $category->description = $description;
        }

        if ($request->hasFile('image')) {
            // $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file_name = time() . '-' . uniqid() . '.' . $file->extension();
            $file_path = config('constants.uploads.category') . "/" . $file_name;

            // resize image
            // $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 416, 236);
            // Storage::put($file_path, (string) $resizedimg->encode());

            $resizedimg = file_get_contents($file->getRealPath());
            Storage::put($file_path, (string) $resizedimg);

            $category->image = $file_name;
        }
        if ($meta_title) {
            $category->meta_title = $meta_title;
        }
        if ($meta_description) {
            $category->meta_description = $meta_description;
        }
        if ($meta_keyword) {
            $category->meta_keyword = $meta_keyword;
        }
        $category->status = $status;

        $category->save();

        return back()->with('success', 'Category created successfully');
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function subcategory($id)
    {
        $category = Category::with(['sub_categories' => function ($query) {
            $query->orWhere('status', 0)->orWhere('status', 1);
        }])->where('id', $id)->latest()->first();
        return view('backend.category.subcategory.index', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $parent_categories = Category::where('id', '!=', $id)->latest()->get();
        $category = Category::findOrFail($id);
        return view('backend.category.edit', compact('category', 'parent_categories'));
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
        $parent_category_id = $request->input('parent_category_id');
        $file = $request->file('image');
        $description = $request->input('description');
        $meta_title = $request->input('meta_title');
        $meta_description = $request->input('meta_description');
        $meta_keyword = $request->input('meta_keyword');
        $status = $request->input('status') == 1 ? 1 : 0;

        $category = Category::findOrFail($id);
        $category->name = $name;
        $category->slug = Str::slug($slug);
        if ($parent_category_id) {
            $category->parent_id = $parent_category_id;
        }
        if ($description) {
            $category->description = $description;
        }

        if ($request->hasFile('image')) {
            // remove previous image first
            if (Storage::exists(config('constants.uploads.category') . "/" . $category->image)) {
                Storage::delete(config('constants.uploads.category') . "/" . $category->image);
            }

            $file_name = time() . '-' . uniqid() . '.' . $file->extension();
            $file_path = config('constants.uploads.category') . "/" . $file_name;

            $resizedimg = file_get_contents($file->getRealPath());
            Storage::put($file_path, (string) $resizedimg);

            $category->image = $file_name;
        }
        if ($meta_title) {
            $category->meta_title = $meta_title;
        } else {
            $category->meta_title = null;
        }
        if ($meta_description) {
            $category->meta_description = $meta_description;
        } else {
            $category->meta_description = null;
        }
        if ($meta_keyword) {
            $category->meta_keyword = $meta_keyword;
        } else {
            $category->meta_keyword = null;
        }
        $category->status = $status;

        $category->save();

        return back()->with('success', 'Category updated successfully');
    }

    public function status($id)
    {
        $category = Category::find($id);
        if ($category->status == '1') {
            $category->status = 0;
            $category->save();
            return back()->with('success', 'Category disabled');
        } else {
            $category->status = 1;
            $category->save();
            return back()->with('success', 'Category enabled');
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
            // remove subcategory first
            $subcategories = Category::where('parent_id', $id)->get();
            foreach ($subcategories as $subcategory) {
                if ($subcategory) {
                    // remove image
                    if (Storage::exists(config('constants.uploads.category') . "/" . $subcategory->image)) {
                        Storage::delete(config('constants.uploads.category') . "/" . $subcategory->image);
                    }
                    $subcategory->delete();
                }
            }

            // then remove main category
            $category = Category::find($id);
            // remove image
            if (Storage::exists(config('constants.uploads.category') . "/" . $category->image)) {
                Storage::delete(config('constants.uploads.category') . "/" . $category->image);
            }
            $category->delete();
            return back()->with('success', 'Category deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
