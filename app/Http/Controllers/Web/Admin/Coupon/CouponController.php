<?php

namespace App\Http\Controllers\Web\Admin\Coupon;

use App\Http\Controllers\Controller;
use App\Models\Coupon\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
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

        $coupons = Coupon::query();

        if ($q) {
            $categories = $coupons->orWhere('code', 'like', '%' . $q . '%');
        }

        $coupons = $coupons->latest()->paginate(15);

        return view('backend.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.coupon.create');
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


            $request->validate([
                // 'code' => 'required|unique:coupons',
                'amount' => 'required',
                // 'expires_at' => 'required',
            ]);

            $coupon_type = $request->input('coupon_type');
            $method = $request->input('method');
            $code = $request->input('code');
            $title = $request->input('title');
            $amount_type = $request->input('amount_type');
            $amount = $request->input('amount');

            // Minimum purchase requirements
            $min_type = $request->input('min_type');
            $min_amount = $request->input('min_amount');
            $min_qnty = $request->input('min_qnty');

            // limit
            $max_uses = $request->input('max_uses');
            $max_uses_user = $request->input('max_uses_user');

            $starts_at = $request->input('starts_at');
            $expires_at = $request->input('expires_at');
            $status = $request->input('status') == 1 ? 1 : 0;


            $coupon = new Coupon();
            $coupon->coupon_type = $coupon_type;
            $coupon->method = $method;

            if ($method == "auto") {
                $coupon->title = $title;
            } else {
                $coupon->code = $code;
                // limit
                $coupon->max_uses = $max_uses;
                $coupon->max_uses_user = $max_uses_user;
            }

            $coupon->amount_type = $amount_type;
            $coupon->amount = $amount;
            // Minimum purchase requirements
            $coupon->min_type = $min_type;
            $coupon->min_amount = $min_amount;
            $coupon->min_qnty = $min_qnty;
            //
            $coupon->starts_at = $starts_at;
            $coupon->expires_at = $expires_at;
            $coupon->status = $status;
            $coupon->save();

            return back()->with('success', 'Created successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
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
        $coupon = Coupon::find($id);
        return view('backend.coupon.edit', compact('coupon'));
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
        $coupon = Coupon::find($id);
        $coupon->update($request->all());

        return back()->with('success', 'Updated successfully');
    }


    public function status($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon->status == 1) {
            $coupon->status = 0;
            $coupon->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $coupon->status = 1;
            $coupon->save();
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
        $coupon = Coupon::find($id);
        $coupon->delete();

        return back()->with('success', 'Deleted successfully');
    }
}
