<?php

namespace App\Http\Controllers\Api\App\Shipping;

use App\Http\Controllers\Controller;
use App\Models\Shipping\Shipping;
use App\Models\Shipping\ShippingZone;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shipping = Shipping::with('shopping_zone')->first();

        return response()->json([
            'success' => true,
            'data' => $shipping,
        ]);
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
        $shipping = ShippingZone::query()
            ->select('id', 'name', 'price')
            ->with(['countries' => function ($query) {
                // $query->select('id', 'name', 'country_code', 'dial_code', 'flag');
            }, 'payment_providers' => function ($query) {
                // $query->select('id', 'label', 'name',);
            }])
            ->find($id);

        return response()->json([
            'success' => true,
            'data' => $shipping,
        ]);
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
