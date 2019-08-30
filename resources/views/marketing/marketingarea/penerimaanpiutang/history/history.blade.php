@extends('main')

@section('content')
@include('marketing.marketingarea.penerimaanpiutang.history.detail')

<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Kelola Riwayat Pembayaran Piutang </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Marketing</span>
            / <a href="{{ route('marketingarea.index') }}"><span>Manajemen Marketing Area </span></a>
            / <span class="text-primary" style="font-weight: bold;"> Riwayat Pembayaran Piutang </span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Kelola Riwayat Pembayaran Piutang </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{ route('marketingarea.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <div class="card-block">
                        <section>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-4 col-sm-12">
                                    <div class="input-group input-group-sm input-daterange">
                                        <input type="text" class="form-control" id="date_from_pp" value="">
                                        <span class="input-group-addon">-</span>
                                        <input type="text" class="form-control" id="date_to_pp" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <input type="text" class="form-control form-control-sm" id="agen_pp" placeholder="Nama/Kode Agen" style="text-transform: uppercase">
                                    <input type="hidden" id="id_agen_pp">
                                </div>
                                <div class="col-lg-2 col-md-1 col-sm-12">
                                    <button type="button" class="btn btn-primary" id="btnFind">Cari</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_history_penerimaan">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th class="text-center">Agen</th>
                                        <th class="text-center">Total Piutang</th>
                                        <!-- <th class="text-center">Status</th> -->
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        </section>

                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('marketingarea.index') }}" class="btn btn-secondary">Kembali</a>
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
        var table_history_penerimaan;
        var cur_date = new Date();
        var first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
        var last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
        // order produk ke agen
        $('#date_from_pp').datepicker('setDate', first_day);
        $('#date_to_pp').datepicker('setDate', last_day);

        getListPenerimaan();

        $("#agen_pp").on('keyup', function () {
            $('#id_agen_pp').val('');
        });
        $("#agen_pp").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('mmapenerimaanpiutang.getDataAgenH') }}",
                    data: {
                        term: $("#agen_pp").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function (event, data) {
                let agen = data.item;
                $('#id_agen_pp').val(agen.id);
            }
        });

        $('#btnFind').on('click', function() {
            getListPenerimaan();
        });
        $('#date_from_pp').datepicker().on('changeDate', function () {
            getListPenerimaan();
        });
        $('#date_to_pp').datepicker().on('changeDate', function () {
            getListPenerimaan();
        });
    });

    function getListPenerimaan() {
        $('#table_history_penerimaan').dataTable().fnDestroy();
        table_history_penerimaan = $('#table_history_penerimaan').DataTable({
            serverSide: true,
            processing: true,
            searching: true,
            paging: true,
            responsive: true,
            ajax: {
                url: "{{ route('mmapenerimaanpiutang.getDataHistoryPayment') }}",
                type: "get",
                data: {
                    start: $('#date_from_pp').val(),
                    end: $('#date_to_pp').val(),
                    status: $('#status_pp').val(),
                    agen: $('#id_agen_pp').val()
                }
            },
            columns: [
                {data: 'c_name'},
                {data: 'sisa', class: 'text-right'},
                // {data: 'status', class: 'text-center'},
                {data: 'aksi'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function getDetailPiutang(id) {
        loadingShow();
        axios.get('{{ route("mmapenerimaanpiutang.getDetailTransaksi") }}', {
            params:{
                "id": id
            }
        }).then(function (response) {
            loadingHide();
            let detail = response.data.data;
            let pay = response.data.pay;
            $('#table_detailpp > tbody').empty();
            $('#table_detailpembayaranpp > tbody').empty();
            $.each(detail, function (index, value) {
                let no = "<td>"+(index + 1)+"</td>";
                let nama = "<td>"+value.i_name+"</td>";
                let qty = "<td>"+value.scd_qty+"</td>";
                let satuan = "<td>"+value.u_name+"</td>";
                let harga = "<td class='text-right'>"+convertToRupiah(value.scd_value)+"</td>";
                let diskon = "<td class='text-right'>"+convertToRupiah(value.scd_discvalue)+"</td>";
                let total = "<td class='text-right'>"+convertToRupiah(value.scd_totalnet)+"</td>";
                $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
            });
            let terbayar = 0;
            $.each(pay, function (index, value) {
                terbayar = terbayar + parseInt(value.scp_pay);
                let no = "<td>"+(index + 1)+"</td>";
                let tanggal = "<td>"+value.scp_date+"</td>";
                let nominal = "<td class='text-right'>"+convertToRupiah(value.scp_pay)+"</td>";
                $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
            });
            $('#nota_dtpp').val(detail[0].sc_nota);
            $('#date_dtpp').val(detail[0].sc_datetop);
            $('#agent_dtpp').val(detail[0].c_name);
            $('#total_dtpp').val(parseInt(detail[0].sc_total)-terbayar);
            $('#modalDetailpp').modal('show');
        }).catch(function (error) {
            loadingHide();
            alert('error');
        })
    }

</script>
@endsection
