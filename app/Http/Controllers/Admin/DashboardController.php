<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Validator;
use Carbon\Carbon;
use App\Models\Bkk;
use App\Models\MasterFraksi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function change(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->color_layout = $request->color_layout;
        $user->nav_color = $request->nav_color;
        $user->behaviour = $request->behaviour;
        $user->layout = $request->layout;
        $user->radius = $request->radius;
        $user->placement = $request->placement;
        $user->save();
    }

    public function grafik_bkk_desa_perbulan()
    {
        $bulans = [
            [
                'id' => '01',
                'nama' => 'Januari',
            ],
            [
                'id' => '02',
                'nama' => 'Februari',
            ],
            [
                'id' => '03',
                'nama' => 'Maret',
            ],
            [
                'id' => '04',
                'nama' => 'April',
            ],
            [
                'id' => '05',
                'nama' => 'Mei',
            ],
            [
                'id' => '06',
                'nama' => 'Juni',
            ],
            [
                'id' => '07',
                'nama' => 'Juli',
            ],
            [
                'id' => '08',
                'nama' => 'Agustus',
            ],
            [
                'id' => '09',
                'nama' => 'September',
            ],
            [
                'id' => '10',
                'nama' => 'Oktober',
            ],
            [
                'id' => '11',
                'nama' => 'November',
            ],
            [
                'id' => '12',
                'nama' => 'Desember',
            ],
        ];

        $data_bkk = [];
        foreach ($bulans as $bulan) {
            $data_bkk[] = Bkk::where('status_konfirmasi', 'ya')
                            ->whereMonth('tanggal_realisasi', $bulan['id'])
                            ->whereYear('tanggal_realisasi', Carbon::now()->year)
                            ->count();
        }

        $nama_bulan = [];
        foreach ($bulans as $bulan) {
            $nama_bulan[] = $bulan['nama'];
        }

        return response()->json([
            'data_bkk' => $data_bkk,
            'nama_bulan' => $nama_bulan
        ]);
    }

    public function grafik_bkk_desa_perpartai()
    {
        $getPartais = MasterFraksi::all();
        $data_bkk = [];
        foreach ($getPartais as $getPartai) {
            $data_bkk[] = Bkk::where('status_konfirmasi', 'ya')
                            ->whereYear('tanggal_realisasi', Carbon::now()->year)
                            ->whereHas('aspirator', function($q) use ($getPartai){
                                $q->where('master_fraksi_id', $getPartai->id);
                            })
                            ->count();
        }

        $nama_partai = [];
        foreach ($getPartais as $getPartai) {
            $nama_partai[] = $getPartai->nama;
        }

        return response()->json([
            'data_bkk' => $data_bkk,
            'nama_partai' => $nama_partai
        ]);
    }

    public function grafik_apbd_papbd_bkk_desa()
    {
        $bulans = [
            [
                'id' => '01',
                'nama' => 'Januari',
            ],
            [
                'id' => '02',
                'nama' => 'Februari',
            ],
            [
                'id' => '03',
                'nama' => 'Maret',
            ],
            [
                'id' => '04',
                'nama' => 'April',
            ],
            [
                'id' => '05',
                'nama' => 'Mei',
            ],
            [
                'id' => '06',
                'nama' => 'Juni',
            ],
            [
                'id' => '07',
                'nama' => 'Juli',
            ],
            [
                'id' => '08',
                'nama' => 'Agustus',
            ],
            [
                'id' => '09',
                'nama' => 'September',
            ],
            [
                'id' => '10',
                'nama' => 'Oktober',
            ],
            [
                'id' => '11',
                'nama' => 'November',
            ],
            [
                'id' => '12',
                'nama' => 'Desember',
            ],
        ];

        $tipes = ['apbd', 'p_apbd'];

        $i = 1;

        foreach ($tipes as $tipe) {
            $dBkkDesa = [];
            foreach ($bulans as $bulan) {
                $dBkkDesa[] = Bkk::where('status_konfirmasi', 'ya')
                                ->whereMonth('tanggal_realisasi', $bulan['id'])
                                ->whereYear('tanggal_realisasi', Carbon::now()->year)
                                ->sum($tipe);
            }
            if($tipe == 'apbd')
            {
                $name_tipe = 'APBD';
            } else {
                $name_tipe = 'P-APBD';
            }
            $data_bkk[] = [
                'name' => $name_tipe,
                'data' => $dBkkDesa
            ];
            $i++;
        }

        $nama_bulan = [];
        foreach ($bulans as $bulan) {
            $nama_bulan[] = $bulan['nama'];
        }

        return response()->json([
            'data_bkk' => $data_bkk,
            'nama_bulan' => $nama_bulan
        ]);
    }
}
