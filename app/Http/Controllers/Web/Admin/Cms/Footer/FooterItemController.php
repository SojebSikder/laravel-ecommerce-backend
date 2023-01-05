<?php

namespace App\Http\Controllers\Web\Admin\Cms\Footer;

use App\Http\Controllers\Controller;
use App\Models\Cms\Footer\FooterItem;
use Illuminate\Http\Request;

class FooterItemController extends Controller
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
            $footer_id = $request->input('footer_id');
            $footer_item_id = $request->input('footer_item_id');
            $name = $request->input('name');
            $link = $request->input('link');
            $sort_order = $request->input('sort_order');

            if ($footer_item_id != 'null') {
                // update
                $updateFooterItem = FooterItem::find($footer_item_id);
                $updateFooterItem->name = $name;
                $updateFooterItem->link = $link;
                $updateFooterItem->sort_order = $sort_order;
                $updateFooterItem->save();
            } else {
                // create new
                $footerItem = new FooterItem();
                $footerItem->footer_id = $footer_id;
                $footerItem->name = $name;
                $footerItem->link = $link;
                $footerItem->sort_order = $sort_order;
                $footerItem->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Created successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
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
        //
    }

    public function status($id)
    {
        $footerItem = FooterItem::find($id);
        if ($footerItem->status == 1) {
            $footerItem->status = 0;
            $footerItem->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $footerItem->status = 1;
            $footerItem->save();
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
            $footerItem = FooterItem::find($id);
            $footerItem->delete();

            return back()->with('success', 'Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('success', $th->getMessage());
        }
    }
}
