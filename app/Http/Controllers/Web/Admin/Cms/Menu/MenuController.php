<?php

namespace App\Http\Controllers\Web\Admin\Cms\Menu;

use App\Http\Controllers\Controller;
use App\Models\Cms\Menu\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        // search query
        $q = $request->input('q');

        $menus = Menu::query();

        if ($q) {
            $menus = $menus->orWhere('name', 'like', '%' . $q . '%');
        }

        $menus = $menus->with('sublinks')
            ->orderBy('sort_order', 'ASC')
            ->latest()
            ->paginate(15);
        return view('backend.cms.menu.index', compact('menus'));
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
            $name = $request->input('name');
            $sub_menu = $request->input('sub_menu') == 1 ? true : false;
            $link = $request->input('link');
            $is_link = $request->input('is_link') == 1 ? 1 : 0;
            $is_right = $request->input('is_right') == 1 ? 1 : 0;
            $head = $request->input('head');
            $text = $request->input('text');
            $right_link = $request->input('right_link');
            $file = $request->file('image');
            $style = $request->input('style');


            // insert into db
            $menu = new Menu();
            $menu->name = $name;
            $menu->sub_menu = $sub_menu;
            if ($is_link) {
                $menu->link = $link;
            } else {
                $menu->link = null;
            }
            $menu->is_link = $is_link;
            if ($is_right) {
                $menu->is_right = $is_right;
                $menu->head = $head;
                $menu->text = $text;
                if ($right_link != null) {
                    $menu->is_link = 1;
                }
                $menu->right_link = $right_link;

                // upload image
                if ($request->hasFile('image')) {
                    $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file_path = config('constants.uploads.menu') . "/" . $file_name;

                    // resize image
                    // $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 416, 236);
                    // Storage::put($file_path, (string) $resizedimg->encode());

                    $resizedimg = file_get_contents($file->getRealPath());
                    Storage::put($file_path, (string) $resizedimg);

                    $menu->image = $file_name;
                }
            }
            if ($style) {
                $menu->style = $style;
            }
            $menu->save();

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
        try {
            $name = $request->input('name');
            $sub_menu = $request->input('sub_menu') == 1 ? true : false;
            $link = $request->input('link');
            $is_link = $request->input('is_link') == 1 ? 1 : 0;
            $is_right = $request->input('is_right') == 1 ? 1 : 0;
            $head = $request->input('head');
            $text = $request->input('text');
            $right_link = $request->input('right_link');
            $file = $request->file('image');
            $style = $request->input('style');


            // insert into db
            $menu = Menu::where('id', $id)->first();
            $menu->name = $name;
            $menu->sub_menu = $sub_menu;
            if ($is_link) {
                $menu->link = $link;
            } else {
                $menu->link = null;
            }
            $menu->is_link = $is_link;
            if ($is_right) {
                $menu->is_right = $is_right;
                $menu->head = $head;
                $menu->text = $text;
                if ($right_link != null) {
                    $menu->is_link = 1;
                }
                $menu->right_link = $right_link;

                // upload image
                if ($request->hasFile('image')) {
                    // remove previous image first
                    if (Storage::exists(config('constants.uploads.menu') . "/" . $menu->image)) {
                        Storage::delete(config('constants.uploads.menu') . "/" . $menu->image);
                    }

                    $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file_path = config('constants.uploads.menu') . "/" . $file_name;

                    // resize image
                    // $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 416, 236);
                    // Storage::put($file_path, (string) $resizedimg->encode());

                    $resizedimg = file_get_contents($file->getRealPath());
                    Storage::put($file_path, (string) $resizedimg);

                    $menu->image = $file_name;
                }
            }
            if ($style) {
                $menu->style = $style;
            }
            $menu->save();

            return back()->with('success', 'Updated successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }

    public function status($id)
    {
        $menu = Menu::find($id);
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
        $menu = Menu::find($id);

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
            $menu = Menu::where('id', $id)->first();
            $menu->delete();

            return back()->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
