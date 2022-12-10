@extends('admin.layouts.app')
@section('title', 'Admin | BKK Desa')

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
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css" type="text/css">
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
        #map { position: absolute; top: 0; bottom: 0; width: 100%; }
        #foto_peta{
            visibility : hidden;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">BKK Desa</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bkk.index') }}">BKK Desa</a></li>
                        <li class="breadcrumb-item"><a href="#">Edit</a></li>
                    </ul>
                </nav>
            </div>
            <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="card mb-5">
            <div class="card-body">
                <h2 class="small-title">Edit Data</h2>
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('admin.bkk.update') }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="bkk_id" value="{{$data->id}}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group position-relative mb-3">
                                        <label for="master_fraksi_id" class="form-label">Partai</label>
                                        <select name="master_fraksi_id" id="master_fraksi_id" class="form-control" required>
                                            <option value="">--- Pilih Partai ---</option>
                                            @foreach ($master_fraksi as $id => $nama)
                                                <option value="{{$id}}">{{$nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group position-relative mb-3">
                                        <label for="aspirator_id" class="form-label">Aspirator</label>
                                        <select name="aspirator_id" id="aspirator_id" class="form-control" disabled required>
                                            <option value="">--- Pilih Aspirator ---</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group position-relative mb-3">
                                <label for="uraian" class="form-label">Uraian</label>
                                <textarea name="uraian" id="uraian" rows="5" class="form-control" required>{{$data->uraian}}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="tipe_kegiatan_id" class="form-label">Tipe Kegiatan</label>
                                        <select name="tipe_kegiatan_id" id="tipe_kegiatan_id" class="form-control" required>
                                            <option value="">--- Pilih Tipe Kegiatan ---</option>
                                            @foreach ($master_tipe_kegiatan as $id => $nama)
                                                <option value="{{$id}}" @if($id == $data->tipe_kegiatan_id) selected @endif>{{$nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group position-relative mb-3">
                                        <label for="master_jenis_id" class="form-label">Jenis</label>
                                        <select name="master_jenis_id" id="master_jenis_id" class="form-control" required>
                                            <option value="">--- Pilih Jenis ---</option>
                                            @foreach ($master_jenis as $id => $nama)
                                                <option value="{{$id}}" @if($data->master_jenis_id == $id) selected @endif>{{$nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-group position-relative">
                                        <label for="master_kategori_pembangunan_id" class="form-label">Kategori Pembangunan</label>
                                        <select name="master_kategori_pembangunan_id" id="master_kategori_pembangunan_id" class="form-control" required>
                                            <option value="">--- Pilih Kategori Pembangunan ---</option>
                                            @foreach ($master_kategori_pembangunans as $master_kategori_pembangunan)
                                                <option value="{{$master_kategori_pembangunan->id}}" @if($master_kategori_pembangunan->id == $data->master_kategori_pembangunan_id) selected @endif>{{$master_kategori_pembangunan->nama}} ({{$master_kategori_pembangunan->satuan}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group position-relative">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="text" class="form-control" id="jumlah" name="jumlah" value="{{$data->jumlah}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="apbd" class="form-label">APBD</label>
                                        <input type="text" class="form-control" id="apbd" name="apbd" value="{{$data->apbd}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="p_apbd" class="form-label">P-APBD</label>
                                        <input type="text" class="form-control" id="p_apbd" name="p_apbd" value="{{$data->p_apbd}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="tanggal_realisasi" class="form-label">Tanggal Realisasi</label>
                                        <input type="date" name="tanggal_realisasi" id="tanggal_realisasi" class="form-control" value="{{$data->tanggal_realisasi}}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <select name="tahun" id="tahun" class="form-control" required></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="kecamatan_id" class="form-label">Kecamatan</label>
                                        <select name="kecamatan_id" id="kecamatan_id" class="form-control" required>
                                            <option value="">--- Pilih Kecamatan ---</option>
                                            @foreach ($kecamatan as $id => $nama)
                                                <option value="{{$id}}">{{$nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="kelurahan_id" class="form-label">Kelurahan/Desa</label>
                                        <select name="kelurahan_id" id="kelurahan_id" class="form-control" disabled required>
                                            <option value="">--- Pilih Kelurahan ---</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="position-relative form-group mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="5" class="form-control">{{$data->alamat}}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="foto_before" class="control-label">Foto Before</label>
                                        <input type="file" class="dropify" id="foto_before" name="foto_before" data-height="150" data-allowed-file-extensions="png jpg jpeg" data-show-errors="true">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="position-relative form-group mb-3">
                                        <label for="foto_after" class="control-label">Foto After</label>
                                        <input type="file" class="dropify" id="foto_after" name="foto_after" data-height="150" data-allowed-file-extensions="png jpg jpeg" data-show-errors="true">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div id="map_bkk" style="width: 100%; height:30rem;" class="mb-2"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lng" class="control-label">Longtitude</label>
                                        <input type="text" class="form-control lng" value="{{$data->lng}}" disabled>
                                        <input type="hidden" name="lng" id="lng" value="{{$data->lng}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lat" class="control-label">Lattitude</label>
                                        <input type="text" class="form-control lat" value="{{$data->lat}}" disabled>
                                        <input type="hidden" name="lat" id="lat" value="{{$data->lat}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12" style="text-align: right">
                                    <a class="btn btn-outline-primary waves-effect waves-light mr-2" href="{{ route('admin.bkk.index') }}">Kembali</a>
                                    <button class="btn btn-primary waves-effect waves-light">Simpan</button>
                                </div>
                            </div>
                        </form>
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
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <script src="https://github.com/niklasvh/html2canvas/releases/download/0.4.1/html2canvas.js"></script>
    <script>
        var lng = "{{$data->lng}}";
        var lat = "{{$data->lat}}";
        $(document).ready(function(){
            $('.dropify').dropify();
            $('#master_fraksi_id').select2();
            $('#aspirator_id').select2();
            $('#master_jenis_id').select2();
            $('#kecamatan_id').select2();
            $('#kelurahan_id').select2();
            $('#tahun').select2();

            mapboxgl.accessToken = "{{ env('MAPBOX_ACCESS_TOKEN') }}";
            var map = new mapboxgl.Map({
                container: 'map_bkk', // container id
                style: 'mapbox://styles/mapbox/light-v10', // style URL
                center: [lng,lat], // starting position [lng, lat]
                zoom: 12 // starting zoom
            });

            map.addControl(new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                mapboxgl: mapboxgl
            }));

            var geolocate = new mapboxgl.GeolocateControl({
                                positionOptions: {
                                    enableHighAccuracy: true
                                },
                                trackUserLocation: true,
                                showUserHeading: true
                            });
            map.addControl(geolocate);

            geolocate.on('geolocate', function(e){
                const longtitude = e.coords.longitude;
                const lattitude = e.coords.latitude;
                $('.lng').val(longtitude);
                $('#lng').val(longtitude);
                $('.lat').val(lattitude);
                $('#lat').val(lattitude);
                map.setZoom(12);
            });

            map.addControl(new mapboxgl.NavigationControl());

            map.on('click', (e) => {
                const longtitude = e.lngLat.lng;
                const lattitude = e.lngLat.lat;

                $('.lng').val(longtitude);
                $('#lng').val(longtitude);
                $('.lat').val(lattitude);
                $('#lat').val(lattitude);
            });

            map.on('load', () => {
                // Load an image from an external URL.
                map.loadImage(
                "{{ asset('images/dll/posisi.png') }}",
                (error, image) => {
                if (error) throw error;

                // Add the image to the map style.
                map.addImage('posisi', image);

                // Add a data source containing one point feature.
                map.addSource('point', {
                    'type': 'geojson',
                    'data': {
                            'type': 'FeatureCollection',
                            'features': [
                                    {
                                        'type': 'Feature',
                                        'geometry': {
                                            'type': 'Point',
                                            'coordinates': [lng, lat]
                                        }
                                    }
                                ]
                            }
                        });

                        // Add a layer to use the image to represent the data.
                        map.addLayer({
                            'id': 'points',
                            'type': 'symbol',
                            'source': 'point', // reference the data source
                            'layout': {
                                'icon-image': 'posisi', // reference the image
                                'icon-size': 0.1,
                                'icon-anchor': 'center',
                            }
                        });
                    }
                );
            });

            $("[name='master_fraksi_id']").val("{{$data->aspirator->master_fraksi_id}}").trigger('change');
            $("[name='tahun']").val("{{ $data->tahun }}").trigger('change');
            $("[name='kecamatan_id']").val("{{$data->kelurahan->kecamatan_id}}").trigger('change');

            var lokasi_foto_before = "{{ asset('images/foto-bkk/'.$data->foto_before) }}";
            var fileDropperFotoBefore = $("#foto_before").dropify();

            fileDropperFotoBefore = fileDropperFotoBefore.data('dropify');
            fileDropperFotoBefore.resetPreview();
            fileDropperFotoBefore.clearElement();
            fileDropperFotoBefore.settings['defaultFile'] = lokasi_foto_before;
            fileDropperFotoBefore.destroy();
            fileDropperFotoBefore.init();

            var lokasi_foto_after = "{{ asset('images/foto-bkk/'.$data->foto_after) }}";
            var fileDropperFotoAfter = $("#foto_after").dropify();

            fileDropperFotoAfter = fileDropperFotoAfter.data('dropify');
            fileDropperFotoAfter.resetPreview();
            fileDropperFotoAfter.clearElement();
            fileDropperFotoAfter.settings['defaultFile'] = lokasi_foto_after;
            fileDropperFotoAfter.destroy();
            fileDropperFotoAfter.init();
        });

        $('#master_fraksi_id').change(function(){
            if($(this).val() != '')
            {
                $.ajax({
                    url: "{{ route('admin.bkk.get-aspirator') }}",
                    method: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id:$(this).val()
                    },
                    success: function(data)
                    {
                        $('#aspirator_id').empty();
                        $('#aspirator_id').prop('disabled', false);
                        $('#aspirator_id').append('<option value="">--- Pilih Aspirator---</option>');
                        $.each(data, function(id, nama){
                            $('#aspirator_id').append(new Option(nama, id));
                        });
                        $("[name='aspirator_id']").val("{{ $data->aspirator_id }}").trigger('change');
                    }
                });
            } else {
                $("[name='aspirator_id']").val('').trigger('change');
                $('#aspirator_id').prop('disabled', true);
            }
        });

        $('#kecamatan_id').change(function(){
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
                        $('#kelurahan_id').empty();
                        $('#kelurahan_id').prop('disabled', false);
                        $('#kelurahan_id').append('<option value="">--- Pilih Kelurahan---</option>');
                        $.each(data, function(id, nama){
                            $('#kelurahan_id').append(new Option(nama, id));
                        });
                        $("[name='kelurahan_id']").val("{{ $data->kelurahan_id }}").trigger('change');
                    }
                });
            } else {
                $("[name='kelurahan_id']").val('').trigger('change');
                $('#kelurahan_id').prop('disabled', true);
            }
        });

        $('#tahun').each(function(){
            var year = (new Date()).getFullYear();
            var current = year;
            year -= 20;
            for(var i = 0; i < 21; i++)
            {
                if((year+i) == current)
                {
                    $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
                } else {
                    $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
                }
            }
        });
    </script>
@endsection
