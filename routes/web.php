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

@include('fasilitator.php');

Route::prefix('admin')->group(function () {
    Route::get('/login','Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
});

Route::prefix('fasilitator')->group(function () {
    Route::get('/login','Auth\FasilitatorLoginController@showLoginForm')->name('fasilitator.login');
    Route::post('/login', 'Auth\FasilitatorLoginController@login')->name('fasilitator.login.submit');
    Route::get('/logout', 'Auth\FasilitatorLoginController@logout')->name('fasilitator.logout');
});
