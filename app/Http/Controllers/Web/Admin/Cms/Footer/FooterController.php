<?php

namespace App\Http\Controllers\Web\Admin\Cms\Footer;

use App\Http\Controllers\Controller;
use App\Models\Cms\Footer\Footer;
use App\Models\Cms\Footer\FooterItem;
use Illuminate\Http\Request;

class FooterController extends Controller
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

        $footers = Footer::query();

        if ($q) {
            $footers = $footers->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $footers = $footers->with('items')
            ->orderBy('sort_order', 'asc')
            ->paginate(15);
        return view('backend.cms.footer.index', compact('footers'));
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
        $name = $request->input('name');
        $status = $request->input('status') == 1 ? 1 : 0;
        $sort_order = $request->input('sort_order');

        $footer = new Footer();
        $footer->name = $name;
        $footer->status = $status;
        $footer->sort_order = $sort_order;
        $footer->save();

        // return back()->with('success', 'Created Successfully');
        return redirect("/footer/$footer->id/edit")->with('success', 'Created successfully');
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
        $footer = Footer::find($id);
        $footerItems = FooterItem::where('footer_id', $id)->get();
        return view('backend.cms.footer.edit', compact('footer', 'footerItems'));
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
        $status = $request->input('status') == 1 ? 1 : 0;
        $sort_order = $request->input('sort_order');

        $footer = Footer::find($id);
        $footer->name = $name;
        $footer->status = $status;
        $footer->sort_order = $sort_order;
        $footer->save();

        return back()->with('success', 'Updated Successfully');
    }

    public function status($id)
    {
        $footer = Footer::find($id);
        if ($footer->status == 1) {
            $footer->status = 0;
            $footer->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $footer->status = 1;
            $footer->save();
            return back()->with('success', 'Enabled successfully');
        }
    }

    public function sortOrder(Request $request, $id)
    {
        $sortValue = $request->input('sort');
        $footer = Footer::find($id);

        $footer->sort_order = $sortValue;
        $footer->save();
        return back()->with('success', 'Item sorted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $footer = Footer::find($id);
        $footer->delete();
        return back()->with('success', 'Deleted successfully');
    }
}
