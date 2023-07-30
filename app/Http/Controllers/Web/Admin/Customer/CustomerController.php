<?php

namespace App\Http\Controllers\Web\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\Newsletter\MailingList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_management_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // search query
        $q = $request->input('q');

        $customers = User::latest();
        if ($q) {
            $customers = $customers->orWhere('email', 'like', '%' . $q . '%')
                ->orWhere('fname', 'like', '%' . $q . '%')
                ->orWhere('lname', 'like', '%' . $q . '%')
                ->orWhere('phone_number', 'like', '%' . $q . '%');
        }
        $customers = $customers->where('type', 'user')->paginate(15);

        return view('backend.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('user_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backend.customer.create');
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
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $password = $request->input('password');
            $cpassword = $request->input('cpassword');
            $mailing = $request->input('mailing');
            $type = $request->input('type');

            if ($password != $cpassword) {
                return back()->with('warning', 'Password not matched');
            }
            $customer = new User();
            if ($fname) {
                $customer->fname = $fname;
            }
            if ($lname) {
                $customer->lname = $lname;
            }
            $customer->email = $email;
            $customer->password = Hash::make($password);
            if ($type) {
                if ($type == 'su_admin') {
                    if (auth()->user()->type != "su_admin") {
                        return back()->with('warning', 'You cannot assign super user');
                    }
                }
                $customer->type = $type;
            }
            // add into mailing list
            if ($mailing) {
                $mailingList = new MailingList();
                $mailingList->user_id = $$customer->id;
                $mailingList->save();
            }
            $customer->save();

            return back()->with('success', 'New user created');
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
        abort_if(Gate::denies('user_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer = User::findOrFail($id);

        return view('backend.customer.edit', compact('customer'));
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
        $fname = $request->input('fname');
        $lname = $request->input('lname');
        $email = $request->input('email');
        $type = $request->input('type');
        $status = $request->input('status');

        $customer = User::findOrFail($id);
        $customer->fname = $fname;
        $customer->lname = $lname;
        $customer->email = $email;
        if ($type) {
            if ($type == 'su_admin') {
                if (auth()->user()->type != "su_admin") {
                    return back()->with('warning', 'You cannot assign super user');
                }
            }
            $customer->type = $type;
        }
        $customer->status = $status;
        $customer->save();

        return back()->with('success', 'Updated user');
    }

    public function status($id)
    {
        $user = User::find($id);
        if ($user->status == 1) {
            $user->status = 0;
            $user->save();
            return back()->with('success', 'Disabled successfully');
        } else {
            $user->status = 1;
            $user->save();
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
        abort_if(Gate::denies('user_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer = User::findOrFail($id);
        if ($customer->type == 'su_admin') {
            if (auth()->user()->type != "su_admin") {
                return back()->with('warning', 'You cannot delete a super user');
            }
        }
        $customer->delete();
        return redirect('/customer')->with('warning', 'You just deleted a customer');
    }
}
