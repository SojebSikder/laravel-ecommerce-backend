<?php

namespace App\Http\Controllers\Web\Admin\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Checkout\Checkout;
use Illuminate\Http\Request;

class CheckoutController extends Controller
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

        $checkouts = Checkout::query();

        if ($q) {
            $checkouts = $checkouts->orWhere('email', 'like', '%' . $q . '%')
                ->orWhereHas('user_shipping_address', function ($query) use ($q) {
                    $query->where('name', 'like', '%' . $q . '%');
                })
                ->orWhere('id', 'like', '%' . $q . '%')
                ->orWhere('order_id', 'like', '%' . $q . '%')
                ->orWhere('payment_ref_id', 'like', '%' . $q . '%')
                ->orWhere('tracking_number', 'like', '%' . $q . '%')
                ->orWhere('phone_number', 'like', '%' . $q . '%');
        }

        $checkouts = $checkouts->with('checkout_items')->latest()->paginate(15);
        return view('backend.checkout.index', compact('checkouts'));
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $checkout = Checkout::findOrFail($id);
        $checkout->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
