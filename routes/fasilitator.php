<?php

Route::group(['prefix' => 'fasilitator', 'middleware' => 'auth:fasilitator'], function(){
    Route::get('/dashboard', 'Fasilitator\DashboardController@index')->name('fasilitator.dashboard.index');
    Route::post('/dashboard/change', 'Fasilitator\DashboardController@change')->name('fasilitator.dashboard.change');
});
