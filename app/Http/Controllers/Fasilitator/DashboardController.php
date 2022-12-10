<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\AkunFasilitator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Validator;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('fasilitator.dashboard.index');
    }

    public function change(Request $request)
    {
        $akun_fasilitator = AkunFasilitator::find(Auth::user()->id);
        $akun_fasilitator->color_layout = $request->color_layout;
        $akun_fasilitator->nav_color = $request->nav_color;
        $akun_fasilitator->behaviour = $request->behaviour;
        $akun_fasilitator->layout = $request->layout;
        $akun_fasilitator->radius = $request->radius;
        $akun_fasilitator->placement = $request->placement;
        $akun_fasilitator->save();
    }
}
