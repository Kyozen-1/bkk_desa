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

class DashboardController extends Controller
{
    public function index()
    {
        $tahun_periode = TahunPeriode::where('status', 'Aktif')->first();
        $fraksis = MasterFraksi::paginate(10);
        $kelurahans = Kelurahan::whereHas('kecamatan', function($q){
            $q->whereHas('kabupaten', function($q){
                $q->where('id', 62);
            });
        })->pluck('nama', 'id');
        $masterFraksis = MasterFraksi::pluck('nama', 'id');
        return view('admin.dashboard.index',[
            'tahun_periode' => $tahun_periode,
            'fraksis' => $fraksis,
            'kelurahans' => $kelurahans,
            'masterFraksis' => $masterFraksis
        ]);
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

    public function table($tahun)
    {
        if(request()->ajax())
        {
            $data = Bkk::where('tahun', $tahun);
            if(request()->filter_lokasi)
            {
                $data = $data->where('kelurahan_id', request()->filter_lokasi);
            }
            if(request()->filter_fraksi)
            {
                $data = $data->whereHas('aspirator', function($q){
                    $q->whereHas('master_fraksi', function($q){
                        $q->where('id', request()->filter_fraksi);
                    });
                });
            }
            $data = $data->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('kelurahan_id', function($data){
                    return $data->kelurahan->nama;
                })
                ->editColumn('uraian', function($data){
                    $uraian = strip_tags(substr($data->uraian,0, 40)) . '...';
                    return $uraian;
                })
                ->editColumn('tipe_kegiatan_id', function($data){
                    return $data->master_tipe_kegiatan ? $data->master_tipe_kegiatan->nama : '';
                })
                ->editColumn('apbd', function($data){
                    return 'Rp. '.number_format((int)$data->apbd, 2, ',', '.');
                })
                ->addColumn('fraksi', function($data){
                    if($data->aspirator)
                    {
                        return '<img src="'.asset('images/logo-fraksi/'.$data->aspirator->master_fraksi->logo).'" style="height:3rem;">';
                    }
                })
                ->addColumn('aspirator_id', function($data){
                    return $data->aspirator ? $data->aspirator->nama : '';
                })
                ->addColumn('aksi', function($data){
                    $id = Crypt::encryptString($data->id);
                    return '<button type="button" name="detail" id="'.$id.'" class="detail btn btn-icon waves-effect btn-success" title="Detail Data"><i class="fas fa-eye"></i></button>';
                })
                ->rawColumns(['aksi', 'fraksi'])
                ->make(true);
        }
    }

    public function detail($id)
    {
        $id = Crypt::decryptString($id);
        $data = Bkk::find($id);
        $array = [
            'master_fraksi' => $data->aspirator_id ? $data->aspirator->master_fraksi->nama : '',
            'aspirator' => $data->aspirator->nama,
            'uraian' => $data->uraian,
            'master_jenis' => $data->master_jenis->nama,
            'tipe_kegiatan' => $data->master_tipe_kegiatan->nama,
            'apbd' => 'Rp. '.number_format($data->apbd, 2),
            'p_apbd' => 'Rp. '.number_format($data->p_apbd, 2),
            'tanggal_realisasi' => Carbon::parse($data->tanggal_realisasi)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y'),
            'tahun' => $data->tahun,
            'kecamatan' => $data->kelurahan->kecamatan->nama,
            'kelurahan' => $data->kelurahan->nama,
            'alamat' => $data->alamat,
            'lng' => $data->lng,
            'lat' => $data->lat,
            'foto_before' => $data->foto_before,
            'foto_after' => $data->foto_after,
            'kategori_pembangunan' => $data->master_kategori_pembangunan_id?$data->master_kategori_pembangunan->nama :'',
            'jumlah' => $data->jumlah
        ];

        return response()->json(['result' => $array]);
    }
}
