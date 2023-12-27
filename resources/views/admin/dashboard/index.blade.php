@extends('admin.layouts.app')
@section('title', 'Admin | Dashboard')

@section('css')
    <link rel="stylesheet" href="{{ asset('acorn/acorn-elearning-portal/css/vendor/datatables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('acorn/acorn-elearning-portal/css/vendor/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('acorn/acorn-elearning-portal/css/vendor/select2-bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('acorn/acorn-elearning-portal/css/vendor/bootstrap-datepicker3.standalone.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('acorn/acorn-elearning-portal/css/vendor/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('acorn/acorn-elearning-portal/css/vendor/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dropify/css/dropify.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css" integrity="sha512-RvQxwf+3zJuNwl4e0sZjQeX7kUa3o82bDETpgVCH2RiwYSZVDdFJ7N/woNigN/ldyOOoKw8584jM4plQdt8bhA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .select2-selection__rendered {
            line-height: 40px !important;
        }
        .select2-container .select2-selection--single {
            height: 41px !important;
        }
        .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
@endsection

@section('content')
    @php
        use Carbon\Carbon;
        use App\Models\Bkk;

        $tahun_awal = $tahun_periode->tahun_awal;
        $jarak_tahun = $tahun_periode->tahun_akhir - $tahun_awal;
        $tahuns = [];
        for ($i=0; $i < $jarak_tahun + 1; $i++) {
            $tahuns[] = $tahun_awal + $i;
        }
    @endphp
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">Dashboard</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    </ul>
                </nav>
            </div>
            <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h2 class="small-title">Data BKK Periode {{$tahun_periode->tahun_awal}} - {{$tahun_periode->tahun_akhir}}</h2>
                        <hr>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="align-middle">
                                            <th scope="col" rowspan="2">#</th>
                                            <th scope="col" rowspan="2">Nama Fraksi</th>
                                            @foreach ($tahuns as $tahun)
                                                <th scope="col" colspan="2">{{$tahun}}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($tahuns as $tahun)
                                                <th scope="col">APBD</th>
                                                <th scope="col">P-APBD</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($fraksis as $fraksi)
                                            <tr>
                                                <th scope="row">{{$i++}}</th>
                                                <td>{{$fraksi->nama}}</td>
                                                @foreach ($tahuns as $tahun)
                                                    @php
                                                        $apbd = Bkk::where('tahun', $tahun)->where('status_konfirmasi', 'ya')
                                                                ->whereHas('aspirator', function($q) use ($fraksi){
                                                                    $q->where('master_fraksi_id', $fraksi->id);
                                                                })->sum('apbd');
                                                        $p_apbd = Bkk::where('tahun', $tahun)->where('status_konfirmasi', 'ya')
                                                                ->whereHas('aspirator', function($q) use ($fraksi){
                                                                    $q->where('master_fraksi_id', $fraksi->id);
                                                                })->sum('p_apbd');
                                                    @endphp
                                                    <td>Rp. {{number_format($apbd, 2, ',', '.')}}</td>
                                                    <td>Rp. {{number_format($p_apbd, 2, ',', '.')}}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                {{ $fraksis->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header border-0 pb-0">
                <ul class="nav nav-tabs nav-tabs-line card-header-tabs responsive-tabs" role="tablist">
                    @foreach ($tahuns as $tahun)
                        @if ($loop->first)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active tabPeriode" data-bs-toggle="tab" data-bs-target="#periode{{$tahun}}" role="tab" type="button" aria-selected="true" data-tahun="{{$tahun}}">
                                {{$tahun}}
                                </button>
                            </li>
                        @else
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tabPeriode" data-bs-toggle="tab" data-bs-target="#periode{{$tahun}}" role="tab" type="button" aria-selected="false" data-tahun="{{$tahun}}">
                                    {{$tahun}}
                                </button>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($tahuns as $tahun)
                        <div class="tab-pane fade @if($loop->first) active show @endif" id="periode{{$tahun}}" role="tabpanel">
                            <div class="row">
                                {{-- Filter Start --}}
                                <div class="col-12 mb-5">
                                    <div class="row">
                                        <div class="col-5">
                                            <select class="form-control filter-lokasi" id="filterLokasi{{$tahun}}">
                                                <option value="">--- Pilih Semua Kelurahan ---</option>
                                                @foreach ($kelurahans as $id => $nama)
                                                    <option value="{{$id}}">{{$nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <select class="form-control filter-fraksi" id="filterFraksi{{$tahun}}">
                                                <option value="">--- Pilih Semua Fraksi ---</option>
                                                @foreach ($masterFraksis as $id => $nama)
                                                    <option value="{{$id}}">{{$nama}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2" style="text-align: center;">
                                            <button class="btn btn-primary waves-effect waves-light btn_filter" type="button" data-tahun="{{$tahun}}">Filter</button>
                                        </div>
                                    </div>
                                </div>
                                {{-- Filter End --}}
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped" id="tableBkk{{$tahun}}">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Kelurahan/Desa</th>
                                                <th scope="col">Uraian</th>
                                                <th scope="col">Kegiatan</th>
                                                <th scope="col">Nilai APBD</th>
                                                <th scope="col">Fraksi</th>
                                                <th scope="col">Nama Aspirator</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="small-title">Grafik BKK Desa Perbulan pada Tahun {{Carbon::now()->year}}</h2>
                        <div id="grafik_bkk_desa_perbulan"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="small-title">Grafik BKK Desa Per Partai pada Tahun {{Carbon::now()->year}}</h2>
                        <div id="grafik_bkk_desa_perpartai"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="small-title">Grafik APBD dan P-APBD untuk BKK Desa pada Tahun {{Carbon::now()->year}}</h2>
                        <div id="grafik_apbd_papbd_bkk_desa"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group position relative mb-3">
                                <label for="detail_foto_before" class="form-label">Foto Sebelum</label>
                                <img id="detail_foto_before" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="detail_foto_after" class="form-label">Foto Sesudah</label>
                            <img id="detail_foto_after" class="img-fluid">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_master_fraksi" class="form-label">Partai</label>
                                <input type="text" class="form-control" id="detail_master_fraksi" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_aspirator" class="form-label">Aspirator</label>
                                <input type="text" class="form-control" id="detail_aspirator" disabled>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_uraian" class="form-label">Uraian</label>
                                <textarea name="detail_uraian" id="detail_uraian" rows="5" class="form-control" disabled></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_tipe_kegiatan" class="form-label">Tipe Kegiatan</label>
                                <input type="text" class="form-control" id="detail_tipe_kegiatan" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_master_jenis" class="form-label">Jenis</label>
                                <input type="text" class="form-control" id="detail_master_jenis" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_kategori_pembangunan" class="form-label">Kategori Pembangunan</label>
                                <input type="text" class="form-control" id="detail_kategori_pembangunan" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_jumlah" class="form-label">Jumlah</label>
                                <input type="text" class="form-control" id="detail_jumlah" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_apbd" class="form-label">APBD</label>
                                <input type="text" class="form-control" id="detail_apbd" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_p_apbd" class="form-label">P-APBD</label>
                                <input type="text" class="form-control" id="detail_p_apbd" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_tanggal_realisasi" class="form-label">Tanggal Realisasi</label>
                                <input type="text" class="form-control" id="detail_tanggal_realisasi" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_tahun" class="form-label">Tahun</label>
                                <input type="text" class="form-control" id="detail_tahun" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_kecamatan" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="detail_kecamatan" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_kelurahan" class="form-label">Kelurahan</label>
                                <input type="text" class="form-control" id="detail_kelurahan" disabled>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_alamat" class="form-label">Alamat</label>
                                <textarea id="detail_alamat" rows="5" class="form-control" disabled></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_lng" class="form-label">LNG</label>
                                <input type="text" class="form-control" id="detail_lng" disabled>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_lat" class="form-label">LAT</label>
                                <input type="text" class="form-control" id="detail_lat" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Oke</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/bootstrap-submenu.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/datatables.min.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/cs/scrollspy.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/jquery.validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/jquery.validate/additional-methods.min.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/select2.full.min.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/tagify.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script src="{{ asset('dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/dropzone.min.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/vendor/singleimageupload.js') }}"></script>
<script src="{{ asset('acorn/acorn-elearning-portal/js/cs/dropzone.templates.js') }}"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/fontawesome.min.js" integrity="sha512-j3gF1rYV2kvAKJ0Jo5CdgLgSYS7QYmBVVUjduXdoeBkc4NFV4aSRTi+Rodkiy9ht7ZYEwF+s09S43Z1Y+ujUkA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/apexcharts.js') }}"></script>
<script>
    var tahun = "{{$tahun_awal}}";
    $(document).ready(function(){
        $('.filter-lokasi').select2();
        $('.filter-fraksi').select2();

        var url = "{{ route('admin.dashboard.table', ['tahun' => ":tahun"]) }}";
        url = url.replace(":tahun", tahun);

        bkk_datatable();
        function bkk_datatable(filter_lokasi = '', filter_fraksi = '')
        {
            var dataTables = $('#tableBkk'+tahun).DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: url,
                    data: {
                        filter_lokasi:filter_lokasi,
                        filter_fraksi:filter_fraksi
                    }
                },
                columns:[
                    {
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'kelurahan_id',
                        name: 'kelurahan_id'
                    },
                    {
                        data: 'uraian',
                        name: 'uraian'
                    },
                    {
                        data: 'tipe_kegiatan_id',
                        name: 'tipe_kegiatan_id'
                    },
                    {
                        data: 'apbd',
                        name: 'apbd'
                    },
                    {
                        data: 'fraksi',
                        name: 'fraksi'
                    },
                    {
                        data: 'aspirator_id',
                        name: 'aspirator_id'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false
                    },
                ]
            });
        }

        $('.btn_filter').click(function(){
            tahun = $(this).attr('data-tahun');
            var filter_lokasi = $('#filterLokasi'+tahun).val();
            var filter_fraksi = $('#filterFraksi'+tahun).val();
            $('#tableBkk'+tahun).DataTable().destroy();
            bkk_datatable(filter_lokasi, filter_fraksi);
        });

        $.ajax({
            url: "{{ route('admin.dashboard.grafik-bkk-desa-perbulan') }}",
            dataType: "json",
            success: function(data)
            {
                var grafik_bkk_desa_perbulan = {
                    series: [{
                        name: 'BKK',
                        data: data.data_bkk
                    }],
                    chart: {
                        type: 'bar',
                        height: 500,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '24px',
                            colors: ['#fff']
                        }
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: data.nama_bulan,
                    },
                    yaxis: {
                        title: {
                            text: 'Jumlah BKK Desa'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                            return val + " BKK Desa"
                            }
                        },
                        style: {
                            fontSize: '2rem',
                        }
                    }
                };

                var chart_grafik_bkk_desa_perbulan = new ApexCharts(document.querySelector("#grafik_bkk_desa_perbulan"), grafik_bkk_desa_perbulan);
                chart_grafik_bkk_desa_perbulan.render();
            }
        });

        $.ajax({
            url: "{{ route('admin.dashboard.grafik-bkk-desa-perpartai') }}",
            dataType: "json",
            success: function(data)
            {
                var grafik_bkk_desa_perpartai = {
                    series: [{
                        name: 'BKK',
                        data: data.data_bkk
                    }],
                    chart: {
                        type: 'bar',
                        height: 500,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '24px',
                            colors: ['#fff']
                        }
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: data.nama_partai,
                    },
                    yaxis: {
                        title: {
                            text: 'Jumlah BKK Desa'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                            return val + " BKK Desa"
                            }
                        },
                        style: {
                            fontSize: '2rem',
                        }
                    }
                };

                var chart_grafik_bkk_desa_perpartai = new ApexCharts(document.querySelector("#grafik_bkk_desa_perpartai"), grafik_bkk_desa_perpartai);
                chart_grafik_bkk_desa_perpartai.render();
            }
        });

        $.ajax({
            url: "{{ route('admin.dashboard.grafik-apbd-papbd-bkk-desa') }}",
            dataType: "json",
            success: function(data)
            {
                var grafik_apbd_papbd_bkk_desa = {
                    series: data.data_bkk,
                    chart: {
                        type: 'bar',
                        height: 500,
                        stacked: true,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: true
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                position: 'bottom',
                                offsetX: -10,
                                offsetY: 0
                            }
                        }
                    }],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 10
                        },
                    },
                    xaxis: {
                        categories: data.nama_bulan,
                    },
                    yaxis: {
                        title: {
                            text: 'APBD dan P-APBD BKK Desa'
                        }
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        style: {
                            fontSize: '2rem',
                        },
                        y: {
                            formatter: function (val) {
                                return 'Rp. ' + new Intl.NumberFormat(["ban", "id"]).format(val)
                            }
                        },
                    },
                    legend: {
                        position: 'right',
                        offsetY: 40
                    },
                    fill: {
                        opacity: 1
                    }
                };

                var chart_grafik_apbd_papbd_bkk_desa = new ApexCharts(document.querySelector("#grafik_apbd_papbd_bkk_desa"), grafik_apbd_papbd_bkk_desa);
                chart_grafik_apbd_papbd_bkk_desa.render();
            }
        });
    });

    $(document).on('click', '.detail', function(){
        var id = $(this).attr('id');
        var url = "{{ route('admin.dashboard.detail', ['id' => ":id"]) }}";
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            dataType: "json",
            success: function(data)
            {
                $('#detail_master_fraksi').val(data.result.master_fraksi);
                $('#detail_aspirator').val(data.result.aspirator);
                $('#detail_uraian').val(data.result.uraian);
                $('#detail_tipe_kegiatan').val(data.result.tipe_kegiatan);
                $('#detail_master_jenis').val(data.result.master_jenis);
                $('#detail_kategori_pembangunan').val(data.result.kategori_pembangunan);
                $('#detail_jumlah').val(data.result.jumlah);
                $('#detail_apbd').val(data.result.apbd);
                $('#detail_p_apbd').val(data.result.p_apbd);
                $('#detail_tanggal_realisasi').val(data.result.tanggal_realisasi);
                $('#detail_tahun').val(data.result.tahun);
                $('#detail_kecamatan').val(data.result.kecamatan);
                $('#detail_kelurahan').val(data.result.kelurahan);
                $('#detail_alamat').val(data.result.alamat);
                $('#detail_lng').val(data.result.lng);
                $('#detail_lat').val(data.result.lat);
                $('#detail_foto_before').attr('src', "{{ asset('images/foto-bkk') }}" + '/' + data.result.foto_before);
                $('#detail_foto_after').attr('src', "{{ asset('images/foto-bkk') }}" + '/' + data.result.foto_after);
                $('#detailModal').modal('show');
            }
        });
    });

    $('.tabPeriode').click(function(){
        tahun = $(this).attr('data-tahun');

        $('.filter-lokasi').val("").trigger('change');
        $('.filter-fraksi').val("").trigger('change');

        var url = "{{ route('admin.dashboard.table', ['tahun' => ":tahun"]) }}";
        url = url.replace(":tahun", tahun);
        $('#tableBkk'+tahun).DataTable().destroy();
        bkk_datatable();
        function bkk_datatable(filter_lokasi = '', filter_fraksi = '')
        {
            var dataTables = $('#tableBkk'+tahun).DataTable({
                processing: true,
                serverSide: true,
                ajax:{
                    url: url,
                    data: {
                        filter_lokasi:filter_lokasi,
                        filter_fraksi:filter_fraksi
                    }
                },
                columns:[
                    {
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'kelurahan_id',
                        name: 'kelurahan_id'
                    },
                    {
                        data: 'uraian',
                        name: 'uraian'
                    },
                    {
                        data: 'tipe_kegiatan_id',
                        name: 'tipe_kegiatan_id'
                    },
                    {
                        data: 'apbd',
                        name: 'apbd'
                    },
                    {
                        data: 'fraksi',
                        name: 'fraksi'
                    },
                    {
                        data: 'aspirator_id',
                        name: 'aspirator_id'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false
                    },
                ]
            });
        }
    });
</script>
@endsection
