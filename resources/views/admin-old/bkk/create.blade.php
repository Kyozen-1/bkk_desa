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
        .select2 {
            width: 100% !important;
        }
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
                        <li class="breadcrumb-item"><a href="#">Create</a></li>
                    </ul>
                </nav>
            </div>
            <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <form action="{{ route('admin.bkk.store') }}" class="form-horizontal" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title">Tambah Data BKK</h2>
                    <hr>
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
                        <div class="col-12 col-md-6">
                            <div class="position-relative form-group mb-3">
                                <label for="tipe_kegiatan_id" class="form-label">Tipe Kegiatan</label>
                                <select name="tipe_kegiatan_id" id="tipe_kegiatan_id" class="form-control" required>
                                    <option value="">--- Pilih Tipe Kegiatan ---</option>
                                    @foreach ($master_tipe_kegiatan as $id => $nama)
                                        <option value="{{$id}}">{{$nama}}</option>
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
                                        <option value="{{$id}}">{{$nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative">
                                <label for="master_kategori_pembangunan_id" class="form-label">Kategori Pembangunan</label>
                                <select name="master_kategori_pembangunan_id" id="master_kategori_pembangunan_id" class="form-control" required>
                                    <option value="">--- Pilih Kategori Pembangunan ---</option>
                                    @foreach ($master_kategori_pembangunans as $master_kategori_pembangunan)
                                        <option value="{{$master_kategori_pembangunan->id}}">{{$master_kategori_pembangunan->nama}} ({{$master_kategori_pembangunan->satuan}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group position-relative">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="text" class="form-control" id="jumlah" name="jumlah" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative form-group mb-3">
                                <label for="apbd" class="form-label">APBD</label>
                                <input type="text" class="form-control" id="apbd" name="apbd" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative form-group mb-3">
                                <label for="p_apbd" class="form-label">P-APBD</label>
                                <input type="text" class="form-control" id="p_apbd" name="p_apbd" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group position-relative mb-3">
                                <label for="uraian" class="form-label">Uraian</label>
                                <textarea name="uraian" id="uraian" rows="5" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative form-group mb-3">
                                <label for="tanggal_realisasi" class="form-label">Tanggal Realisasi</label>
                                <input type="date" name="tanggal_realisasi" id="tanggal_realisasi" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative form-group mb-3">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-control" required></select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title">Dokumentasi</h2>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="position-relative form-group mb-3">
                                <label for="foto_before" class="control-label">Foto Sebelum</label>
                                <input type="file" class="dropify" id="foto_before" name="foto_before[]" data-height="150" data-allowed-file-extensions="png jpg jpeg" data-show-errors="true" multiple required>
                                <span class="text-danger small-title">*silahkan masukkan 2 - 5 gambar</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="position-relative form-group mb-3">
                                <label for="foto_after" class="control-label">Foto Sesudah</label>
                                <input type="file" class="dropify" id="foto_after" name="foto_after[]" data-height="150" data-allowed-file-extensions="png jpg jpeg" data-show-errors="true" multiple>
                                <span class="text-danger small-title">*Isi jika sudah *silahkan masukkan 2 - 5 gambar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5">
                <div class="card-body">
                    <h2 class="small-title">Lokasi</h2>
                    <hr>
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
                        <div class="col-12">
                            <div class="position-relative form-group mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div id="map_bkk" style="width: 100%; height:30rem;" class="mb-2"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lng" class="control-label">Longtitude</label>
                                <input type="text" class="form-control lng" required disabled>
                                <input type="hidden" name="lng" id="lng">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lat" class="control-label">Lattitude</label>
                                <input type="text" class="form-control lat" required disabled>
                                <input type="hidden" name="lat" id="lat">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 align-middle">
                            <h2 class="small-title">Aksi</h2>
                        </div>
                        <div class="col-8" style="text-align: right">
                            <a class="btn btn-outline-primary waves-effect waves-light mr-2" href="{{ route('admin.bkk.index') }}">Kembali</a>
                            <button class="btn btn-primary waves-effect waves-light" id="btn_simpan">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
        $(document).ready(function(){
            $('.dropify').dropify({
                messages: {
                    'default': ''
                }
            });
            $('#master_fraksi_id').select2();
            $('#tipe_kegiatan_id').select2();
            $('#aspirator_id').select2();
            $('#master_jenis_id').select2();
            $('#kecamatan_id').select2();
            $('#kelurahan_id').select2();
            $('#tahun').select2();
            $('#master_kategori_pembangunan_id').select2();

            mapboxgl.accessToken = "{{ env('MAPBOX_ACCESS_TOKEN') }}";
            var map = new mapboxgl.Map({
                container: 'map_bkk', // container id
                style: 'mapbox://styles/mapbox/light-v10', // style URL
                center: [111.65000137302656,-7.616648802470493], // starting position [lng, lat]
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

        $("[name='foto_before[]']").change(function(){
            var number_of_images = $(this)[0].files.length;
            if (number_of_images < 2) {
                alert(`Minimal mengupload gambar sebanyak 2.`);
                $('.dropify-clear').click();
                $('#btn_simpan').prop('disabled', 'disabled');
            } else {
                $('#btn_simpan').prop('disabled', false);
            }
            if (number_of_images > 5) {
                alert(`Kamu hanya bisa mengupload gambar sebanyak 5.`);
                $('.dropify-clear').click();
                $('#btn_simpan').prop('disabled', 'disabled');
            } else {
                $('#btn_simpan').prop('disabled', false);
            }
        });

        $("[name='foto_after[]']").change(function(){
            var number_of_images = $(this)[0].files.length;
            if (number_of_images < 2) {
                alert(`Minimal mengupload gambar sebanyak 2.`);
                $('.dropify-clear').click();
                $('#btn_simpan').prop('disabled', 'disabled');
            } else {
                $('#btn_simpan').prop('disabled', false);
            }
            if (number_of_images > 5) {
                alert(`Kamu hanya bisa mengupload gambar sebanyak 5.`);
                $('.dropify-clear').click();
                $('#btn_simpan').prop('disabled', 'disabled');
            } else {
                $('#btn_simpan').prop('disabled', false);
            }
        });
    </script>
@endsection
