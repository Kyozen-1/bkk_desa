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
    $(document).ready(function(){
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
        })
    });
</script>
@endsection
