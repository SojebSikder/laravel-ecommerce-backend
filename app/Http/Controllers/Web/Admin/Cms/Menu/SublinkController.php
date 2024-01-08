<?php

namespace App\Http\Controllers\Web\Admin\Cms\Menu;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Cms\Menu\Menu;
use App\Models\Cms\Menu\Sublink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SublinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try {
            $sort_order = $request->input('sort_order');
            $menu_id = $request->input('menu_id');
            $category_id = $request->input('category_id');
            $name = $request->input('name');
            $description = $request->input('description');
            $file = $request->file('image');
            $parent_id = $request->input('parent_id');
            $head = $request->input('head');
            $is_link = $request->input('is_link') == 1 ? 1 : 0;
            $link = $request->input('link');
            /**
             * SEO
             */
            $meta_title = $request->input('meta_title');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            //

            // insert into db
            $sublink = new Sublink();
            $sublink->sort_order = $sort_order;
            if ($parent_id == 0) { // it will create submenu
                $sublink->menu_id = $menu_id;
                $sublink->head = $head;
            } else {
                $sublink->parent_id = $parent_id;
                $sublink->name = $name;
                $sublink->description = $description;
                // add image
                if ($request->hasFile('image')) {
                    $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file_path = config('constants.uploads.sublink') . "/" . $file_name;

                    // resize image
                    // $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 1296, 410);
                    // Storage::put($file_path, (string) $resizedimg->encode());

                    $resizedimg = file_get_contents($file->getRealPath());
                    Storage::put($file_path, (string) $resizedimg);

                    $sublink->image = $file_name;
                }
            }
            if ($is_link) {
                $sublink->is_link = $is_link;
                $sublink->link = $link;
            } else {
                if ($category_id == 0) {
                    $sublink->category_id = null;
                } else {
                    $sublink->category_id = $category_id;
                }
            }
            $sublink->meta_title = $meta_title;
            $sublink->meta_description = $meta_description;
            $sublink->meta_keyword = $meta_keyword;
            $sublink->save();

            return back()->with('success', 'Created successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = Category::where('parent_id', null)->get();
        $menu = Menu::findOrFail($id);

        $sublinks = Sublink::with('sublinks')
            ->where('menu_id', $id)
            ->where('parent_id', null)
            ->orderBy('sort_order', 'ASC')
            ->paginate(15);
        // ->get();
        $parent_sublinks = $sublinks;

        return view('backend.cms.menu.sublink.index', compact('menu', 'sublinks', 'parent_sublinks', 'categories'));
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
        try {
            $sort_order = $request->input('sort_order');
            $menu_id = $request->input('menu_id');
            $category_id = $request->input('category_id');
            $name = $request->input('name');
            $description = $request->input('description');
            $file = $request->file('image');
            $parent_id = $request->input('parent_id');
            $head = $request->input('head');
            $is_link = $request->input('is_link') == 1 ? 1 : 0;
            $link = $request->input('link');
            /**
             * SEO
             */
            $meta_title = $request->input('meta_title');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            //

            // update
            $sublink = Sublink::where('id', $id)->first();
            $sublink->sort_order = $sort_order;
            if ($parent_id == 0) { // it will create submenu
                $sublink->menu_id = $menu_id;
                $sublink->head = $head;
            } else {
                $sublink->parent_id = $parent_id;
                // $sublink->name = $name;
            }
            $sublink->name = $name;
            $sublink->description = $description;

            // update image
            if ($request->hasFile('image')) {
                // remove previous image first
                if (Storage::exists(config('constants.uploads.sublink') . "/" . $sublink->image)) {
                    Storage::delete(config('constants.uploads.sublink') . "/" . $sublink->image);
                }
                // insert new image
                $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file_path = config('constants.uploads.sublink') . "/" . $file_name;

                // resize image
                // $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 1296, 410);
                // Storage::put($file_path, (string) $resizedimg->encode());

                $resizedimg = file_get_contents($file->getRealPath());
                Storage::put($file_path, (string) $resizedimg);

                $sublink->image = $file_name;
            }

            if ($is_link) {
                $sublink->is_link = $is_link;
                $sublink->link = $link;
            } else {
                if ($category_id == 0) {
                    $sublink->category_id = null;
                } else {
                    $sublink->category_id = $category_id;
                    $sublink->is_link = 0;
                    $sublink->link = null;
                }
            }
            $sublink->meta_title = $meta_title;
            $sublink->meta_description = $meta_description;
            $sublink->meta_keyword = $meta_keyword;
            $sublink->save();

            return back()->with('success', 'Updated successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }

    public function status($id)
    {
        $menu = Sublink::find($id);
        if ($menu->status == '1') {
            $menu->status = 0;
            $menu->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $menu->status = 1;
            $menu->save();
            return back()->with('success', 'Enabled successfully');
        }
    }

    public function sortingOrder(Request $request, $id)
    {
        $sortValue = $request->input('sort');
        $menu = Sublink::find($id);

        $menu->sort_order = $sortValue;
        $menu->save();
        return back()->with('success', 'Sorted successfully');
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
            // remove child first
            $childlink = Sublink::where('parent_id', $id);
            if ($childlink) {
                $childlink->delete();
            }

            // then remove main sublink
            $sublink = Sublink::where('id', $id)->first();
            $sublink->delete();

            return back()->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
