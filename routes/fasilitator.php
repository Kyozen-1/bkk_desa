<?php

Route::group(['prefix' => 'fasilitator', 'middleware' => 'auth:fasilitator'], function(){
    // Dashboard
    Route::get('/dashboard', 'Fasilitator\DashboardController@index')->name('fasilitator.dashboard.index');
    Route::post('/dashboard/change', 'Fasilitator\DashboardController@change')->name('fasilitator.dashboard.change');

    // BKK Desa
    Route::get('/bkk', 'Fasilitator\BkkController@index')->name('fasilitator.bkk.index');
    Route::post('/bkk/get-aspirator', 'Fasilitator\BkkController@get_aspirator')->name('admin.bkk.get-aspirator');
    Route::post('/bkk/get-kelurahan', 'Fasilitator\BkkController@get_kelurahan')->name('admin.bkk.get-kelurahan');
    Route::get('/bkk/detail/{id}', 'Fasilitator\BkkController@detail');
    Route::get('/bkk/konfirmasi/{id}', 'Fasilitator\BkkController@konfirmasi');
    Route::post('/bkk/konfirmasi', 'Fasilitator\BkkController@konfirmasi_update')->name('fasilitator.bkk.konfirmasi');
});
