<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::if('canAccess', function($permit) {

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
                        return true;
                    }
                }
            } else {
                if (in_array($permit, $permissions)) {
                    return true;
                }
            }

            return false;
        });
    }
}
