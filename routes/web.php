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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'Auth\ProfileController@show')->name('profile');

Route::get('/auth/{provider}', 'Auth\SocialLoginController@redirectToProvider');
Route::get('/auth/{provider}/callback', 'Auth\SocialLoginController@handleProviderCallback');
Route::post('/password/change', 'Auth\ChangePasswordController@change')->name('password.change');

Route::get('/activate/{token}', 'Frontend\ActivationController@activate')->name('activation');
Route::get('/resend-activation-link', 'Frontend\ActivationController@resend');

Route::get('/admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('/admin/login', 'Auth\AdminLoginController@login');
Route::get('/admin/dashboard', 'Auth\AdminLoginController@dashboard')->name('admin.dashboard');
