<?php

namespace App\Http\Controllers\Web\Admin\Product\Variant;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\Variant\Attribute;
use App\Models\Product\Variant\Variant;
use App\Models\Product\Variant\VariantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VariantController extends Controller
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
        $product = Product::findOrFail($id);
        $attributes = Attribute::with('attribute_values')->where('status', 1)->get();

        return view('backend.product.variant.create', compact('product', 'attributes'));
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
            $product_id = $request->input('product_id');

            $price = $request->input('price');
            $cost_per_item = $request->input('cost_per_item');
            $track_quantity = $request->input('track_quantity') == 1 ? 1 : 0;
            $quantity = $request->input('quantity');
            $sku = $request->input('sku');
            $weight = $request->input('weight');
            $weight_unit = $request->input('weight_unit');
            $discount = $request->input('discount');
            $is_sale = $request->input('is_sale') == 1 ? 1 : 0;
            $status = $request->input('status') == 1 ? 1 : 0;


            $variant = new Variant();

            $variant->product_id = $product_id;

            $variant->price = $price;
            $variant->cost_per_item = $cost_per_item;
            $variant->track_quantity = $track_quantity;
            $variant->quantity = $quantity;
            $variant->sku = $sku;
            if ($weight) {
                $variant->weight = $weight;
                $variant->weight_unit = $weight_unit;
            } else {
                $variant->weight = null;
                $variant->weight_unit = null;
            }
            if ($discount) {
                $variant->discount = $discount;
            } else {
                $variant->discount = null;
            }
            $variant->is_sale = $is_sale;
            $variant->status = $status;
            $variant->save();

            if ($request->hasFile('image')) {
                // update images to variant image
                $this->storeImage($request->file('image'), $variant->id);
            }

            return back()->with('success', 'Created Successfully');
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
        $attributes = Attribute::with('attribute_values')->where('status', 1)->get();
        $variant = Variant::with(['variant_attributes' => function ($query) {
            $query->with('attribute', 'attribute_value');
        }])->findOrFail($id);

        $variantImages = VariantImage::where("variant_id", $variant->id)->orderBy('sort_order', 'asc')
            ->paginate(15);

        return view('backend.product.variant.edit', compact('variant', 'variantImages', 'attributes'));
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
            $price = $request->input('price');
            $cost_per_item = $request->input('cost_per_item');
            $track_quantity = $request->input('track_quantity') == 1 ? 1 : 0;
            $quantity = $request->input('quantity');
            $sku = $request->input('sku');
            $weight = $request->input('weight');
            $weight_unit = $request->input('weight_unit');
            $discount = $request->input('discount');
            $is_sale = $request->input('is_sale') == 1 ? 1 : 0;
            $status = $request->input('status') == 1 ? 1 : 0;


            $variant = Variant::find($id);

            $variant->price = $price;
            $variant->cost_per_item = $cost_per_item;
            $variant->track_quantity = $track_quantity;
            $variant->quantity = $quantity;
            $variant->sku = $sku;
            if ($weight) {
                $variant->weight = $weight;
                $variant->weight_unit = $weight_unit;
            } else {
                $variant->weight = null;
                $variant->weight_unit = null;
            }
            if ($discount) {
                $variant->discount = $discount;
            } else {
                $variant->discount = null;
            }
            $variant->is_sale = $is_sale;
            $variant->status = $status;
            $variant->save();

            if ($request->hasFile('image')) {
                // update images to variant image
                $this->storeImage($request->file('image'), $variant->id);
            }

            return back()->with('success', 'Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }

    public function status($id)
    {
        $variant = Variant::find($id);
        if ($variant->status == '1') {
            $variant->status = 0;
            $variant->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $variant->status = 1;
            $variant->save();
            return back()->with('success', 'Enabled successfully');
        }
    }


    private function storeImage($file, $variant_id)
    {
        if ($file) {
            $count = count($file);
            // upload new image
            for ($i = 0; $i < $count; $i++) {
                $file_tmp =  $file[$i];
                $file_name = time() . '-' . uniqid() . '.' . $file_tmp->extension();
                $file_path = config('constants.uploads.product') . "/" . $file_name;

                // resize image
                // $resizedimg = ImageHelper::resize(file_get_contents($file_tmp->getRealPath()), 1000, 1000);
                // Storage::put($file_path, (string) $resizedimg->encode());

                $resizedimg = file_get_contents($file_tmp->getRealPath());
                Storage::put($file_path, (string) $resizedimg);

                // insert into variant image
                $variantImage = new VariantImage();
                $variantImage->variant_id = $variant_id;
                $variantImage->image = $file_name;
                $variantImage->save();
            }
        }
    }

    public function updateImage(Request $request, $id)
    {
        $alt_text = $request->input('alt_text');

        $variantImage = VariantImage::find($id);
        $variantImage->alt_text = $alt_text;
        $variantImage->save();

        return back()->with('success', 'Alt text updated Successfully');
    }


    // remove single product variant image
    public function deleteImage($id)
    {
        try {
            $varianImage = VariantImage::where("id", $id)->first();
            // remove image from storage
            if (Storage::exists(config('constants.uploads.product') . "/" . $varianImage->image)) {
                Storage::delete(config('constants.uploads.product') . "/" . $varianImage->image);
            }
            // remove record from database
            $varianImage->delete();
            return back()->with('success', 'Variant Image Deleted Successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('warning', $th->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant)
    {
        try {
            // remove image from storage
            $variantImages = VariantImage::where('variant_id', $variant->id)->get();

            foreach ($variantImages as $variantImage) {
                if (Storage::exists(config('constants.uploads.product') . "/" . $variantImage->image)) {
                    Storage::delete(config('constants.uploads.product') . "/" . $variantImage->image);
                    // variant_image record will be delete automatically 
                    // as soon as variant got delete
                }
            }
            // remove variant from database
            $variant->delete();
            return back()->with('success', 'Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
