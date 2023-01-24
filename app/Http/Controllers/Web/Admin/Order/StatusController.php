<?php

namespace App\Http\Controllers\Web\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
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

        $statuses = Status::query();

        if ($q) {
            $statuses = $statuses->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $statuses = $statuses->orderBy('sort_order', 'asc')
            ->paginate(15);

        return view('backend.setting.order.status.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.setting.order.status.create');
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
        $status = Status::find($id);
        if ($status->status == 1) {
            $status->status = 0;
            $status->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $status->status = 1;
            $status->save();
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
        //
    }
}
