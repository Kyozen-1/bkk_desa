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

class AspiratorController extends Controller
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
            $data = Aspirator::latest()->get();
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
                ->editColumn('master_fraksi_id', function($data){
                    return $data->master_fraksi->nama;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
        $master_fraksi = MasterFraksi::pluck('nama', 'id');
        return view('admin.aspirator.index', [
            'master_fraksi' => $master_fraksi
        ]);
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
            'master_fraksi_id' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $aspirator = new Aspirator;
        $aspirator->nama = $request->nama;
        $aspirator->master_fraksi_id = $request->master_fraksi_id;
        $aspirator->save();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Menambahkan Aspirator '.$aspirator->nama;
        $log->save();
        return response()->json(['success' => 'Berhasil Menambahkan Aspirator '.$aspirator->nama]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $aspirator = Aspirator::find($id);
        $array = [
            'master_fraksi' => $aspirator->master_fraksi->nama,
            'nama' => $aspirator->nama
        ];
        return response()->json(['result' => $array]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Aspirator::find($id);

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
            'master_fraksi_id' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $aspirator = Aspirator::find($request->hidden_id);
        $aspirator->nama = $request->nama;
        $aspirator->master_fraksi_id = $request->master_fraksi_id;
        $aspirator->save();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Merubah Aspirator '.$aspirator->nama;
        $log->save();

        return response()->json(['success' => 'Berhasil Menambahkan Aspirator '.$fraksi->nama]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Aspirator::find($id)->delete();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Menghapus Aspirator';
        $log->save();

        return response()->json(['success' => 'Berhasil menghapus']);
    }
}
