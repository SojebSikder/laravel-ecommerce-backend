<?php

namespace App\Http\Controllers\Web\Admin\Marketing\CustomMail;

use App\Helper\SettingHelper;
use App\Http\Controllers\Controller;
use App\Mail\User\Marketing\CustomMail\CustomMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomMailController extends Controller
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
        return view('backend.marketing.sendmail.create');
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
            $to_email = $request->input('to_email');
            // $name = $request->input('name');
            $subject = $request->input('subject');
            $body = $request->input('body');

            $from_email = SettingHelper::get('email');
            $from_name = SettingHelper::get('name');

            $data = new \stdClass();
            $data->subject = $subject;
            $data->body = $body;
            $data->from_email = $from_email;
            $data->from_name = $from_name;

            $to = [
                [
                    'email' => $to_email,
                    // 'name' => $name,
                ]
            ];
            Mail::to($to)->send(new CustomMail($data));

            return back()->with('success', 'Mail sent to queue');
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
        //
    }
}
