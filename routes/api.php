<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:admin-api')->group(function() {
    Route::resource('admin', 'Admin\AdminController', ['only' => ['index', 'store']]);
    Route::post('/admin/{admin}/role', 'Admin\AdminRoleController@assignRole');
    Route::delete('/admin/{admin}/role', 'Admin\AdminRoleController@removeRole');
});

Route::middleware('auth:admin-api')->group(function() {
    Route::resource('role', 'Backend\RoleController', ['only' => ['index', 'store']]);
});
