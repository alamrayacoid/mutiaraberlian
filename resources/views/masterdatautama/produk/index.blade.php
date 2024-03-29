@extends('main')

@section('content')

    @include('masterdatautama.produk.modal-create')

    <style media="screen">
        .detail
            /* Or better yet try giving an ID or class if possible*/
        {
            border: 0;
            background: none;
            box-shadow: none;
            border-radius: 0px;
        }

        #table_produk .disabled-row {
            -webkit-text-decoration-line: line-through; /* Safari */
            text-decoration-line: line-through;
            color: #9da0ae;
        }
    </style>

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Data Produk </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Master Data Utama</span>
                / <span class="text-primary font-weight-bold">Data Produk</span>
            </p>
        </div>

        <section class="section">

            <ul class="nav nav-pills mb-3" id="Tabzs">
                <li class="nav-item">
                    <a href="#tab-1" class="nav-link active" data-toggle="tab" data-target="#tab-1">Data Produk</a>
                </li>
                <li class="nav-item">
                    <a href="#tab-2" class="nav-link" data-toggle="tab" data-target="#tab-2">Data Jenis Produk</a>
                </li>

                <li class="nav-item">
                    <a href="#tab-3" class="nav-link" data-toggle="tab" data-target="#tab-3">Data Satuan</a>
                </li>
            </ul>

            <div class="row">

                <div class="col-12">

                    <div class="tab-content">
                        <div class="tab-pane fade in active show" id="tab-1">
                            <div class="card">
                                <div class="card-header bordered p-2">
                                    <div class="header-block">
                                        <h3 class="title"> Data Produk </h3>
                                    </div>
                                    <div class="header-block pull-right">
                                        <a class="btn btn-primary" href="{{route('dataproduk.create')}}"><i
                                                class="fa fa-plus"></i>&nbsp;Tambah Data Produk</a>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <section>
                                        <div class="row mb-5">
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <label>Status Produk</label>
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
                                            <table class="table table-striped table-hover display nowrap"
                                                   cellspacing="0" id="table_produk" style="width:100%">
                                                <thead class="bg-primary">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Barang</th>
                                                    <th>Jenis Barang</th>
                                                    <th>Nama Barang</th>
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

                        <div class="tab-pane fade in" id="tab-2">
                            <div class="card">

                                <div class="card-header p-2 bordered">
                                    <div class="header-block">
                                        <h3 class="title">Data Jenis Produk</h3>
                                    </div>
                                    <div class="header-block pull-right">
                                        <button class="btn btn-primary btn-modal" id="tambahbtn" data-toggle="modal"
                                                data-target="#create" type="button"><i class="fa fa-plus"></i>&nbsp;Tambah
                                            Data Jenis Produk
                                        </button>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <section>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" cellspacing="0"
                                                   id="table_jenis_produk" style="width:100%">
                                                <thead class="bg-primary">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="85%">Nama Jenis Produk</th>
                                                    <th width="10%">Aksi</th>
                                                </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade in" id="tab-3">
                            <div class="card">
                                <div class="card-header bordered p-2">
                                    <div class="header-block">
                                        <h3 class="title"> Data Satuan </h3>
                                    </div>
                                    <div class="header-block pull-right">
                                        <button type="button" data-toggle="modal" data-target="#addSatuan" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <section>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover display nowrap w-100" cellspacing="0" id="table_satuan">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th width="1%">No</th>
                                                        <th>Nama Satuan</th>
                                                        <th width="15%" class="text-center">Aksi</th>
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

                </div>

            </div>

        </section>

    </article>
{{-- Modal Add Satuan --}}
<div class="modal fade" id="addSatuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tmbah Data Satuan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Nama Satuan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" name="s_name" id="s_name">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="tambahSatuan()">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Edit Satuan --}}
<div class="modal fade" id="editSatuan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Data Satuan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Nama Satuan</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="satuan_id" id="u_id">
                        <input type="text" class="form-control form-control-sm" name="s_name" id="u_name">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateSatuan()">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Update
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_script')
    <script type="text/javascript">

        var tb_produk;
        var tb_jenis;

        $(document).ready(function () {
            var table = $('#tabel_jenisproduk').DataTable();

            $('#tabel_jenisproduk tbody').on('click', '.btn-edit', function () {

                window.location.href = '{{route("datajenisproduk.edit")}}';

            });

            setTimeout(function () {
                tablejenis();
                TableProduk();

                $('#status').on('select2:select', function() {
                    TableProduk();
                });
            }, 100);

            function table_hapus(a) {
                table.row($(a).parents('tr')).remove().draw();
            }
        });

        function tablejenis() {
            $('#table_jenis_produk').dataTable().fnDestroy();
            tb_jenis = $('#table_jenis_produk').DataTable({
                responsive: true,
                // language: dataTableLanguage,
                // processing: true,
                serverSide: true,
                ajax: {
                    url: '{{route("jenisitem.getdata")}}',
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'it_name', name: 'it_name'},
                    {data: 'action', name: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // function to retrieve DataTable server side
        // retrieve data 'produk'
        function TableProduk() {
            $('#table_produk').dataTable().fnDestroy();
            tb_produk = $('#table_produk').DataTable({
                responsive: true,
                // language: dataTableLanguage,
                // processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dataproduk.list') }}",
                    type: "get",
                    data: {
                        status: $('#status').val(),
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'i_code', name: 'i_code'},
                    {data: 'it_name', name: 'it_name'},
                    {data: 'i_name', name: 'i_name'},
                    {data: 'action', name: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // function to redirect page to edit page
        function EditDataproduk(idx) {
            window.location.href = baseUrl + "/masterdatautama/produk/edit/" + idx;
        }

        // function to execute delete request
        function DeleteDataproduk(idx) {
            var url_hapus = baseUrl + "/masterdatautama/produk/delete/" + idx;

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
                        action: function () {
                            return $.ajax({
                                type: "post",
                                url: url_hapus,
                                success: function (response) {
                                    if (response.status == 'berhasil') {
                                        $.toast({
                                            heading: 'Success',
                                            text: 'Data berhasil dinonaktifkan!, data akan membutuhkan otorisasi terlebih dahulu',
                                            bgColor: '#00b894',
                                            textColor: 'white',
                                            loaderBg: '#55efc4',
                                            icon: 'success',
                                            stack: false
                                        });
                                        tb_produk.ajax.reload();
                                    }
                                },
                                error: function (e) {
                                    $.toast({
                                        heading: 'Warning',
                                        text: e.message,
                                        bgColor: '#00b894',
                                        textColor: 'white',
                                        loaderBg: '#55efc4',
                                        icon: 'warning',
                                        stack: false
                                    });
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }

        function ActiveDataproduk(idx) {
            var url_hapus = baseUrl + "/masterdatautama/produk/active/" + idx;

            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'fade',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin mengaktifkan data ini ?',
                theme: 'active',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "post",
                                url: url_hapus,
                                success: function (response) {
                                    if (response.status == 'berhasil') {
                                        $.toast({
                                            heading: 'Success',
                                            text: 'Data berhasil diaktifkan!, data akan membutuhkan otorisasi terlebih dahulu',
                                            bgColor: '#00b894',
                                            textColor: 'white',
                                            loaderBg: '#55efc4',
                                            icon: 'success',
                                            stack: false
                                        });
                                        tb_produk.ajax.reload();
                                    }
                                },
                                error: function (e) {
                                    $.toast({
                                        heading: 'Warning',
                                        text: e.message,
                                        bgColor: '#00b894',
                                        textColor: 'white',
                                        loaderBg: '#55efc4',
                                        icon: 'warning',
                                        stack: false
                                    });
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }


        function savejenis() {
            loadingShow();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var jenis = $('#jenis').val();
            $.ajax({
                type: 'post',
                data: {_token: CSRF_TOKEN, jenis: jenis},
                dataType: 'json',
                url: baseUrl + '/masterdatautama/produk/simpanjenis',
                success: function (response) {
                    loadingHide();
                    if (response.status == 'berhasil') {
                        messageSuccess('Success', 'Data berhasil disimpan!');
                        $('#create').modal('hide');
                        tb_jenis.ajax.reload();
                    } else if (response.status == 'invalid') {
                        messageWarning('Warning', response.message);
                    } else {
                        messageFailed('Gagal', 'Data gagal disimpan!');
                    }
                }
            })
        }

        function updatejenis(id) {
            loadingShow();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var jenis = $('#jenis').val();
            $.ajax({
                type: 'post',
                data: {_token: CSRF_TOKEN, jenis: jenis, id: id},
                dataType: 'json',
                url: baseUrl + '/masterdatautama/produk/updatejenis',
                success: function (response) {
                    loadingHide();
                    if (response.status == 'berhasil') {
                        messageSuccess('Success', 'Data berhasil disimpan!');
                        $('#create').modal('hide');
                        tb_jenis.ajax.reload();
                    } else if (response.status == 'invalid') {
                        messageWarning('Warning', response.message);
                    } else {
                        messageFailed('Gagal', 'Data gagal disimpan!');
                    }
                }
            })
        }

        function deletejenis(id) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin menghapus data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                            var jenis = $('#jenis').val();
                            $.ajax({
                                type: 'post',
                                data: {_token: CSRF_TOKEN, id: id},
                                dataType: 'json',
                                url: baseUrl + '/masterdatautama/produk/hapusjenis',
                                success: function (response) {
                                    if (response.status == 'berhasil') {
                                        messageSuccess('Success', 'Berhasil dihapus');
                                        tb_jenis.ajax.reload();
                                    } else if (response.status == 'digunakan') {
                                        messageWarning('Warning', 'Type sedang digunakan');
                                    } else {
                                        messageFailed('Gagal', 'Gagal dihapus');
                                    }
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }

        function editjenis(id, parm) {
            $('#myModalLabel').text('Edit Data Jenis Produk');
            $('#savejenis').attr('onclick', 'updatejenis(' + id + ')');

            var parent = $(parm).parents('tr');
            var jenis = $(parent).find('.jenis').text();

            $('#jenis').val(jenis);
            $('#create').modal('show');
        }

        $('#tambahbtn').on('click', function () {
            $('#myModalLabel').text('Tambah Data Jenis Produk');
            $('#savejenis').attr('onclick', 'savejenis()');
        });

        function DetailDataproduk(id) {
            window.location.href = baseUrl + '/masterdatautama/produk/detail?id=' + id;
        }

    </script>
    <script type="text/javascript">

        var tb_satuan;
        setTimeout(function() {
            TableSatuan();
        }, 1500);

        // function to retrieve DataTable server side
        function TableSatuan()
        {
            $('#table_satuan').dataTable().fnDestroy();
            tb_satuan = $('#table_satuan').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/masterdatautama/datasatuan/list') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'u_name', name: 'u_name'},
                    {data: 'action', name: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function tambahSatuan(){
            var s_id = $('#s_id').val();
            var s_name = $('#s_name').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseUrl + '/masterdatautama/datasatuan/store',
                type: 'get',
                data: {id: s_id, name: s_name},
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status =='sukses'){
                        $('#addSatuan').modal('hide');
                        messageSuccess('Success', 'Data berhasil disimpan');
                        $('#table_satuan').DataTable().ajax.reload();
                        $('#s_name').val("");
                    } else {
                        $('#addSatuan').modal('hide');
                    }
                },
            });
        }

        function editSatuan(id,name)
        {
            $('#editSatuan').modal('show');
            $('#u_id').val(id);
            $('#u_name').val(name);
        }

        function updateSatuan()
        {
            var s_id = $('#u_id').val();
            var s_name = $('#u_name').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: baseUrl + '/masterdatautama/datasatuan/update',
                type: 'get',
                data: {id: s_id, name: s_name},
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status =='sukses'){
                        $('#editSatuan').modal('hide');
                        messageSuccess('Success', 'Data berhasil diupdate');
                        $('#table_satuan').DataTable().ajax.reload();
                    } else {
                        $('#editSatuan').modal('hide');
                        $('#table_satuan').DataTable().ajax.reload();
                    }
                },
            });

        }
        // function to execute delete request
        function deleteSatuan(id)
        {
            var url_hapus = baseUrl + "/masterdatautama/datasatuan/delete/" + id;

            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin menghapus data ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text:'Ya',
                        action : function(){
                            return $.ajax({
                                type : "post",
                                url : url_hapus,
                                success : function (response){
                                    if(response.status == 'sukses'){
                                        $.toast({
                                            heading: 'Success',
                                            text: 'Data berhasil dihapus!, data akan membutuhkan otorisasi terlebih dahulu',
                                            bgColor: '#00b894',
                                            textColor: 'white',
                                            loaderBg: '#55efc4',
                                            icon: 'success',
                                            stack: false
                                        });
                                        tb_satuan.ajax.reload();
                                    }
                                },
                                error : function(e){
                                    $.toast({
                                        heading: 'Warning',
                                        text: e.message,
                                        bgColor: '#00b894',
                                        textColor: 'white',
                                        loaderBg: '#55efc4',
                                        icon: 'warning',
                                        stack: false
                                    });
                                }
                            });

                        }
                    },
                    cancel:{
                        text: 'Tidak',
                        action: function () {
                            // tutup confirm
                        }
                    }
                }
            });
        }

    </script>
@endsection
