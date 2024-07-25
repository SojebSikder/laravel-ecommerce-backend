<?php

namespace App\Http\Controllers\Api\App\Product;

use App\Http\Controllers\Controller;
use App\Lib\SojebVar\SojebVar;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Product\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category_id = $request->input('category');

        // lazy loading
        $default_limit = 40;

        $products = Product::query()->with('images')->where('status', 1);
        if ($category_id) {
            $products = $products->whereHas('categories', function ($query) use ($category_id) {
                return $query->where('category_id', $category_id);
            });
        }
        $products = $products->latest()->paginate($default_limit);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function productWithCategories()
    {
        // lazy loading
        $default_limit = 40;

        $products = Category::query()->has('products')->where('status', 1)
            ->with(['products' => function ($query) {
                $query->latest()->limit(10);
            }])->where('parent_id', null);
        $products = $products->latest()->paginate($default_limit);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function productWithCategory($id)
    {
        // lazy loading
        $default_limit = 40;

        $products = Category::where('id', $id)
            ->where('status', 1)
            ->with(['products' => function ($query) use ($default_limit) {
                $query->latest()->paginate($default_limit);
            }])->first();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Search product
     */
    public function search(Request $request)
    {
        try {
            // measure the search time
            $start = microtime(TRUE);
            $search_text = $request->input('q');

            // lazy loading
            $default_limit = 40;

            $product = Product::query()->with('images')->where('status', 1)
                ->where(function ($query) use ($search_text) {
                    $query->where('name', 'like', '%' . $search_text . '%')
                        ->orWhere('meta_keyword', 'like', '%' . $search_text . '%')
                        ->orWhere('meta_description', 'like', '%' . $search_text . '%');
                });


            $product = $product->latest()->paginate($default_limit);

            $end = microtime(TRUE);
            $time = number_format($end - $start, 2, '.', '');

            return response()->json([
                'time' => $time . ' ms',
                'count' => $product->total(),
                'data' => $product,
                'success' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
                // 'message' => $th->getMessage()
            ]);
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::with(['variants' => function ($variant) {
                $variant->with(['images', 'variant_attributes' => function ($query) {
                    $query->with(['attribute', 'attribute_value']);
                }, 'images']);
            }, 'images', 'categories', 'details' => function ($query) {
                $query->where('status', 1);
            }])
                ->where('status', 1)
                ->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not exist',
                ]);
            }

            // variable parsing
            SojebVar::addVariable([
                'product.name' => $product->name,
            ]);
            $product->description = SojebVar::parse($product->description);
            $product->meta_title = SojebVar::parse($product->meta_title);
            $product->meta_description = SojebVar::parse($product->meta_description);
            // end variable parsing

            return response()->json([
                'success' => true,
                'data' => $product,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function trending()
    {
        $tag = 'bestseller';
        $tagDb = Tag::where('name', $tag)->first();

        if ($tagDb) {
            $products = Product::latest()->with([
                "variants" => function ($query) {
                    $query->where('status', 1);
                },
            ])
                ->whereHas("tags", function ($query) use ($tagDb) {
                    $query->where('tags.id', $tagDb->id);
                })
                ->where('status', 1)
                // ->limit(11)
                ->limit(4)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag not found',
            ]);
        }
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
