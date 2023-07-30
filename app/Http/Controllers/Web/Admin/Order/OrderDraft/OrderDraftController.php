<?php

namespace App\Http\Controllers\Web\Admin\Order\OrderDraft;

use App\Http\Controllers\Controller;
use App\Models\Order\OrderDraft\OrderDraft;
use App\Models\Order\OrderDraft\OrderDraftItem;
use App\Models\Product\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderDraftController extends Controller
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

        $order_drafts = OrderDraft::query();

        if ($q) {
            $order_drafts = $order_drafts->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $order_drafts = $order_drafts->latest()->paginate(15);
        return view('backend.order.draft.index', compact('order_drafts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('status', 1)->get();
        return view('backend.order.draft.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer_id = $request->input('user_id');
        $comment = $request->input('comment');

        $order_draft = new OrderDraft();
        if ($customer_id) {
            $order_draft->user_id = $customer_id;
        }
        if ($comment) {
            $order_draft->comment = $comment;
        }
        $order_draft->save();

        return redirect("order-draft/$order_draft->id")->with('success', 'Order Draft Created Successfully. Add items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order_draft = OrderDraft::findOrFail($id);
        return view('backend.order.draft.show', compact('order_draft'));
    }

    public function create_product($id, Request $request)
    {
        $order_draft_id = $id;

        $order_draft = OrderDraft::findOrFail($order_draft_id);

        // search query
        $q = $request->input('q');

        $products = Product::query();

        if ($q) {
            $products = $products->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('slug', 'like', '%' . $q . '%');
        }

        $products = $products->latest()->where('status', 1)->paginate(15);

        return view('backend.order.draft.product.create', compact('order_draft', 'products'));
    }

    public function store_item(Request $request)
    {
        $order_draft_id = $request->input('order_draft_id');
        $product_id = $request->input('product_id');

        $order_draft = OrderDraft::findOrFail($order_draft_id);

        $order_draft_item = new OrderDraftItem();
        $order_draft_item->order_draft_id = $order_draft->id;


        $product = Product::findOrFail($product_id);
        if (!$product) {
            return back()->with('warning', 'Product not found');
        }
        $order_draft_item->product_id = $product->id;


        $order_draft_item->save();

        return back()->with('success', 'Product added successfully');
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
        $order_draft = OrderDraft::findOrFail($id);
        $order_draft->delete();
        return back()->with('success', 'Order Draft Deleted Successfully');
    }
}
