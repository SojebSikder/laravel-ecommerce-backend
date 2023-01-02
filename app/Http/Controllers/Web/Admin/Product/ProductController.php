<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\Product\Manufacturer;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
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

        $products = Product::query();

        if ($q) {
            $products = $products->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $products = $products->latest()->paginate(15);
        return view('backend.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', 1)->where('parent_id', null)->orderBy('name', 'asc')->get();
        $manufacturers = Manufacturer::where('status', 1)->orderBy('name', 'asc')->get();
        $products = Product::where('status', 1)->get();
        return view('backend.product.create', compact('categories', 'manufacturers', 'products'));
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
            $request->validate([
                'name' => 'required'
            ]);

            $name = $request->input('name');
            $slug = $request->input('slug');
            $category_id = $request->input('category_id');
            $manufacturer_id = $request->input('manufacturer_id');
            $price = $request->input('price');
            $description = $request->input('description');
            $track_quantity = $request->input('track_quantity') == 1 ? 1 : 0;
            $quantity = $request->input('quantity');
            $sku = $request->input('sku');
            $discount = $request->input('discount');
            $is_sale = $request->input('is_sale') == 1 ? 1 : 0;
            // seo
            $meta_title = $request->input('meta_title');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            //
            $status = $request->input('status') == 1 ? 1 : 0;

            // insert record
            $product = new Product();
            $product->name = $name;
            $product->slug = Str::slug($slug);
            $product->price = $price;
            $product->manufacturer_id = $manufacturer_id;
            $product->description = $description;
            $product->track_quantity = $track_quantity;
            $product->quantity = $quantity;
            $product->sku = $sku;
            $product->discount = $discount;
            $product->is_sale = $is_sale;

            $product->meta_title = $meta_title;
            $product->meta_description = $meta_description;
            $product->meta_keyword = $meta_keyword;

            $product->status = $status;
            $product->save();
            // save categories
            foreach ($category_id as $category) {
                // insert into product categories
                $productCategoy = new ProductCategory();
                $productCategoy->product_id = $product->id;
                $productCategoy->category_id = $category;
                $productCategoy->save();
            }

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
        //
    }

    public function status($id)
    {
        $product = Product::find($id);
        if ($product->status == '1') {
            $product->status = 0;
            $product->save();
            return back()->with('success', 'Product disabled');
        } else {
            $product->status = 1;
            $product->save();
            return back()->with('success', 'Product enabled');
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
        //
    }
}
