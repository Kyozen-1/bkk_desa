@extends('admin.layouts.app')

@section('title', 'Admin | Tahun Periode')

@section('subtitle', 'Tahun Periode')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css" integrity="sha512-RvQxwf+3zJuNwl4e0sZjQeX7kUa3o82bDETpgVCH2RiwYSZVDdFJ7N/woNigN/ldyOOoKw8584jM4plQdt8bhA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('dropify/css/dropify.min.css') }}">
    <link href="{{ asset('rocker/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
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
        /* .select2-container{
            z-index:100000;
        } */
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row mb-3">
                <div class="col-12" style="text-align: right">
                    <button class="btn btn-outline-primary waves-effect waves-light" id="create" type="button" data-bs-toggle="modal" data-bs-target="#addEditModal">Tambah</button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tahun_periode_table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jangka Tahun</th>
                                            <th>Status</th>
                                            <th>Status Hubungan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEditModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form id="tahun_periode_form" class="tooltip-label-end" method="POST" novalidate enctype="multipart/form-data">
                        @csrf
                        <div id="div_form"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                    <input type="hidden" name="aksi" id="aksi" value="Save">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <button type="submit" class="btn btn-primary" name="aksi_button" id="aksi_button">Add</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('rocker/assets/plugins/select2/js/select2-custom.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/fontawesome.min.js" integrity="sha512-j3gF1rYV2kvAKJ0Jo5CdgLgSYS7QYmBVVUjduXdoeBkc4NFV4aSRTi+Rodkiy9ht7ZYEwF+s09S43Z1Y+ujUkA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
    <script src="{{ asset('dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('rocker/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('rocker/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.dropify').dropify();
            $('.dropify-wrapper').css('line-height', '3rem');

            var dataTables = $('#tahun_periode_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.tahun-periode.index') }}",
                },
                columns:[
                    {
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'tahun_periode',
                        name: 'tahun_periode'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'status_hubungan',
                        name: 'status_hubungan'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false
                    },
                ]
            });
        });

        $('#create').click(function(){
            $('#form_status').remove();
            $('#tahun_periode_form')[0].reset();
            $('#form_jangka_waktu').remove();
            var jangka_waktu = $(`<div id="form_jangka_waktu">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group position-relative mb-3">
                                    <label for="tahun_awal" class="form-label">Tahun Awal</label>
                                    <select name="tahun_awal" id="tahun_awal" class="form-control" required>
                                        <option value="">--- Pilih Tahun Awal ---</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group position-relative mb-3">
                                    <label for="tahun_akhir" class="form-label">Tahun Akhir</label>
                                    <select name="tahun_akhir" id="tahun_akhir" class="form-control" required>
                                        <option value="">--- Pilih Tahun Akhir ---</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>`);
            $('#div_form').after(jangka_waktu);
            $("[name='tahun_awal']").val('').trigger('change');
            $("[name='tahun_akhir']").val('').trigger('change');
            var year = (new Date()).getFullYear();
            var current = year;
            year += -10;
            for(var i = 0; i < 20; i++)
            {
                if((year+i) == current)
                {
                    $('#tahun_awal').append('<option value="' + (year + i) +'" selected>' + (year + i) +'</option>');
                    $('#tahun_akhir').append('<option value="' + (year + i) +'" selected>' + (year + i) +'</option>');
                } else {
                    $('#tahun_awal').append('<option value="' + (year + i) +'">' + (year + i) +'</option>');
                    $('#tahun_akhir').append('<option value="' + (year + i) +'">' + (year + i) +'</option>');
                }
            }
            $('#tahun_awal').select2({
                dropdownParent: $("#addEditModal"),
                width: '100%'
            });

            $('#tahun_akhir').select2({
                dropdownParent: $("#addEditModal"),
                width: '100%'
            });
            $('#aksi_button').text('Save');
            $('#aksi_button').prop('disabled', false);
            $('.modal-title').text('Add Data');
            $('#aksi_button').val('Save');
            $('#aksi').val('Save');
            $('#form_result').html('');
        });

        $('#tahun_periode_form').on('submit', function(e){
            e.preventDefault();
            if($('#aksi').val() == 'Save')
            {
                $.ajax({
                    url: "{{ route('admin.tahun-periode.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function()
                    {
                        $('#aksi_button').text('Menyimpan...');
                        $('#aksi_button').prop('disabled', true);
                    },
                    success: function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">'+data.errors+'</div>';
                            $('#aksi_button').prop('disabled', false);
                            $('#tahun_periode_form')[0].reset();
                            $("[name='tahun_awal']").val('').trigger('change');
                            $("[name='tahun_akhir']").val('').trigger('change');
                            $('#aksi_button').text('Save');
                            $('#tahun_periode_table').DataTable().ajax.reload();
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">'+data.success+'</div>';
                            $('#aksi_button').prop('disabled', false);
                            $('#tahun_periode_form')[0].reset();
                            $("[name='tahun_awal']").val('').trigger('change');
                            $("[name='tahun_akhir']").val('').trigger('change');
                            $('#aksi_button').text('Save');
                            $('#tahun_periode_table').DataTable().ajax.reload();
                        }

                        $('#form_result').html(html);
                    }
                });
            }
            if($('#aksi').val() == 'Edit')
            {
                $.ajax({
                    url: "{{ route('admin.tahun-periode.update') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    beforeSend: function(){
                        $('#aksi_button').text('Mengubah...');
                        $('#aksi_button').prop('disabled', true);
                    },
                    success: function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">'+data.errors+'</div>';
                            $('#aksi_button').text('Save');
                        }
                        if(data.success)
                        {
                            // html = '<div class="alert alert-success">'+ data.success +'</div>';
                            $('#tahun_periode_form')[0].reset();
                            $('#aksi_button').prop('disabled', false);
                            $('#aksi_button').text('Save');
                            $('#tahun_periode_table').DataTable().ajax.reload();
                            $('#addEditModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil di ubah',
                                showConfirmButton: true
                            });
                        }

                        $('#form_result').html(html);
                    }
                });
            }
        });

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            var url = "{{ route('admin.tahun-periode.edit', ['id' => ":id"]) }}";
            url = url.replace(":id", id);
            $('#form_result').html('');
            $.ajax({
                url: url,
                dataType: "json",
                success: function(data)
                {
                    $('#form_jangka_waktu').remove();
                    $('#form_status').remove();
                    var status = $('<div class="mb-3" id="form_status">'+
                    '<label for="" class="form-label">Status</label>'+
                    '<select name="status" id="status" class="form-control" required>'+
                    '<option value="">--- Pilih Status ---</option>'+
                    '<option value="Aktif">Aktif</option>'+
                    '<option value="Tidak Aktif">Tidak Aktif</option>'+
                    '</select>'+
                    '</div>');
                    $('#div_form').after(status);
                    $("[name='tahun_awal']").val('').trigger('change');
                    $("[name='tahun_akhir']").val('').trigger('change');
                    $("[name='status']").val(data.result.status).trigger('change');
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Data');
                    $('#aksi_button').text('Edit');
                    $('#aksi_button').prop('disabled', false);
                    $('#aksi_button').val('Edit');
                    $('#aksi').val('Edit');
                    $('#addEditModal').modal('show');
                }
            });
        });

        $(document).on('click', '.delete',function(){
            var id = $(this).attr('id');
            var url = "{{ route('admin.tahun-periode.destroy', ['id' => ":id"]) }}";
            url = url.replace(":id", id);
            return new swal({
                title: "Apakah Anda Yakin Menghapus Ini? Menghapus data ini akan menghapus data yang lain!!!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#1976D2",
                confirmButtonText: "Ya"
            }).then((result)=>{
                if(result.value)
                {
                    $.ajax({
                        url: url,
                        dataType: "json",
                        beforeSend: function()
                        {
                            return new swal({
                                title: "Checking...",
                                text: "Harap Menunggu",
                                imageUrl: "{{ asset('/images/preloader.gif') }}",
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(data)
                        {
                            if(data.errors)
                            {
                                Swal.fire({
                                    icon: 'error',
                                    title: data.errors,
                                    showConfirmButton: true
                                });
                            }
                            if(data.success)
                            {
                                $('#tahun_periode_table').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: data.success,
                                    showConfirmButton: true
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection