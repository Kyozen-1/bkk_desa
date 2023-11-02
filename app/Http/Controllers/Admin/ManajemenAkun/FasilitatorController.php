<?php

namespace App\Http\Controllers\Admin\ManajemenAkun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Validator;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\AkunFasilitator;
use App\Models\Fasilitator;

class FasilitatorController extends Controller
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
            $data = AkunFasilitator::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($data){
                    $button_show = '<button type="button" name="detail" id="'.$data->id.'" class="detail btn btn-icon waves-effect btn-outline-success" title="Detail Data"><i class="fas fa-eye"></i></button>';
                    $button_change_password = '<button type="button" name="change-password" id="'.$data->id.'"
                    class="change-password btn btn-icon waves-effect btn-outline-warning" title="Change Password"><i class="fas fa-lock"></i></button>';
                    // $button_delete = '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-icon waves-effect btn-outline-danger" title="Delete Data"><i class="fas fa-trash"></i></button>';
                    $button_edit = '<button type="button" name="edit" id="'.$data->fasilitator_id.'"
                    class="edit btn btn-icon waves-effect btn-outline-warning" title="Edit Data"><i class="fas fa-edit"></i></button>';
                    // $button = $button_show . ' ' .$button_edit. ' ' .$button_change_password . ' ' . $button_delete;
                    $button = $button_show . ' ' .$button_edit. ' ' .$button_change_password;
                    return $button;
                })
                ->editColumn('no_hp', function($data){
                    return $data->fasilitator->no_hp;
                })
                ->editColumn('foto', function($data){
                    return '<img src="'.asset('images/fasilitator/'.$data->fasilitator->foto).'" style="width:5rem;">';
                })
                ->rawColumns(['aksi', 'foto'])
                ->make(true);
        }
        return view('admin.manajemen-akun.fasilitator.index');
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
            'no_hp' => 'required',
            'email' => 'required|unique:akun_fasilitators',
            'password' => 'required',
            'foto' => 'mimes:jpeg,jpg,png|required|max:1024'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        $fotoExtension = $request->foto->extension();
        $fotoName =  uniqid().'-'.date("ymd").'.'.$fotoExtension;
        $foto = Image::make($request->foto);
        $fotoSize = public_path('images/fasilitator/'.$fotoName);
        $foto->save($fotoSize, 100);

        $fasilitator = new Fasilitator;
        $fasilitator->nama = $request->nama;
        $fasilitator->no_hp = $request->no_hp;
        $fasilitator->foto = $fotoName;
        $fasilitator->save();

        $akun_fasilitator = new AkunFasilitator;
        $akun_fasilitator->name = $fasilitator->nama;
        $akun_fasilitator->email = $request->email;
        $akun_fasilitator->password = Hash::make($request->password);
        $akun_fasilitator->fasilitator_id = $fasilitator->id;
        $akun_fasilitator->save();

        return response()->json(['success' => 'Berhasil Menyimpan Data']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = AkunFasilitator::find($id);
        $array = [
            'nama' => $data->name,
            'email' => $data->email,
            'no_hp' => $data->Fasilitator->no_hp,
            'foto' => $data->Fasilitator->foto
        ];

        return response()->json(['result' => $array]);
    }

    public function change_password(Request $request)
    {
        $akun_fasilitator = AkunFasilitator::find($request->id);
        $akun_fasilitator->password = Hash::make('12345678');
        $akun_fasilitator->save();

        return response()->json(['success' => 'Berhasil Merubah Password! Passwordnya 12345678']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fasilitator = Fasilitator::find($id);

        return response()->json(['result' => $fasilitator]);
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
            'no_hp' => 'required'
        ]);

        if($errors -> fails())
        {
            return response()->json(['errors' => $errors->errors()->all()]);
        }

        if($request->foto)
        {
            $errors = Validator::make($request->all(), [
                'foto' => 'mimes:jpeg,jpg,png|max:1024'
            ]);

            if($errors -> fails())
            {
                return response()->json(['errors' => $errors->errors()->all()]);
            }
        }

        $fasilitator = Fasilitator::find($request->hidden_id);
        $fasilitator->nama = $request->nama;
        $fasilitator->no_hp = $request->no_hp;

        if($request->foto)
        {
            File::delete(public_path('images/fasilitator/'.$fasilitator->foto));

            $fotoExtension = $request->foto->extension();
            $fotoName =  uniqid().'-'.date("ymd").'.'.$fotoExtension;
            $foto = Image::make($request->foto);
            $fotoSize = public_path('images/fasilitator/'.$fotoName);
            $foto->save($fotoSize, 100);

            $fasilitator->foto = $fotoName;
        }
        $fasilitator->save();

        return response()->json(['success' => 'Berhasil mengupdate data']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $akun_fasilitator = AkunFasilitator::find($request->id);
        $akun_fasilitator->status_hapus = '1';
        $akun_fasilitator->save();

        return response()->json(['success' => 'Berhasil Menghapus Akun']);
    }
}
