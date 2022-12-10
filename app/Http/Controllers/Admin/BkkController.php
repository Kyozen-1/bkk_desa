<?php

namespace App\Http\Controllers\Admin;

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
                    $button_delete = '<a type="button" name="delete" href="'.url('/admin/bkk/destroy/'.$data->id).'" class="delete btn btn-icon waves-effect btn-danger" title="Delete Data"><i class="fas fa-trash"></i></a>';
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
                    return $data->master_tipe_kegiatan->nama;
                })
                ->editColumn('apbd', function($data){
                    return 'Rp. '.number_format($data->apbd, 2, ',', '.');
                })
                ->addColumn('master_fraksi', function($data){
                    return $data->aspirator->master_fraksi->nama;
                })
                ->addColumn('foto', function($data){
                    if($data->foto_after)
                    {
                        return '<img src="'.asset('images/foto-bkk/'.$data->foto_after).'" style="width:5rem;" title="Foto Setelah">';
                    } else {
                        return '<img src="'.asset('images/foto-bkk/'.$data->foto_before).'" style="width:5rem;" title="Foto Sebelum">';
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
        return view('admin.bkk.index', [
            'master_kecamatan' => $master_kecamatan,
            'master_fraksi' => $master_fraksi,
            'master_jenis' => $master_jenis
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
            'foto_before' => 'mimes:jpeg,jpg,png,gif|required',
            'foto_after' => 'mimes:jpeg,jpg,png,gif|required',
            'master_kategori_pembangunan' => 'required',
            'jumlah' => 'required',
        ]);

        if($errors -> fails())
        {
            return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
        }

        $fotoBeforeExtension = $request->foto_before->extension();
        $fotoBeforeName =  uniqid().'-'.date("ymd").'.'.$fotoBeforeExtension;
        $fotoBefore = Image::make($request->foto_before);
        $fotoBeforeSize = public_path('images/foto-bkk/'.$fotoBeforeName);
        $fotoBefore->save($fotoBeforeSize, 100);

        $fotoAfterExtension = $request->foto_after->extension();
        $fotoAfterName =  uniqid().'-'.date("ymd").'.'.$fotoAfterExtension;
        $fotoAfter = Image::make($request->foto_after);
        $fotoAfterSize = public_path('images/foto-bkk/'.$fotoAfterName);
        $fotoAfter->save($fotoAfterSize, 100);

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
        $bkk->foto_before = $fotoBeforeName;
        $bkk->foto_after = $fotoAfterName;
        $bkk->master_kategori_pembangunan_id = $request->master_kategori_pembangunan_id;
        $bkk->jumlah = $request->jumlah;
        $bkk->save();

        Alert::success('Berhasil', 'Berhasil Menambahkan Data BKK');
        return redirect()->route('admin.bkk.index');
    }

    public function detail($id)
    {
        $data = Bkk::find($id);

        $array = [
            'master_fraksi' => $data->aspirator->master_fraksi->nama,
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

        if($request->foto_before)
        {
            $errors = Validator::make($request->all(), [
                'foto_before' => 'mimes:jpeg,jpg,png,gif'
            ]);

            if($errors -> fails())
            {
                return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
            }
        }

        if($request->foto_after)
        {
            $errors = Validator::make($request->all(), [
                'foto_after' => 'mimes:jpeg,jpg,png,gif'
            ]);

            if($errors -> fails())
            {
                return redirect()->back()->with('Gagal Menyimpan', $errors->errors()->all());
            }
        }

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
        if($request->foto_before)
        {
            $fotoBeforeName = $bkk->foto_before;
            File::delete(public_path('images/foto_bkk/'.$fotoBeforeName));

            $fotoBeforeExtension = $request->foto_before->extension();
            $fotoBeforeName =  uniqid().'-'.date("ymd").'.'.$fotoBeforeExtension;
            $fotoBefore = Image::make($request->foto_before);
            $fotoBeforeSize = public_path('images/foto-bkk/'.$fotoBeforeName);
            $fotoBefore->save($fotoBeforeSize, 100);

            $bkk->foto_before = $fotoBeforeName;
        }
        if($request->foto_after)
        {
            $fotoAfterName = $bkk->foto_after;
            File::delete(public_path('images/foto_bkk/'.$fotoAfterName));

            $fotoAfterExtension = $request->foto_after->extension();
            $fotoAfterName =  uniqid().'-'.date("ymd").'.'.$fotoAfterExtension;
            $fotoAfter = Image::make($request->foto_after);
            $fotoAfterSize = public_path('images/foto-bkk/'.$fotoAfterName);
            $fotoAfter->save($fotoAfterSize, 100);

            $bkk->foto_after = $fotoAfterName;
        }
        $bkk->master_kategori_pembangunan_id = $request->master_kategori_pembangunan_id;
        $bkk->jumlah = $request->jumlah;
        $bkk->save();

        Alert::success('Berhasil', 'Berhasil Merubah Data BKK');
        return redirect()->route('admin.bkk.index');
    }

    public function destroy($id)
    {
        $bkk = Bkk::find($id);

        $gambarName = $bkk->foto;
        File::delete(public_path('images/foto_bkk/'.$gambarName));

        $bkk->delete();
    }
}
