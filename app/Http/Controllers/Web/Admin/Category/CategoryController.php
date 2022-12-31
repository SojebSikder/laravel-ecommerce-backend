<?php

namespace App\Http\Controllers\Web\Admin\Category;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('sub_categories')->whereNull('parent_id')->latest()->get();
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
        $parent_category_id = $request->input('parent_category_id');

        $category = new Category();
        $category->name = $name;
        $category->slug = Str::slug($name);
        if ($parent_category_id) {
            $category->parent_id = $parent_category_id;
        }
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
        $category = Category::with('sub_categories')->where('id', $id)->latest()->first();
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
            // TODO delete child category too
            $category = Category::find($id);
            $category->delete();
            return back()->with('success', 'Category deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
