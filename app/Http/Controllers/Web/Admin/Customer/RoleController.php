<?php

namespace App\Http\Controllers\Web\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('role_management_read'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // search query
        $q = $request->input('q');

        $roles = Role::query();

        if ($q) {
            $roles = $roles->orWhere('name', 'like', '%' . $q . '%')
                ->orWhere('name', 'like', '%' . $q . '%');
        }

        $roles = $roles->latest()->paginate(15);
        return view('backend.customer.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('role_management_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Role::withoutGlobalScopes()
            ->findOrFail(3)
            ->permissions()
            ->get();

        return view('backend.customer.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create($request->only([
            'title',
            'name',
        ]));

        $role->permissions()->sync($request->input('permission_ids', []));

        return redirect()->route('role.index')->with('success', 'Role has been created successfully');
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
        abort_if(Gate::denies('role_management_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role = Role::with('permissions')->findOrFail($id);

        $permissions = Role::withoutGlobalScopes()
            ->findOrFail(3)
            ->permissions()
            ->get();

        return view('backend.customer.role.edit', compact('role', 'permissions'));
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
        $role = Role::with('permissions')->findOrFail($id);

        $role->update($request->only([
            'title',
            'name',
        ]));

        if ($role->permissions->pluck('id')->toArray() != $request->input('permission_ids', [])) {
            $role->permissions()->sync($request->input('permission_ids', []));
        }

        return redirect()->route('role.index')->with('success', 'Role has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('role_management_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role = Role::findOrFail($id);
        $role->delete();

        return back()->with('success', 'Role has been deleted successfully');
    }
}
