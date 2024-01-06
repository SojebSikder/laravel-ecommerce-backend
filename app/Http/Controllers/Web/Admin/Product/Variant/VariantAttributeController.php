<?php

namespace App\Http\Controllers\Web\Admin\Product\Variant;

use App\Http\Controllers\Controller;
use App\Models\Product\Variant\Attribute;
use App\Models\Product\Variant\VariantAttribute;
use Illuminate\Http\Request;

class VariantAttributeController extends Controller
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
    public function create($variant_id)
    {
        $attributes = Attribute::with('attribute_values')->where('status', 1)->get();

        return view('backend.product.variant.variant-attribute.create', compact('attributes', 'variant_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $variant_id = $request->input('variant_id');
        $attribute_id = $request->input('attribute_id');
        $attribute_value_id = $request->input('attribute_value_id');

        $variant_attribute = new VariantAttribute();
        $variant_attribute->variant_id = $variant_id;
        $variant_attribute->attribute_id = $attribute_id;
        $variant_attribute->attribute_value_id = $attribute_value_id;
        $variant_attribute->save();

        return redirect()->route('variant.edit', $variant_id)->with('success', 'Variant attribute added successfully.');
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
        $attributes = Attribute::with('attribute_values')->where('status', 1)->get();
        $variant_attribute = VariantAttribute::find($id);

        return view('backend.product.variant.variant-attribute.edit', compact('attributes', 'variant_attribute'));
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
        $attribute_id = $request->input('attribute_id');
        $attribute_value_id = $request->input('attribute_value_id');

        $variant_attribute = VariantAttribute::find($id);
        $variant_attribute->attribute_id = $attribute_id;
        $variant_attribute->attribute_value_id = $attribute_value_id;
        $variant_attribute->save();

        return redirect()->route('variant.edit', $variant_attribute->variant_id)->with('success', 'Variant attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $variant_attribute = VariantAttribute::find($id);
        $variant_id = $variant_attribute->variant_id;
        $variant_attribute->delete();

        return redirect()->route('variant.edit', $variant_id)->with('success', 'Variant attribute deleted successfully.');
    }
}
