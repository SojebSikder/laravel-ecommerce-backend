<?php

namespace App\Http\Controllers\Web\Admin\Product\Variant;

use App\Http\Controllers\Controller;
use App\Models\Product\Variant\Attribute;
use App\Models\Product\Variant\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
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

        $attributes = Attribute::query();

        if ($q) {
            $attributes = $attributes->orWhere('name', 'like', '%' . $q . '%');
        }

        $attributes = $attributes->with('attribute_values')->latest()->paginate(15);
        return view('backend.product.variant.attribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.product.variant.attribute.create');
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
            'name' => 'required|unique:attributes'
        ]);

        $name = $request->input('name');
        $status = $request->input('status') == 1 ? 1 : 0;

        $attribute = new Attribute();
        $attribute->name = $name;
        $attribute->status = $status;
        $attribute->save();

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
        $attribute = Attribute::findOrFail($id);
        $attribute_values = AttributeValue::where('attribute_id', $id)->orderBy('sort_order', 'ASC')->get();

        return view('backend.product.variant.attribute.attribute-value.index', compact('attribute', 'attribute_values'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('backend.product.variant.attribute.edit', compact('attribute'));
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
        $status = $request->input('status') == 1 ? 1 : 0;

        $attribute = Attribute::findOrFail($id);
        $attribute->name = $name;
        $attribute->status = $status;
        $attribute->save();

        return back()->with('success', 'Updated successfully');
    }

    public function status($id)
    {
        $attribute = Attribute::find($id);
        if ($attribute->status == '1') {
            $attribute->status = 0;
            $attribute->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $attribute->status = 1;
            $attribute->save();
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
            $attribute = Attribute::find($id);
            $attribute->delete();
            return back()->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
