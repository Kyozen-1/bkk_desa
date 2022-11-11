<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'Auth\AdminLoginController@showLoginForm');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
Auth::routes(['register' => false, 'login' => false]);
@include('admin.php');

Route::get('admin/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
Route::get('admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
