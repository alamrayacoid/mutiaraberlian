@extends('main')
@section('extra_style')
    <style>
        #table_agen td {
            padding: 5px;
        }
    </style>
@stop
@section('content')

<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Master Agen</h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Master Data Utama</span>
            / <span class="text-primary" style="font-weight: bold;">Master Agen</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Data Agen </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a class="btn btn-primary" href="{{route('agen.create')}}" id="e-create"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>

                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="row mb-5">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Status Agen</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <select name="status" id="status" class="form-control form-control-sm select2">
                                        <option value="">Semua</option>
                                        <option value="Y" selected>Aktif</option>
                                        <option value="N">Non Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover display w-100" cellspacing="0" id="table_agen">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th width="1%">No</th>
                                            <th>Area Agen</th>
                                            <th>Nama Agen</th>
                                            <th>Tipe Agen</th>
                                            <th width="20%">Alamat Agen</th>
                                            <th>Email</th>
                                            <th>No Telp</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>

            </div>

        </div>

    </section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function () {
            TableAgen();

            $('#status').on('select2:select', function() {
                TableAgen();
            });

        }, 100);
    });

    var tb_agen;
    // function to retrieve DataTable server side
    function TableAgen() {
        $('#table_agen').dataTable().fnDestroy();
        tb_agen = $('#table_agen').DataTable({
            responsive: true,
            // language: dataTableLanguage,
            // processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('agen.list') }}",
                type: "post",
                data: {
                    status: $('#status').val(),
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'area'
                },
                {
                    data: 'a_name',
                    name: 'a_name'
                },
                {
                    data: 'a_type',
                    name: 'a_type'
                },
                {
                    data: 'a_address',
                    name: 'a_address'
                },
                {
                    data: 'a_email'
                },
                {
                    data: 'a_telp'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ],
            pageLength: 10,
            lengthMenu: [
                [10, 20, 50, -1],
                [10, 20, 50, 'All']
            ]
        });
    }
    // function to redirect page to edit page
    function EditAgen(idx) {
        window.location.href = baseUrl + "/masterdatautama/agen/edit/" + idx;
    }
    // function to execute disable request
    function DisableAgen(idx) {
        var url_hapus = baseUrl + "/masterdatautama/agen/disable/" + idx;

        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menonaktifkan data ini ?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function() {
                        return $.ajax({
                            type: "post",
                            url: url_hapus,
                            success: function(response) {
                                if (response.status == 'berhasil') {
                                    messageSuccess('Berhasil', 'Data berhasil dinonaktifkan !');
                                    loadingShow();
                                    tb_agen.ajax.reload();
                                    loadingHide();
                                }
                            },
                            error: function(e) {
                                messageWarning('Gagal', 'Error, hubungi pengembang !');
                            }
                        });

                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function() {
                        // tutup confirm
                    }
                }
            }
        });

    }
    // function to execute enable request
    function EnableAgen(idx) {
        var url_hapus = baseUrl + "/masterdatautama/agen/enable/" + idx;

        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin mengaktifkan data ini ?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function() {
                        return $.ajax({
                            type: "post",
                            url: url_hapus,
                            success: function(response) {
                                if (response.status == 'berhasil') {
                                    messageSuccess('Berhasil', 'Data berhasil diaktifkan !');
                                    loadingShow();
                                    tb_agen.ajax.reload();
                                    loadingHide();
                                }
                            },
                            error: function(e) {
                                messageWarning('Gagal', 'Erro, hubungi pengembang !');
                            }
                        });

                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function() {
                        // tutup confirm
                    }
                }
            }
        });

    }


</script>
@endsection
