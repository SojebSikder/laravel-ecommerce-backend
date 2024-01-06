<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use App\Models\OptionSet\OptionSet;
use App\Models\Product\Manufacturer;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductDetails;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductTag;
use App\Models\Product\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $tags = Tag::orderBy('name', 'asc')->get();
        $categories = Category::where('status', 1)->where('parent_id', null)->orderBy('name', 'asc')->get();
        $option_sets = OptionSet::where('status', 1)->orderBy('name', 'asc')->get();
        $manufacturers = Manufacturer::where('status', 1)->orderBy('name', 'asc')->get();

        return view('backend.product.create', compact('categories', 'option_sets', 'manufacturers', 'tags'));
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

            $saveContinue = $request->input('save_continue');

            $name = $request->input('name');
            $slug = $request->input('slug');
            $category_id = $request->input('category_id');
            $option_set_id = $request->input('option_set_id');
            $manufacturer_id = $request->input('manufacturer_id');
            $tags = $request->input('tags');
            $price = $request->input('price');
            $cost_per_item = $request->input('cost_per_item');
            $description = $request->input('description');
            $track_quantity = $request->input('track_quantity') == 1 ? 1 : 0;
            $quantity = $request->input('quantity');
            $sku = $request->input('sku');

            $weight = $request->input('weight');
            $weight_unit = $request->input('weight_unit');

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
            $product->cost_per_item = $cost_per_item;
            if ($manufacturer_id) {
                $product->manufacturer_id = $manufacturer_id;
            } else {
                $product->manufacturer_id = null;
            }
            if ($description) {

                $product->description = $description;
            } else {
                $product->description = null;
            }
            $product->track_quantity = $track_quantity;
            $product->quantity = $quantity;
            $product->sku = $sku;

            if ($weight) {
                $product->weight = $weight;
                $product->weight_unit = $weight_unit;
            } else {
                $product->weight = null;
                $product->weight_unit = null;
            }
            if ($discount) {
                $product->discount = $discount;
            } else {
                $product->discount = null;
            }
            $product->is_sale = $is_sale;

            $product->meta_title = $meta_title;
            $product->meta_description = $meta_description;
            $product->meta_keyword = $meta_keyword;

            $product->status = $status;
            $product->save();
            // save categories
            $product->categories()->sync($category_id);
            // save option sets
            $product->option_sets()->sync($option_set_id);
            // save tags
            $this->saveTags($tags, $product);

            // if ($category_id) {
            //     foreach ($category_id as $category) {
            //         // insert into product categories
            //         $productCategoy = new ProductCategory();
            //         $productCategoy->product_id = $product->id;
            //         $productCategoy->category_id = $category;
            //         $productCategoy->save();
            //     }
            // }

            if ($saveContinue) {
                return redirect("/product/$product->id/edit")->with('success', 'Created successfully');
            } else {
                return back()->with('success', 'Created successfully');
            }
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
    public function edit(Request $request, $id)
    {
        $tags = Tag::orderBy('name', 'asc')->get();
        $categories = Category::where('status', 1)->where('parent_id', null)->orderBy('name', 'asc')->get();
        $option_sets = OptionSet::where('status', 1)->orderBy('name', 'asc')->get();
        $manufacturers = Manufacturer::where('status', 1)->orderBy('name', 'asc')->get();
        $product = Product::with(['variants' => function ($variant) {
            $variant->with(['variant_attributes' => function ($query) {
                $query->with(['attribute', 'attribute_value']);
            }, 'images']);
        }, 'categories', 'option_sets', 'details'])->findOrFail($id);
        $productImages = ProductImage::where('product_id', $product->id)
            ->orderBy('sort_order', 'asc')
            ->paginate(15);

        return view('backend.product.edit', compact('categories', 'option_sets', 'manufacturers', 'productImages', 'product', 'tags'));
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
            $request->validate([
                'name' => 'required'
            ]);

            $name = $request->input('name');
            $slug = $request->input('slug');
            $category_id = $request->input('category_id');
            $option_set_id = $request->input('option_set_id');
            $manufacturer_id = $request->input('manufacturer_id');
            $tags = $request->input('tags');
            $price = $request->input('price');
            $cost_per_item = $request->input('cost_per_item');
            $description = $request->input('description');
            $track_quantity = $request->input('track_quantity') == 1 ? 1 : 0;
            $quantity = $request->input('quantity');
            $sku = $request->input('sku');

            $image = $request->file('image');

            $weight = $request->input('weight');
            $weight_unit = $request->input('weight_unit');

            $discount = $request->input('discount');
            $is_sale = $request->input('is_sale') == 1 ? 1 : 0;
            // seo
            $meta_title = $request->input('meta_title');
            $meta_description = $request->input('meta_description');
            $meta_keyword = $request->input('meta_keyword');
            //
            $status = $request->input('status') == 1 ? 1 : 0;


            // insert record
            $product = Product::findOrFail($id);
            $product->name = $name;
            $product->slug = Str::slug($slug);
            $product->price = $price;
            $product->cost_per_item = $cost_per_item;
            if ($manufacturer_id) {
                $product->manufacturer_id = $manufacturer_id;
            } else {
                $product->manufacturer_id = null;
            }
            if ($description) {

                $product->description = $description;
            } else {
                $product->description = null;
            }
            $product->track_quantity = $track_quantity;
            $product->quantity = $quantity;
            $product->sku = $sku;

            if ($weight) {
                $product->weight = $weight;
                $product->weight_unit = $weight_unit;
            } else {
                $product->weight = null;
                $product->weight_unit = null;
            }
            if ($discount) {
                $product->discount = $discount;
            } else {
                $product->discount = null;
            }
            $product->is_sale = $is_sale;

            $product->meta_title = $meta_title;
            $product->meta_description = $meta_description;
            $product->meta_keyword = $meta_keyword;

            $product->status = $status;
            $product->save();
            // save categories
            $product->categories()->sync($category_id);
            // save option sets
            $product->option_sets()->sync($option_set_id);
            // save tags
            $this->saveTags($tags, $product);

            // if ($category_id) {
            //     foreach ($category_id as $category) {
            //         // insert into product categories
            //         $productCategoy = new ProductCategory();
            //         $productCategoy->product_id = $product->id;
            //         $productCategoy->category_id = $category;
            //         $productCategoy->save();
            //     }
            // }
            if ($request->hasFile('image')) {
                // update images to variant image
                $this->storeImage($image, $product->id);
            }

            return back()->with('success', 'Updated successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }


    // save tags
    private function saveTags($tags, $product)
    {
        // remove first product tags record
        if ($tags) {
            ProductTag::where('product_id', $product->id)->delete();
            foreach ($tags as $tag) {
                $existTag = Tag::where('name', $tag)->first();
                if (!$existTag) {
                    $insertTag = new Tag();
                    $insertTag->name = $tag;
                    $insertTag->save();

                    // insert tag into product tag
                    $productTag1 = new ProductTag();
                    $productTag1->product_id = $product->id;
                    $productTag1->tag_id = $insertTag->id;
                    $productTag1->save();
                } else {
                    // insert tag into product tag
                    $productTag2 = new ProductTag();
                    $productTag2->product_id = $product->id;
                    $productTag2->tag_id = $existTag->id;
                    $productTag2->save();
                }
            }
        } else {
            ProductTag::where('product_id', $product->id)->delete();
        }
    }

    /**
     * store image
     */
    private function storeImage($file, $product_id)
    {
        if ($file) {
            $count = count($file);
            // upload new image
            for ($i = 0; $i < $count; $i++) {
                $file_tmp =  $file[$i];
                $file_name = time() . '-' . uniqid() . '.' . $file_tmp->extension();
                $file_path = config('constants.uploads.product') . "/" . $file_name;

                // // resize image
                // $resizedimg = ImageHelper::resize(file_get_contents($file_tmp->getRealPath()), 1000, 1000);
                // Storage::put($file_path, (string) $resizedimg->encode());
                // resize image
                $resizedimg = file_get_contents($file_tmp->getRealPath());
                Storage::put($file_path, (string) $resizedimg);

                // insert into product image
                $productImage = new ProductImage();
                $productImage->product_id = $product_id;
                $productImage->image = $file_name;
                $productImage->save();
            }
        }
    }

    /**
     * Update product image
     */
    public function updateImage(Request $request, $id)
    {
        $title = $request->input('title');
        $alt_text = $request->input('alt_text');
        $sort_order = $request->input('sort_order');

        $productImage = ProductImage::find($id);
        $productImage->title = $title;
        $productImage->alt_text = $alt_text;
        $productImage->sort_order = $sort_order;
        $productImage->save();

        return back()->with('success', 'Updated Successfully');
    }

    // remove single product variant image
    public function deleteImage($id)
    {
        try {
            $productImage = ProductImage::where("id", $id)->first();
            // remove image from storage
            if (Storage::exists(config('constants.uploads.product') . "/" . $productImage->image)) {
                Storage::delete(config('constants.uploads.product') . "/" . $productImage->image);
            }
            // remove record from database
            $productImage->delete();
            return back()->with('success', 'Deleted Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('success', 'Not Deleted. Something went wrong :(');
        }
    }

    public function status($id)
    {
        $product = Product::find($id);
        if ($product->status == '1') {
            $product->status = 0;
            $product->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $product->status = 1;
            $product->save();
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
        // product image will be delete with products
        $product = Product::find($id);
        $product->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
