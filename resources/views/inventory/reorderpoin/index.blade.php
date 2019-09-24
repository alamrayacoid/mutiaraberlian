@extends('main')

@section('extra_style')
    <style type="text/css">
        a:not(.btn){
            text-decoration: none;
        }
        .card img{
            margin: auto;
        }
        .card-custom{
            min-height: calc(100vh / 2);
        }
        .card-custom:hover,
        .card-custom:focus-within{
            background-color: rgba(255,255,255,.6);
        }
    </style>
@endsection

@section('content')
    @include('inventory.reorderpoin.modal')
    <article class="content">
        <div class="title-block text-primary">
            <h1 class="title"> Pengelolaan Data Re-Order Poin </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="#"><span>Kelola Data Re-order Poin & Repeat Order</span></a>
            </p>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Kelola Data Reorder Poin & Repeat Order </h3>
                            </div>
                            {{--<div class="header-block pull-right">
                                <button type="button" class="btn btn-primary" id="e-create" onclick="window.location.href = '{{route('pegawai.create')}}'"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
                            </div>--}}
                        </div>
                        <div class="card-block">
                            <section>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_reorderpoin">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Stock</th>
                                            <th>Reorder</th>
                                            <th class="text-center">Status</th>
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
        var tabel_reorderpoin;
        $(document).ready(function(){
            setTimeout(function () {
                tabel_reorderpoin = $('#table_reorderpoin').DataTable({
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('reorderController.getDataReorderPoin') }}",
                        type: "get",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        }
                    },
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
                        {data: 'i_code'},
                        {data: 'i_name'},
                        {data: 's_qty'},
                        {data: 's_reorderpoin'},
                        {data: 'status'},
                        {data: 'aksi'}
                    ],
                    pageLength: 10,
                    lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
                },500);
            });

        });

        function setReorderPoin(id) {
            $('#id_stock').val(id);
            $('#edit_reoderpoin').val(0);
            $('#setReorder').modal('show');
        }

        function editReorderPoin(id, qty) {
            $('#edit_reorderpoin').val(qty);
            $('#edit_id_stock').val(id);
            $('#editReorder').modal('show');
        }

        function simpan() {
            loadingShow();
            axios.post('{{ route("reorderController.save") }}', {
                "idStock": $('#id_stock').val(),
                "qty": $('#reorderpoin').val(),
                "_token": "{{ csrf_token() }}"
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    tabel_reorderpoin.ajax.reload();
                    $('#setReorder').modal('hide');
                } else {
                    messageWarning("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                messageWarning("Error", 'Terjadi kesalahan : ' + error);
            })
        }

        function update() {
            loadingShow();
            axios.post('{{ route("reorderController.update") }}', {
                "idStock": $('#edit_id_stock').val(),
                "qty": $('#edit_reorderpoin').val(),
                "_token": "{{ csrf_token() }}"
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    tabel_reorderpoin.ajax.reload();
                    $('#editReorder').modal('hide');
                } else {
                    messageWarning("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                messageWarning("Error", 'Terjadi kesalahan : ' + error);
            })
        }
    </script>
@endsection
