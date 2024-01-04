<?php

namespace App\Http\Controllers\Web\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Currency\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
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

        $currencies = Currency::query();

        if ($q) {
            $currencies = $currencies->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('currency_code', 'like', '%' . $q . '%');
        }

        $currencies = $currencies->orderBy("name", "ASC")->latest()->paginate(15);
        return view('backend.currency.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.currency.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:currencies',
            'currency_code' => 'required|unique:currencies',
            'rate' => 'required',
        ]);

        $name = $request->input('name');
        $currency_code = $request->input('currency_code');
        $currency_sign = $request->input('currency_sign');
        $rate = $request->input('rate');
        $is_primary_exchange = $request->input('is_primary_exchange') == 1 ? "true" : "false";
        $is_primary_store = $request->input('is_primary_store') == 1 ? "true" : "false";
        $status = $request->input('status') == 1 ? 1 : 0;

        $currency = new Currency();
        $currency->name = $name;
        $currency->currency_code = $currency_code;
        $currency->currency_sign = $currency_sign;
        $currency->rate = $rate;
        // if ($is_primary_exchange == "true") {
        //     $currency->is_primary_exchange = 1;
        // } else {
        //     $currency->is_primary_exchange = 0;
        // }
        // if ($is_primary_store == "true") {
        //     $currency->is_primary_store = 1;
        // } else {
        //     $currency->is_primary_store = 0;
        // }
        $currency->status = $status;
        $currency->save();

        return back()->with('success', 'Created successfully');
    }

    public function markPrimaryExchange($id)
    {
        $currency = Currency::find($id);
        $currency->is_primary_exchange = 1;
        $currency->save();

        $currencies = Currency::where('id', '!=', $id)->get();
        foreach ($currencies as $currency) {
            $currency->is_primary_exchange = 0;
            $currency->save();
        }

        return back()->with('success', 'Currency has been marked as primary exchange successfully.');
    }

    public function markPrimaryStore($id)
    {
        $currency = Currency::find($id);
        $currency->is_primary_store = 1;
        $currency->save();

        $currencies = Currency::where('id', '!=', $id)->get();
        foreach ($currencies as $currency) {
            $currency->is_primary_store = 0;
            $currency->save();
        }

        return back()->with('success', 'Currency has been marked as primary store successfully.');
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
        try {
            $currency = Currency::find($id);

            return view('backend.currency.edit', compact('currency'));
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
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
        $request->validate([
            'name' => "required|unique:currencies,name,$id",
            'currency_code' => 'required|unique:currencies,currency_code,' . $id . ',id',
            'rate' => 'required',
        ]);

        $name = $request->input('name');
        $currency_code = $request->input('currency_code');
        $currency_sign = $request->input('currency_sign');
        $rate = $request->input('rate');
        $is_primary_exchange = $request->input('is_primary_exchange') == 1 ? "true" : "false";
        $is_primary_store = $request->input('is_primary_store') == 1 ? "true" : "false";
        $status = $request->input('status') == 1 ? 1 : 0;

        $currency = Currency::find($id);
        $currency->name = $name;
        $currency->currency_code = $currency_code;
        $currency->currency_sign = $currency_sign;
        $currency->rate = $rate;
        // if ($is_primary_exchange == "true") {
        //     $currency->is_primary_exchange = 1;
        // } else {
        //     $currency->is_primary_exchange = 0;
        // }
        // if ($is_primary_store == "true") {
        //     $currency->is_primary_store = 1;
        // } else {
        //     $currency->is_primary_store = 0;
        // }
        $currency->status = $status;
        $currency->save();

        return back()->with('success', 'Currency has been updated successfully.');
    }

    public function status($id)
    {
        $currency = Currency::find($id);
        if ($currency->status == '1') {
            $currency->status = 0;
            $currency->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $currency->status = 1;
            $currency->save();
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
            $currency = Currency::find($id);

            $currency->delete();

            return back()->with('success', 'Deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
