<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bkk;
use App\Models\MasterFraksi;
use App\Models\MasterJenis;
use App\Models\MasterKategoriPembangunan;

class PetaPersebaranBkkController extends Controller
{
    public function index()
    {
        $fraksi = MasterFraksi::pluck('nama', 'id');
        $jenis = MasterJenis::pluck('nama', 'id');
        $kategori_pembangunan = MasterKategoriPembangunan::pluck('nama', 'id');
        return view('admin.peta-persebaran-bkk.index', [
            'fraksi' => $fraksi,
            'jenis' => $jenis,
            'kategori_pembangunan' => $kategori_pembangunan,
        ]);
    }

    public function get_data()
    {
        $datas = [];
        $bkks = Bkk::all();
        $fotoBkk = '';
        foreach ($bkks as $bkk) {
            if($bkk->foto_after)
            {
                $fotoBkk = $bkk->foto_after;
            } else {
                $fotoBkk = $bkk->foto_before;
            }
            $datas[] = [
                'id' => $bkk->id,
                'uraian' => $bkk->uraian,
                'aspirator' => $bkk->aspirator->nama,
                'partai' => $bkk->aspirator->master_fraksi->nama,
                'logo_partai' => $bkk->aspirator->master_fraksi->logo,
                'tahun' => $bkk->tahun,
                'lat' => (float) $bkk->lat,
                'lng' => (float) $bkk->lng,
                'foto' => $fotoBkk
            ];
        }

        return $datas;
    }

    public function filter_data(Request $request)
    {
        $datas = [];
        $bkks = new Bkk;
        if($request->filter_fraksi_id)
        {
            $bkks = $bkks->whereHas('aspirator', function($q) use ($request){
                $q->whereHas('master_fraksi', functioN($q) use ($request) {
                    $q->where('id', $request->filter_fraksi_id);
                });
            });
        }
        if($request->filter_aspirator_id)
        {
            $bkks = $bkks->where('aspirator_id', $request->filter_aspirator_id);
        }

        if($request->filter_master_jenis_id)
        {
            $bkks = $bkks->where('master_jenis_id', $request->filter_master_jenis_id);
        }

        if($request->filter_tahun)
        {
            $bkks = $bkks->where('tahun', $request->filter_tahun);
        }

        if($request->filter_master_kategori_pembangunan_id)
        {
            $bkks = $bkks->where('master_kategori_pembangunan_id', $request->filter_master_kategori_pembangunan_id);
        }

        $bkks = $bkks->get();
        $fotoBkk = '';
        foreach ($bkks as $bkk) {
            if($bkk->foto_after)
            {
                $fotoBkk = $bkk->foto_after;
            } else {
                $fotoBkk = $bkk->foto_before;
            }
            $datas[] = [
                'id' => $bkk->id,
                'uraian' => $bkk->uraian,
                'aspirator' => $bkk->aspirator->nama,
                'partai' => $bkk->aspirator->master_fraksi->nama,
                'logo_partai' => $bkk->aspirator->master_fraksi->logo,
                'tahun' => $bkk->tahun,
                'lat' => (float) $bkk->lat,
                'lng' => (float) $bkk->lng,
                'foto' => $fotoBkk
            ];
        }

        return $datas;
    }
}
