@extends('main')
@section('tittle')
    Penerimaan Piutang
@endsection
@section('extra_style')
    <style>
        @media (min-width: 992px) {
            .modal-xl {
                max-width: 1200px !important;
            }
        }
    </style>
@stop
@section('content')
    @include('marketing.marketingarea.penerimaanpiutang.modal.detail')
    @include('marketing.marketingarea.penerimaanpiutang.modal.bayar')

    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Penerimaan Piutang </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Keuangan</span> /
                <span class="text-primary" style="font-weight: bold;">Penerimaan Piutang</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-3" id="Tabzs">
                        <li class="nav-item">
                            <a href="#penerimaanpiutang" class="nav-link active" data-target="#penerimaanpiutang"
                               aria-controls="penerimaanpiutang" data-toggle="tab" role="tab">Penerimaan Piutang Agen</a>
                        </li>
                        <li class="nav-item">
                            <a href="#pembayarancabang" class="nav-link" data-target="#pembayarancabang"
                               aria-controls="pembayarancabang" data-toggle="tab" role="tab">Pembayaran Cabang</a>
                        </li>
                        <li class="nav-item">
                            <a href="#historypenerimaanpiutang" class="nav-link" data-target="#historypenerimaanpiutang"
                               aria-controls="historypenerimaanpiutang" data-toggle="tab" role="tab">History Penerimaan Piutang Agen</a>
                        </li>
                        <li class="nav-item">
                            <a href="#historypembayaran" class="nav-link" data-target="#historypembayaran"
                               aria-controls="historypembayaran" data-toggle="tab" role="tab">History Pembayaran Cabang</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        @include('keuangan.penerimaan_piutang.pembayaranagen.index')
                        @include('keuangan.penerimaan_piutang.pembayarancabang.index')
                    </div>
                </div>
            </div>
        </section>
    </article>

@endsection
@section('extra_script')
    <script type="text/javascript">
        var table_pembayarancabang;
        var table_penerimaanpiutang;
        $(document).ready(function () {
            $('#agen_pp').val('');
            $('#id_agen_pp').val('');
            $('#agen_pc').val('');
            $('#id_cabang_pc').val('');
            setTimeout(function () {
                table_penerimaanpiutang = $('#table_penerimaanpiutang').DataTable({
                    serverSide: true,
                    processing: true,
                    searching: false,
                    paging: false,
                    responsive: true,
                    ajax: {
                        url: "{{ route('mmapenerimaanpiutang.getdata') }}",
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
                        {data: 'sisa'},
                        {data: 'sc_datetop'},
                        {data: 'status'},
                        {data: 'aksi'}
                    ],
                    pageLength: 10,
                    lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
                });
            }, 200);

            setTimeout(function () {
                table_pembayarancabang = $('#table_pembayarancabang').DataTable({
                    serverSide: true,
                    processing: true,
                    searching: false,
                    paging: false,
                    responsive: true,
                    ajax: {
                        url: "{{ route('pembayarancabang.getdatalistcabang') }}",
                        type: "get",
                        data: {
                            start: $('#date_from_pc').val(),
                            end: $('#date_to_pc').val(),
                            status: $('#status_pc').val(),
                            cabang: $('#id_cabang_pc').val()
                        }
                    },
                    columns: [
                        {data: 'cabang'},
                        {data: 'c_name'},
                        {data: 'sisa'},
                        {data: 'sc_datetop'},
                        {data: 'status'},
                        {data: 'aksi'}
                    ],
                    pageLength: 10,
                    lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
                });
            }, 400);
        })

        //======== Penerimaan Piutang ===============
        function getDataPP() {
            $('#table_penerimaanpiutang').dataTable().fnClearTable();
            $('#table_penerimaanpiutang').dataTable().fnDestroy();
            table_penerimaanpiutang = $('#table_penerimaanpiutang').DataTable({
                serverSide: true,
                processing: true,
                searching: false,
                paging: false,
                responsive: true,
                ajax: {
                    url: "{{ route('mmapenerimaanpiutang.getdata') }}",
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
                    {data: 'sisa'},
                    {data: 'sc_datetop'},
                    {data: 'status'},
                    {data: 'aksi'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        $("#agen_pp").on('keyup', function () {
            $('#id_agen_pp').val('');
        })

        $("#agen_pp").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('mmapenerimaanpiutang.getDataAgen') }}",
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

        function detailnotapiutang(id){
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

        function bayarnotapiutang(id){
            loadingShow();
            axios.get('{{ route("mmapenerimaanpiutang.getDetailTransaksi") }}', {
                params:{
                    "id": id
                }
            }).then(function (response) {
                loadingHide();
                let detail = response.data.data;
                let pay = response.data.pay;
                let method = response.data.jenis;
                $('#table_bayarpp > tbody').empty();
                $('#table_bayarpembayaranpp > tbody').empty();
                $('#paymentpp').empty();
                $.each(detail, function (index, value) {
                    let no = "<td>"+(index + 1)+"</td>";
                    let nama = "<td>"+value.i_name+"</td>";
                    let qty = "<td>"+value.scd_qty+"</td>";
                    let satuan = "<td>"+value.u_name+"</td>";
                    let harga = "<td class='text-right'>"+convertToRupiah(value.scd_value)+"</td>";
                    let diskon = "<td class='text-right'>"+convertToRupiah(value.scd_discvalue)+"</td>";
                    let total = "<td class='text-right'>"+convertToRupiah(value.scd_totalnet)+"</td>";
                    $('#table_bayarpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                });
                let terbayar = 0;
                $.each(pay, function (index, value) {
                    terbayar = terbayar + parseInt(value.scp_pay);
                    let no = "<td>"+(index + 1)+"</td>";
                    let tanggal = "<td>"+value.scp_date+"</td>";
                    let nominal = "<td class='text-right'>"+convertToRupiah(value.scp_pay)+"</td>";
                    $('#table_bayarpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                });
                $('#paymentpp').append("<option value='disable'> == Pilih Metode Pembayaran == </option>");
                $.each(method, function (index, value) {
                    $('#paymentpp').append("<option value='"+value.ak_id+"'>"+value.ak_nama+"</option>");
                });
                $('#nota_paypp').val(detail[0].sc_nota);
                $('#date_paypp').val(detail[0].sc_datetop);
                $('#agent_paypp').val(detail[0].c_name);
                $('#total_paypp').val(parseInt(detail[0].sc_total)-terbayar);
                $('#modalBayarpp').modal('show');
            }).catch(function (error) {
                loadingHide();
                alert('error');
            })
        }

        function bayarPP() {
            let nota = $('#nota_paypp').val();
            let bayar = $('#bayarpaypp').val();
            let tanggal = $('#datepaypp').val();
            let paymentmethod = $('#paymentpp').val();
            if (bayar == 0 || bayar == ''){
                messageWarning("Lengkapi form pembayaran");
                return false;
            }
            if (tanggal == ''){
                messageWarning("Lengkapi form pembayaran");
                return false;
            }
            if (paymentmethod == 'disable' || paymentmethod == ''){
                messageWarning("Lengkapi form pembayaran");
                return false;
            }
            loadingShow();
            axios.get('{{ route("mmapenerimaanpiutang.bayarPiutang") }}', {
                params:{
                    "nota": nota,
                    "bayar": bayar,
                    "tanggal": tanggal,
                    "paymentmethod": paymentmethod
                }
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Pembayaran sudah tersimpan");
                    table_penerimaanpiutang.ajax.reload();
                    $('#modalBayarpp').modal('hide');
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                alert("error");
            })
        }
//====== end peneirmaan piutang agen =======

//====== start Pembayaran cabang ===========
        $("#cabang_pc").on('keyup', function () {
            $('#id_agen_pp').val('');
        })
    </script>
@endsection
