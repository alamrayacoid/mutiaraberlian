@extends('main')

@section('content')

    @include('inventory.manajemenstok.pengelolaanmms.modal')

    <article class="content animated fadeInLeft">


        <div class="title-block text-primary">
            <h1 class="title"> Manajemen Penjualan Stok </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Inventory</span> /
                <span class="text-primary" style="font-weight: bold;">Pengelolaan Manajemen Stok</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Penglolaan Data Max/Min Stok, Safety Stok </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a class="btn btn-primary" href="{{route('pengelolaanmms.create')}}"><i
                                        class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                            </div>
                        </div>
                        <div class="card-block">
                            <section>
                                <div class="top-section mb-3">
                                    <fieldset>
                                        <div class="row">
                                            <div class="col-md-1 col-sm-12">
                                                <label for="">Pemilik</label>
                                            </div>
                                            <div class="col-md-2 col-sm-12">
                                                <select name="q_pemilik" id="q_pemilik"
                                                        class="form-control form-control-sm select2">
                                                    <option value="">Pilih</option>
                                                    @foreach($companies as $key => $comp)
                                                        <option
                                                            value="{{ $comp->c_id }}">{{ strtoupper($comp->c_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 col-sm-12">
                                                <label for="">Posisi</label>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <select name="q_posisi" id="q_posisi"
                                                        class="form-control form-control-sm select2">
                                                    <option value="">Pilih</option>
                                                    @foreach($companies as $key => $comp)
                                                        <option
                                                            value="{{ $comp->c_id }}">{{ strtoupper($comp->c_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 col-sm-12">
                                                <label for="">Barang</label>
                                            </div>
                                            <div class="col-md-3 col-sm-12">
                                                <input type="hidden" name="q_idItem" id="q_idItem">
                                                <input type="text" name="q_barang" id="q_barang"
                                                       class="form-control form-control-sm">
                                            </div>
                                            <div class="col-1">
                                                <button class="btn btn-md btn-primary" onclick="search()"><i class="fa fa-search"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </fieldset>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover display nowrap" cellspacing="0"
                                           id="table_pengelolaanmms">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th>Pemilik</th>
                                            <th>Posisi</th>
                                            <th>Barang</th>
                                            <th>Qty</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{--<tr>--}}
                                        {{--<td>1</td>--}}
                                        {{--<td>Agung</td>--}}
                                        {{--<td>Rungkut</td>--}}
                                        {{--<td>Kode - Nama Barang</td>--}}
                                        {{--<td>12</td>--}}
                                        {{--<td>--}}
                                        {{--<div class="btn-group btn-group-sm">--}}
                                        {{--<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>--}}
                                        {{--<button class="btn btn-warning btn-edit" type="button" title="Edit" onclick="window.location.href='{{route('pengelolaanmms.edit')}}'"><i class="fa fa-pencil"></i></button>--}}
                                        {{--</div>--}}
                                        {{--</td>--}}
                                        {{--</tr>--}}
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
        var table;
        $(document).ready(function () {
            table = $('#table_pengelolaanmms').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('pengelolaanmms.liststock') }}",
                    type: "get"
                },
                columns: [
                    {data: 'pemilik'},
                    {data: 'posisi'},
                    {data: 'item'},
                    {data: 'qty'},
                    {data: 'action'}
                ]
            });

            $("#q_barang").on("keyup", function () {
                $("#q_idItem").val('');
            });

            $("#q_barang").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: '{{ route('pengelolaanmms.caribarang') }}',
                        data: {
                            term: $("#q_barang").val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $("#q_idItem").val(data.item.id);
                }
            });
        });

        function detail(id) {
            loadingShow();
            axios.get(baseUrl + '/inventory/manajemenstok/pengelolaanmms/detail-stock/' + id)
                .then(function (resp) {
                    if (resp.data.status == "Failed") {
                        loadingHide();
                        messageFailed("Gagal", resp.data.message);
                    } else if (resp.data.status == "Success") {
                        $("#pemilik").val(resp.data.message.pemilik);
                        $("#posisi").val(resp.data.message.posisi);
                        $("#item").val(resp.data.message.item);
                        $("#minstock").val(resp.data.message.qtymin);
                        $("#maxstock").val(resp.data.message.qtymax);
                        $("#rangemin").val(resp.data.message.rangemin);
                        $("#rangemax").val(resp.data.message.rangemax);
                        loadingHide();
                        $("#detailPengelolaanms").modal("show");
                        $('#table_detail').DataTable().clear().destroy();
                        var tb_detail = $('#table_detail').DataTable({
                            responsive: true,
                            info: false,
                            searching: false,
                            paging: false
                        });
                        $.each(resp.data.codes, function(key, val){
                            tb_detail.row.add([
                                val.sd_code,
                                val.sd_qty
                            ]).draw(false);
                        });
                    }
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error);
                })
        }

        function edit(id) {
            window.location.href = baseUrl + "/inventory/manajemenstok/pengelolaanmms/edit/" + id;
        }

        function search() {
            loadingShow();
            if ($.fn.DataTable.isDataTable("#table_pengelolaanmms")) {
                $('#table_pengelolaanmms').DataTable().clear().destroy();
            }

            table = $('#table_pengelolaanmms').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('pengelolaanmms.caristock') }}",
                    type: "get",
                    data: {
                        pemilik: $("#q_pemilik").val(),
                        posisi: $("#q_posisi").val(),
                        item: $("#q_idItem").val()
                    }
                },
                columns: [
                    {data: 'pemilik'},
                    {data: 'posisi'},
                    {data: 'item'},
                    {data: 'qty'},
                    {data: 'action'}
                ],
                drawCallback: function( settings ) {
                    loadingHide();
                }
            });
        }
    </script>
@endsection
