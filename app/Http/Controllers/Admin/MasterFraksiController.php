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
use App\Models\MasterFraksi;
use App\Models\LogAktivitas;

class MasterFraksiController extends Controller
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
            $data = MasterFraksi::latest()->get();
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
                ->editColumn('logo', function($data){
                    return '<img src="'.asset('images/logo-fraksi/'.$data->logo).'" style="width:5rem;">';
                })
                ->editColumn('color', function($data){
                    return '<input type="color" class="form-control form-control-color" value="'.$data->color.'" disabled>';
                })
                ->rawColumns(['aksi', 'logo', 'color'])
                ->make(true);
        }
        return view('admin.master-fraksi.index');
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
            'color' => 'required',
            'logo' => 'mimes:jpeg,jpg,png,gif|required',
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $logoExtension = $request->logo->extension();
        $logoName =  uniqid().'-'.date("ymd").'.'.$logoExtension;
        $logo = Image::make($request->logo);
        $logoSize = public_path('images/logo-fraksi/'.$logoName);
        $logo->save($logoSize, 100);

        $fraksi = new MasterFraksi;
        $fraksi->nama = $request->nama;
        $fraksi->color = $request->color;
        $fraksi->logo = $logoName;
        $fraksi->save();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Menambahkan Fraksi '.$fraksi->nama;
        $log->save();

        return response()->json(['success' => 'Berhasil Menambahkan Fraksi '.$fraksi->nama]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = MasterFraksi::find($id);

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
        $data = MasterFraksi::find($id);

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
            'color' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $fraksi = MasterFraksi::find($request->hidden_id);
        $fraksi->nama = $request->nama;
        $fraksi->color = $request->color;
        if($request->logo)
        {
            $logoName = $fraksi->logo;
            File::delete(public_path('images/logo-fraksi/'.$logoName));

            $logoExtension = $request->logo->extension();
            $logoName =  uniqid().'-'.date("ymd").'.'.$logoExtension;
            $logo = Image::make($request->logo);
            $logoSize = public_path('images/logo-fraksi/'.$logoName);
            $logo->save($logoSize, 100);

            $fraksi->logo = $logoName;
        }
        $fraksi->save();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Merubah fraksi '.$fraksi->nama;
        $log->save();

        return response()->json(['success' => 'Berhasil Menambahkan Fraksi '.$fraksi->nama]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MasterFraksi::find($id)->delete();

        $log = new LogAktivitas;
        $log->akun_admin_id = Auth::user()->id;
        $log->keterangan = 'Menghapus Fraksi';
        $log->save();

        return response()->json(['success' => 'Berhasil Menghapus Fraksi']);
    }
}
