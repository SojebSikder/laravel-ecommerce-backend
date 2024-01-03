<?php

namespace App\Http\Controllers\Web\Admin\OptionSet;

use App\Http\Controllers\Controller;
use App\Models\OptionSet\OptionSet;
use App\Models\OptionSet\OptionSetElement;
use Illuminate\Http\Request;

class OptionSetController extends Controller
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

        $option_sets = OptionSet::query();

        if ($q) {
            $option_sets = $option_sets->orWhere('name', 'like', '%' . $q . '%');
        }

        $option_sets = $option_sets->with('elements')
            ->latest()->paginate(15);

        return view('backend.option-set.index', compact('option_sets'));
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
        $name = $request->input('name');
        $description = $request->input('description');

        $option_set = new OptionSet();
        $option_set->name = $name;
        $option_set->description = $description;
        $option_set->save();

        return back()->with('success', 'Option Set created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $option_set = OptionSet::findOrFail($id);
        $option_set_elements = OptionSetElement::where('option_set_id', $id)->orderBy('sort_order', 'ASC')->get();

        return view('backend.option-set.option-set-element.index', compact('option_set', 'option_set_elements'));
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
        $name = $request->input('name');
        $description = $request->input('description');

        $option_set = OptionSet::findOrFail($id);
        $option_set->name = $name;
        $option_set->description = $description;
        $option_set->save();

        return back()->with('success', 'Option Set created');
    }

    public function duplicate($id)
    {
        $optionSet = OptionSet::find($id);

        $option_set_elements = OptionSetElement::where('option_set_id', $id)->orderBy('sort_order', 'ASC')->get();


        // first replicate option set
        $newOptionSet = $optionSet->replicate();
        $newOptionSet->name = "new " . $optionSet->name;
        $newOptionSet->save();

        // then replicate option set element
        foreach ($option_set_elements as  $option_set_element) {
            $newOptionSetElement = $option_set_element->replicate();
            $newOptionSetElement->option_set_id = $newOptionSet->id;
            $newOptionSetElement->save();
        }

        return back()->with('success', 'Option set dupilcated');
    }


    public function status($id)
    {
        $option_set = OptionSet::find($id);
        if ($option_set->status == 1) {
            $option_set->status = 0;
            $option_set->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $option_set->status = 1;
            $option_set->save();
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
            $option_set = OptionSet::find($id);
            $option_set->delete();

            return back()->with('success', 'Option Set deleted');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
