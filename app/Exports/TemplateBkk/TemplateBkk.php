<?php

namespace App\Exports\TemplateBkk;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\MasterFraksi;
use App\Models\Aspirator;
use App\Models\MasterTipeKegiatan;
use App\Models\MasterKategoriPembangunan;
use App\Models\MasterJenis;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class TemplateBkk implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new Sheet1Export(),
            new Sheet2Export()
        ];
    }
}

class Sheet1Export implements WithTitle, withEvents, WithStyles
{
    protected $countFraksi;
    protected $countAspirator;
    protected $countTipeKegiatan;
    protected $countKategoriPembangunan;
    protected $countJenis;
    protected $countKecamatan;
    protected $countKelurahan;

    public function __construct()
    {
        $this->countFraksi = MasterFraksi::count();
        $this->countAspirator = Aspirator::count();
        $this->countTipeKegiatan = MasterTipeKegiatan::count();
        $this->countKategoriPembangunan = MasterKategoriPembangunan::count();
        $this->countJenis = MasterJenis::count();
        $this->countKecamatan = Kecamatan::count();
        $this->countKelurahan = Kelurahan::count();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A1'    => [
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'quotePrefix'    => true
            ],
            'A3' => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'rgb' => 'FF0000'
                    ]
                ],
            ],
            'A1:Q1' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ],
                ],
            ],
            'A5:Q5' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ],
                ],
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event)
            {
                $sheet = $event->sheet;
                $sheet->mergeCells('A1:Q1');
                $sheet->setCellValue('A1', 'Templat BKK Desa Kabupaten Madiun');
                $sheet->mergeCells('A3:Q3');
                $sheet->setCellValue('A3', 'Ikuti Cara Pengisian Data Seperti Di Contoh!');
                $sheet->setCellValue('A5', 'No');
                $sheet->setCellValue('B5', 'Uraian');
                $sheet->setCellValue('C5', 'APBD');
                $sheet->setCellValue('D5', 'P-APBD');
                $sheet->setCellValue('E5', 'Tanggal Realisasi');
                $sheet->setCellValue('F5', 'Tahun');
                $sheet->setCellValue('G5', 'Alamat');
                $sheet->setCellValue('H5', 'lng (jika ada)');
                $sheet->setCellValue('I5', 'lat (jika ada)');
                $sheet->setCellValue('J5', 'Jumlah');
                $sheet->setCellValue('K5', 'Partai');
                $sheet->setCellValue('L5', 'Aspirator');
                $sheet->setCellValue('M5', 'Tipe Kegiatan');
                $sheet->setCellValue('N5', 'Jenis');
                $sheet->setCellValue('O5', 'Kategori Pembangunan');
                $sheet->setCellValue('P5', 'Kecamatan');
                $sheet->setCellValue('Q5', 'Kelurahan / Desa');

                // Fraksi Start
                    $validationFraksi = $event->sheet->getCell('K6')->getDataValidation();
                    $validationFraksi->setType(DataValidation::TYPE_LIST );
                    $validationFraksi->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validationFraksi->setAllowBlank(false);
                    $validationFraksi->setShowInputMessage(true);
                    $validationFraksi->setShowErrorMessage(true);
                    $validationFraksi->setShowDropDown(true);
                    $validationFraksi->setErrorTitle('Input error');
                    $validationFraksi->setError('Value is not in list.');
                    $validationFraksi->setPromptTitle('Pick from list');
                    $validationFraksi->setPrompt('Please pick a value from the drop-down list.');
                    $countRowFraksi = $this->countFraksi + 1;
                    $validationFraksi->setFormula1('Sheet2!$B$2:$B$'.$countRowFraksi);
                // Fraksi End

                // Aspirator Start
                    $validationAspirator = $event->sheet->getCell('L6')->getDataValidation();
                    $validationAspirator->setType(DataValidation::TYPE_LIST );
                    $validationAspirator->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validationAspirator->setAllowBlank(false);
                    $validationAspirator->setShowInputMessage(true);
                    $validationAspirator->setShowErrorMessage(true);
                    $validationAspirator->setShowDropDown(true);
                    $validationAspirator->setErrorTitle('Input error');
                    $validationAspirator->setError('Value is not in list.');
                    $validationAspirator->setPromptTitle('Pick from list');
                    $validationAspirator->setPrompt('Please pick a value from the drop-down list.');
                    $countRowAspirator = $this->countAspirator + 1;
                    $validationAspirator->setFormula1('Sheet2!$F$2:$F$'.$countRowAspirator);
                // Aspirator End

                // Tipe Kegiatan Start
                    $validationTipeKegiatan = $event->sheet->getCell('M6')->getDataValidation();
                    $validationTipeKegiatan->setType(DataValidation::TYPE_LIST );
                    $validationTipeKegiatan->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validationTipeKegiatan->setAllowBlank(false);
                    $validationTipeKegiatan->setShowInputMessage(true);
                    $validationTipeKegiatan->setShowErrorMessage(true);
                    $validationTipeKegiatan->setShowDropDown(true);
                    $validationTipeKegiatan->setErrorTitle('Input error');
                    $validationTipeKegiatan->setError('Value is not in list.');
                    $validationTipeKegiatan->setPromptTitle('Pick from list');
                    $validationTipeKegiatan->setPrompt('Please pick a value from the drop-down list.');
                    $countRowTipeKegiatan = $this->countTipeKegiatan + 1;
                    $validationTipeKegiatan->setFormula1('Sheet2!$I$2:$I$'.$countRowTipeKegiatan);
                // Tipe Kegiatan End

                // Jenis Start
                    $validationJenis = $event->sheet->getCell('N6')->getDataValidation();
                    $validationJenis->setType(DataValidation::TYPE_LIST );
                    $validationJenis->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validationJenis->setAllowBlank(false);
                    $validationJenis->setShowInputMessage(true);
                    $validationJenis->setShowErrorMessage(true);
                    $validationJenis->setShowDropDown(true);
                    $validationJenis->setErrorTitle('Input error');
                    $validationJenis->setError('Value is not in list.');
                    $validationJenis->setPromptTitle('Pick from list');
                    $validationJenis->setPrompt('Please pick a value from the drop-down list.');
                    $countRowJenis = $this->countJenis + 1;
                    $validationJenis->setFormula1('Sheet2!$L$2:$L$'.$countRowJenis);
                // Jenis End

                // Kategori Pembangunan Start
                    $validationKategoriPembangunan = $event->sheet->getCell('O6')->getDataValidation();
                    $validationKategoriPembangunan->setType(DataValidation::TYPE_LIST );
                    $validationKategoriPembangunan->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validationKategoriPembangunan->setAllowBlank(false);
                    $validationKategoriPembangunan->setShowInputMessage(true);
                    $validationKategoriPembangunan->setShowErrorMessage(true);
                    $validationKategoriPembangunan->setShowDropDown(true);
                    $validationKategoriPembangunan->setErrorTitle('Input error');
                    $validationKategoriPembangunan->setError('Value is not in list.');
                    $validationKategoriPembangunan->setPromptTitle('Pick from list');
                    $validationKategoriPembangunan->setPrompt('Please pick a value from the drop-down list.');
                    $countRowKategoriPembangunan = $this->countKategoriPembangunan + 1;
                    $validationKategoriPembangunan->setFormula1('Sheet2!$O$2:$O$'.$countRowKategoriPembangunan);
                // Kategori Pembangunan End

                // Kecamatan Start
                    $validationKecamatan = $event->sheet->getCell('P6')->getDataValidation();
                    $validationKecamatan->setType(DataValidation::TYPE_LIST );
                    $validationKecamatan->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validationKecamatan->setAllowBlank(false);
                    $validationKecamatan->setShowInputMessage(true);
                    $validationKecamatan->setShowErrorMessage(true);
                    $validationKecamatan->setShowDropDown(true);
                    $validationKecamatan->setErrorTitle('Input error');
                    $validationKecamatan->setError('Value is not in list.');
                    $validationKecamatan->setPromptTitle('Pick from list');
                    $validationKecamatan->setPrompt('Please pick a value from the drop-down list.');
                    $countRowKecamatan = $this->countKecamatan + 1;
                    $validationKecamatan->setFormula1('Sheet2!$R$2:$R$'.$countRowKecamatan);
                // Kecamatan End

                // Kelurahan Start
                    $validationKelurahan = $event->sheet->getCell('Q6')->getDataValidation();
                    $validationKelurahan->setType(DataValidation::TYPE_LIST );
                    $validationKelurahan->setErrorStyle(DataValidation::STYLE_INFORMATION );
                    $validationKelurahan->setAllowBlank(false);
                    $validationKelurahan->setShowInputMessage(true);
                    $validationKelurahan->setShowErrorMessage(true);
                    $validationKelurahan->setShowDropDown(true);
                    $validationKelurahan->setErrorTitle('Input error');
                    $validationKelurahan->setError('Value is not in list.');
                    $validationKelurahan->setPromptTitle('Pick from list');
                    $validationKelurahan->setPrompt('Please pick a value from the drop-down list.');
                    $countRowKelurahan = $this->countKelurahan + 1;
                    $validationKelurahan->setFormula1('Sheet2!$V$2:$V$'.$countRowKelurahan);
                // Kelurahan End
            }
        ];
    }

    public function title(): string
    {
        return 'Template Impor BKK';
    }
}

class Sheet2Export implements WithTitle, withEvents
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event)
            {
                $sheet = $event->sheet;
                // Partai Start
                $sheet->setCellValue('A1', 'No');
                $sheet->setCellValue('B1', 'Partai');
                $partaies = MasterFraksi::all();
                $number_partai = 0;
                foreach ($partaies as $partai) {
                    $number_partai++;
                    $sheet->setCellValue('A'. $number_partai+1, $number_partai);
                    $sheet->setCellValue('B'.$number_partai+1, $partai->nama);
                }
                // Partai End

                // Aspirator Start
                $sheet->setCellValue('D1', 'No');
                $sheet->setCellValue('E1', 'Partai');
                $sheet->setCellValue('F1', 'Aspirator');
                $aspirators = Aspirator::all();
                $number_aspirator = 0;
                foreach($aspirators as $aspirator)
                {
                    $number_aspirator++;
                    $sheet->setCellValue('D'.$number_aspirator+1, $number_aspirator);
                    $sheet->setCellValue('E'.$number_aspirator+1, $aspirator->master_fraksi->nama);
                    $sheet->setCellValue('F'.$number_aspirator+1, $aspirator->nama);
                }
                // Aspirator End

                // Tipe Kegiatan Start
                $sheet->setCellValue('H1', 'No');
                $sheet->setCellValue('I1', 'Nama');
                $tipeKegiatans = MasterTipeKegiatan::all();
                $number_tipe_kegiatan = 0;
                foreach ($tipeKegiatans as $tipeKegiatan) {
                    $number_tipe_kegiatan++;
                    $sheet->setCellValue('H'.$number_tipe_kegiatan+1, $number_tipe_kegiatan);
                    $sheet->setCellValue('I'.$number_tipe_kegiatan+1, $tipeKegiatan->nama);
                }
                // Tipe Kegiatan End

                // Jenis Start
                $sheet->setCellValue('K1', 'No');
                $sheet->setCellValue('L1', 'Nama');
                $jenises = MasterJenis::all();
                $number_jenis = 0;
                foreach($jenises as $jenis)
                {
                    $number_jenis++;
                    $sheet->setCellValue('K'.$number_jenis+1, $number_jenis);
                    $sheet->setCellValue('L'.$number_jenis+1, $jenis->nama);
                }
                // Jenis End

                // Kategori Pembangunan Start
                $sheet->setCellValue('N1', 'NO');
                $sheet->setCellValue('O1', 'Nama');
                $kategoriPembangunans = MasterKategoriPembangunan::all();
                $number_kategori_pembangunan = 0;
                foreach ($kategoriPembangunans as $kategoriPembangunan) {
                    $number_kategori_pembangunan++;
                    $sheet->setCellValue('N'.$number_kategori_pembangunan+1, $number_kategori_pembangunan);
                    $sheet->setCellValue('O'.$number_kategori_pembangunan+1, $kategoriPembangunan->nama);
                }
                // Kategori Pembangunan End

                // Kecamatan Start
                $sheet->setCellValue('Q1', 'No');
                $sheet->setCellValue('R1', 'Nama');
                $kecamatans = Kecamatan::where('kabupaten_id', '62')->get();
                $number_kecamatan = 0;
                foreach ($kecamatans as $kecamatan) {
                    $number_kecamatan++;
                    $sheet->setCellValue('Q'.$number_kecamatan+1, $number_kecamatan);
                    $sheet->setCellValue('R'.$number_kecamatan+1, $kecamatan->nama);
                }
                // Kecamatan End

                // Kelurahan Start
                $sheet->setCellValue('T1', 'No');
                $sheet->setCellValue('U1', 'Kecamatan');
                $sheet->setCellValue('V1', 'Nama');
                $kelurahans = Kelurahan::whereHas('kecamatan', function($q){
                    $q->where('kabupaten_id', 62);
                })->get();
                $number_kelurahan = 0;
                foreach($kelurahans as $kelurahan)
                {
                    $number_kelurahan++;
                    $sheet->setCellValue('T'.$number_kelurahan+1, $number_kelurahan);
                    $sheet->setCellValue('U'.$number_kelurahan+1, $kelurahan->kecamatan->nama);
                    $sheet->setCellValue('V'.$number_kelurahan+1, $kelurahan->nama);
                }
            }
        ];
    }

    public function title(): string
    {
        return 'Sheet2';
    }
}
