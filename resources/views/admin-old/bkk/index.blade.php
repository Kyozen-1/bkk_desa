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
    <style>
        .select2.select2-container.select2-container--default {
            width: 100%;
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
        @media (min-width: 374px) {
            .scrollBarPagination {
                height:40rem;
                overflow-y: scroll;
            }
        }
        @media (min-width: 992px) {
            .scrollBarPagination {
                margin-left: -40px;
                height:90rem;
                overflow-y: scroll;
            }
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
                        <li class="breadcrumb-item"><a href="#">BKK Desa</a></li>
                    </ul>
                </nav>
            </div>
            <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->
        <div class="row mb-3">
            <div class="col-12" style="text-align: right">
                <a class="btn btn-outline-primary waves-effect waves-light" href="{{ route('admin.bkk.create') }}">Tambah</a>
                <a class="btn btn-outline-secondary waves-effect waves-light" href="{{ route('admin.bkk.testing-import-template') }}" title="Download Contoh Template Impor BKK">Template Impor</a>
                <button class="btn btn-outline-success waves-effect waves-light" type="button" id="btn_impor_bkk" title="Impor BKK">Impor</button>
                {{-- <button class="btn btn-outline-primary waves-effect waves-light" type="button" id="btn_testing_impor_bkk">Testing Impor</button> --}}
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h2 class="small-title">Filter Data</h2>
                <div class="row">
                    <div class="col">
                        <div class="form-group position-relative">
                            <label for="filter_kecamatan_id" class="form-label">Kecamatan</label>
                            <select name="filter_kecamatan_id" id="filter_kecamatan_id" class="form-control">
                                <option value="">Semua</option>
                                @foreach ($master_kecamatan as $id => $nama)
                                    <option value="{{$id}}">{{$nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group position-relative">
                            <label for="filter_kelurahan_id" class="form-label">Kelurahan</label>
                            <select name="filter_kelurahan_id" id="filter_kelurahan_id" class="form-control" disabled>
                                <option value="">Semua</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group position-relative">
                            <label for="filter_tahun" class="form-label">Tahun</label>
                            <select name="filter_tahun" id="filter_tahun" class="form-control">
                                <option value="">Semua</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group position-relative">
                            <label for="filter_fraksi_id" class="form-label">Partai</label>
                            <select name="filter_fraksi_id" id="filter_fraksi_id" class="form-control">
                                <option value="">Semua</option>
                                @foreach ($master_fraksi as $id => $nama)
                                    <option value="{{$id}}">{{$nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group position-relative">
                            <div class="form-group position-relative">
                                <label for="filter_aspirator_id" class="form-label">Aspirator</label>
                                <select name="filter_aspirator_id" id="filter_aspirator_id" class="form-control" disabled>
                                    <option value="">Semua</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="data-table-rows slim">
            <!-- Table Start -->
            <div class="data-table-responsive-wrapper">
                <table id="bkk_table" class="data-table nowrap w-100">
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">No</th>
                            <th class="text-muted text-small text-uppercase">Kelurahan/Desa</th>
                            <th class="text-muted text-small text-uppercase">Uraian</th>
                            <th class="text-muted text-small text-uppercase">Kegiatan</th>
                            <th class="text-muted text-small text-uppercase">Nilai APBD</th>
                            <th class="text-muted text-small text-uppercase">Tahun</th>
                            <th class="text-muted text-small text-uppercase">Partai</th>
                            <th class="text-muted text-small text-uppercase">Foto</th>
                            <th class="text-muted text-small text-uppercase">Status</th>
                            <th class="text-muted text-small text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- Table End -->
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
                    <h2 class="small-title">Data BKK</h2>
                    <hr>
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
                        <div class="col-12">
                            <div class="form-group position-relative mb-3">
                                <label for="detail_uraian" class="form-label">Uraian</label>
                                <textarea name="detail_uraian" id="detail_uraian" rows="5" class="form-control" disabled></textarea>
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
                    </div>
                    <hr>
                    <h2 class="small-title">Lokasi</h2>
                    <hr>
                    <div class="row">
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
                    <hr>
                    <h2 class="small-title">Dokumentasi</h2>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label for="detail_foto_before" class="form-label">Foto Sebelum</label>
                            <ul class="scrollBarPagination" style="list-style:none" id="detailFotoSebelum">
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="detail_foto_after" class="form-label">Foto Sesudah</label>
                            <ul class="scrollBarPagination" style="list-style:none" id="detailFotoSesudah">
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Oke</button>
                </div>
            </div>
        </div>
    </div>

    <div id="imporModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="imporModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="imporModalLabel">Impor BKK</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form class="form-horizontal" action="{{ route('admin.bkk.impor') }}" method="POST" data-parsley-validate novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="file" name="file_impor" required class="dropify" data-height="150">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light waves-effect width-md waves-light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light">Save</button>
                </div>
            </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
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
        $(document).ready(function(){
            $('.dropify').dropify();
            $('#filter_kecamatan_id').select2();
            $('#filter_kelurahan_id').select2();
            $('#filter_tahun').select2();
            $('#filter_fraksi_id').select2();
            $('#filter_aspirator_id').select2();
            bkk_datatable();
            function bkk_datatable(filter_kecamatan_id = '', filter_kelurahan_id = '', filter_tahun = '', filter_fraksi_id = '', filter_aspirator_id = '')
            {
                var dataTables = $('#bkk_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.bkk.index') }}",
                        data: {
                            filter_kecamatan_id:filter_kecamatan_id,
                            filter_kelurahan_id:filter_kelurahan_id,
                            filter_tahun:filter_tahun,
                            filter_fraksi_id:filter_fraksi_id,
                            filter_aspirator_id:filter_aspirator_id
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
                            data: 'tahun',
                            name: 'tahun'
                        },
                        {
                            data: 'master_fraksi',
                            name: 'master_fraksi'
                        },
                        {
                            data: 'foto',
                            name: 'foto'
                        },
                        {
                            data: 'status_konfirmasi',
                            name: 'status_konfirmasi'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false
                        },
                    ]
                });
            }

            $('#filter_kecamatan_id').change(function(){
                var filter_kecamatan_id = $('#filter_kecamatan_id').val();
                var filter_kelurahan_id = $('#filter_kelurahan_id').val();
                var filter_tahun = $('#filter_tahun').val();
                var filter_fraksi_id = $('#filter_fraksi_id').val();
                var filter_aspirator_id = $('#filter_aspirator_id').val();
                $('#bkk_table').DataTable().destroy();
                bkk_datatable(filter_kecamatan_id, filter_kelurahan_id, filter_tahun, filter_fraksi_id, filter_aspirator_id);
            });

            $('#filter_kelurahan_id').change(function(){
                var filter_kecamatan_id = $('#filter_kecamatan_id').val();
                var filter_kelurahan_id = $('#filter_kelurahan_id').val();
                var filter_tahun = $('#filter_tahun').val();
                var filter_fraksi_id = $('#filter_fraksi_id').val();
                var filter_aspirator_id = $('#filter_aspirator_id').val();
                $('#bkk_table').DataTable().destroy();
                bkk_datatable(filter_kecamatan_id, filter_kelurahan_id, filter_tahun, filter_fraksi_id, filter_aspirator_id);
            });

            $('#filter_tahun').change(function(){
                var filter_kecamatan_id = $('#filter_kecamatan_id').val();
                var filter_kelurahan_id = $('#filter_kelurahan_id').val();
                var filter_tahun = $('#filter_tahun').val();
                var filter_fraksi_id = $('#filter_fraksi_id').val();
                var filter_aspirator_id = $('#filter_aspirator_id').val();
                $('#bkk_table').DataTable().destroy();
                bkk_datatable(filter_kecamatan_id, filter_kelurahan_id, filter_tahun, filter_fraksi_id, filter_aspirator_id);
            });

            $('#filter_fraksi_id').change(function(){
                var filter_kecamatan_id = $('#filter_kecamatan_id').val();
                var filter_kelurahan_id = $('#filter_kelurahan_id').val();
                var filter_tahun = $('#filter_tahun').val();
                var filter_fraksi_id = $('#filter_fraksi_id').val();
                var filter_aspirator_id = $('#filter_aspirator_id').val();
                $('#bkk_table').DataTable().destroy();
                bkk_datatable(filter_kecamatan_id, filter_kelurahan_id, filter_tahun, filter_fraksi_id, filter_aspirator_id);
            });

            $('#filter_aspirator_id').change(function(){
                var filter_kecamatan_id = $('#filter_kecamatan_id').val();
                var filter_kelurahan_id = $('#filter_kelurahan_id').val();
                var filter_tahun = $('#filter_tahun').val();
                var filter_fraksi_id = $('#filter_fraksi_id').val();
                var filter_aspirator_id = $('#filter_aspirator_id').val();
                $('#bkk_table').DataTable().destroy();
                bkk_datatable(filter_kecamatan_id, filter_kelurahan_id, filter_tahun, filter_fraksi_id, filter_aspirator_id);
            });

            $('#master_fraksi_id').select2({
                dropdownParent: $("#imporModal")
            });

            $('#aspirator_id').select2({
                dropdownParent: $("#imporModal")
            });

            $('#tipe_kegiatan_id').select2({
                dropdownParent: $("#imporModal")
            });

            $('#master_jenis_id').select2({
                dropdownParent: $("#imporModal")
            });

            $('#master_kategori_pembangunan_id').select2({
                dropdownParent: $("#imporModal")
            });

            $('#kecamatan_id').select2({
                dropdownParent: $("#imporModal")
            });

            $('#kelurahan_id').select2({
                dropdownParent: $("#imporModal")
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
                    // $('#detail_foto_before').attr('src', "{{ asset('images/foto-bkk') }}" + '/' + data.result.foto_before);
                    // $('#detail_foto_after').attr('src', "{{ asset('images/foto-bkk') }}" + '/' + data.result.foto_after);
                    var html = '';
                    var urlFoto = '';
                    $.each(data.result.foto_before, function(i, v){
                        urlFoto = "{{ asset('images/foto-bkk') }}" + '/' + v['nama'];
                        html += '<hr class="border-top">';
                        html += '<li>';
                            html += '<div class="card shadow p-2">';
                                html += '<div class="form-group position relative mb-3">';
                                    html += '<img class="img-fluid" src="'+urlFoto+'" style="height:5rem;">';
                                html += '</div>';
                            html += '</div>';
                        html += '</li>';
                    });
                    $('#detailFotoSebelum').html(html);

                    if (data.result.foto_after.length != 0) {
                        html = '';
                        $.each(data.result.foto_after, function(i, v){
                            urlFoto = "{{ asset('images/foto-bkk') }}" + '/' + v['nama'];
                            html += '<hr class="border-top">';
                            html += '<li>';
                                html += '<div class="card shadow p-2">';
                                    html += '<div class="form-group position relative mb-3">';
                                        html += '<img class="img-fluid" src="'+urlFoto+'" style="height:5rem;">';
                                    html += '</div>';
                                html += '</div>';
                            html += '</li>';
                        });
                        $('#detailFotoSesudah').html(html);
                    }

                    $('#detailModal').modal('show');
                }
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

        $('#filter_tahun').each(function(){
            var year = (new Date()).getFullYear();
            var current = year;
            year -= 20;
            for(var i = 0; i < 21; i++)
            {
                $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
            }
        });

        $('#btn_impor_bkk').click(function(){
            $('.dropify-clear').click();
            $("[name='master_fraksi_id']").val('').trigger('change');

            $("[name='aspirator_id']").val('').trigger('change');

            $("[name='tipe_kegiatan_id']").val('').trigger('change');

            $("[name='master_jenis_id']").val('').trigger('change');

            $("[name='master_kategori_pembangunan_id']").val('').trigger('change');

            $("[name='kecamatan_id']").val('').trigger('change');

            $("[name='kelurahan_id']").val('').trigger('change');

            $('#imporModal').modal('show');
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

        // $('#btn_testing_impor_bkk').click(function(){
        //     var url = "{{ route('admin.bkk.testing-import-template') }}";
        //     $.ajax({
        //         url: url,
        //         type: 'get',
        //         success: function(data)
        //         {
        //             window.location.href = url;
        //         }
        //     });
        // });
    </script>
@endsection
