<?php

namespace App\Http\Controllers\Web\Admin\OptionSet;

use App\Http\Controllers\Controller;
use App\Models\OptionSet\OptionSet;
use App\Models\OptionSet\OptionSetElement;
use Illuminate\Http\Request;

class OptionSetElementController extends Controller
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
    public function create($id)
    {
        $option_set = OptionSet::findOrFail($id);
        $elements = OptionSetElement::where('option_set_id', $id)->get();
        return view('backend.option-set.option-set-element.add', compact('option_set', 'elements'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $option_set_id = $request->input('option_set_id');
        $type = $request->input('type'); // values: text, select
        $label = $request->input('label');
        $name = $request->input('name');
        $help_text = $request->input('help_text');
        $is_condition = $request->input('is_condition') == 1 ? "true" : "false";
        $condition = json_encode($request->input('condition'));
        // text
        $limit = $request->input('limit');
        $placeholder = $request->input('placeholder');
        // dialog
        $dialog_title = $request->input('dialog_title');
        $dialog_body = $request->input('dialog_body');
        // fontpreview
        $font_id = $request->input('font_id');
        $color = $request->input('color');
        // select
        $option_value = json_encode($request->input("option_value")); // values [{value:value}]


        $element = new OptionSetElement();

        $element->option_set_id = $option_set_id;
        $element->type = $type;
        $element->label = $label;
        $element->name = $name;
        $element->help_text = $help_text;
        $element->placeholder = $placeholder;
        if ($is_condition == "true") {
            $element->condition = $condition;
        } else {
            $element->condition = null;
        }
        $element->is_condition = $is_condition;
        if ($type == "select") {
            // select type
            if ($option_value == null) {
                $element->option_value = null;
            } else {
                $element->option_value = $option_value;
            }
        } else if ($type == "dialog") {
            // dialog type
            if ($dialog_title) {
                $element->dialog_title = $dialog_title;
            }
            if ($dialog_body) {
                $element->dialog_body = $dialog_body;
            }
        } else if ($type == "fontpreview") {
            // fontpreview type
            if ($font_id) {
                $element->font_id = $font_id;
            }
            if ($color) {
                $element->color = $color;
            }
        } else {
            // text, textarea type
            // if ($limit) {
            //     $element->limit = $limit;
            // } else {
            //     $element->limit = null;
            // }
        }
        if ($limit) {
            $element->limit = $limit;
        } else {
            $element->limit = null;
        }
        $element->save();

        return back()->with('sms', 'Element Added Successfully');
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
        $element = OptionSetElement::findOrFail($id);
        $elements = OptionSetElement::where('option_set_id', $element->option_set_id)->get();
        return view('backend.option-set.option-set-element.edit', compact('element', 'elements'));
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
        $type = $request->input('type'); // values: text, select
        $label = $request->input('label');
        $name = $request->input('name');
        $help_text = $request->input('help_text');
        $is_condition = $request->input('is_condition') == 1 ? "true" : "false";
        $condition = json_encode($request->input('condition'));
        // text
        $limit = $request->input('limit');
        $placeholder = $request->input('placeholder');
        // dialog
        $dialog_title = $request->input('dialog_title');
        $dialog_body = $request->input('dialog_body');
        // fontpreview
        $font_id = $request->input('font_id');
        $color = $request->input('color');
        // select
        $option_value = json_encode($request->input("option_value")); // values [{value:value}]


        $element = OptionSetElement::where('id', $id)->first();

        $element->type = $type;
        $element->label = $label;
        $element->name = $name;
        $element->help_text = $help_text;
        $element->placeholder = $placeholder;
        if ($is_condition == "true") {
            $element->condition = $condition;
        } else {
            $element->condition = null;
        }
        $element->is_condition = $is_condition;
        if ($type == "select") {
            // select type
            if ($option_value == null) {
                $element->option_value = null;
            } else {
                $element->option_value = $option_value;
            }
        } else if ($type == "dialog") {
            // dialog type
            if ($dialog_title) {
                $element->dialog_title = $dialog_title;
            }
            if ($dialog_body) {
                $element->dialog_body = $dialog_body;
            }
        } else if ($type == "fontpreview") {
            // fontpreview type
            if ($font_id) {
                $element->font_id = $font_id;
            } else {
                $element->font_id = null;
            }
            if ($color) {
                $element->color = $color;
            } else {
                $element->color = null;
            }
        } else {
            // text, textarea type

        }
        if ($limit) {
            $element->limit = $limit;
        } else {
            $element->limit = null;
        }
        $element->save();

        return back()->with('sms', 'Element Updated Successfully');
    }

    // create duplicate element
    public function duplicate($id)
    {
        $element = OptionSetElement::find($id);

        $newElement = $element->replicate();
        $newElement->name = "new " . $element->name;
        $newElement->save();

        return back()->with('sms', 'Element dupilcated');
    }

    public function status($id)
    {
        $element = OptionSetElement::find($id);
        if ($element->status == '1') {
            $element->status = 0;
            $element->save();
            return back()->with('sms', 'Deactivated');
        } else {
            $element->status = 1;
            $element->save();
            return back()->with('sms', 'Activated');
        }
    }

    public function sortingOrder(Request $request, $id)
    {
        $sortValue = $request->input('sort');
        $element = OptionSetElement::find($id);

        $element->sort_order = $sortValue;
        $element->save();
        return back()->with('sms', 'Element sorted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $element = OptionSetElement::where('id', $id)->first();
        $element->delete();
        return back()->with('sms', 'Element Deleted Successfully');
    }
}
