@extends('main')
@section('extra_style')
    <style>
        #table_cabang td {
            padding: 5px;
        }
    </style>
@endsection
@section('content')
<article class="content animated fadeInLeft">
    <div class="title-block text-primary">
        <h1 class="title"> Master Cabang</h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Master Data Utama</span>
            / <span class="text-primary" style="font-weight: bold;">Master Cabang</span>
        </p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Data Cabang </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a class="btn btn-primary" href="{{route('cabang.create')}}" id="e-create">
                                <i class="fa fa-plus"></i>&nbsp;Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="row mb-5">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Status Cabang</label>
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
                                <table class="table table-hover display nowrap w-100" cellspacing="0" id="table_cabang">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>Nama Cabang</th>
                                            <th>Alamat</th>
                                            <th>Area</th>
                                            <th>No Telp</th>
                                            <th>Status</th>
                                            <th class="text-center">Aksi</th>
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
    var tb_cabang;
    setTimeout(function() {
        $('[data-toggle="tooltip"]').tooltip();
        TableCabang();

        $('#status').on('select2:select', function() {
            TableCabang();
        });
    }, 100);

    function TableCabang() {
        $('#table_cabang').dataTable().fnDestroy();
        tb_cabang = $('#table_cabang').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ route('cabang.list') }}",
                type: "get",
                data: {
                    status: $('#status').val(),
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'wc_name',
                    name: 'wc_name'
                },
                {
                    data: 'telepon',
                    name: 'telepon'
                },
                {
                    data: 'status',
                    name: 'status'
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

    function EditCabang(idx) {
        window.location = baseUrl + "/masterdatautama/cabang/edit/" + idx;
    }

    function nonActive(idx) {
        var nonActive = baseUrl + "/masterdatautama/cabang/nonactive/" + idx;

        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Pesan!',
            content: 'Apakah anda yakin ingin nonaktifkan cabang ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function() {
                        return $.ajax({
                            type: "get",
                            url: nonActive,
                            beforeSend: function() {
                                loadingShow();
                            },
                            success: function(response) {
                                if (response.status == 'sukses') {
                                    loadingHide();
                                    messageSuccess('Berhasil', 'Cabang Berhasil Dinonaktifkan!');
                                    tb_cabang.ajax.reload();
                                } else {
                                    loadingHide();
                                    messageFailed('Gagal', response.message);
                                }
                            },
                            error: function(e) {
                                loadingHide();
                                messageWarning('Peringatan', e.message);
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function() {

                    }
                }
            }
        });
    }

    function active(idx) {
        var actived = baseUrl + "/masterdatautama/cabang/actived/" + idx;

        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Pesan!',
            content: 'Apakah anda yakin ingin aktifkan cabang ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function() {
                        return $.ajax({
                            type: "get",
                            url: actived,
                            beforeSend: function() {
                                loadingShow();
                            },
                            success: function(response) {
                                if (response.status == 'sukses') {
                                    loadingHide();
                                    messageSuccess('Berhasil', 'Cabang Berhasil Diaktifkan!');
                                    tb_cabang.ajax.reload();
                                } else {
                                    loadingHide();
                                    messageFailed('Gagal', response.message);
                                }
                            },
                            error: function(e) {
                                loadingHide();
                                messageWarning('Peringatan', e.message);
                            }
                        });
                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function() {

                    }
                }
            }
        });
    }

</script>
@endsection
