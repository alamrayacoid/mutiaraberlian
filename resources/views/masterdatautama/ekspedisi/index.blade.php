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
            <h1 class="title"> Master Ekspedisi</h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Master Data Utama</span>
                / <span class="text-primary" style="font-weight: bold;">Master Ekspedisi</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Data Ekspedisi </h3>
                            </div>
                        </div>
                        <div class="card-block">
                            <section>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#modal_tambahekspedisi"><i class="fa fa-plus"></i>Tambah</button>
                                            </div>
                                            <div class="col-12">
                                                <table class="table table-hover table-striped display nowrap" cellspacing="0"
                                                       id="table_ekspedisi">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th width="10%">No</th>
                                                        <th width="70" style="text-align:center">Nama</th>
                                                        <th width="20%">Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <fieldset class="col-md-8 col-sm-12">

                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Tambah Ekspedisi: <span class="" id="nama_ekspedisi"></span></label>
                                                <input type="hidden" id="id_ekspedisi" value="">
                                            </div>
                                            <div class="col-3">
                                                <label>Nama Layanan</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control form-control-sm" id="form_namaproduk" value="" readonly>
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-primary" id="btn-saveproduk" onclick="simpanProduk()" disabled>Tambahkan</button>
                                            </div>
                                            <div class="col-12 table-responsive" style="margin-top: 10px;">
                                                <table class="table table-hover table-striped display nowrap" cellspacing="0"
                                                       id="table_productekspedisi">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th width="1%">No</th>
                                                        <th>Nama Produk</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </section>

                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>
@include('masterdatautama.ekspedisi.modal')
@endsection
@section('extra_script')
    <script type="text/javascript">
        var table;
        var product;
        $(document).ready(function () {
            setTimeout(function () {
                table = $('#table_ekspedisi').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    searching: false,
                    paging: false,
                    "bAutoWidth": false,
                    ajax: {
                        url: baseUrl + '/masterdatautama/ekspedisi/get-data-ekspedisi',
                        type: "get",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex'},
                        {data: 'e_name'},
                        {data: 'aksi', name: 'aksi'}
                    ],
                    pageLength: 10,
                    lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 100]]
                });
            }, 250);
        })

        function simpanDistribusi() {
            loadingShow();
            var name = $('#modal_inputekspedisi').val();
            axios.post('{{ route("ekspedisi.save") }}', {
                'name': name,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Data berhasil ditambahkan");
                    $('#modal_tambahekspedisi').modal('hide');
                    table.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert('Error');
            })
        }

        function detailEkspedisi(id, nama) {
            $('#nama_ekspedisi').html(nama);
            $('#id_ekspedisi').val(id);
            $('#form_namaproduk').removeAttr('readonly');
            $('#btn-saveproduk').removeAttr('disabled');
            $('#table_productekspedisi').dataTable().fnDestroy();
            product = $('#table_productekspedisi').DataTable({
                responsive: true,
                serverSide: true,
                paging: false,
                searching: false,
                processing: true,
                ajax: {
                    url: baseUrl + '/masterdatautama/ekspedisi/get-data-product-ekspedisi',
                    type: "get",
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'ed_product'},
                    {data: 'status'},
                    {data: 'aksi'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 100]]
            });
        }

        function simpanProduk() {
            loadingShow();
            let name = $('#form_namaproduk').val();
            let id = $('#id_ekspedisi').val();
            axios.post('{{ route("ekspedisi.saveProduk") }}', {
                'name': name,
                'id': id,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Data berhasil ditambahkan");
                    $('#modal_tambahekspedisi').modal('hide');
                    product.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert('Error');
            })
        }

        function nonaktifEkspedisi(id) {
            loadingShow();
            axios.post('{{ route("ekspedisi.disableEkspedisi") }}', {
                'id': id,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    table.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert('Error');
            })
        }

        function nonaktifProduk(id, detail) {
            loadingShow();
            axios.post('{{ route("ekspedisi.disableProduk") }}', {
                'id': id,
                'detail': detail,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    product.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert('Error');
            })
        }

        function enableEkspedisi(id) {
            loadingShow();
            axios.post('{{ route("ekspedisi.enableEkspedisi") }}', {
                'id': id,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    table.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert('Error');
            })
        }

        function enableProduk(id, detail) {
            loadingShow();
            axios.post('{{ route("ekspedisi.enableProduk") }}', {
                'id': id,
                'detail': detail,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    product.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert('Error');
            })
        }
    </script>
@endsection
