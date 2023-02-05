<?php

namespace App\Http\Controllers\Web\Admin\Shipping;

use App\Http\Controllers\Controller;
use App\Models\Address\Country;
use App\Models\Payment\PaymentProvider;
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
    public function index(Request $request)
    {
        // search query
        $q = $request->input('q');

        $shippings = Shipping::query();

        if ($q) {
            $shippings = $shippings->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $shippings = $shippings->latest()->paginate(15);
        return view('backend.setting.shipping.index', compact('shippings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.setting.shipping.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $status = $request->input('status') == 1 ? 1 : 0;

        $shipping = new Shipping();
        $shipping->name = $name;
        $shipping->status = $status;
        $shipping->save();

        // return back()->with('sms', 'Created Successfully');
        return redirect("/setting/shipping/$shipping->id/edit")->with('success', 'Created successfully');
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
        $shipping = Shipping::findOrFail($id);
        $countries = Country::orderBy('name', 'asc')->get();
        $shippingZone = ShippingZone::where('shipping_id', $id)->get();
        $payment_providers = PaymentProvider::latest()->get();

        return view('backend.setting.shipping.edit', compact('shipping', 'shippingZone', 'countries', 'payment_providers'));
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
        $name = $request->input('name');
        $status = $request->input('status') == 1 ? 1 : 0;

        $shipping = Shipping::findOrFail($id);
        $shipping->name = $name;
        $shipping->status = $status;
        $shipping->save();

        return back()->with('success', 'Updated Successfully');
    }

    public function status($id)
    {
        $shipping = Shipping::find($id);
        if ($shipping->status == 1) {
            $shipping->status = 0;
            $shipping->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $shipping->status = 1;
            $shipping->save();
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
        $shipping = Shipping::find($id);
        $shipping->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
