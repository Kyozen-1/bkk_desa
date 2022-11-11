<?php

Route::group(['middleware' => 'auth:admin'], function(){
    Route::get('/admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');
    Route::post('/admin/dashboard/change', 'Admin\DashboardController@change')->name('admin.dashboard.change');

    //Kecamatan
    Route::get('/admin/kecamatan', 'Admin\KecamatanController@index')->name('admin.kecamatan.index');
    Route::get('/admin/kecamatan/detail/{id}', 'Admin\KecamatanController@show');
    Route::post('/admin/kecamatan','Admin\KecamatanController@store')->name('admin.kecamatan.store');
    Route::get('/admin/kecamatan/edit/{id}','Admin\KecamatanController@edit');
    Route::post('/admin/kecamatan/update','Admin\KecamatanController@update')->name('admin.kecamatan.update');
    Route::get('/admin/kecamatan/destroy/{id}','Admin\KecamatanController@destroy');

    //Kelurahan
    Route::get('/admin/kelurahan', 'Admin\KelurahanController@index')->name('admin.kelurahan.index');
    Route::get('/admin/kelurahan/detail/{id}', 'Admin\KelurahanController@show');
    Route::post('/admin/kelurahan','Admin\KelurahanController@store')->name('admin.kelurahan.store');
    Route::get('/admin/kelurahan/edit/{id}','Admin\KelurahanController@edit');
    Route::post('/admin/kelurahan/update','Admin\KelurahanController@update')->name('admin.kelurahan.update');
    Route::get('/admin/kelurahan/destroy/{id}','Admin\KelurahanController@destroy');

    //Master Fraksi
    Route::get('/admin/master-fraksi', 'Admin\MasterFraksiController@index')->name('admin.master-fraksi.index');
    Route::get('/admin/master-fraksi/detail/{id}', 'Admin\MasterFraksiController@show');
    Route::post('/admin/master-fraksi','Admin\MasterFraksiController@store')->name('admin.master-fraksi.store');
    Route::get('/admin/master-fraksi/edit/{id}','Admin\MasterFraksiController@edit');
    Route::post('/admin/master-fraksi/update','Admin\MasterFraksiController@update')->name('admin.master-fraksi.update');
    Route::get('/admin/master-fraksi/destroy/{id}','Admin\MasterFraksiController@destroy');

    //Aspirator
    Route::get('/admin/aspirator', 'Admin\AspiratorController@index')->name('admin.aspirator.index');
    Route::get('/admin/aspirator/detail/{id}', 'Admin\AspiratorController@show');
    Route::post('/admin/aspirator','Admin\AspiratorController@store')->name('admin.aspirator.store');
    Route::get('/admin/aspirator/edit/{id}','Admin\AspiratorController@edit');
    Route::post('/admin/aspirator/update','Admin\AspiratorController@update')->name('admin.aspirator.update');
    Route::get('/admin/aspirator/destroy/{id}','Admin\AspiratorController@destroy');

    //Master Jenis
    Route::get('/admin/master-jenis', 'Admin\MasterJenisController@index')->name('admin.master-jenis.index');
    Route::get('/admin/master-jenis/detail/{id}', 'Admin\MasterJenisController@show');
    Route::post('/admin/master-jenis','Admin\MasterJenisController@store')->name('admin.master-jenis.store');
    Route::get('/admin/master-jenis/edit/{id}','Admin\MasterJenisController@edit');
    Route::post('/admin/master-jenis/update','Admin\MasterJenisController@update')->name('admin.master-jenis.update');
    Route::get('/admin/master-jenis/destroy/{id}','Admin\MasterJenisController@destroy');

    // BKK
    Route::get('/admin/bkk', 'Admin\BkkController@index')->name('admin.bkk.index');
});
