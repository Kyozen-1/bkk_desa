@extends('admin.layouts.app')
@section('title', 'Admin | Peta Persebaran BKK')

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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="{{ asset('leaflet/js/leaflet.textpath.js') }}"></script>
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
        #map { height: 35rem; }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">Peta Per Kelurahan</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Peta</a></li>
                        <li class="breadcrumb-item"><a href="#">Peta Per Kelurahan</a></li>
                    </ul>
                </nav>
            </div>
            <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <select name="filter_kecamatan_id" id="filter_kecamatan_id" class="form-control">
                            <option value="">--- Pilih Kecamatan ---</option>
                            @foreach ($kecamatan as $id => $nama)
                                <option value="{{$id}}">{{$nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <select name="filter_kelurahan_id" id="filter_kelurahan_id" class="form-control" disabled>
                            <option value="">--- Pilih Kelurahan ---</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4" style="text-align: center">
                        <button class="btn btn-icon btn-success waves-effect-waves-light" id="btn_filter"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
                <div id="map"></div>
            </div>
        </div>
    </div>

    <div class="modal modal-right large fade scroll-out-negative" id="listDataBkkModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable full">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">List Data BKK Kec: <span id="span_nama_kecamatan"></span>, Kel: <span id="span_nama_kelurahan"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="background: #f1f1f1">
                    <div class="scroll-track-visible">
                        <ul id="list_data_bkk"></ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    <script>
        const map = L.map('map').setView([-7.616648802470493,111.65000137302656], 13);
        var popup = L.popup();
        googleStreets = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}',{
            maxZoom: 20,
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(map);
        var geoLayer;
        $(document).ready(function(){
            $('#filter_kecamatan_id').select2();
            $('#filter_kelurahan_id').select2();
            $.getJSON("{{ asset('geojson/kelurahan.geojson') }}", function(json){
                geoLayer = L.geoJson(json, {
                    style: function(feature)
                    {
                        return {
                            weight: 1,
                            opacity: 1,
                            color: feature.properties.warna,
                            fillOpacity: 0.5,
                        };
                    },
                    onEachFeature: function(feature, layer){
                        // alert(feature.properties.nama)
                        $.ajax({
                            url: "{{ url('/admin/peta-per-kelurahan/get-data-kelurahan') }}" + '/' + feature.properties.kelurahanId,
                            dataType: "json",
                            success: function(data)
                            {
                                var iconLabel = L.divIcon({
                                    className: 'label-bidang',
                                    html: '<b class="text-white">'+data.bkk+'</b>',
                                    iconSize:[20,20]
                                });
                                L.marker(layer.getBounds().getCenter(), {icon:iconLabel}).addTo(map);
                            }
                        });
                        layer.addTo(map).on('click', function(e){
                            $.ajax({
                                url: "{{ url('/admin/peta-per-kelurahan/get-data-kelurahan/detail') }}" + '/' + feature.properties.kelurahanId,
                                dataType: 'json',
                                success: function(data)
                                {
                                    $('#list_data_bkk').html(data.html);
                                    $('#span_nama_kelurahan').text(data.kelurahan.nama);
                                    $('#span_nama_kecamatan').text(data.kecamatan);
                                    $('#listDataBkkModal').modal('show');
                                }
                            });
                        });
                    }
                });
            });
        });

        $('#filter_kecamatan_id').change(function(){
            if($(this).val() != '')
            {
                $.ajax({
                    url: "{{ route('admin.bkk.get-kelurahan') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id:$(this).val()
                    },
                    success: function(data)
                    {
                        $('#filter_kelurahan_id').empty();
                        $('#filter_kelurahan_id').prop('disabled', false);
                        $('#filter_kelurahan_id').append('<option value="">--- Pilih Kelurahan---</option>');
                        $.each(data, function(id, nama){
                            $('#filter_kelurahan_id').append(new Option(nama, id));
                        });
                    }
                });
            } else {
                $("[name='filter_kelurahan_id']").val('').trigger('change');
                $('#filter_kelurahan_id').prop('disabled', true);
            }
        });

        $('#btn_filter').click(function(){
            var id = $('#filter_kelurahan_id').val();
            geoLayer.eachLayer(function(layer){
                if(layer.feature.properties.kelurahanId == id)
                {
                    map.flyTo(layer.getBounds().getCenter(), 16);
                    layer.bindPopup(layer.feature.properties.nama);
                }
            });
        });
    </script>
@endsection
