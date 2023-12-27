<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelurahan;
use App\Models\KoordinatKelurahan;
use App\Models\Bkk;
use App\Models\Kecamatan;

class PetaPerKelurahanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::pluck('nama', 'id');
        return view('admin.peta-per-kelurahan.index', [
            'kecamatan' => $kecamatan
        ]);
    }

    // public function get_data_kelurahan()
    // {
    //     $dataKelurahan = [];

    //     $datas = Kelurahan::whereHas('kecamatan', function($q){
    //         $q->whereHas('kabupaten', function($q){
    //             $q->where('id', 62);
    //         });
    //     })->where('id', 1)->get();

    //     foreach ($datas as $data) {
    //         $koordinat_kelurahans = KoordinatKelurahan::where('kelurahan_id', $data->id)->get();
    //         foreach ($koordinat_kelurahans as $koordinat_kelurahan) {
    //             $koordinat = [
    //                 'type' => 'Feature',
    //                 'geometry' => [
    //                     'coordinates' => [[$koordinat_kelurahan->koordinat]],
    //                     'type' => 'Polygon'
    //                 ],
    //                 'properties' => [
    //                     'id' => $koordinat_kelurahan->id,
    //                     'kelurahanId' => $data->id,
    //                     'nama' => $data->nama,
    //                     'warna' => $data->warna
    //                 ]
    //             ] ;
    //             $dataKelurahan[] = $koordinat;
    //         }
    //     }

    //     $geoLocation = [
    //         'type' => 'FeatureCollection',
    //         'features' => $dataKelurahan
    //     ];

    //     $geoJson = collect($geoLocation)->toJson();


    //     return $geoJson;
    // }

    public function get_data_kelurahan($id)
    {
        $bkk = Bkk::where('kelurahan_id', $id)->count();

        return response()->json(['bkk' => $bkk]);
    }

    public function detail($id)
    {
        $bkks = Bkk::where('kelurahan_id', $id)->get();
        $kelurahan = Kelurahan::find($id);
        $html = '';
        $fotoBkk = '';
        foreach ($bkks as $bkk) {
            if($bkk->foto_after)
            {
                $fotoBkk = $bkk->foto_after;
            } else {
                $fotoBkk = $bkk->foto_before;
            }
            if($bkk->aspirator)
            {
                $namaAspirator = $bkk->aspirator->nama;
                $namaFraksi = $bkk->aspirator->master_fraksi->nama;
            } else {
                $namaAspirator = '';
                $namaFraksi = '';
            }
            $html .= '<li>
                        <div class="card">
                            <div class="card-body">
                                <div class="row shadow bg-white text-dark">
                                    <div class="col-6 col-md-4">
                                        <img src="'.asset('images/foto-bkk/'.$fotoBkk).'" alt="" class="img-fluid">
                                    </div>
                                    <div class="col-6 col-md-8">
                                        <p>Uraian: '.$bkk->uraian.'</p>
                                        <p>Alamat: '.$bkk->alamat.'</p>
                                        <p>Aspirator:  '.$namaAspirator.'</p>
                                        <p>Partai: '.$namaFraksi.'</p>
                                        <p>Tahun: '.$bkk->tahun.'</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>';
        }

        return response()->json(['html' => $html, 'kelurahan' => $kelurahan, 'kecamatan' => $kelurahan->kecamatan->nama]);
    }
}
