<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
Use DB;
use App\Models\Bkk;
use App\Models\MasterFraksi;
use App\Models\Aspirator;
use App\Models\MasterTipeKegiatan;
use App\Models\MasterJenis;
use App\Models\MasterKategoriPembangunan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class BkkImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new FirstSheetImport(),
        ];
    }
}

class FirstSheetImport implements ToCollection,WithStartRow
{
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

            foreach ($rows as $row) {
                // Validasi Start
                if($row[1]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Uraian Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[2]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'APBD Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[3]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'P-APBD Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[4]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Tanggal Realisasi Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[5]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Tahun Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[6]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Alamat Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[9]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Jumlah jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[10]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Partai jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[11]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Apirator jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[12]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Tipe Kegiatan jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[13]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Jenis jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[14]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Kategori Pembangunan jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[15]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Kecamatan jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                if($row[16]==''){
                    $response['import_status'] = false;
                    $response['import_message'] = 'Kelurahan jika Ada Harap Diisi';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }

                $row_data = $n + 6;

                // Cek Partai Start
                $cekPartai = MasterFraksi::where('nama', 'like', '%'.$row[10].'%')
                                ->first();
                if(!$cekPartai)
                {
                    $response['import_status'] = false;
                    $response['import_message'] = 'Pada data ke baris ' . $row_data . ', kolom PARTAI, Data Partai tidak ditemukan ';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }
                // Cek Partai End

                // Cek Aspirator Start
                $cekAspirator = Aspirator::where('nama', 'like', '%'.$row[11].'%')
                                ->where('master_fraksi_id', $cekPartai->id)
                                ->first();

                if(!$cekAspirator)
                {
                    $response['import_status'] = false;
                    $response['import_message'] = 'Pada data ke baris ' . $row_data . ', kolom ASPIRATOR, Data Aspirator tidak ditemukan (Pilihan partai juga berpengaruh)';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }
                // Cek Aspirator End

                // Cek Tipe Kegiatan Start
                $cekTipeKegiatan = MasterTipeKegiatan::where('nama', 'like', '%'.$row[12].'%')->first();
                if(!$cekTipeKegiatan)
                {
                    $response['import_status'] = false;
                    $response['import_message'] = 'Pada data ke baris ' . $row_data . ', kolom TIPE KEGIATAN, Data Tipe Kegiatan tidak ditemukan';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }
                // Cek Tipe Kegiatan End

                // Cek Jenis Start
                $cekJenis = MasterJenis::where('nama', 'like', '%'.$row[13].'%')->first();
                if(!$cekJenis)
                {
                    $response['import_status'] = false;
                    $response['import_message'] = 'Pada data ke baris ' . $row_data . ', kolom JENIS, Data Jenis tidak ditemukan';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }
                // Cek Jenis End

                // Cek Kategori Pembangunan Start
                $cekKategoriPembangunan = MasterKategoriPembangunan::where('nama', 'like', '%'.$row[14].'%')->first();
                if(!$cekKategoriPembangunan)
                {
                    $response['import_status'] = false;
                    $response['import_message'] = 'Pada data ke baris ' . $row_data . ', kolom KATEGORI PEMBANGUNAN, Data Kategori Pembangunan tidak ditemukan';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }
                // Cek Kategori Pembangunan End

                // Cek Kecamatan Start
                $cekKecamatan = Kecamatan::where('nama', 'like', '%'.$row[15].'%')->first();
                if(!$cekKecamatan)
                {
                    $response['import_status'] = false;
                    $response['import_message'] = 'Pada data ke baris ' . $row_data . ', kolom KECAMATAN, Data Kecamatan tidak ditemukan';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }
                // Cek Kecamatan End

                // Cek Kelurahan Start
                $cekKelurahan = Kelurahan::where('nama', 'like', '%'.$row[16].'%')->where('kecamatan_id', $cekKecamatan->id)->first();
                if(!$cekKelurahan)
                {
                    $response['import_status'] = false;
                    $response['import_message'] = 'Pada data ke baris ' . $row_data . ', kolom KELURAHAN, Data Kelurahan tidak ditemukan (pilihan kecamatan juga berpengaruh)';
                    session(['import_status' => $response['import_status']]);
                    session(['import_message' => $response['import_message']]);
                    return false;
                }
                // Cek Kelurahan End
                // Validasi End

                $bkk = new Bkk;
                $bkk->aspirator_id = $cekAspirator->id;
                $bkk->master_jenis_id = $cekJenis->id;
                $bkk->uraian = $row[1];
                $bkk->tipe_kegiatan_id = $cekTipeKegiatan->id;
                $bkk->apbd = $row[2];
                $bkk->p_apbd = $row[3];
                $bkk->tanggal_realisasi = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4]);
                $bkk->tahun = $row[5];
                $bkk->kelurahan_id = $cekKelurahan->id;
                $bkk->alamat = $row[6];
                if($row[7] != '')
                {
                    $bkk->lng = $row[7];
                }
                if($row[8] != '')
                {
                    $bkk->lat = $row[8];
                }
                $bkk->master_kategori_pembangunan_id = $cekKategoriPembangunan->id;
                $bkk->jumlah = $row[9];
                $bkk->save();

                $n++;

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
            $response['import_message'] = $errorInfo[2];
            session(['import_status' => $response['import_status']]);
            session(['import_message' => $response['import_message']]);
        }
        session(['import_status' => $response['import_status']]);
        session(['import_message' => $response['import_message']]);
        return true;
    }
}
