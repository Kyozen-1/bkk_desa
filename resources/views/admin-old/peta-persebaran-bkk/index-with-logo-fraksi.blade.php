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
                <h1 class="mb-0 pb-0 display-4" id="title">Peta Persebaran BKK</h1>
                <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                    <ul class="breadcrumb pt-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Peta</a></li>
                        <li class="breadcrumb-item"><a href="#">Peta Persebaran BKK</a></li>
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
                    <div class="col-12 col-md-2">
                        <select name="filter_fraksi_id" id="filter_fraksi_id" class="form-control">
                            <option value="">--- Pilih Semua Partai---</option>
                            @foreach ($fraksi as $id => $nama)
                                <option value="{{$id}}">{{$nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <select name="filter_aspirator_id" id="filter_aspirator_id" class="form-control" disabled>
                            <option value="">--- Pilih Semua Aspirator ---</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <select name="filter_master_jenis_id" id="filter_master_jenis_id" class="form-control">
                            <option value="">--- Pilih Semua jenis ---</option>
                            @foreach ($jenis as $id => $nama)
                                <option value="{{$id}}">{{$nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <select name="filter_tahun" id="filter_tahun" class="form-control">
                            <option value="">--- Pilih Semua Tahun ---</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <select name="filter_master_kategori_pembangunan_id" id="filter_master_kategori_pembangunan_id" class="form-control">
                            <option value="">--- Pilih Semua Kategori Pembangunan ---</option>
                            @foreach ($kategori_pembangunan as $id => $nama)
                                <option value="{{$id}}">{{$nama}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2" style="text-align: center">
                        <button class="btn btn-success btn-icon waves-effect waves-light" id="btn_filter"><i class="fas fa-filter"></i></button>
                    </div>
                </div>
                <div id="map"></div>
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
    <script>
        const map = L.map('map').setView([-7.5433519,111.6515913], 15);
        var popup = L.popup();
                googleStreets = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}',{
                    maxZoom: 20,
                    subdomains:['mt0','mt1','mt2','mt3']
                }).addTo(map);
        var marker;
        var konten_html;
        var urlGambar;
        $(document).ready(function(){
            $('#filter_fraksi_id').select2();
            $('#filter_aspirator_id').select2();
            $('#filter_master_jenis_id').select2();
            $('#filter_tahun').select2();
            $('#filter_master_kategori_pembangunan_id').select2();
            $.getJSON("{{ route('admin.peta-persebaran-bkk.get-data') }}", function(data){
                $.each(data, function(index){
                        marker = L.marker([data[index].lat,data[index].lng],{icon:L.icon({
                            iconUrl: "{{asset('images/logo-fraksi')}}" + '/' + data[index].logo_partai,

                            iconSize:     [30, 35], // size of the icon
                            shadowSize:   [50, 64], // size of the shadow
                            // iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
                            shadowAnchor: [4, 62],  // the same for the shadow
                            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                        })}).addTo(map).on('click', function(e){
                            urlGambar = "{{ asset('images/foto-bkk') }}" + '/' + data[index].foto;
                            konten_html = '<div>';
                                konten_html += '<p>Uraian: '+data[index].uraian+'</p>';
                                konten_html += '<p>Tahun: '+data[index].tahun+'</p>';
                                konten_html += '<img src="'+urlGambar+'" class="img-fluid">';
                                konten_html += '<hr>';
                                konten_html += '<button type="button" name="detail" id="'+data[index].id+'" class="detail btn btn-icon waves-effect btn-success" title="Detail Data"><i class="fas fa-eye"></i></button>';
                            konten_html += '</div>';
                            popup
                                .setLatLng(e.latlng)
                                .setContent(konten_html)
                                .openOn(map);
                        });
                    });
            });
        });

        $(document).on('click', '.detail', function(){
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ url('/admin/bkk/detail') }}" + '/' + id,
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

        $('#filter_tahun').each(function(){
            var year = (new Date()).getFullYear();
            var current = year;
            year -= 20;
            for(var i = 0; i < 21; i++)
            {
                $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
            }
        });

        $('#filter_fraksi_id').change(function(){
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
                        $('#filter_aspirator_id').empty();
                        $('#filter_aspirator_id').prop('disabled', false);
                        $('#filter_aspirator_id').append('<option value="">--- Pilih Semua---</option>');
                        $.each(data, function(id, nama){
                            $('#filter_aspirator_id').append(new Option(nama, id));
                        });
                    }
                });
            } else {
                $("[name='filter_aspirator_id']").val('').trigger('change');
                $('#filter_aspirator_id').prop('disabled', true);
            }
        });

        $('#btn_filter').click(function(){
            map.removeLayer(marker);
            var filter_fraksi_id = $('#filter_fraksi_id').val();
            var filter_aspirator_id = $('#filter_aspirator_id').val();
            var filter_master_jenis_id = $('#filter_master_jenis_id').val();
            var filter_tahun = $('#filter_tahun').val();
            var filter_master_kategori_pembangunan_id = $('#filter_master_kategori_pembangunan_id').val();
            $.ajax({
                url: "{{ route('admin.peta-persebaran-bkk.filter') }}",
                method: 'POST',
                data: {
                        "_token": "{{ csrf_token() }}",
                        filter_fraksi_id:filter_fraksi_id,
                        filter_aspirator_id:filter_aspirator_id,
                        filter_master_jenis_id:filter_master_jenis_id,
                        filter_tahun:filter_tahun,
                        filter_master_kategori_pembangunan_id:filter_master_kategori_pembangunan_id,
                    },
                success: function(data)
                {
                    $.each(data, function(index){
                        marker = L.marker([data[index].lat,data[index].lng],{icon:L.icon({
                            iconUrl: "{{asset('images/logo-fraksi')}}" + '/' + data[index].logo_partai,

                            iconSize:     [30, 35], // size of the icon
                            shadowSize:   [50, 64], // size of the shadow
                            // iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
                            shadowAnchor: [4, 62],  // the same for the shadow
                            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
                        })}).addTo(map).on('click', function(e){
                            urlGambar = "{{ asset('images/foto-bkk') }}" + '/' + data[index].foto;
                            konten_html = '<div>';
                                konten_html += '<p>Uraian: '+data[index].uraian+'</p>';
                                konten_html += '<p>Tahun: '+data[index].tahun+'</p>';
                                konten_html += '<img src="'+urlGambar+'" class="img-fluid">';
                                konten_html += '<hr>';
                                konten_html += '<button type="button" name="detail" id="'+data[index].id+'" class="detail btn btn-icon waves-effect btn-success" title="Detail Data"><i class="fas fa-eye"></i></button>';
                            konten_html += '</div>';
                            popup
                                .setLatLng(e.latlng)
                                .setContent(konten_html)
                                .openOn(map);
                        });
                    });
                }
            });
        });
    </script>
@endsection
