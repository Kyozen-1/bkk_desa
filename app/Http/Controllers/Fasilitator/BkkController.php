<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Validator;
use DataTables;
use Carbon\Carbon;
use Auth;
use App\Models\MasterFraksi;
use App\Models\Aspirator;
use App\Models\LogAktivitas;
use App\Models\Bkk;
use App\Models\MasterJenis;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use App\Models\MasterTipeKegiatan;
use App\Models\MasterKategoriPembangunan;
use App\Models\PivotBkkLampiran;

class BkkController extends Controller
{
    public function index()
    {
        if(request()->ajax())
        {
            $data = new Bkk;
            if(request()->filter_kecamatan_id)
            {
                $data = $data->whereHas('kelurahan', function($q){
                    $q->where('kecamatan_id', request()->filter_kecamatan_id);
                });
            }
            if(request()->filter_kelurahan_id)
            {
                $data = $data->where('kelurahan_id', request()->filter_kelurahan_id);
            }

            if(request()->filter_tahun)
            {
                $data = $data->where('tahun', request()->filter_tahun);
            }

            if(request()->filter_fraksi_id)
            {
                // $data = $data->where('fraksi_id', request()->filter_fraksi_id);
                $data = $data->whereHas('aspirator', function($q){
                    $q->whereHas('master_fraksi', function($q){
                        $q->where('id', request()->filter_fraksi_id);
                    });
                });
            }

            if(request()->filter_aspirator_id)
            {
                $data = $data->where('aspirator_id', request()->filter_aspirator_id);
            }

            $data = $data->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($data){
                    $button_show = '<button type="button" name="detail" id="'.$data->id.'" class="detail btn btn-icon waves-effect btn-info" title="Detail Data"><i class="fas fa-eye"></i></button>';
                    $button_confirm = '<a href="'.url('/fasilitator/bkk/konfirmasi/'.$data->id).'" class="konfirmasi btn btn-icon waves-effect btn-success" title="Konfirmasi Data"><i class="fas fa-check"></i></a>';
                    if($data->status_konfirmasi == 'ya')
                    {
                        return $button_show;
                    } else {
                        return $button_show.' '.$button_confirm;
                    }
                })
                ->editColumn('uraian', function($data){
                    $uraian = strip_tags(substr($data->uraian,0, 40)) . '...';
                    return $uraian;
                })
                ->editColumn('kelurahan_id', function($data){
                    return $data->kelurahan->nama;
                })
                ->editColumn('tipe_kegiatan_id', function($data){
                    return $data->master_tipe_kegiatan ? $data->master_tipe_kegiatan->nama : '';
                })
                ->editColumn('apbd', function($data){
                    return 'Rp. '.number_format((int)$data->apbd, 2, ',', '.');
                })
                ->addColumn('master_fraksi', function($data){
                    return $data->aspirator ? $data->aspirator->master_fraksi->nama : '';
                })
                ->addColumn('foto', function($data){
                    $cekFotoAfter = $data->pivot_bkk_lampiran->where('status', 'after')->first();
                    if($cekFotoAfter)
                    {
                        return '<img src="'.asset('images/foto-bkk/'.$cekFotoAfter->nama).'" style="width:5rem;" title="Foto Setelah">';
                    } else {
                        $cekFotoBefore = $data->pivot_bkk_lampiran->where('status', 'before')->first();
                        if($cekFotoBefore)
                        {
                            return '<img src="'.asset('images/foto-bkk/'.$cekFotoBefore->nama).'" style="width:5rem;" title="Foto Sebelum">';
                        }
                    }
                })
                ->editColumn('status_konfirmasi', function($data){
                    if($data->status_konfirmasi == 'ya')
                    {
                        $html = '<i class="fas fa-check text-success" title="Sudah Di Konfirmasi"></i>';
                    }
                    if($data->status_konfirmasi == 'tidak')
                    {
                        $html = '<i class="fas fa-minus text-danger" title="Belum Di Konfirmasi"></i>';
                    }
                    return $html;
                })
                ->rawColumns(['aksi', 'foto', 'status_konfirmasi'])
                ->make(true);
        }
        $master_kecamatan = Kecamatan::pluck('nama', 'id');
        $master_fraksi = MasterFraksi::pluck('nama', 'id');
        $master_jenis = MasterJenis::pluck('nama', 'id');

        return view('fasilitator.bkk.index',[
            'master_kecamatan' => $master_kecamatan,
            'master_fraksi' => $master_fraksi,
            'master_jenis' => $master_jenis
        ]);
    }

    public function get_aspirator(Request $request)
    {
        $aspirator = Aspirator::where('master_fraksi_id', $request->id)->pluck('nama', 'id');
        return response()->json($aspirator);
    }

    public function get_kelurahan(Request $request)
    {
        $kelurahan = Kelurahan::where('kecamatan_id', $request->id)->pluck('nama', 'id');
        return response()->json($kelurahan);
    }

    public function detail($id)
    {
        $data = Bkk::find($id);

        $array = [
            'master_fraksi' => $data->aspirator_id ? $data->aspirator->master_fraksi->nama : '',
            'aspirator' => $data->aspirator->nama,
            'uraian' => $data->uraian,
            'master_jenis' => $data->master_jenis->nama,
            'tipe_kegiatan' => $data->master_tipe_kegiatan->nama,
            'apbd' => $data->p_apbd,
            'p_apbd' => $data->p_apbd,
            'tanggal_realisasi' => Carbon::parse($data->tanggal_realisasi)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y'),
            'tahun' => $data->tahun,
            'kecamatan' => $data->kelurahan->kecamatan->nama,
            'kelurahan' => $data->kelurahan->nama,
            'alamat' => $data->alamat,
            'lng' => $data->lng,
            'lat' => $data->lat,
            // 'foto_before' => $data->foto_before,
            // 'foto_after' => $data->foto_after,
            'kategori_pembangunan' => $data->master_kategori_pembangunan_id?$data->master_kategori_pembangunan->nama :'',
            'jumlah' => $data->jumlah,
            'foto_before' => $data->pivot_bkk_lampiran
                            ->where('status', 'before')
                            ->map(function($d){
                                return [
                                    'id' => $d->id,
                                    'nama' => $d->nama
                                ];
                            }),
            'foto_after' => $data->pivot_bkk_lampiran
                            ->where('status', 'after')
                            ->map(function($d){
                                return [
                                    'id' => $d->id,
                                    'nama' => $d->nama
                                ];
                            }),
        ];

        return response()->json(['result' => $array]);
    }

    public function konfirmasi($id)
    {
        $data = Bkk::find($id);
        $master_kecamatan = Kecamatan::pluck('nama', 'id');
        $master_fraksi = MasterFraksi::pluck('nama', 'id');
        $master_jenis = MasterJenis::pluck('nama', 'id');
        $kecamatan = Kecamatan::pluck('nama', 'id');
        $master_tipe_kegiatan = MasterTipeKegiatan::pluck('nama', 'id');
        $master_kategori_pembangunans = MasterKategoriPembangunan::all();

        return view('fasilitator.bkk.konfirmasi', [
            'master_kecamatan' => $master_kecamatan,
            'master_fraksi' => $master_fraksi,
            'master_jenis' => $master_jenis,
            'kecamatan' => $kecamatan,
            'data' => $data,
            'master_tipe_kegiatan' => $master_tipe_kegiatan,
            'master_kategori_pembangunans' => $master_kategori_pembangunans
        ]);
    }

    public function konfirmasi_update(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'bkk_id' => 'required',
            'foto_after.*' => 'mimes:jpeg,jpg,png,gif',
        ]);

        if($errors -> fails())
        {
            return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
        }

        // $fotoAfterExtension = $request->foto_after->extension();
        // $fotoAfterName =  uniqid().'-'.date("ymd").'.'.$fotoAfterExtension;
        // $fotoAfter = Image::make($request->foto_after);
        // $fotoAfterSize = public_path('images/foto-bkk/'.$fotoAfterName);
        // $fotoAfter->save($fotoAfterSize, 100);

        $bkk = Bkk::find($request->bkk_id);
        // $bkk->foto_after = $fotoAfterName;
        $bkk->status_konfirmasi = 'ya';
        $bkk->tanggal_konfirmasi = Carbon::now();
        $bkk->konfirmasi_by_fasilitator_id = Auth::user()->fasilitator_id;
        $bkk->save();

        $dataFotoAfter = [];
        foreach ($request->file('foto_after') as $file) {
            $fotoAfterExtension = $file->extension();
            $width = getimagesize($file)[0];
            $fotoAfterName = uniqid().'-'.date("ymd").'.'.$fotoAfterExtension;
            $fotoAfter = Image::make($file);
            if($width <= 750)
            {
                $fotoAfter->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$request->alamat, 50, 50, function($font){
                    $font->file(public_path('font/roboto/Roboto-Bold.ttf'));
                    $font->size(14);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
                $fotoAfter->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                    $font->file(public_path('font/roboto/Roboto-Bold.ttf'));
                    $font->size(14);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
            }
            if($width > 750 && $width <= 1500)
            {
                $fotoAfter->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$request->alamat, 50, 50, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(30);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
                $fotoAfter->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(30);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
            }
            if($width > 1500)
            {
                $fotoAfter->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$request->alamat, 50, 50, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(35);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
                $fotoAfter->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(35);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
            }
            $fotoAfterSize = public_path('images/foto-bkk/'.$fotoAfterName);
            $fotoAfter->save($fotoAfterSize, 60);

            $dataFotoAfter[] = $fotoAfterName;
        }

        for ($i=0; $i < count($dataFotoAfter); $i++) {
            $pivot = new PivotBkkLampiran;
            $pivot->bkk_id = $bkk->id;
            $pivot->nama = $dataFotoAfter[$i];
            $pivot->status = 'after';
            $pivot->save();
        }

        Alert::success('Berhasil', 'Berhasil Mengkonfirmasi Lokasi BKK');
        return redirect()->route('fasilitator.bkk.index');
    }
}
