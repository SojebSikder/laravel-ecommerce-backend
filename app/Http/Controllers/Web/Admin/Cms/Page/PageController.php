<?php

namespace App\Http\Controllers\Web\Admin\Cms\Page;

use App\Http\Controllers\Controller;
use App\Models\Cms\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
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

        $pages = Page::query();

        if ($q) {
            $pages = $pages->orWhere('title', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $pages = $pages->latest()->paginate(15);

        return view('backend.cms.page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.cms.page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $slug = $request->input('slug');
        $meta_title = $request->input('meta_title');
        $meta_description = $request->input('meta_description');
        $meta_keyword = $request->input('meta_keyword');
        $body = $request->input('body');
        $status = $request->input('status') == 1 ? 1 : 0;

        $page = new Page();
        $page->title = $title;
        $page->slug =  Str::slug($slug);
        $page->meta_title = $meta_title;
        $page->meta_description = $meta_description;
        $page->meta_keyword = $meta_keyword;
        $page->body = $body;
        $page->status = $status;
        $page->save();

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
        $page = Page::findOrFail($id);
        return view('backend.cms.page.edit', compact('page'));
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
        $title = $request->input('title');
        $slug = $request->input('slug');
        $meta_title = $request->input('meta_title');
        $meta_description = $request->input('meta_description');
        $meta_keyword = $request->input('meta_keyword');
        $body = $request->input('body');
        $status = $request->input('status') == 1 ? 1 : 0;


        $page = Page::find($id);
        $page->title = $title;
        $page->slug = Str::slug($slug);
        $page->meta_title = $meta_title;
        $page->meta_description = $meta_description;
        $page->meta_keyword = $meta_keyword;
        $page->body = $body;
        $page->status = $status;
        $page->save();

        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function status($id)
    {
        $page = Page::find($id);
        if ($page->status == 1) {
            $page->status = 0;
            $page->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $page->status = 1;
            $page->save();
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
        $page = Page::find($id);
        $page->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
