<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function() {
    return bcrypt('123456');
    return app()->version();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/', 'AdminController@login')->name('login');
        Route::post('/login', 'AdminController@loginProcess')->name('loginProcess'); 
    });
    Route::group(['middleware' => 'admin'], function () {
        Route::post('/logout', 'AdminController@adminLogout')->name('logout');
        Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::resource('/role', 'RoleController')->except('show');
        Route::get('/permissions/{roleId}', 'RoleController@permissions')->name('role.permissions');
        Route::resource('/admin', 'AdminController');
        Route::resource('/category', 'CategoryController')->except('show');
        Route::get('/sub-categories/{id}', 'CategoryController@subCategories')->name('sub_categories');
    });
});


