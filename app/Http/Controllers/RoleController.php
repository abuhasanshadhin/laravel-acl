<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $params = 'role-list|role-create|role-edit|role-delete';
        $this->middleware('permission:' . $params, ['only' => ['index', 'permissions']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::select('id', 'name')->latest()->get();
        return view('admin.role.index', compact('roles'));
    }

    public function permissions($roleId)
    {
        $role = Role::select('id', 'name')->find($roleId);
        $permissions = [];
        foreach ($role->permissions as $p) {
            $name = str_replace('-', ' ', $p->name);
            $batch = ucwords($p->batch);
            $permissions[$batch][] = ucwords($name);
        }
        unset($role->permissions);
        $role->permissions = $permissions;
        return response()->json($role);
    }

    private function permissionAll() {
        $permissionAll = Permission::all();
        $permissions = [];
        foreach ($permissionAll as $p) {
            $name = str_replace('-', ' ', $p->name);
            $batch = ucwords($p->batch);
            $permissions[$batch][] = [
                'id' => $p->id,
                'name' => ucwords($name),
            ];
        }
        return $permissions;
    }

    public function create()
    {
        $permissions = $this->permissionAll();
        return view('admin.role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name|max:180',
            'permissions' => 'required'
        ], [
            'name.required' => 'The role name field is required',
            'permissions.required' => 'Please choose at least one permission',
        ]);

        $role = Role::create(['name' => strtolower($request->name)]);
        $role->permissions()->attach($request->permissions);

        session()->flash('success', 'Role added successfully');
        return redirect()->route('admin.role.index');
    }

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        $permissions = $this->permissionAll();
        $existsPermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.role.edit', compact('permissions', 'role', 'existsPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|max:180',
            'permissions' => 'required',
        ], [
            'name.required' => 'The role name field is required',
            'permissions.required' => 'Please choose at least one permission',
        ]);
        
        $role->update(['name' => strtolower($request->name)]);
        $role->permissions()->sync($request->permissions);

        session()->flash('success', 'Role updated successfully');
        return redirect()->route('admin.role.index');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        session()->flash('success', 'Role deleted successfully');
        return redirect()->route('admin.role.index');
    }
}
