<?php

namespace App\Http\Controllers\Web\Admin\Order\OrderTimeline;

use App\Http\Controllers\Controller;
use App\Models\Order\OrderTimeline\OrderTimeline;
use Illuminate\Http\Request;

class OrderTimelineController extends Controller
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
        // Validate request
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'body' => 'required|string',
        ]);

        $order_id = $request->input('order_id');
        $body = $request->input('body');

        // Create order timeline
        $orderTimeline = new OrderTimeline();
        $orderTimeline->order_id = $order_id;
        $orderTimeline->user_id = auth()->user()->id;
        $orderTimeline->body = $body;
        $orderTimeline->save();

        // Return response
        return back()->with('success', 'Added successfully.');
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
        OrderTimeline::destroy($id);

        // Return response
        return back()->with('success', 'Deleted successfully.');
    }
}
