<?php

namespace App\Http\Controllers\Web\Admin\Review;

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
    public function index(Request $request)
    {
        // search query
        $q = $request->input('q');

        $reviews = Review::query()->with('user');

        if ($q) {
            $reviews = $reviews->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $reviews = $reviews->latest()->paginate(15);
        return view('backend.review.index', compact('reviews'));
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
        $review = Review::find($id);
        if ($review->status == '1') {
            $review->status = 0;
            $review->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $review->status = 1;
            $review->save();
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
            $review = Review::find($id);
            $review->delete();

            return back()->with('sms', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
