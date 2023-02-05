<?php

namespace App\Http\Controllers\Web\Admin\Shipping;

use App\Http\Controllers\Controller;
use App\Models\Address\Country;
use App\Models\Payment\PaymentProvider;
use App\Models\Shipping\Shipping;
use App\Models\Shipping\ShippingZone;
use App\Models\Shipping\ShippingZoneAddress;
use App\Models\Shipping\ShippingZonePaymentProvider;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
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
            $shipping_id = $request->input('shipping_id');
            $shipping_list_id = $request->input('shipping_list_id');
            $name = $request->input('name');
            $price = $request->input('price');
            $shipping_time_start = $request->input('shipping_time_start');
            $shipping_time_end = $request->input('shipping_time_end');
            $status = $request->input('status')  == 1 ? 1 : 0;
            $sort_order = $request->input('sort_order');
            $address = json_decode($request->input('tags'));


            if ($shipping_list_id != 'null') {
                // update
                $updateShippingZone = ShippingZone::find($shipping_list_id);
                $updateShippingZone->name = $name;
                $updateShippingZone->price = $price;
                $updateShippingZone->shipping_time_start = $shipping_time_start;
                $updateShippingZone->shipping_time_end = $shipping_time_end;
                $updateShippingZone->status = $status;
                $updateShippingZone->sort_order = $sort_order;
                $updateShippingZone->save();
                // create tag if not exist
                // $this->saveAddress($address, $updateShippingZone);
            } else {
                $shippingZone = new ShippingZone();
                $shippingZone->shipping_id = $shipping_id;
                $shippingZone->name = $name;
                $shippingZone->price = $price;
                $shippingZone->shipping_time_start = $shipping_time_start;
                $shippingZone->shipping_time_end = $shipping_time_end;
                $shippingZone->status = $status;
                $shippingZone->sort_order = $sort_order;
                $shippingZone->save();
                // create tag if not exist
                // $this->saveAddress($address, $shippingZone);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Created successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
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
        //
    }

    /**
     * Address
     */
    public function addressEdit($shipping_id, $shipping_zone_id)
    {
        $countries = Country::orderBy('name', 'asc')->get();
        $shipping = Shipping::find($shipping_id);
        $shippingZone = ShippingZone::find($shipping_zone_id);

        return view('backend.setting.shipping.address.edit', compact('shipping', 'shippingZone', 'countries'));
    }
    public function addressUpdate(Request $request)
    {
        $shipping_id = $request->input('shipping_id');
        $shipping_zone_id = $request->input('shipping_zone_id');
        $address = $request->input('tags');

        // $shipping = Shipping::find($shipping_id);
        $shippingZone = ShippingZone::find($shipping_zone_id);

        $this->saveAddress($address, $shippingZone);

        return redirect("/setting/shipping/$shipping_id/edit")->with('sms', 'Added successfully');
    }
    // save shipping address
    private function saveAddress($addresses, $shipping)
    {
        // remove first shipping addresses record
        if ($addresses) {
            ShippingZoneAddress::where('shipping_zone_id', $shipping->id)->delete();
            foreach ($addresses as $tag) {
                $existTag = Country::where('name', $tag)->first();

                // insert address into shiping address
                $shippingAddress = new ShippingZoneAddress();
                $shippingAddress->shipping_zone_id = $shipping->id;
                $shippingAddress->country_id = $existTag->id;
                $shippingAddress->save();
            }
        } else {
            ShippingZoneAddress::where('shipping_zone_id', $shipping->id)->delete();
        }
    }



    /**
     * Payment provider
     */
    public function paymentProviderEdit($shipping_id, $shipping_zone_id)
    {
        $payment_providers = PaymentProvider::latest()->get();
        $shipping = Shipping::find($shipping_id);
        $shippingZone = ShippingZone::find($shipping_zone_id);

        return view('backend.setting.shipping.payment_provider.edit', compact('shipping', 'shippingZone', 'payment_providers'));
    }
    public function paymentProviderUpdate(Request $request)
    {
        $shipping_id = $request->input('shipping_id');
        $shipping_zone_id = $request->input('shipping_zone_id');
        $payment_providers = $request->input('payment_providers');
        $shippingZone = ShippingZone::find($shipping_zone_id);

        $this->savePaymentProvider($payment_providers, $shippingZone);

        return redirect("/setting/shipping/$shipping_id/edit")->with('sms', 'Added successfully');
    }

    // save shipping address
    private function savePaymentProvider($payment_providers, $shipping)
    {
        // remove first shipping payment_providers record
        if ($payment_providers) {
            ShippingZonePaymentProvider::where('shipping_zone_id', $shipping->id)->delete();
            foreach ($payment_providers as $tag) {
                $existTag = PaymentProvider::where('name', $tag)->first();

                // insert address into shiping address
                $shippingAddress = new ShippingZonePaymentProvider();
                $shippingAddress->shipping_zone_id = $shipping->id;
                $shippingAddress->payment_provider_id = $existTag->id;
                $shippingAddress->save();
            }
        } else {
            ShippingZonePaymentProvider::where('shipping_zone_id', $shipping->id)->delete();
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
            $shippingZone = ShippingZone::find($id);
            $shippingZone->delete();

            return back()->with('success', 'Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('success', $th->getMessage());
        }
    }
}
