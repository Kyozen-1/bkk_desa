@extends('admin.layouts.app')

@section('title', 'Admin | Dashboard')

@section('subtitle', 'Dashboard')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css" integrity="sha512-RvQxwf+3zJuNwl4e0sZjQeX7kUa3o82bDETpgVCH2RiwYSZVDdFJ7N/woNigN/ldyOOoKw8584jM4plQdt8bhA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset('dropify/css/dropify.min.css') }}">
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
    .zoom {
        transition: transform .2s;
    }
    .zoom:hover {
        transform: scale(1.5);
    }
    .text-hover {
        transition: transform .2s;
    }
    .text-hover:hover{
        font-size: 40px;
    }

    .card-grafik {
        overflow-x: auto;
        overflow-y: hidden;
    }

    .grafik-container {
        width: 250%;
    }
</style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-5">
            <div class="card card-grafik">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="small-title text-hover">Grafik BKK Pertahun Anggaran Murni dan Perubahan</h2>
                        </div>
                    </div>
                    <div class="grafik-container" id="grafik_pertahun_anggaran_murni_dan_perubahan"></div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-5">
            <div class="card card-grafik">
                <div class="card-body">
                    <div class="row" id="row_label_grafik_bkk_kecamatan_anggaran_murni_dan_perubahan">
                        <div class="col-md-6" style="text-align: left;">
                            <h2 class="small-title text-hover">Grafik BKK Kecamatan Anggaran Murni dan Perubahan</h2>
                        </div>
                        <div class="col-md-6" style="text-align: right;">
                            <select name="filter_tahun" id="filter_tahun" class="form-control">
                                <option value="semua">Semua</option>
                            </select>
                        </div>
                    </div>
                    <div class="grafik-container" id="grafik_bkk_kecamatan_anggaran_murni_dan_perubahan"></div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-5">
            <div class="card card-grafik">
                <div class="card-body">
                    <div class="row" id="row_label_grafik_bkk_berdasarkan_tipe_kegiatan">
                        <div class="col-md-6" style="text-align: left;">
                            <h2 class="small-title text-hover">Grafik BKK Berdasarkan Tipe Kegiatan</h2>
                        </div>
                        <div class="col-md-6" style="text-align: right;">
                            <select name="filter_tahun_grafik_bkk_berdasarkan_tipe_kegiatan" id="filter_tahun_grafik_bkk_berdasarkan_tipe_kegiatan" class="form-control">
                                <option value="semua">Semua</option>
                            </select>
                        </div>
                    </div>
                    <div class="grafik-container" id="grafik_bkk_berdasarkan_tipe_kegiatan"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('rocker/assets/plugins/select2/js/select2-custom.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/fontawesome.min.js" integrity="sha512-j3gF1rYV2kvAKJ0Jo5CdgLgSYS7QYmBVVUjduXdoeBkc4NFV4aSRTi+Rodkiy9ht7ZYEwF+s09S43Z1Y+ujUkA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/apexcharts.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#filter_tahun').select2();
            $('#filter_tahun_grafik_bkk_berdasarkan_tipe_kegiatan').select2();
            $.ajax({
                url: "{{ route('admin.dashboard.grafik-pertahun-anggaran-murni-dan-perubahan') }}",
                dataType: "json",
                success: function(data)
                {
                    var grafik_pertahun_anggaran_murni_dan_perubahan = {
                        series: data.data_anggaran,
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
                                borderRadius: 20,
                            },
                        },
                        dataLabels: {
                            formatter: function (val) {
                                var value =  new Intl.NumberFormat("id-ID", {
                                                    style: "currency",
                                                    currency: "IDR"
                                                }).format(val);
                                return value;
                            }
                        },
                        xaxis: {
                            categories: data.tahun,
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Anggaran'
                            },
                            labels: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            style: {
                                fontSize: '2rem',
                            },
                            y: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
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

                    var chart_grafik_pertahun_anggaran_murni_dan_perubahan = new ApexCharts(document.querySelector("#grafik_pertahun_anggaran_murni_dan_perubahan"), grafik_pertahun_anggaran_murni_dan_perubahan);
                    chart_grafik_pertahun_anggaran_murni_dan_perubahan.render();
                }
            });

            $.ajax({
                url: "{{ route('admin.dashboard.grafik-bkk-kecamatan-anggaran-murni-dan-perubahan', ['tahun' => 'semua']) }}",
                dataType: "json",
                success: function(data)
                {
                    var grafik_bkk_kecamatan_anggaran_murni_dan_perubahan = {
                        series: data.data_anggaran,
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
                                borderRadius: 20,
                            },
                        },
                        dataLabels: {
                            formatter: function (val) {
                                var value =  new Intl.NumberFormat("id-ID", {
                                                    style: "currency",
                                                    currency: "IDR"
                                                }).format(val);
                                return value;
                            }
                        },
                        xaxis: {
                            categories: data.nama_kecamatan,
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Anggaran'
                            },
                            labels: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            style: {
                                fontSize: '2rem',
                            },
                            y: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
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

                    var chart_grafik_bkk_kecamatan_anggaran_murni_dan_perubahan = new ApexCharts(document.querySelector("#grafik_bkk_kecamatan_anggaran_murni_dan_perubahan"), grafik_bkk_kecamatan_anggaran_murni_dan_perubahan);
                    chart_grafik_bkk_kecamatan_anggaran_murni_dan_perubahan.render();
                }
            });

            $.ajax({
                url: "{{ route('admin.dashboard.grafik-bkk-berdasarkan-tipe-kegiatan', ['tahun' => 'semua']) }}",
                dataType: "json",
                success: function(data)
                {
                    var grafik_bkk_berdasarkan_tipe_kegiatan = {
                        series: data.data_anggaran,
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
                                borderRadius: 20,
                            },
                        },
                        dataLabels: {
                            formatter: function (val) {
                                var value =  new Intl.NumberFormat("id-ID", {
                                                    style: "currency",
                                                    currency: "IDR"
                                                }).format(val);
                                return value;
                            }
                        },
                        xaxis: {
                            categories: data.nama_tipe_kegiatan,
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Anggaran'
                            },
                            labels: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            style: {
                                fontSize: '2rem',
                            },
                            y: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
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

                    var chart_grafik_bkk_berdasarkan_tipe_kegiatan = new ApexCharts(document.querySelector("#grafik_bkk_berdasarkan_tipe_kegiatan"), grafik_bkk_berdasarkan_tipe_kegiatan);
                    chart_grafik_bkk_berdasarkan_tipe_kegiatan.render();
                }
            });
        });

        $('#filter_tahun').each(function(){
            var year = (new Date()).getFullYear();
            var current = year;
            year -= 10;
            for(var i = 0; i < 21; i++)
            {
                $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
            }
        });

        $('#filter_tahun_grafik_bkk_berdasarkan_tipe_kegiatan').each(function(){
            var year = (new Date()).getFullYear();
            var current = year;
            year -= 10;
            for(var i = 0; i < 21; i++)
            {
                $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
            }
        });

        $('#filter_tahun').change(function(){
            var value = $(this).val();
            var url = "{{ route('admin.dashboard.grafik-bkk-kecamatan-anggaran-murni-dan-perubahan', ['tahun' => ":value"]) }}";
            url = url.replace(':value', value);

            $.ajax({
                url: url,
                dataType: "json",
                success: function(data)
                {
                    $('#grafik_bkk_kecamatan_anggaran_murni_dan_perubahan').remove();

                    $('#row_label_grafik_bkk_kecamatan_anggaran_murni_dan_perubahan').after(`<div class="grafik-container" id="grafik_bkk_kecamatan_anggaran_murni_dan_perubahan"></div>`);

                    var grafik_bkk_kecamatan_anggaran_murni_dan_perubahan = {
                        series: data.data_anggaran,
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
                                borderRadius: 20,
                            },
                        },
                        dataLabels: {
                            formatter: function (val) {
                                var value =  new Intl.NumberFormat("id-ID", {
                                                    style: "currency",
                                                    currency: "IDR"
                                                }).format(val);
                                return value;
                            }
                        },
                        xaxis: {
                            categories: data.nama_kecamatan,
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Anggaran'
                            },
                            labels: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            style: {
                                fontSize: '2rem',
                            },
                            y: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
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

                    var chart_grafik_bkk_kecamatan_anggaran_murni_dan_perubahan = new ApexCharts(document.querySelector("#grafik_bkk_kecamatan_anggaran_murni_dan_perubahan"), grafik_bkk_kecamatan_anggaran_murni_dan_perubahan);
                    chart_grafik_bkk_kecamatan_anggaran_murni_dan_perubahan.render();
                }
            })
        })

        $('#filter_tahun_grafik_bkk_berdasarkan_tipe_kegiatan').change(function(){
            var value = $(this).val();
            var url = "{{ route('admin.dashboard.grafik-bkk-berdasarkan-tipe-kegiatan', ['tahun' => ":value"]) }}";
            url = url.replace(':value', value);

            $.ajax({
                url:url,
                dataType: "json",
                success: function(data)
                {
                    $('#grafik_bkk_berdasarkan_tipe_kegiatan').remove();

                    $('#row_label_grafik_bkk_berdasarkan_tipe_kegiatan').after(`<div class="grafik-container" id="grafik_bkk_berdasarkan_tipe_kegiatan"></div>`);

                    var grafik_bkk_berdasarkan_tipe_kegiatan = {
                        series: data.data_anggaran,
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
                                borderRadius: 20,
                            },
                        },
                        dataLabels: {
                            formatter: function (val) {
                                var value =  new Intl.NumberFormat("id-ID", {
                                                    style: "currency",
                                                    currency: "IDR"
                                                }).format(val);
                                return value;
                            }
                        },
                        xaxis: {
                            categories: data.nama_tipe_kegiatan,
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Anggaran'
                            },
                            labels: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
                                }
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            style: {
                                fontSize: '2rem',
                            },
                            y: {
                                formatter: function (val) {
                                    var value =  new Intl.NumberFormat("id-ID", {
                                                        style: "currency",
                                                        currency: "IDR"
                                                    }).format(val);
                                    return value;
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

                    var chart_grafik_bkk_berdasarkan_tipe_kegiatan = new ApexCharts(document.querySelector("#grafik_bkk_berdasarkan_tipe_kegiatan"), grafik_bkk_berdasarkan_tipe_kegiatan);
                    chart_grafik_bkk_berdasarkan_tipe_kegiatan.render();
                }
            })
        })
    </script>
@endsection
