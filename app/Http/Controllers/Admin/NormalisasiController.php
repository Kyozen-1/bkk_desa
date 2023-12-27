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

class NormalisasiController extends Controller
{
    public function fotoBkk()
    {
        $getBkks = Bkk::whereHas('aspirator')->doesntHave('pivot_bkk_lampiran')->get();
        foreach ($getBkks as $getBkk) {
            if($getBkk->foto_before)
            {
                $pivotFotoBefore = new PivotBkkLampiran;
                $pivotFotoBefore->bkk_id = $getBkk->id;
                $pivotFotoBefore->nama = $getBkk->foto_before;
                $pivotFotoBefore->status = 'before';
                $pivotFotoBefore->save();
            }

            if($getBkk->foto_after)
            {
                $pivotFotoAfter = new PivotBkkLampiran;
                $pivotFotoAfter->bkk_id = $getBkk->id;
                $pivotFotoAfter->nama = $getBkk->foto_after;
                $pivotFotoAfter->status = 'after';
                $pivotFotoAfter->save();
            }
        }

        return 'selesai';
    }
}
