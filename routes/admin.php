<?php

Route::group(['middleware' => 'auth:admin'], function(){
    Route::get('/admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');
    Route::post('/admin/dashboard/change', 'Admin\DashboardController@change')->name('admin.dashboard.change');

    // Peta Persebaran Bkk
    Route::get('/admin/peta-persebaran-bkk', 'Admin\PetaPersebaranBkkController@index')->name('admin.peta-persebaran-bkk.index');
    Route::get('/admin/peta-persebaran-bkk/get-data', 'Admin\PetaPersebaranBkkController@get_data')->name('admin.peta-persebaran-bkk.get-data');
    Route::post('/admin/peta-persebaran-bkk/filter', 'Admin\PetaPersebaranBkkController@filter_data')->name('admin.peta-persebaran-bkk.filter');

    // Peta Per Kelurahan
    Route::get('/admin/peta-per-kelurahan', 'Admin\PetaPerKelurahanController@index')->name('admin.peta-per-kelurahan.index');
    Route::get('/admin/peta-per-kelurahan/get-data-kelurahan/{id}', 'Admin\PetaPerKelurahanController@get_data_kelurahan');
    Route::get('/admin/peta-per-kelurahan/get-data-kelurahan/detail/{id}', 'Admin\PetaPerKelurahanController@detail');

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
    Route::get('/admin/master-partai', 'Admin\MasterFraksiController@index')->name('admin.master-fraksi.index');
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
    Route::get('/admin/bkk/create', 'Admin\BkkController@create')->name('admin.bkk.create');
    Route::post('/admin/bkk/get-aspirator', 'Admin\BkkController@get_aspirator')->name('admin.bkk.get-aspirator');
    Route::post('/admin/bkk/get-kelurahan', 'Admin\BkkController@get_kelurahan')->name('admin.bkk.get-kelurahan');
    Route::post('/admin/bkk/store', 'Admin\BkkController@store')->name('admin.bkk.store');
    Route::get('/admin/bkk/detail/{id}', 'Admin\BkkController@detail');
    Route::get('/admin/bkk/edit/{id}', 'Admin\BkkController@edit');
    Route::post('/admin/bkk/update', 'Admin\BkkController@update')->name('admin.bkk.update');
    Route::get('/admin/bkk/destroy/{id}', 'Admin\BkkController@destroy');

    //Master Tipe Kegiatan
    Route::get('/admin/master-tipe-kegiatan', 'Admin\MasterTipeKegiatanController@index')->name('admin.master-tipe-kegiatan.index');
    Route::get('/admin/master-tipe-kegiatan/detail/{id}', 'Admin\MasterTipeKegiatanController@show');
    Route::post('/admin/master-tipe-kegiatan','Admin\MasterTipeKegiatanController@store')->name('admin.master-tipe-kegiatan.store');
    Route::get('/admin/master-tipe-kegiatan/edit/{id}','Admin\MasterTipeKegiatanController@edit');
    Route::post('/admin/master-tipe-kegiatan/update','Admin\MasterTipeKegiatanController@update')->name('admin.master-tipe-kegiatan.update');
    Route::get('/admin/master-tipe-kegiatan/destroy/{id}','Admin\MasterTipeKegiatanController@destroy');

    //Master Kategori Pembangunan
    Route::get('/admin/master-kategori-pembangunan', 'Admin\MasterKategoriPembangunanController@index')->name('admin.master-kategori-pembangunan.index');
    Route::get('/admin/master-kategori-pembangunan/detail/{id}', 'Admin\MasterKategoriPembangunanController@show');
    Route::post('/admin/master-kategori-pembangunan','Admin\MasterKategoriPembangunanController@store')->name('admin.master-kategori-pembangunan.store');
    Route::get('/admin/master-kategori-pembangunan/edit/{id}','Admin\MasterKategoriPembangunanController@edit');
    Route::post('/admin/master-kategori-pembangunan/update','Admin\MasterKategoriPembangunanController@update')->name('admin.master-kategori-pembangunan.update');
    Route::get('/admin/master-kategori-pembangunan/destroy/{id}','Admin\MasterKategoriPembangunanController@destroy');
});
