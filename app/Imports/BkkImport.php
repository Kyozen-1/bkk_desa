<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
Use DB;
use App\Models\Bkk;

class BkkImport implements ToCollection,WithStartRow
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function startRow(): int
    {
        return 6;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $response = [
            'import_status' => true,
            'import_message' => ''
        ];
        DB::beginTransaction();

        try{
            $start = microtime(true);
            $response['import_status']  = true;
            session(['import_status' => $response['import_status']]);
            $n = 0;

            $data = $this->data;

            foreach ($rows as $kunci=>$row) {
                // dd($row);
                if ($row->filter()->isNotEmpty()) {
                    // Validasi Start
                    if($row[1]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'Uraian Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }

                    if($row[2]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'APBD Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }

                    if($row[3]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'P-APBD Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }

                    if($row[3]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'P-APBD Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }

                    if($row[4]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'Tanggal Realisasi Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }

                    if($row[5]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'Tahun Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }

                    if($row[6]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'Alamat Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }

                    if($row[9]==null){
                        $response['import_status'] = false;
                        $response['import_message'] = 'Jumlah jika Ada Harap Diisi';
                        session(['import_status' => $response['import_status']]);
                        session(['import_message' => $response['import_message']]);
                        return false;
                    }
                    // Validasi End

                    // $pembayaran = Pembayaran::find($cek->id);
                    // $pembayaran->status_pajak = 'sudah_bayar';
                    // $pembayaran->ntpn = $row[3];

                    // if(is_string($row[2])){
                    //     if ( preg_match('/\s/',$row[2]) ){
                    //         $response['import_status'] = false;
                    //         $response['import_message'] = 'Format tanggal salah! Ada spasi di Invoice  '.$row[1];
                    //         session(['import_status' => $response['import_status']]);
                    //         session(['import_message' => $response['import_message']]);
                    //         return false;
                    //     }
                    //     if(strpos($row[2], "/")!==false){
                    //         $arrayTanggal = explode("/", $row[2]);
                    //         if(strlen($arrayTanggal[0])==4){
                    //             $tgl_bayar_pajak = $arrayTanggal[0]."-".$arrayTanggal[1]."-".$arrayTanggal[2];
                    //         }else{
                    //             $tgl_bayar_pajak = $arrayTanggal[2]."-".$arrayTanggal[1]."-".$arrayTanggal[0];
                    //         }
                    //     }elseif(strpos($row[2], "-")!==false){
                    //         $arrayTanggal = explode("-", $row[2]);
                    //         if(strlen($arrayTanggal[0])==4){
                    //             $tgl_bayar_pajak = $arrayTanggal[0]."-".$arrayTanggal[1]."-".$arrayTanggal[2];
                    //         }else{
                    //             $tgl_bayar_pajak = $arrayTanggal[2]."-".$arrayTanggal[1]."-".$arrayTanggal[0];
                    //         }
                    //     }else{
                    //         $response['import_status'] = false;
                    //         $response['import_message'] = 'Format tanggal salah! Pada tanggal '.$row[1].'Harap Periksa Lagi.';
                    //         session(['import_status' => $response['import_status']]);
                    //         session(['import_message' => $response['import_message']]);
                    //         return false;
                    //     }

                    // }else{
                    //     $tgl_bayar_pajak = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]);
                    // }
                    // $pembayaran->tgl_bayar_pajak = $tgl_bayar_pajak;
                    // $pembayaran->save();

                    $bkk = new Bkk;
                    $bkk->aspirator_id = $data['aspirator_id'];
                    $bkk->master_jenis_id = $data['master_jenis_id'];
                    $bkk->uraian = $row[1];
                    $bkk->tipe_kegiatan_id = $data['tipe_kegiatan_id'];
                    $bkk->apbd = $row[2];
                    $bkk->p_apbd = $row[3];
                    $bkk->tanggal_realisasi = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
                    $bkk->tahun = $row[5];
                    $bkk->kelurahan_id = $data['kelurahan_id'];
                    $bkk->alamat = $row[6];
                    if($row[7] != '')
                    {
                        $bkk->lng = $row[7];
                    }
                    if($row[8] != '')
                    {
                        $bkk->lat = $row[8];
                    }
                    $bkk->master_kategori_pembangunan_id = $data['master_kategori_pembangunan_id'];
                    $bkk->jumlah = $row[9];
                    $bkk->save();

                    $n++;
                }

            }

            $time_elapsed_secs = microtime(true) - $start;
            $response['import_message'] = $n. ' data telah diimport dalam '. $time_elapsed_secs.' Second';
            session(['import_message' => $response['import_message']]);
            DB::commit();
        }catch (\Illuminate\Database\QueryException $exception) {
            DB::rollback();
            $errorInfo = $exception->errorInfo;
            // dd($exception->errorInfo);
            $response['import_status'] = false;
            $response['import_message'] = $errorInfo[2];;
            session(['import_status' => $response['import_status']]);
            session(['import_message' => $response['import_message']]);
        }
        session(['import_status' => $response['import_status']]);
        session(['import_message' => $response['import_message']]);
        return true;
    }
}
