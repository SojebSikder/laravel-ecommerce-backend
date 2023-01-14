<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductDetails;
use Illuminate\Http\Request;

class ProductDetailsController extends Controller
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
        $product = Product::find($id);

        return view('backend.product.details.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $name = $request->input('name');
        $body = $request->input('body');
        $status = $request->input('status') == 1 ? 1 : 0;
        $sort_order = $request->input('sort_order');


        $productDetails = new ProductDetails();
        $productDetails->product_id = $id;
        $productDetails->name = $name;
        $productDetails->body = $body;
        $productDetails->status = $status;
        $productDetails->sort_order = $sort_order;
        $productDetails->save();

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
        $productDetail = ProductDetails::find($id);

        return view('backend.product.details.edit', compact('productDetail'));
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
        $body = $request->input('body');
        $status = $request->input('status') == 1 ? 1 : 0;
        $sort_order = $request->input('sort_order');


        $productDetails = ProductDetails::find($id);
        $productDetails->name = $name;
        $productDetails->body = $body;
        $productDetails->status = $status;
        $productDetails->sort_order = $sort_order;
        $productDetails->save();

        return back()->with('success', 'Updated successfully');
    }

    public function status($id)
    {
        $productDetails = ProductDetails::find($id);
        if ($productDetails->status == 1) {
            $productDetails->status = 0;
            $productDetails->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $productDetails->status = 1;
            $productDetails->save();
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
        $productDetails = ProductDetails::find($id);
        $productDetails->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
