<?php

namespace App\Http\Controllers\Api\App\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserFavoriteProduct;
use Illuminate\Http\Request;

class UserFavoriteProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $loggedInUser = auth("api")->user();

            $favoriteProducts = UserFavoriteProduct::where('user_id', $loggedInUser->id)
                ->with(['product' => function ($query) {
                    $query->with(['images']);
                }])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $favoriteProducts,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Login to see your favorite products',
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
        try {
            $loggedInUser = auth("api")->user();

            $favoriteProduct = UserFavoriteProduct::where('user_id', $loggedInUser->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($favoriteProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product already in favorite list',
                ]);
            }

            $favoriteProduct = new UserFavoriteProduct();
            $favoriteProduct->user_id = $loggedInUser->id;
            $favoriteProduct->product_id = $request->product_id;
            $favoriteProduct->save();

            return response()->json([
                'success' => true,
                'message' => 'Product added to favorite list',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Login to add product to favorite list',
            ]);
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
            $loggedInUser = auth("api")->user();

            $favoriteProduct = UserFavoriteProduct::where('user_id', $loggedInUser->id)
                ->where('product_id', $id)
                ->first();

            if (!$favoriteProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not in favorite list',
                ]);
            }

            $favoriteProduct->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from favorite list',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Login to remove product from favorite list',
            ]);
        }
    }
}
