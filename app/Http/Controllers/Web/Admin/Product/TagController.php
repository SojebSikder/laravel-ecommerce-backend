<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
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

        $tags = Tag::query();

        if ($q) {
            $tags = $tags->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('label', 'like', '%' . $q . '%');
        }

        $tags = $tags->latest()->paginate(15);
        return view('backend.tag.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.tag.create');
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
            'name' => 'required|unique:tags'
        ]);

        $name = $request->input('name');
        $label = $request->input('label');
        $status = $request->input('status') == 1 ? 1 : 0;

        $tag = new Tag();
        $tag->name = Str::slug($name);
        $tag->label = $label;
        $tag->status = $status;

        $tag->save();

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
        $tag = Tag::findOrFail($id);
        return view('backend.tag.edit', compact('tag'));
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
            'name' => 'required|unique:tags,id'
        ]);

        $name = $request->input('name');
        $label = $request->input('label');
        $status = $request->input('status') == 1 ? 1 : 0;


        $tag = Tag::findOrFail($id);
        $tag->name = Str::slug($name);
        $tag->label = $label;
        $tag->status = $status;

        $tag->save();

        return back()->with('success', 'Updated successfully');
    }

    public function status($id)
    {
        $tag = Tag::find($id);
        if ($tag->status == '1') {
            $tag->status = 0;
            $tag->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $tag->status = 1;
            $tag->save();
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
            $tag = Tag::find($id);
            $tag->delete();
            return back()->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
