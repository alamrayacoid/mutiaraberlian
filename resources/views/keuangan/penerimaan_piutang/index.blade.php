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
    @include('keuangan.penerimaan_piutang.modal.detail')
    @include('keuangan.penerimaan_piutang.modal.bayar')

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
                            <a href="#penerimaanagen" class="nav-link active" data-target="#penerimaanagen"
                               aria-controls="penerimaanagen" data-toggle="tab" role="tab">Penerimaan Piutang Agen</a>
                        </li>
                        <li class="nav-item">
                            <a href="#penerimaancabang" class="nav-link" data-target="#penerimaancabang"
                               aria-controls="penerimaancabang" data-toggle="tab" role="tab">Penerimaan Piutang Cabang</a>
                        </li>
                        <li class="nav-item">
                            <a href="#historyagen" class="nav-link" data-target="#historyagen"
                               aria-controls="historyagen" data-toggle="tab" role="tab">Riwayat Penerimaan Piutang Agen</a>
                        </li>
                        <li class="nav-item">
                            <a href="#historycabang" class="nav-link" data-target="#historycabang"
                               aria-controls="historycabang" data-toggle="tab" role="tab">Riwayat Penerimaan Piutang Cabang</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        @include('keuangan.penerimaan_piutang.penerimaan_agen.index')
                        @include('keuangan.penerimaan_piutang.penerimaan_cabang.index')
                        @include('keuangan.penerimaan_piutang.history_agen.index')
                        @include('keuangan.penerimaan_piutang.history_cabang.index')
                    </div>
                </div>
            </div>
        </section>
    </article>

@endsection
@section('extra_script')
<!-- ======== start: Penerimaan Piutang Agen =============== -->
    <script type="text/javascript">
        var table_penerimaanagen;
        // document ready
        $(document).ready(function () {
            setTimeout(function () {
                $('#agen_pa').val('');
                $('#id_agen_pa').val('');

                $('#btnCari_pa').on('click', function() {
                    getListPenerimaanAgen();
                });

                getListPenerimaanAgen();

                $("#agen_pa").on('keyup', function () {
                    $('#id_agen_pa').val('');
                });

                $("#agen_pa").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{ route('penerimaanpiutang.getListAgen') }}",
                            data: {
                                term: $("#agen_pa").val()
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    minLength: 1,
                    select: function (event, data) {
                        let agen = data.item;
                        $('#id_agen_pa').val(agen.id);
                    }
                });
            }, 100);
        });

        function getListPenerimaanAgen() {
            $('#table_penerimaanagen').DataTable().clear().destroy();
            table_penerimaanagen = $('#table_penerimaanagen').DataTable({
                serverSide: true,
                processing: true,
                searching: true,
                paging: true,
                responsive: true,
                ajax: {
                    url: "{{ route('penerimaanpiutang.getDataPenerimaanAgen') }}",
                    type: "get",
                    data: {
                        start: $('#date_from_pa').val(),
                        end: $('#date_to_pa').val(),
                        status: $('#status_pa').val(),
                        agen: $('#id_agen_pa').val()
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

        function detailNotaPiutangAgen(nota) {
            let type = 'AGEN';
            loadingShow();
            axios.get('{{ route("penerimaanpiutang.getDetailTransaksi") }}', {
                params:{
                    nota: nota,
                    type: type
                }
            }).then(function (response) {
                loadingHide();
                let detail = response.data.data;
                let pay = response.data.pay;
                let terbayar = 0;

                $('#table_detailpp > tbody').empty();
                $('#table_detailpembayaranpp > tbody').empty();
                $.each(detail.get_sales_comp_dt, function (index, value) {
                    let no = "<td>"+ (index + 1) +"</td>";
                    let nama = "<td>"+ value.get_item.i_name+"</td>";
                    let qty = "<td>"+ value.scd_qty +"</td>";
                    let satuan = "<td>"+ value.get_unit.u_name +"</td>";
                    let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
                    let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
                    let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
                    $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                });
                $.each(pay, function (index, value) {
                    terbayar = terbayar + parseFloat(value.scp_pay);
                    let no = "<td>"+ (index + 1) +"</td>";
                    let tanggal = "<td>"+ value.scp_date +"</td>";
                    let nominal = "<td class='text-right rupiah'>"+ parseFloat(value.scp_pay)+"</td>";
                    $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                });
                $('#nota_dtpp').val(detail.sc_nota);
                $('#date_dtpp').val(detail.sc_datetop);
                $('#agent_dtpp').val(detail.get_agent.c_name);
                $('#total_dtpp').val(parseFloat(detail.sc_total) - terbayar);
                //mask rupiah
                $('.rupiah').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: ' Rp ', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });

                $('#modalDetailpp').modal('show');
            }).catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ error);
            })
        }

        function showPaymentProcessAgen(nota) {
            let type = 'AGEN';
            loadingShow();
            axios.get('{{ route("penerimaanpiutang.getDetailTransaksi") }}', {
                params:{
                    nota: nota,
                    type: type
                }
            }).then(function (response) {
                loadingHide();
                let detail = response.data.data;
                let pay = response.data.pay;
                let method = response.data.jenis;
                let terbayar = 0;

                $('#table_bayarpp > tbody').empty();
                $('#table_bayarpembayaranpp > tbody').empty();
                $('#paymentpp').empty();
                $.each(detail.get_sales_comp_dt, function (index, value) {
                    let no = "<td>"+ (index + 1) +"</td>";
                    let nama = "<td>"+ value.get_item.i_name+"</td>";
                    let qty = "<td>"+ value.scd_qty +"</td>";
                    let satuan = "<td>"+ value.get_unit.u_name +"</td>";
                    let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
                    let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
                    let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
                    $('#table_bayarpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                });
                $.each(pay, function (index, value) {
                    terbayar = terbayar + parseFloat(value.scp_pay);
                    let no = "<td>"+ (index + 1) +"</td>";
                    let tanggal = "<td>"+ value.scp_date +"</td>";
                    let nominal = "<td class='text-right rupiah'>"+ parseFloat(value.scp_pay)+"</td>";
                    $('#table_bayarpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                });
                $('#paymentpp').append("<option value='disable'> == Pilih Metode Pembayaran == </option>");
                $.each(method, function (index, value) {
                    if ($('#userType').val() == 'PUSAT') {
                        $('#paymentpp').append('<option value="'+ value.pm_id +'">'+ value.get_akun.ak_nomor +' - '+ value.pm_name +'</option>');
                    }
                    else {
                        $('#paymentpp').append("<option value='"+value.get_akun.ak_id +"'>"+ value.get_akun.ak_nama +"</option>");
                    }
                });
                $('#nota_paypp').val(detail.sc_nota);
                $('#date_paypp').val(detail.sc_datetop);
                $('#agent_paypp').val(detail.get_agent.c_name);
                $('#total_paypp').val(parseFloat(detail.sc_total) - terbayar);//mask rupiah
                $('.rupiah').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: ' Rp ', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });

                $('#modalBayarpp').modal('show');
            }).catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ error);
            })
        }

        function sendPaymentAgen() {
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
            axios.get('{{ route("penerimaanpiutang.payPiutangAgen") }}', {
                params:{
                    "nota": nota,
                    "bayar": bayar,
                    "tanggal": tanggal,
                    "paymentmethod": paymentmethod
                }
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Pembayaran berhasil dilakukan !");
                    table_penerimaanpiutang.ajax.reload();
                    $('#modalBayarpp').modal('hide');
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ error);
            })
        }

    </script>
<!-- ====== end: Penerimaan Piutang Agen ======= -->

<!-- ====== start: Penerimaan Piutang cabang =========== -->
    <script type="text/javascript">
        var table_penerimaancabang;
        // document ready
        $(document).ready(function() {
            setTimeout(function () {
                $('#agen_pc').val('');
                $('#id_cabang_pc').val('');

                $('#btnCari_pc').on('click', function() {
                    getListPenerimaanCabang();
                });

                getListPenerimaanCabang();

                $("#cabang_pc").on('keyup', function () {
                    $('#id_cabang_pc').val('');
                });

                $("#cabang_pc").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{ route('penerimaanpiutang.getListCabang') }}",
                            data: {
                                term: $("#cabang_pc").val()
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    minLength: 1,
                    select: function (event, data) {
                        let agen = data.item;
                        $('#id_cabang_pc').val(agen.id);
                    }
                });
            }, 200);
        });

        function getListPenerimaanCabang() {
            let dateStart = $('#date_from_pc').val();
            let dateEnd = $('#date_to_pc').val();

            $('#table_penerimaancabang').DataTable().clear().destroy();
            table_penerimaancabang = $('#table_penerimaancabang').DataTable({
                serverSide: true,
                processing: true,
                searching: true,
                paging: true,
                responsive: true,
                ajax: {
                    url: "{{ route('penerimaanpiutang.getDataPenerimaanCabang') }}",
                    type: "get",
                    data: {
                        start: dateStart,
                        end: dateEnd,
                        status: $('#status_pc').val(),
                        cabang: $('#id_cabang_pc').val()
                    }
                },
                columns: [
                    {data: 'cabang'},
                    {data: 'agen'},
                    {data: 'piutang'},
                    {data: 'sc_datetop'},
                    // {data: ''},
                    {data: 'aksi'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function detailNotaPiutangCabang(nota) {
            let type = 'CABANG';
            loadingShow();
            axios.get('{{ route("penerimaanpiutang.getDetailTransaksi") }}', {
                params:{
                    nota: nota,
                    type: type
                }
            }).then(function (response) {
                loadingHide();
                let detail = response.data.data;
                let pay = response.data.pay;
                // let terbayar = 0;

                console.log(detail);
                $('#table_detailpp > tbody').empty();
                $('#table_detailpembayaranpp > tbody').empty();
                // if transaction is from sales
                if (detail.source == 'Sales') {
                    $('#table_detailpembayaranpp').addClass('d-none');
                    $.each(detail.get_sales_dt, function (index, value) {
                        let no = "<td>"+ (index + 1) +"</td>";
                        let nama = "<td>"+ value.get_item.i_name+"</td>";
                        let qty = "<td>"+ value.sd_qty +"</td>";
                        let satuan = "<td>"+ value.get_unit.u_name +"</td>";
                        let harga = "<td class='text-right rupiah'>"+ parseFloat(value.sd_value) +"</td>";
                        let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.sd_discvalue) +"</td>";
                        let total = "<td class='text-right rupiah'>"+ parseFloat(value.sd_totalnet) +"</td>";
                        $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                    });
                }
                // if transaction is from salesComp
                else {
                    $('#table_detailpembayaranpp').removeClass('d-none');
                    $.each(detail.get_sales_comp_dt, function (index, value) {
                        let no = "<td>"+ (index + 1) +"</td>";
                        let nama = "<td>"+ value.get_item.i_name+"</td>";
                        let qty = "<td>"+ value.scd_qty +"</td>";
                        let satuan = "<td>"+ value.get_unit.u_name +"</td>";
                        let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
                        let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
                        let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
                        $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                    });

                    $.each(pay, function (index, value) {
                        // terbayar = terbayar + parseFloat(value.scp_pay);
                        let no = "<td>"+ (index + 1) +"</td>";
                        let tanggal = "<td>"+ value.scp_date +"</td>";
                        let nominal = "<td class='text-right rupiah'>"+ parseFloat(value.scp_pay)+"</td>";
                        $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                    });
                }
                $('#agent_dtpp').val(detail.agent);
                $('#nota_dtpp').val(detail.nota);
                $('#date_dtpp').val(detail.sc_datetop);
                $('#total_dtpp').val(parseFloat(detail.total));
                //mask rupiah
                $('.rupiah').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: ' Rp ', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });

                $('#modalDetailpp').modal('show');
            }).catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ error);
            })
        }

        function showPaymentProcessCabang(nota) {
            let type = 'CABANG';
            loadingShow();
            axios.get('{{ route("penerimaanpiutang.getDetailTransaksi") }}', {
                params:{
                    nota: nota,
                    type: type
                }
            }).then(function (response) {
                loadingHide();
                let detail = response.data.data;
                let pay = response.data.pay;
                let method = response.data.jenis;
                let terbayar = 0;

                $('#table_bayarpp > tbody').empty();
                $('#table_bayarpembayaranpp > tbody').empty();
                $('#paymentpp').empty();
                // if transaction is from sales
                if (detail.source == 'Sales') {
                    $('#table_detailpembayaranpp').addClass('d-none');
                    $.each(detail.get_sales_dt, function (index, value) {
                        let no = "<td>"+ (index + 1) +"</td>";
                        let nama = "<td>"+ value.get_item.i_name+"</td>";
                        let qty = "<td>"+ value.sd_qty +"</td>";
                        let satuan = "<td>"+ value.get_unit.u_name +"</td>";
                        let harga = "<td class='text-right rupiah'>"+ parseFloat(value.sd_value) +"</td>";
                        let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.sd_discvalue) +"</td>";
                        let total = "<td class='text-right rupiah'>"+ parseFloat(value.sd_totalnet) +"</td>";
                        $('#table_bayarpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                    });
                }
                // if transaction is from salesComp
                else {
                    $('#table_detailpembayaranpp').removeClass('d-none');
                    $.each(detail.get_sales_comp_dt, function (index, value) {
                        let no = "<td>"+ (index + 1) +"</td>";
                        let nama = "<td>"+ value.get_item.i_name+"</td>";
                        let qty = "<td>"+ value.scd_qty +"</td>";
                        let satuan = "<td>"+ value.get_unit.u_name +"</td>";
                        let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
                        let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
                        let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
                        $('#table_bayarpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                    });

                    $.each(pay, function (index, value) {
                        terbayar = terbayar + parseFloat(value.scp_pay);
                        let no = "<td>"+ (index + 1) +"</td>";
                        let tanggal = "<td>"+ value.scp_date +"</td>";
                        let nominal = "<td class='text-right rupiah'>"+ parseFloat(value.scp_pay)+"</td>";
                        $('#table_bayarpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                    });
                }
                $('#paymentpp').append("<option value='disable'> == Pilih Metode Pembayaran == </option>");
                $.each(method, function (index, value) {
                    if ($('#userType').val() == 'PUSAT') {
                        $('#paymentpp').append('<option value="'+ value.pm_id +'">'+ value.get_akun.ak_nomor +' - '+ value.pm_name +'</option>');
                    }
                    else {
                        $('#paymentpp').append("<option value='"+value.get_akun.ak_id +"'>"+ value.get_akun.ak_nama +"</option>");
                    }
                });
                $('#agent_paypp').val(detail.agent);
                $('#nota_paypp').val(detail.nota);
                $('#date_paypp').val(detail.sc_datetop);
                $('#total_paypp').val(parseFloat(detail.total));//mask rupiah
                $('.rupiah').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: ' Rp ', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });

                $('#modalBayarpp').modal('show');
            }).catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ error);
            })
        }

        function sendPaymentCabang() {
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
            axios.get('{{ route("penerimaanpiutang.payPiutangCabang") }}', {
                params:{
                    "nota": nota,
                    "bayar": bayar,
                    "tanggal": tanggal,
                    "paymentmethod": paymentmethod
                }
            }).then(function (response) {
                loadingHide();
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Pembayaran berhasil dilakukan !");
                    table_penerimaanpiutang.ajax.reload();
                    $('#modalBayarpp').modal('hide');
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ error);
            })
        }

    </script>
<!-- ====== end: Penerimaan Piutang cabang =========== -->


<!-- ======== start: History Penerimaan Piutang Agen =============== -->
    <script type="text/javascript">
        var table_historyagen;
        // document ready
        $(document).ready(function () {
            setTimeout(function () {
                $('#agen_pah').val('');
                $('#id_agen_pah').val('');

                $('#btnCari_pah').on('click', function() {
                    getListHistoryAgen();
                });

                getListHistoryAgen();

                $("#agen_pah").on('keyup', function () {
                    $('#id_agen_pah').val('');
                });

                $("#agen_pah").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{ route('penerimaanpiutang.getListAgen') }}",
                            data: {
                                term: $("#agen_pah").val()
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    minLength: 1,
                    select: function (event, data) {
                        let agen = data.item;
                        $('#id_agen_pah').val(agen.id);
                    }
                });
            }, 300);
        });

        function getListHistoryAgen() {
            $('#table_historyagen').DataTable().clear().destroy();
            table_historyagen = $('#table_historyagen').DataTable({
                serverSide: true,
                processing: true,
                searching: true,
                paging: true,
                responsive: true,
                ajax: {
                    url: "{{ route('penerimaanpiutang.getDataHistoryAgen') }}",
                    type: "get",
                    data: {
                        start: $('#date_from_pah').val(),
                        end: $('#date_to_pah').val(),
                        status: $('#status_pah').val(),
                        agen: $('#id_agen_pah').val()
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

        // function detailHistoryPiutangAgen(nota) {
        //     let type = 'AGEN';
        //     loadingShow();
        //     axios.get('{{ route("penerimaanpiutang.getDetailTransaksi") }}', {
        //         params:{
        //             nota: nota,
        //             type: type
        //         }
        //     }).then(function (response) {
        //         loadingHide();
        //         let detail = response.data.data;
        //         let pay = response.data.pay;
        //         let terbayar = 0;
        //
        //         $('#table_detailpp > tbody').empty();
        //         $('#table_detailpembayaranpp > tbody').empty();
        //         $.each(detail.get_sales_comp_dt, function (index, value) {
        //             let no = "<td>"+ (index + 1) +"</td>";
        //             let nama = "<td>"+ value.get_item.i_name+"</td>";
        //             let qty = "<td>"+ value.scd_qty +"</td>";
        //             let satuan = "<td>"+ value.get_unit.u_name +"</td>";
        //             let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
        //             let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
        //             let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
        //             $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
        //         });
        //         $.each(pay, function (index, value) {
        //             terbayar = terbayar + parseFloat(value.scp_pay);
        //             let no = "<td>"+ (index + 1) +"</td>";
        //             let tanggal = "<td>"+ value.scp_date +"</td>";
        //             let nominal = "<td class='text-right rupiah'>"+ parseFloat(value.scp_pay)+"</td>";
        //             $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
        //         });
        //         $('#nota_dtpp').val(detail.sc_nota);
        //         $('#date_dtpp').val(detail.sc_datetop);
        //         $('#agent_dtpp').val(detail.get_agent.c_name);
        //         $('#total_dtpp').val(parseFloat(detail.sc_total) - terbayar);
        //         //mask rupiah
        //         $('.rupiah').inputmask("currency", {
        //             radixPoint: ",",
        //             groupSeparator: ".",
        //             digits: 0,
        //             autoGroup: true,
        //             prefix: ' Rp ', //Space after $, this will not truncate the first character.
        //             rightAlign: true,
        //             autoUnmask: true,
        //             nullable: false,
        //             // unmaskAsNumber: true,
        //         });
        //
        //         $('#modalDetailpp').modal('show');
        //     }).catch(function (error) {
        //         loadingHide();
        //         messageWarning('Error', 'Terjadi kesalahan : '+ error);
        //     })
        // }

    </script>
<!-- ====== end: History Penerimaan Piutang Agen ======= -->

<!-- ======== start: History Penerimaan Piutang Cabang =============== -->
    <script type="text/javascript">
        var table_historycabang;
        // document ready
        $(document).ready(function() {
            setTimeout(function () {
                $('#agen_pch').val('');
                $('#id_cabang_pch').val('');

                $('#btnCari_pch').on('click', function() {
                    getListPenerimaanCabang();
                });

                getListPenerimaanCabang();

                $("#cabang_pch").on('keyup', function () {
                    $('#id_cabang_pch').val('');
                });

                $("#cabang_pch").autocomplete({
                    source: function (request, response) {
                        $.ajax({
                            url: "{{ route('penerimaanpiutang.getListCabang') }}",
                            data: {
                                term: $("#cabang_pch").val()
                            },
                            success: function (data) {
                                response(data);
                            }
                        });
                    },
                    minLength: 1,
                    select: function (event, data) {
                        let agen = data.item;
                        $('#id_cabang_pch').val(agen.id);
                    }
                });
            }, 200);
        });

        function getListPenerimaanCabang() {
            let dateStart = $('#date_from_pch').val();
            let dateEnd = $('#date_to_pch').val();

            $('#table_historycabang').DataTable().clear().destroy();
            table_historycabang = $('#table_historycabang').DataTable({
                serverSide: true,
                processing: true,
                searching: true,
                paging: true,
                responsive: true,
                ajax: {
                    url: "{{ route('penerimaanpiutang.getDataHistoryCabang') }}",
                    type: "get",
                    data: {
                        start: dateStart,
                        end: dateEnd,
                        status: $('#status_pch').val(),
                        cabang: $('#id_cabang_pch').val()
                    }
                },
                columns: [
                    {data: 'cabang'},
                    {data: 'agen'},
                    {data: 'piutang'},
                    {data: 'sc_datetop'},
                    // {data: ''},
                    {data: 'aksi'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // function detailNotaPiutangCabang(nota) {
        //     let type = 'CABANG';
        //     loadingShow();
        //     axios.get('{{ route("penerimaanpiutang.getDetailTransaksi") }}', {
        //         params:{
        //             nota: nota,
        //             type: type
        //         }
        //     }).then(function (response) {
        //         loadingHide();
        //         let detail = response.data.data;
        //         let pay = response.data.pay;
        //         // let terbayar = 0;
        //
        //         console.log(detail);
        //         $('#table_detailpp > tbody').empty();
        //         $('#table_detailpembayaranpp > tbody').empty();
        //         // if transaction is from sales
        //         if (detail.source == 'Sales') {
        //             $('#table_detailpembayaranpp').addClass('d-none');
        //             $.each(detail.get_sales_dt, function (index, value) {
        //                 let no = "<td>"+ (index + 1) +"</td>";
        //                 let nama = "<td>"+ value.get_item.i_name+"</td>";
        //                 let qty = "<td>"+ value.sd_qty +"</td>";
        //                 let satuan = "<td>"+ value.get_unit.u_name +"</td>";
        //                 let harga = "<td class='text-right rupiah'>"+ parseFloat(value.sd_value) +"</td>";
        //                 let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.sd_discvalue) +"</td>";
        //                 let total = "<td class='text-right rupiah'>"+ parseFloat(value.sd_totalnet) +"</td>";
        //                 $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
        //             });
        //         }
        //         // if transaction is from salesComp
        //         else {
        //             $('#table_detailpembayaranpp').removeClass('d-none');
        //             $.each(detail.get_sales_comp_dt, function (index, value) {
        //                 let no = "<td>"+ (index + 1) +"</td>";
        //                 let nama = "<td>"+ value.get_item.i_name+"</td>";
        //                 let qty = "<td>"+ value.scd_qty +"</td>";
        //                 let satuan = "<td>"+ value.get_unit.u_name +"</td>";
        //                 let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
        //                 let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
        //                 let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
        //                 $('#table_detailpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
        //             });
        //
        //             $.each(pay, function (index, value) {
        //                 // terbayar = terbayar + parseFloat(value.scp_pay);
        //                 let no = "<td>"+ (index + 1) +"</td>";
        //                 let tanggal = "<td>"+ value.scp_date +"</td>";
        //                 let nominal = "<td class='text-right rupiah'>"+ parseFloat(value.scp_pay)+"</td>";
        //                 $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
        //             });
        //         }
        //         $('#agent_dtpp').val(detail.agent);
        //         $('#nota_dtpp').val(detail.nota);
        //         $('#date_dtpp').val(detail.sc_datetop);
        //         $('#total_dtpp').val(parseFloat(detail.total));
        //         //mask rupiah
        //         $('.rupiah').inputmask("currency", {
        //             radixPoint: ",",
        //             groupSeparator: ".",
        //             digits: 0,
        //             autoGroup: true,
        //             prefix: ' Rp ', //Space after $, this will not truncate the first character.
        //             rightAlign: true,
        //             autoUnmask: true,
        //             nullable: false,
        //             // unmaskAsNumber: true,
        //         });
        //
        //         $('#modalDetailpp').modal('show');
        //     }).catch(function (error) {
        //         loadingHide();
        //         messageWarning('Error', 'Terjadi kesalahan : '+ error);
        //     })
        // }

    </script>
<!-- ====== end: History Penerimaan Piutang Cabang ======= -->

@endsection
