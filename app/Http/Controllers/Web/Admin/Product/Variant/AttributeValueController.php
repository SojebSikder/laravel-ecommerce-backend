<?php

namespace App\Http\Controllers\Web\Admin\Product\Variant;

use App\Http\Controllers\Controller;
use App\Models\Product\Variant\Attribute;
use App\Models\Product\Variant\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
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
    public function create($id)
    {
        $attribute = Attribute::findOrFail($id);
        $attribute_values = AttributeValue::where('attribute_id', $id)->get();
        return view('backend.product.variant.attribute.attribute-value.create', compact('attribute', 'attribute_values'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attribute_id = $request->input('attribute_id');
        $name = $request->input('name');
        $status = $request->input('status') == 1 ? 1 : 0;

        $attribute_value = new AttributeValue();

        $attribute_value->attribute_id = $attribute_id;
        $attribute_value->name = $name;
        $attribute_value->status = $status;
        $attribute_value->save();

        return back()->with('success', 'Added Successfully');
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
        $attribute_value = AttributeValue::findOrFail($id);

        return view('backend.product.variant.attribute.attribute-value.edit', compact('attribute_value'));
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

        $attribute_value = AttributeValue::find($id);

        $attribute_value->name = $name;
        $attribute_value->status = $status;
        $attribute_value->save();

        return back()->with('success', 'Updated Successfully');
    }

    public function status($id)
    {
        $attribute_value = AttributeValue::find($id);
        if ($attribute_value->status == '1') {
            $attribute_value->status = 0;
            $attribute_value->save();
            return back()->with('success', 'Deactivated');
        } else {
            $attribute_value->status = 1;
            $attribute_value->save();
            return back()->with('success', 'Activated');
        }
    }

    public function sortingOrder(Request $request, $id)
    {
        $sortValue = $request->input('sort');
        $attribute_value = AttributeValue::find($id);

        $attribute_value->sort_order = $sortValue;
        $attribute_value->save();
        return back()->with('success', 'Element sorted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attribute_value = AttributeValue::where('id', $id)->first();
        $attribute_value->delete();
        return back()->with('success', 'Element Deleted Successfully');
    }
}
