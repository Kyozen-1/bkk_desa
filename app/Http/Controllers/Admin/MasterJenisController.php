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
use Auth;
use Carbon\Carbon;
use App\Models\MasterJenis;
use App\Models\LogAktivitas;

class MasterJenisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            $data = MasterJenis::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($data){
                    $button_show = '<button type="button" name="detail" id="'.$data->id.'" class="detail btn btn-icon waves-effect btn-success" title="Detail Data"><i class="fas fa-eye"></i></button>';
                    $button_edit = '<button type="button" name="edit" id="'.$data->id.'"
                    class="edit btn btn-icon waves-effect btn-warning" title="Edit Data"><i class="fas fa-edit"></i></button>';
                    $button_delete = '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-icon waves-effect btn-danger" title="Delete Data"><i class="fas fa-trash"></i></button>';
                    $button = $button_show . ' ' . $button_edit . ' ' . $button_delete;
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        return view('admin.master-jenis.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }
        $jenis = new MasterJenis;
        $jenis->nama = $request->nama;
        $jenis->save();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Menambahkan Jenis '.$jenis->nama;
        $log->save();

        return response()->json(['success' => 'Berhasil Menambahkan Jenis '.$jenis->nama]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = MasterJenis::find($id);

        return response()->json(['result' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = MasterJenis::find($id);

        return response()->json(['result' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }
        $jenis = MasterJenis::find($request->hidden_id);
        $jenis->nama = $request->nama;
        $jenis->save();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Merubah jenis '.$jenis->nama;
        $log->save();

        return response()->json(['success' => 'Berhasil Merubah Jenis menjadi '.$jenis->nama]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MasterJenis::find($id)->delete();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Menghapus Jenis';
        $log->save();

        return response()->json(['success' => 'Berhasil']);
    }
}
