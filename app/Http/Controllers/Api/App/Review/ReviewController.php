<?php

namespace App\Http\Controllers\Api\App\Review;

use App\Http\Controllers\Controller;
use App\Models\Review\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
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
            $product_id = $request->input('product_id');
            $rating_value = $request->input('rating_value');
            $title = $request->input('title');
            $body = $request->input('body');

            $user_id = auth('api')->user()->id;

            // check if user already reviewd
            $review = Review::where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->first();
            if ($review) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already reviewed this product'
                ]);
            }

            // insert into review
            $review = new Review();
            $review->user_id = $user_id;
            $review->product_id = $product_id;
            $review->rating_value = $rating_value;
            if ($title) {
                $review->title = $title;
            }
            if ($body) {
                $review->body = $body;
            }
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Review added Successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
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
        try {
            $product_id = $request->input('product_id');
            $rating_value = $request->input('rating_value');
            $title = $request->input('title');
            $body = $request->input('body');

            $user_id = auth('api')->user()->id;

            // check if user already reviewd
            $review = Review::where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->first();
            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not allowed'
                ]);
            }

            // insert into review
            $review = Review::find($id);
            $review->rating_value = $rating_value;
            $review->title = $title;
            $review->body = $body;
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Review updated Successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ]);
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
            $user_id = auth('api')->user()->id;
            $review = Review::where('user_id', $user_id)
                ->where('id', $id)
                ->first();
            if ($review) {

                $review->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Deleted review successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Rating is not found'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong :('
            ]);
        }
    }
}
