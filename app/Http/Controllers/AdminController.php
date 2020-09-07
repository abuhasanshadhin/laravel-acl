<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $params = 'admin-list|admin-create|admin-edit|admin-delete';
        $this->middleware('permission:' . $params, ['only' => ['index', 'show']]);
        $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
    }

    public function login()
    {
        return view('admin.login');
    }

    public function loginProcess(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:admins,username',
            'password' => 'required',
        ], ['username.exists' => 'The given username is not exists']);

        $credentials = $request->only('username', 'password');

        $remember = $request->has('remember') ?? false;

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            if (Auth::guard('admin')->user()->is_active) {
                return redirect()->intended('/admin/dashboard');
            }
            $msg = 'Your account is not active.'; 
            $msg .= '<br><small>Please contact with admin.</small>';
            session()->flash('error', $msg);
            return $this->adminLogout();
        }

        session()->flash('error', 'Username or Password was incorrect');
        return back()->withInput($request->only('username'));
    }

    public function adminLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function index()
    {
        $adminUsers = Admin::with('roles')->latest()->get();
        return view('admin.admin-user.index', compact('adminUsers'));
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.admin-user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admins,username|max:180',
            'name' => 'required|max:180',
            'phone' => 'required_without:email',
            'email' => 'required_without:phone',
            'password' => 'required|min:6',
            'roles' => 'required',
            'is_active' => 'required',
        ], ['roles.required' => 'Please select at least one role.']);

        $inputs = $request->except('roles');
        $inputs['password'] = bcrypt($request->password);
        $admin = Admin::create($inputs);
        $admin->roles()->attach($request->roles);

        session()->flash('success', 'Admin user added successfully');
        return redirect()->route('admin.admin.index');
    }

    public function show(Admin $admin)
    {
        //
    }

    public function edit(Admin $admin)
    {
        $roles = Role::all();
        return view('admin.admin-user.edit', compact('roles', 'admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'username' => 'required|max:180',
            'name' => 'required|max:180',
            'phone' => 'required_without:email',
            'email' => 'required_without:phone',
            'password' => 'nullable|min:6',
            'roles' => 'required',
            'is_active' => 'required',
        ], ['roles.required' => 'Please select at least one role.']);

        $inputs = $request->except('roles');

        if ($request->filled('password')) {
            $inputs['password'] = bcrypt($request->password);
        } else {
            unset($inputs['password']);
        }

        $admin->update($inputs);
        $admin->roles()->sync($request->roles);

        session()->flash('success', 'Admin user updated successfully');
        return redirect()->route('admin.admin.index');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        session()->flash('success', 'Admin user deleted successfully');
        return redirect()->route('admin.admin.index');
    }
}
