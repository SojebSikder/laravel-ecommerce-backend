<?php

namespace App\Http\Controllers\Web\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

    public function profile()
    {
        $user = auth('web')->user();

        return view('backend.profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');

            $password = $request->input('password');
            $confirm_password = $request->input('confirm_password');
            $old_password = $request->input('old_password');

            // confirm password
            if ($password != $confirm_password) {
                return back()->with('warning', 'Password not matched correctly :(');
            }

            $user_id = auth()->user()->id;

            $user = User::findOrFail($user_id);
            $user->fname = $fname;
            $user->lname = $lname;
            $user->email = $email;
            if ($password) {
                // varify password
                if (Hash::check($old_password, $user->password)) {
                    $user->password = Hash::make($password);
                } else {
                    return back()->with('warning', 'Old password is not correct :(');
                }
            }
            $user->save();
            return back()->with('success', 'Updated Successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
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
