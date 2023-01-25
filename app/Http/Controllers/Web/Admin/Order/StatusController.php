<?php

namespace App\Http\Controllers\Web\Admin\Order;

use App\Helper\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Order\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $label = $request->input('label');
        $name = $request->input('name');
        $description = $request->input('description');
        $on_shipping_status = $request->input('on_shipping_status')  == 1 ? 1 : 0;
        $default = $request->input('default')  == 1 ? 1 : 0;
        $sort_order = $request->input('sort_order');
        $file = $request->file('image');

        $status = new Status();
        $status->label = $label;
        $status->name = $name;
        $status->description = $description;
        $status->on_shipping_status = $on_shipping_status;
        $status->default = $default;
        $status->sort_order = $sort_order;
        if ($request->hasFile('image')) {
            $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file_path = config('constants.uploads.setting_order_status') . "/" . $file_name;

            // resize image
            $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 35, 35);
            Storage::put($file_path, (string) $resizedimg->encode());

            $status->image = $file_name;
        }
        $status->save();

        return back()->with('success', 'Created successfully');
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
        $label = $request->input('label');
        $name = $request->input('name');
        $description = $request->input('description');
        $on_shipping_status = $request->input('on_shipping_status')  == 1 ? 1 : 0;
        $default = $request->input('default')  == 1 ? 1 : 0;
        $sort_order = $request->input('sort_order');
        $file = $request->file('image');

        $status = Status::find($id);
        $status->label = $label;
        $status->name = $name;
        $status->description = $description;
        $status->on_shipping_status = $on_shipping_status;
        $status->default = $default;
        $status->sort_order = $sort_order;
        if ($request->hasFile('image')) {
            // remove previous image first
            if (Storage::exists(config('constants.uploads.setting_order_status') . "/" . $status->image)) {
                Storage::delete(config('constants.uploads.setting_order_status') . "/" . $status->image);
            }

            $file_name = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file_path = config('constants.uploads.setting_order_status') . "/" . $file_name;

            // resize image
            $resizedimg = ImageHelper::resize(file_get_contents($file->getRealPath()), 35, 35);
            Storage::put($file_path, (string) $resizedimg->encode());

            $status->image = $file_name;
        }
        $status->save();

        return back()->with('success', 'Updated successfully');
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
        try {
            $status = Status::find($id);
            // remove previous image first
            if (Storage::exists(config('constants.uploads.setting_order_status') . "/" . $status->image)) {
                Storage::delete(config('constants.uploads.setting_order_status') . "/" . $status->image);
            }
            // remove record from database
            $status->delete();
            return back()->with('success', 'Deleted Successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
