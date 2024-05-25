<?php

Route::group(['middleware' => 'auth:admin'], function(){
    Route::get('/admin/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');
    Route::post('/admin/dashboard/change', 'Admin\DashboardController@change')->name('admin.dashboard.change');
    Route::prefix('admin')->group(function(){
        Route::prefix('dashboard')->group(function(){
            Route::prefix('table')->group(function(){
                Route::get('/{tahun}', 'Admin\DashboardController@table')->name('admin.dashboard.table');
                Route::get('/detail/{id}', 'Admin\DashboardController@detail')->name('admin.dashboard.detail');
            });

            Route::prefix('grafik')->group(function(){
                // Grafik Old Start
                Route::get('/grafik-bkk-desa-perbulan', 'Admin\DashboardController@grafik_bkk_desa_perbulan')->name('admin.dashboard.grafik-bkk-desa-perbulan');
                Route::get('/grafik-bkk-desa-perpartai', 'Admin\DashboardController@grafik_bkk_desa_perpartai')->name('admin.dashboard.grafik-bkk-desa-perpartai');
                Route::get('/grafik-apbd-papbd-bkk-desa', 'Admin\DashboardController@grafik_apbd_papbd_bkk_desa')->name('admin.dashboard.grafik-apbd-papbd-bkk-desa');
                // Grafik Old End

                // Grafik Start
                Route::get('/grafik-pertahun-anggaran-murni-dan-perubahan', 'Admin\DashboardController@grafikPertahunAnggaranMurnidanPerubahan')->name('admin.dashboard.grafik-pertahun-anggaran-murni-dan-perubahan');
                Route::get('/grafik-bkk-kecamatan-anggaran-murni-dan-perubahan/{tahun}', 'Admin\DashboardController@grafikBkkKecamatanAnggaranMurnidanPerubahan')->name('admin.dashboard.grafik-bkk-kecamatan-anggaran-murni-dan-perubahan');
                // Grafik End
            });
        });
    });

    // Peta Persebaran Bkk
    Route::get('/admin/peta-persebaran-bkk', 'Admin\PetaPersebaranBkkController@index')->name('admin.peta-persebaran-bkk.index');
    Route::get('/admin/peta-persebaran-bkk/get-data', 'Admin\PetaPersebaranBkkController@get_data')->name('admin.peta-persebaran-bkk.get-data');
    Route::post('/admin/peta-persebaran-bkk/filter', 'Admin\PetaPersebaranBkkController@filter_data')->name('admin.peta-persebaran-bkk.filter');

    // Peta Per Kelurahan
    Route::get('/admin/peta-per-kelurahan', 'Admin\PetaPerKelurahanController@index')->name('admin.peta-per-kelurahan.index');
    Route::get('/admin/peta-per-kelurahan/get-data-kelurahan/{id}', 'Admin\PetaPerKelurahanController@get_data_kelurahan');
    Route::get('/admin/peta-per-kelurahan/get-data-kelurahan/detail/{id}', 'Admin\PetaPerKelurahanController@detail');
    Route::get('/admin/peta-per-kelurahan/get-data-kelurahan/{id}/filter/{tahun}', 'Admin\PetaPerKelurahanController@get_data_kelurahan_filter_tahun')->name('admin.peta-per-kelurahan.get-data-kelurahan.filter.tahun');
    Route::get('/admin/peta-per-kelurahan/get-data-kelurahan/detail/{id}/filter/{tahun}', 'Admin\PetaPerKelurahanController@detail_filter_tahun')->name('admin.peta-per-kelurahan.get-data-kelurahan.detail.filter.tahun');

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
    Route::get('/admin/bkk/edit/{id}', 'Admin\BkkController@edit')->name('admin.bkk.edit');
    Route::post('/admin/bkk/update', 'Admin\BkkController@update')->name('admin.bkk.update');
    Route::post('/admin/bkk/destroy', 'Admin\BkkController@destroy')->name('admin.bkk.destroy');
    Route::post('/admin/bkk/impor', 'Admin\BkkController@impor')->name('admin.bkk.impor');
    Route::prefix('admin')->group(function(){
        Route::prefix('bkk')->group(function(){
            Route::post('/delete-bkk-lampiran', 'Admin\BkkController@delete_bkk_lampiran')->name('admin.bkk.delete-bkk-lampiran');
            Route::post('/tambah-bkk-foto-before/{id}', 'Admin\BkkController@tambah_bkk_foto_before')->name('admin.bkk.tambah-bkk-foto-before');
            Route::post('/tambah-bkk-foto-after/{id}', 'Admin\BkkController@tambah_bkk_foto_after')->name('admin.bkk.tambah-bkk-foto-after');

            // Testing Impor Template
            Route::get('/testing-impor-template', 'Admin\BkkController@testingImporTemplate')->name('admin.bkk.testing-import-template');
        });
    });

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

    Route::prefix('admin')->group(function(){
        Route::prefix('manajemen-akun')->group(function(){
            Route::prefix('fasilitator')->group(function(){
                Route::get('/', 'Admin\ManajemenAkun\FasilitatorController@index')->name('admin.manajemen-akun.fasilitator.index');
                Route::get('/detail/{id}', 'Admin\ManajemenAkun\FasilitatorController@show');
                Route::post('','Admin\ManajemenAkun\FasilitatorController@store')->name('admin.manajemen-akun.fasilitator.store');
                Route::get('/edit/{id}', 'Admin\ManajemenAkun\FasilitatorController@edit')->name('admin.manajemen-akun.fasilitator.edit');
                Route::post('/update','Admin\ManajemenAkun\FasilitatorController@update')->name('admin.manajemen-akun.fasilitator.update');
                Route::post('/change-password','Admin\ManajemenAkun\FasilitatorController@change_password')->name('admin.manajemen-akun.fasilitator.change-password');
                Route::post('/destroy','Admin\ManajemenAkun\FasilitatorController@destroy')->name('admin.manajemen-akun.fasilitator.destroy');
            });
        });

        Route::prefix('tahun-periode')->group(function(){
            Route::get('/', 'Admin\TahunPeriodeController@index')->name('admin.tahun-periode.index');
            Route::post('/','Admin\TahunPeriodeController@store')->name('admin.tahun-periode.store');
            Route::get('/edit/{id}','Admin\TahunPeriodeController@edit')->name('admin.tahun-periode.edit');
            Route::post('/update','Admin\TahunPeriodeController@update')->name('admin.tahun-periode.update');
            Route::get('/destroy/{id}','Admin\TahunPeriodeController@destroy')->name('admin.tahun-periode.destroy');

        });

        Route::prefix('normalisasi')->group(function(){
            Route::get('/foto-bkk', 'Admin\NormalisasiController@fotoBkk');
        });
    });
});
