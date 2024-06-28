<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Validator;
use DataTables;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Bkk;
use App\Models\MasterFraksi;
use App\Models\TahunPeriode;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use App\Models\MasterTipeKegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun_periode = TahunPeriode::where('status', 'Aktif')->first();
        return view('admin.dashboard.index',[
            'tahun_periode' => $tahun_periode
        ]);
    }

    public function grafikPertahunAnggaranMurnidanPerubahan()
    {
        if(request()->ajax())
        {
            $tahuns = [];

            $tahunSekarang = Carbon::now()->year;

            for ($i=0; $i < 10; $i++) {
                $tahuns[] = $tahunSekarang - $i;
            }

            $tahuns = array_reverse($tahuns);

            $tipeAnggarans = ['apbd', 'p_apbd'];

            foreach ($tipeAnggarans as $tipeAnggaran) {
                $dAnggaran = [];
                foreach ($tahuns as $tahun) {
                    if($tipeAnggaran == 'apbd')
                    {
                        $dAnggaran[] = Bkk::where('tahun', $tahun)->where('status_konfirmasi', 'ya')->sum('apbd');
                    }

                    if($tipeAnggaran == 'p_apbd')
                    {
                        $dAnggaran[] = Bkk::where('tahun', $tahun)->where('status_konfirmasi', 'ya')->sum('p_apbd');
                    }
                }

                if($tipeAnggaran == 'apbd')
                {
                    $namaAnggaran = 'Anggaran Murni';
                }

                if($tipeAnggaran == 'p_apbd')
                {
                    $namaAnggaran = 'Anggaran Perubahan';
                }

                $data_anggaran[] = [
                    'name' => $namaAnggaran,
                    'data' => $dAnggaran
                ];
            }

            return response()->json([
                'data_anggaran' => $data_anggaran,
                'tahun' => $tahuns
            ]);
        }
    }

    public function grafikBkkKecamatanAnggaranMurnidanPerubahan($tahun)
    {
        if(request()->ajax())
        {
            $kecamatans = Kecamatan::where('kabupaten_id', 62)->pluck('nama', 'id');

            $tipeAnggarans = ['apbd', 'p_apbd'];

            foreach ($tipeAnggarans as $tipeAnggaran) {
                $dAnggaran = [];
                foreach ($kecamatans as $id => $nama) {
                    if($tipeAnggaran == 'apbd')
                    {
                        $tempAnggaran = Bkk::whereHas('kelurahan', function($q) use ($id){
                                            $q->where('kecamatan_id', $id);
                                        })->where('status_konfirmasi', 'ya');
                        if($tahun != 'semua')
                        {
                            $tempAnggaran = $tempAnggaran->where('tahun', $tahun);
                        }
                        $tempAnggaran = $tempAnggaran->sum('apbd');

                        $dAnggaran[] = $tempAnggaran;
                    }

                    if($tipeAnggaran == 'p_apbd')
                    {
                        $tempAnggaran = Bkk::whereHas('kelurahan', function($q) use ($id){
                                            $q->where('kecamatan_id', $id);
                                        })->where('status_konfirmasi', 'ya');
                        if($tahun != 'semua')
                        {
                            $tempAnggaran = $tempAnggaran->where('tahun', $tahun);
                        }
                        $tempAnggaran = $tempAnggaran->sum('p_apbd');

                        $dAnggaran[] = $tempAnggaran;
                    }
                }

                if($tipeAnggaran == 'apbd')
                {
                    $namaAnggaran = 'Anggaran Murni';
                }

                if($tipeAnggaran == 'p_apbd')
                {
                    $namaAnggaran = 'Anggaran Perubahan';
                }

                $data_anggaran[] = [
                    'name' => $namaAnggaran,
                    'data' => $dAnggaran
                ];
            }

            $nama_kecamatan = [];
            foreach ($kecamatans as $id => $nama) {
                $nama_kecamatan[] = $nama;
            }

            return response()->json([
                'data_anggaran' => $data_anggaran,
                'nama_kecamatan' => $nama_kecamatan
            ]);
        }
    }

    public function grafikBkkBerdasarkanTipeKegiatan($tahun)
    {
        if(request()->ajax())
        {
            $tipeKegiatans = MasterTipeKegiatan::pluck('nama', 'id');
            $tipeAnggarans = ['apbd', 'p_apbd'];

            foreach ($tipeAnggarans as $tipeAnggaran) {
                $dAnggaran = [];
                foreach ($tipeKegiatans as $id => $nama) {
                    if($tipeAnggaran == 'apbd')
                    {
                        $tempAnggaran = Bkk::where('tipe_kegiatan_id', $id)
                                        ->where('status_konfirmasi', 'ya');
                        if($tahun != 'semua')
                        {
                            $tempAnggaran = $tempAnggaran->where('tahun', $tahun);
                        }
                        $tempAnggaran = $tempAnggaran->sum('apbd');

                        $dAnggaran[] = $tempAnggaran;
                    }

                    if($tipeAnggaran == 'p_apbd')
                    {
                        $tempAnggaran = Bkk::where('tipe_kegiatan_id', $id)
                                        ->where('status_konfirmasi', 'ya');
                        if($tahun != 'semua')
                        {
                            $tempAnggaran = $tempAnggaran->where('tahun', $tahun);
                        }
                        $tempAnggaran = $tempAnggaran->sum('p_apbd');

                        $dAnggaran[] = $tempAnggaran;
                    }
                }

                if($tipeAnggaran == 'apbd')
                {
                    $namaAnggaran = 'Anggaran Murni';
                }

                if($tipeAnggaran == 'p_apbd')
                {
                    $namaAnggaran = 'Anggaran Perubahan';
                }

                $data_anggaran[] = [
                    'name' => $namaAnggaran,
                    'data' => $dAnggaran
                ];
            }

            $nama_tipe_kegiatan = [];
            foreach ($tipeKegiatans as $id => $nama) {
                $nama_tipe_kegiatan[] = $nama;
            }

            return response()->json([
                'data_anggaran' => $data_anggaran,
                'nama_tipe_kegiatan' => $nama_tipe_kegiatan
            ]);
        }
    }
}
