<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permit)
    {
        $roles = Auth::guard('admin')->user()->roles;

        $permissions = [];

        foreach ($roles as $key => $role) {
            $_permissions = $role->permissions->pluck('name')->toArray();
            $_permissions = array_merge($permissions, $_permissions);
            $permissions = array_unique($_permissions);
        }

        if (strpos($permit, '|') !== false) {
            $permits = explode('|', $permit);
            foreach ($permits as $value) {
                if (in_array($value, $permissions)) {
                    return $next($request);
                }
            }
        } else {
            if (in_array($permit, $permissions)) {
                return $next($request);
            }
        }

        abort(401);
    }
}
