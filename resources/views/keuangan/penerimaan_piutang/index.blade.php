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
                            type: "get",
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

                $('#modalBayarpp').on('hidden.bs.modal', function () {
                    $('#bayarpaypp').val('0');
                });
            }, 100);
        });
        // get list payment
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
        // show detail payment
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
                $('#nota_dtpp').val(detail.nota);
                $('#date_dtpp').val(detail.sc_datetop);
                $('#agent_dtpp').val(detail.agent);
                $('#total_dtpp').val(parseFloat(detail.total) - terbayar);
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
        // show modal payment
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
                $('#table_bayarpembayaranpp').removeClass('d-none');
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
                $('#total_paypp').val(parseFloat(detail.sc_total) - terbayar);

                $('#btnPay').off();
                $('#btnPay').on('click', function() {
                    sendPaymentAgen();
                });
                // mask rupiah
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
        // send payment agent
        function sendPaymentAgen() {
            let nota = $('#nota_paypp').val();
            let bayar = $('#bayarpaypp').val();
            let tanggal = $('#datepaypp').val();
            let paymentmethod = $('#paymentpp').val();
            if (bayar == 0 || bayar == ''){
                messageWarning("Perhatian", "Nilai bayar tidak boleh kosong !");
                return false;
            }
            if (tanggal == ''){
                messageWarning("Perhatian", "Tanggal pembayaran tidak boleh kosong !");
                return false;
            }
            if (paymentmethod == 'disable' || paymentmethod == ''){
                messageWarning("Perhatian", "Metode pembayaran tidak boleh kosong !");
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
                    table_penerimaanagen.ajax.reload();
                    $('#modalBayarpp').modal('hide');
                } else if (response.data.status == 'gagal'){
                    messageFailed("Gagal", response.data.message);
                }
            }).catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ error);
            })
        }
        // delete all payment
        function declineAllPaymentsAgen(nota) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin mau membatalkan pembayaran piutang ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            confirmDeclineAllPaymentsAgen(nota);
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
        // confirm delete all-payment
        function confirmDeclineAllPaymentsAgen(nota) {
            $.ajax({
                url: "{{ route('penerimaanpiutang.declineAllPaymentsAgen') }}",
                type: "post",
                data: {
                    nota: nota
                },
                beforeSend: function () {
                    loadingShow();
                },
                success: function (resp) {
                    if (resp.status == 'sukses') {
                        messageSuccess('Berhasil', 'Seluruh data pembayaran terkait berhasil dibatalkan !');
                        table_penerimaanagen.ajax.reload();
                    }
                    else {
                        messageWarning('Gagal', 'Terjadi kesalahan : '+ resp.message);
                    }
                },
                error: function (err) {
                    messageWarning('Error', 'Terjadi kesalahan : '+ error);
                },
                complete: function () {
                    loadingHide();
                }
            });
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

                $('#modalBayarpp').on('hidden.bs.modal', function () {
                    $('#bayarpaypp').val('0');
                    $('#bayarpaypp').attr('readonly', false);
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
                $('#table_detailpembayaranpp').addClass('d-none');
                // if transaction is from sales
                if (detail.source == 'Sales') {
                    // $('#table_detailpembayaranpp').addClass('d-none');
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
                    // $('#table_detailpembayaranpp').removeClass('d-none');
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
                $('#table_bayarpembayaranpp').addClass('d-none');
                $('#paymentpp').empty();
                // if transaction is from sales
                if (detail.source == 'Sales') {
                    // $('#table_bayarpembayaranpp').addClass('d-none');
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
                    // $('#table_bayarpembayaranpp').removeClass('d-none');
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

                    // $.each(pay, function (index, value) {
                    //     terbayar = terbayar + parseFloat(value.scp_pay);
                    //     let no = "<td>"+ (index + 1) +"</td>";
                    //     let tanggal = "<td>"+ value.scp_date +"</td>";
                    //     let nominal = "<td class='text-right rupiah'>"+ parseFloat(value.scp_pay)+"</td>";
                    //     $('#table_bayarpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal +"</tr>");
                    // });
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
                $('#total_paypp').val(parseFloat(detail.total));
                $('#bayarpaypp').val(parseFloat(detail.total));
                $('#bayarpaypp').attr('readonly', true);

                $('#btnPay').off();
                $('#btnPay').on('click', function() {
                    sendPaymentCabang();
                });
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
                messageWarning("Perhatian", "Nilai bayar tidak boleh kosong !");
                return false;
            }
            if (tanggal == ''){
                messageWarning("Perhatian", "Tanggal pembayaran tidak boleh kosong !");
                return false;
            }
            if (paymentmethod == 'disable' || paymentmethod == ''){
                messageWarning("Perhatian", "Metode pembayaran tidak boleh kosong !");
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
                    table_penerimaancabang.ajax.reload();
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

                $('#modalDetailpp').on('hidden.bs.modal', function() {
                    $('.paiddate').addClass('d-none');
                    $('#table_detailpembayaranpp .is-edit').addClass('d-none');
                });
                $('#modalBayarpp').on('hidden.bs.modal', function() {
                    $('.table-list-payment').removeClass('d-none');
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
                    {data: 'piutang'},
                    {data: 'date_top'},
                    {data: 'status'},
                    {data: 'aksi'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }
        // show detail payment-history
        function showDetailHistoryAgen(nota) {
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
                $('#nota_dtpp').val(detail.nota);
                $('#date_dtpp').val(detail.sc_datetop);
                $('#agent_dtpp').val(detail.agent);
                $('#total_dtpp').val(parseFloat(detail.total) - terbayar);
                $('.paiddate').removeClass('d-none');
                $('#paiddate_dtpp').val(detail.paidDate);
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
        // show detail payment-history for edit
        function showDetailEditHistoryAgen(nota) {
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
                $('#table_detailpembayaranpp .is-edit').removeClass('d-none');
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
                    let aksi = '<td class="text-center"><div class="btn-group btn-group-sm">' +
                        '<button type="button" class="btn btn-sm btn-warning hint--top hint--warning" aria-label="Edit" onclick="showEditHistoryAgen(\''+ value.scp_salescomp +'\', \''+ value.scp_detailid +'\')"><i class="fa fa-pencil"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-danger hint--top hint--danger" aria-label="Batalkan pembayaran" onclick="declineSelectedPaymentAgen(\''+ value.scp_salescomp +'\', \''+ value.scp_detailid +'\')"><i class="fa fa-ban"></i></button>' +
                        '</div></td>';
                    $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal + aksi +"</tr>");
                });
                $('#nota_dtpp').val(detail.nota);
                $('#date_dtpp').val(detail.sc_datetop);
                $('#agent_dtpp').val(detail.agent);
                $('#total_dtpp').val(parseFloat(detail.total) - terbayar);
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
        // show edit payment-history
        function showEditHistoryAgen(id, detailId) {
            $.ajax({
                url: '{{ route("penerimaanpiutang.getDetailPayment") }}',
                type: 'get',
                data: {
                    id: id,
                    detailId: detailId
                },
                beforeSend: function () {
                    loadingShow();
                },
                success: function (resp) {
                    $('#modalDetailpp').modal('hide');
                    $('#nota_paypp').val(resp.get_sales_comp.sc_nota);
                    $('#date_tempo').val(resp.get_sales_comp.sc_datetop);
                    $('#agent_paypp').val(resp.get_sales_comp.get_agent.c_name);
                    $('#total_paypp').val(parseFloat(resp.remainingPayment));
                    $('#bayarpaypp').val(parseFloat(resp.scp_pay));

                    $('.table-list-payment').addClass('d-none');
                    $('#table_bayarpp > tbody').empty();
                    $.each(resp.get_sales_comp.get_sales_comp_dt, function (index, value) {
                        let no = "<td>"+ (index + 1) +"</td>";
                        let nama = "<td>"+value.get_item.i_name+"</td>";
                        let qty = "<td>"+value.scd_qty+"</td>";
                        let satuan = "<td>"+value.get_unit.u_name+"</td>";
                        let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
                        let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
                        let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
                        $('#modalBayarpp #table_bayarpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
                    });
                    // re-init inputmask
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

                    $('#paymentpp').empty();
                    $('#paymentpp').append("<option value='disable'> == Pilih Metode Pembayaran == </option>");
                    $.each(resp.method, function (index, value) {
                        if ($('#userType').val() == 'PUSAT') {
                            $('#paymentpp').append('<option value="'+ value.pm_id +'">'+ value.get_akun.ak_nomor +' - '+ value.pm_name +'</option>');
                        }
                        else {
                            $('#paymentpp').append("<option value='"+value.get_akun.ak_id +"'>"+ value.get_akun.ak_nama +"</option>");
                        }
                    });

                    $('#btnPay').off();
                    $('#btnPay').on('click', function () {
                        sendEditHistoryAgen(resp.scp_salescomp, resp.scp_detailid);
                    });

                    $('#modalBayarpp').modal('show');
                },
                error: function (err) {
                    messageWarning('Error', 'Terjadi kesalahan : '+ err.message);
                },
                complete: function () {
                    loadingHide();
                }
            });
        }
        // send edit payment-history
        function sendEditHistoryAgen(salesCompId, paymentDetailId) {
            let nota = $('#nota_paypp').val();
            let bayar = $('#bayarpaypp').val();
            let tanggal = $('#datepaypp').val();
            let paymentmethod = $('#paymentpp').val();
            if (bayar == 0 || bayar == ''){
                messageWarning("Perhatian", "Jumlah pembayaran tidak boleh kosong atau '0' !");
                return false;
            }
            if (tanggal == ''){
                messageWarning("Perhatian", "Tanggal pembayaran tidak boleh kosong !");
                return false;
            }
            if (paymentmethod == 'disable' || paymentmethod == ''){
                messageWarning("Perhatian", "Metode pembayaran tidak boleh kosong !");
                return false;
            }

            $.ajax({
                url: '{{ route("penerimaanpiutang.updateHistoryPayment") }}',
                type: 'post',
                data: {
                    salesCompId: salesCompId,
                    paymentDetailId: paymentDetailId,
                    nota: nota,
                    bayar: bayar,
                    tanggal: tanggal,
                    paymentmethod: paymentmethod
                },
                beforeSend: function (){
                    loadingShow();
                },
                success: function (resp) {
                    if (resp.status == 'sukses') {
                        $('#modalBayarpp').modal('hide');
                        table_historyagen.ajax.reload();
                        messageSuccess('Berhasil', 'Pembayaran berhasil di perbarui !');
                    }
                    else {
                        messageFailed('Gagal', 'Gagal melakukan update pembayaran : ' + resp.message);
                    }
                },
                error: function (err) {
                    messageWarning('Error', 'Terjadi kesalahan : '+ err);
                },
                complete: function () {
                    loadingHide();
                }
            });
        }
        // decline selected payment
        function declineSelectedPaymentAgen(salesCompId, paymentDetailId) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin mau membatalkan pembayaran piutang ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            confirmDeclineSelectedPaymentAgen(salesCompId, paymentDetailId);
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
        // confirm decline selected payment
        function confirmDeclineSelectedPaymentAgen(salesCompId, paymentDetailId) {
            $.ajax({
                url: "{{ route('penerimaanpiutang.declineSelectedPaymentAgen') }}",
                type: "post",
                data: {
                    salesCompId: salesCompId,
                    paymentDetailId: paymentDetailId
                },
                beforeSend: function () {
                    loadingShow();
                },
                success: function (resp) {
                    if (resp.status == 'sukses') {
                        messageSuccess('Berhasil', 'Seluruh data pembayaran terkait berhasil dibatalkan !');
                        table_penerimaanagen.ajax.reload();
                        table_historyagen.ajax.reload();
                        $('#modalDetailpp').modal('hide');
                    }
                    else {
                        messageWarning('Gagal', 'Terjadi kesalahan : '+ resp.message);
                    }
                },
                error: function (err) {
                    messageWarning('Error', 'Terjadi kesalahan : '+ error);
                },
                complete: function () {
                    loadingHide();
                }
            });
        }
        // decline all payment
        function declineAllPaymentsHistoryAgen(nota) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin mau membatalkan pembayaran piutang ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            confirmDeclineAllPaymentsHistoryAgen(nota);
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
        // confirm decline all payment
        function confirmDeclineAllPaymentsHistoryAgen(nota) {
            $.ajax({
                url: "{{ route('penerimaanpiutang.declineAllPaymentsAgen') }}",
                type: "post",
                data: {
                    nota: nota
                },
                beforeSend: function () {
                    loadingShow();
                },
                success: function (resp) {
                    if (resp.status == 'sukses') {
                        messageSuccess('Berhasil', 'Seluruh data pembayaran terkait berhasil dibatalkan !');
                        table_historyagen.ajax.reload();
                    }
                    else {
                        messageWarning('Gagal', 'Terjadi kesalahan : '+ resp.message);
                    }
                },
                error: function (err) {
                    messageWarning('Error', 'Terjadi kesalahan : '+ error);
                },
                complete: function () {
                    loadingHide();
                }
            });
        }
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
                    getListHitoryCabang();
                });

                getListHitoryCabang();

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

                $('#modalDetailpp').on('hidden.bs.modal', function() {
                    $('.paiddate').addClass('d-none');
                });
            }, 200);
        });

        function getListHitoryCabang() {
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

        function showDetailHistoryCabang(nota) {
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

                $('#table_detailpp > tbody').empty();
                $('#table_detailpembayaranpp > tbody').empty();
                $('#table_detailpembayaranpp').addClass('d-none');
                // if transaction is from sales
                if (detail.source == 'Sales') {
                    // $('#table_detailpembayaranpp').addClass('d-none');
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
                    // $('#table_detailpembayaranpp').removeClass('d-none');
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
                }
                $('#agent_dtpp').val(detail.agent);
                $('#nota_dtpp').val(detail.nota);
                $('#date_dtpp').val(detail.sc_datetop);
                $('#total_dtpp').val(parseFloat(detail.total));
                $('.paiddate').removeClass('d-none');
                $('#paiddate_dtpp').val(detail.paidDate);
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

        function declineCabang(nota) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin mau membatalkan pembayaran piutang ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            declinePaymentCabang(nota);
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

        function declinePaymentCabang(nota) {
            $.ajax({
                url: "{{ route('penerimaanpiutang.declinePaymentCabang') }}",
                type: "post",
                data: {
                    nota: nota
                },
                beforeSend: function () {
                    loadingShow();
                },
                success: function (resp) {
                    if (resp.status == 'sukses') {
                        messageSuccess('Berhasil', 'Seluruh data pembayaran terkait berhasil dibatalkan !');
                        table_historycabang.ajax.reload();
                    }
                    else {
                        messageWarning('Gagal', 'Terjadi kesalahan : '+ resp.message);
                    }
                },
                error: function (err) {
                    messageWarning('Error', 'Terjadi kesalahan : '+ error);
                },
                complete: function () {
                    loadingHide();
                }
            });
        }
    </script>
<!-- ====== end: History Penerimaan Piutang Cabang ======= -->

@endsection
