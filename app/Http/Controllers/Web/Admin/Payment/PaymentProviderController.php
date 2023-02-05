<?php

namespace App\Http\Controllers\Web\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentProvider;
use Illuminate\Http\Request;

class PaymentProviderController extends Controller
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

        $payment_providers = PaymentProvider::query();

        if ($q) {
            $payment_providers = $payment_providers->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('label', 'like', '%' . $q . '%');
        }

        $payment_providers = $payment_providers->latest()->paginate(15);
        return view('backend.setting.payment_provider.index', compact('payment_providers'));
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
        $payment_provider = PaymentProvider::find($id);
        if ($payment_provider->status == 1) {
            $payment_provider->status = 0;
            $payment_provider->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $payment_provider->status = 1;
            $payment_provider->save();
            return back()->with('success', 'Activated');
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
