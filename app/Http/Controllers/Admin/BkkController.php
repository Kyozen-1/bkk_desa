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
use App\Models\MasterFraksi;
use App\Models\Aspirator;
use App\Models\LogAktivitas;
use App\Models\Bkk;
use App\Models\MasterJenis;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use App\Models\MasterTipeKegiatan;
use App\Models\MasterKategoriPembangunan;
use App\Imports\BkkImport;
use App\Models\PivotBkkLampiran;
use App\Exports\TemplateBkk\TemplateBkk;

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
                    $button_show = '<button type="button" name="detail" id="'.$data->id.'" class="detail btn btn-icon waves-effect btn-success" title="Detail Data"><i class="fas fa-eye"></i></button>';
                    $button_edit = '<a href="'.url('/admin/bkk/edit/'.$data->id).'" class="edit btn btn-icon waves-effect btn-warning" title="Edit Data"><i class="fas fa-edit"></i></a>';
                    $button_delete = '<button type="button" name="delete" data-id="'.$data->id.'" class="delete btn btn-icon waves-effect btn-danger" title="Delete Data"><i class="fas fa-trash"></i></button>';
                    if($data->tahun < Carbon::now()->year)
                    {
                        $button = $button_show . ' ' . $button_edit;
                    } else {
                        $button = $button_show . ' ' . $button_edit . ' ' . $button_delete;
                    }
                    return $button;
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
        $kecamatan = Kecamatan::pluck('nama', 'id');
        $master_tipe_kegiatan = MasterTipeKegiatan::pluck('nama', 'id');
        $master_kategori_pembangunans = MasterKategoriPembangunan::all();
        return view('admin.bkk.index', [
            'master_kecamatan' => $master_kecamatan,
            'master_fraksi' => $master_fraksi,
            'master_jenis' => $master_jenis,
            'kecamatan' => $kecamatan,
            'master_tipe_kegiatan' => $master_tipe_kegiatan,
            'master_kategori_pembangunans' => $master_kategori_pembangunans
        ]);
    }

    public function create()
    {
        $master_kecamatan = Kecamatan::pluck('nama', 'id');
        $master_fraksi = MasterFraksi::pluck('nama', 'id');
        $master_jenis = MasterJenis::pluck('nama', 'id');
        $kecamatan = Kecamatan::pluck('nama', 'id');
        $master_tipe_kegiatan = MasterTipeKegiatan::pluck('nama', 'id');
        $master_kategori_pembangunans = MasterKategoriPembangunan::all();
        return view('admin.bkk.create',[
            'master_kecamatan' => $master_kecamatan,
            'master_fraksi' => $master_fraksi,
            'master_jenis' => $master_jenis,
            'kecamatan' => $kecamatan,
            'master_tipe_kegiatan' => $master_tipe_kegiatan,
            'master_kategori_pembangunans' => $master_kategori_pembangunans
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

    public function store(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'aspirator_id' => 'required',
            'master_jenis_id' => 'required',
            'uraian' => 'required',
            'tipe_kegiatan_id' => 'required',
            'apbd' => 'required',
            'p_apbd' => 'required',
            'tanggal_realisasi' => 'required',
            'tahun' => 'required',
            'kelurahan_id' => 'required',
            'alamat' => 'required',
            'lng' => 'required',
            'lat' => 'required',
            'foto_before.*' => 'mimes:jpeg,jpg,png,gif|required',
            'master_kategori_pembangunan_id' => 'required',
            'jumlah' => 'required',
        ]);

        if($errors -> fails())
        {
            return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
        }

        if($request->foto_after)
        {
            $errors = Validator::make($request->all(), [
                'foto_after.*' => 'mimes:jpeg,jpg,png,gif',
            ]);

            if($errors -> fails())
            {
                return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
            }
        }

        $bkk = new Bkk;
        $bkk->aspirator_id = $request->aspirator_id;
        $bkk->master_jenis_id = $request->master_jenis_id;
        $bkk->uraian = $request->uraian;
        $bkk->tipe_kegiatan_id = $request->tipe_kegiatan_id;
        $bkk->apbd = $request->apbd;
        $bkk->p_apbd = $request->p_apbd;
        $bkk->tanggal_realisasi = $request->tanggal_realisasi;
        $bkk->tahun = $request->tahun;
        $bkk->kelurahan_id = $request->kelurahan_id;
        $bkk->alamat = $request->alamat;
        $bkk->lng = $request->lng;
        $bkk->lat = $request->lat;
        $bkk->master_kategori_pembangunan_id = $request->master_kategori_pembangunan_id;
        $bkk->jumlah = $request->jumlah;
        $bkk->save();

        $dataFotoBefore = [];
        foreach ($request->file('foto_before') as $file) {
            $fotoBeforeExtension = $file->extension();
            $width = getimagesize($file)[0];
            $fotoBeforeName = uniqid().'-'.date("ymd").'.'.$fotoBeforeExtension;
            $fotoBefore = Image::make($file);
            if($width <= 750)
            {
                $fotoBefore->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$request->alamat, 50, 50, function($font){
                    $font->file(public_path('font/roboto/Roboto-Bold.ttf'));
                    $font->size(14);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
                $fotoBefore->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                    $font->file(public_path('font/roboto/Roboto-Bold.ttf'));
                    $font->size(14);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
            }
            if($width > 750 && $width <= 1500)
            {
                $fotoBefore->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$request->alamat, 50, 50, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(30);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
                $fotoBefore->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(30);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
            }
            if($width > 1500)
            {
                $fotoBefore->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$request->alamat, 50, 50, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(35);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
                $fotoBefore->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                    $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                    $font->size(35);
                    $font->color('#f56042');
                    $font->align('left');
                    $font->angle(360);
                });
            }
            $fotoBeforeSize = public_path('images/foto-bkk/'.$fotoBeforeName);
            $fotoBefore->save($fotoBeforeSize, 60);

            $dataFotoBefore[] = $fotoBeforeName;
        }

        for ($i=0; $i < count($dataFotoBefore); $i++) {
            $pivot = new PivotBkkLampiran;
            $pivot->bkk_id = $bkk->id;
            $pivot->nama = $dataFotoBefore[$i];
            $pivot->status = 'before';
            $pivot->save();
        }

        if($request->foto_after)
        {
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

            $bkk->status_konfirmasi = 'ya';
            $bkk->save();
        }

        Alert::success('Berhasil', 'Berhasil Menambahkan Data BKK');
        return redirect()->route('admin.bkk.index');
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

    public function edit($id)
    {
        $data = Bkk::find($id);
        $master_kecamatan = Kecamatan::pluck('nama', 'id');
        $master_fraksi = MasterFraksi::pluck('nama', 'id');
        $master_jenis = MasterJenis::pluck('nama', 'id');
        $kecamatan = Kecamatan::pluck('nama', 'id');
        $master_tipe_kegiatan = MasterTipeKegiatan::pluck('nama', 'id');
        $master_kategori_pembangunans = MasterKategoriPembangunan::all();

        return view('admin.bkk.edit', [
            'master_kecamatan' => $master_kecamatan,
            'master_fraksi' => $master_fraksi,
            'master_jenis' => $master_jenis,
            'kecamatan' => $kecamatan,
            'data' => $data,
            'master_tipe_kegiatan' => $master_tipe_kegiatan,
            'master_kategori_pembangunans' => $master_kategori_pembangunans
        ]);
    }

    public function update(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'aspirator_id' => 'required',
            'master_jenis_id' => 'required',
            'uraian' => 'required',
            'tipe_kegiatan_id' => 'required',
            'apbd' => 'required',
            'p_apbd' => 'required',
            'tanggal_realisasi' => 'required',
            'tahun' => 'required',
            'kelurahan_id' => 'required',
            'alamat' => 'required',
            'lng' => 'required',
            'lat' => 'required',
            'master_kategori_pembangunan_id' => 'required',
            'jumlah' => 'required',
        ]);

        if($errors -> fails())
        {
            return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
        }

        // if($request->foto_before)
        // {
        //     $errors = Validator::make($request->all(), [
        //         'foto_before' => 'mimes:jpeg,jpg,png,gif'
        //     ]);

        //     if($errors -> fails())
        //     {
        //         return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
        //     }
        // }

        // if($request->foto_after)
        // {
        //     $errors = Validator::make($request->all(), [
        //         'foto_after' => 'mimes:jpeg,jpg,png,gif'
        //     ]);

        //     if($errors -> fails())
        //     {
        //         return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
        //     }
        // }

        $bkk = Bkk::find($request->bkk_id);
        $bkk->aspirator_id = $request->aspirator_id;
        $bkk->master_jenis_id = $request->master_jenis_id;
        $bkk->uraian = $request->uraian;
        $bkk->tipe_kegiatan_id = $request->tipe_kegiatan_id;
        $bkk->apbd = $request->apbd;
        $bkk->p_apbd = $request->p_apbd;
        $bkk->tanggal_realisasi = $request->tanggal_realisasi;
        $bkk->tahun = $request->tahun;
        $bkk->kelurahan_id = $request->kelurahan_id;
        $bkk->alamat = $request->alamat;
        $bkk->lng = $request->lng;
        $bkk->lat = $request->lat;
        // if($request->foto_before)
        // {
        //     $fotoBeforeName = $bkk->foto_before;
        //     File::delete(public_path('images/foto_bkk/'.$fotoBeforeName));

        //     $fotoBeforeExtension = $request->foto_before->extension();
        //     $fotoBeforeName =  uniqid().'-'.date("ymd").'.'.$fotoBeforeExtension;
        //     $fotoBefore = Image::make($request->foto_before);
        //     $fotoBeforeSize = public_path('images/foto-bkk/'.$fotoBeforeName);
        //     $fotoBefore->save($fotoBeforeSize, 100);

        //     $bkk->foto_before = $fotoBeforeName;
        // }
        // if($request->foto_after)
        // {
        //     $fotoAfterName = $bkk->foto_after;
        //     File::delete(public_path('images/foto_bkk/'.$fotoAfterName));

        //     $fotoAfterExtension = $request->foto_after->extension();
        //     $fotoAfterName =  uniqid().'-'.date("ymd").'.'.$fotoAfterExtension;
        //     $fotoAfter = Image::make($request->foto_after);
        //     $fotoAfterSize = public_path('images/foto-bkk/'.$fotoAfterName);
        //     $fotoAfter->save($fotoAfterSize, 100);

        //     $bkk->foto_after = $fotoAfterName;
        // }
        $bkk->master_kategori_pembangunan_id = $request->master_kategori_pembangunan_id;
        $bkk->jumlah = $request->jumlah;
        $bkk->save();

        Alert::success('Berhasil', 'Berhasil Merubah Data BKK');
        return redirect()->route('admin.bkk.index');
    }

    public function destroy(Request $request)
    {
        $bkk = Bkk::find($request->id);

        foreach ($bkk->pivot_bkk_lampiran as $item) {
            $gambarName = $item->nama;
            File::delete(public_path('images/foto_bkk/'.$gambarName));
        }

        $bkk->delete();
    }

    public function impor(Request $request)
    {
        // dd($request->all());
        $file = $request->file('file_impor');
        // import data
        Excel::import(new BkkImport, $file);

        $msg = [session('import_status'), session('import_message')];

        if ($msg[0]) {
            Alert::success('Berhasil', $msg[1]);
            return redirect()->route('admin.bkk.index');
        } else {
            Alert::error('Gagal', $msg[1]);
            return redirect()->route('admin.bkk.index');
        }
    }

    public function delete_bkk_lampiran(Request $request)
    {
        // dd($request->all());
        $errors = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $lampiran = PivotBkkLampiran::find($request->id);
        $idBkk = $lampiran->bkk_id;

        $fotoBeforeName = $lampiran->nama;
        File::delete(public_path('images/foto-bkk/'.$fotoBeforeName));

        $lampiran->delete();

        return response()->json(['success' => 'Berhasil', 'id' => $idBkk]);
    }

    public function tambah_bkk_foto_before(Request $request, $id)
    {
        $errors = Validator::make($request->all(), [
            'foto_before.*' => 'mimes:jpeg,jpg,png,gif|required'
        ]);

        if($errors -> fails())
        {
            Alert::error('Gagal Menyimpan', $errors->errors()->all());
            return redirect()->route('admin.bkk.edit', ['id'=> $id]);
        }
        $bkk = Bkk::find($id);
        $jumlahBeforeBkkFoto = $bkk->pivot_bkk_lampiran->where('status', 'before')->count();
        $max_count = 5;
        if($jumlahBeforeBkkFoto == $max_count)
        {
            Alert::error('Gagal Menyimpan', 'Jumlah foto sudah maksimal');
            return redirect()->route('admin.bkk.edit', ['id'=> $id]);
        }
        $dataFotoBefore = [];
        foreach ($request->file('foto_before') as $file) {
            if($jumlahBeforeBkkFoto < $max_count)
            {
                $fotoBeforeExtension = $file->extension();
                $width = getimagesize($file)[0];
                $fotoBeforeName = uniqid().'-'.date("ymd").'.'.$fotoBeforeExtension;
                $fotoBefore = Image::make($file);
                if($width <= 750)
                {
                    $fotoBefore->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$bkk->alamat, 50, 50, function($font){
                        $font->file(public_path('font/roboto/Roboto-Bold.ttf'));
                        $font->size(14);
                        $font->color('#f56042');
                        $font->align('left');
                        $font->angle(360);
                    });
                    $fotoBefore->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                        $font->file(public_path('font/roboto/Roboto-Bold.ttf'));
                        $font->size(14);
                        $font->color('#f56042');
                        $font->align('left');
                        $font->angle(360);
                    });
                }
                if($width > 750 && $width <= 1500)
                {
                    $fotoBefore->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$bkk->alamat, 50, 50, function($font){
                        $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                        $font->size(30);
                        $font->color('#f56042');
                        $font->align('left');
                        $font->angle(360);
                    });
                    $fotoBefore->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                        $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                        $font->size(30);
                        $font->color('#f56042');
                        $font->align('left');
                        $font->angle(360);
                    });
                }
                if($width > 1500)
                {
                    $fotoBefore->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$bkk->alamat, 50, 50, function($font){
                        $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                        $font->size(35);
                        $font->color('#f56042');
                        $font->align('left');
                        $font->angle(360);
                    });
                    $fotoBefore->text(Carbon::now()->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y ; h:i a'), 50, 100, function($font){
                        $font->file(public_path('font/roboto/Roboto-Regular.ttf'));
                        $font->size(35);
                        $font->color('#f56042');
                        $font->align('left');
                        $font->angle(360);
                    });
                }
                $fotoBeforeSize = public_path('images/foto-bkk/'.$fotoBeforeName);
                $fotoBefore->save($fotoBeforeSize, 60);

                $dataFotoBefore[] = $fotoBeforeName;

                $jumlahBeforeBkkFoto++;
            }
        }

        for ($i=0; $i < count($dataFotoBefore); $i++) {
            $pivot = new PivotBkkLampiran;
            $pivot->bkk_id = $bkk->id;
            $pivot->nama = $dataFotoBefore[$i];
            $pivot->status = 'before';
            $pivot->save();
        }

        Alert::success('Berhasil', 'Berhasil Menambahkan Lampiran Foto Before');
        return redirect()->route('admin.bkk.edit', ['id'=>$bkk->id]);
    }

    public function tambah_bkk_foto_after(Request $request, $id)
    {
        $errors = Validator::make($request->all(), [
            'foto_after.*' => 'mimes:jpeg,jpg,png,gif|required'
        ]);

        if($errors -> fails())
        {
            Alert::error('Gagal Menyimpan', $errors->errors()->all());
            return redirect()->route('admin.bkk.edit', ['id'=> $id]);
        }
        $bkk = Bkk::find($id);
        $jumlahAfterBkkFoto = $bkk->pivot_bkk_lampiran->where('status', 'after')->count();
        $max_count = 5;
        if($jumlahAfterBkkFoto == $max_count)
        {
            Alert::error('Gagal Menyimpan', 'Jumlah foto sudah maksimal');
            return redirect()->route('admin.bkk.edit', ['id'=> $id]);
        }
        $dataFotoAfter = [];
        foreach ($request->file('foto_after') as $file) {
            if($jumlahAfterBkkFoto < $max_count)
            {
                $fotoAfterExtension = $file->extension();
                $width = getimagesize($file)[0];
                $fotoAfterName = uniqid().'-'.date("ymd").'.'.$fotoAfterExtension;
                $fotoAfter = Image::make($file);
                if($width <= 750)
                {
                    $fotoAfter->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$bkk->alamat, 50, 50, function($font){
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
                    $fotoAfter->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$bkk->alamat, 50, 50, function($font){
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
                    $fotoAfter->text('Kec: '.$bkk->kelurahan->kecamatan->nama.', Kel: '.$bkk->kelurahan->nama.', Alamat: '.$bkk->alamat, 50, 50, function($font){
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

                $jumlahAfterBkkFoto++;
            }
        }

        for ($i=0; $i < count($dataFotoAfter); $i++) {
            $pivot = new PivotBkkLampiran;
            $pivot->bkk_id = $bkk->id;
            $pivot->nama = $dataFotoAfter[$i];
            $pivot->status = 'after';
            $pivot->save();
        }

        Alert::success('Berhasil', 'Berhasil Menambahkan Lampiran Foto After');
        return redirect()->route('admin.bkk.edit', ['id'=>$bkk->id]);
    }

    public function testingImporTemplate()
    {
        return Excel::download(new TemplateBkk, 'Template BKK.xlsx');
    }
}
