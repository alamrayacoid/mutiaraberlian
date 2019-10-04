@extends('main')

@section('content')
@include('marketing.marketingarea.penerimaanpiutang.history.detail')
@include('marketing.marketingarea.penerimaanpiutang.history.edit-pembayaran')

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
                        <input type="hidden" class="userType" value="{{ Auth::user()->getCompany->c_type }}">
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
        var idPayment;
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
    // back to detail-piutang with edit button
    $('#modalEditPembayaran').on('hidden.bs.modal', function () {
        getDetailEditPiutang(idPayment);
    });
    // get history-penerimaan index
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
    // get detail-piutang with edit button
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
            $('#table_detailpembayaranpp .is-edit').addClass('d-none');
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
    // get detail-piutang
    function getDetailEditPiutang(id) {
        idPayment = id;
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
            $('#table_detailpembayaranpp .is-edit').removeClass('d-none');
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
                let aksi = '<td class="text-center"><button type="button" class="btn btn-sm btn-warning hint--top hint--warning" aria-label="Edit" onclick="editPiutang(\''+ value.scp_salescomp +'\', \''+ value.scp_detailid +'\')"><i class="fa fa-pencil"></i></button></td>';
                $('#table_detailpembayaranpp > tbody').append("<tr>" + no + tanggal + nominal + aksi +"</tr>");
            });
            $('#nota_dtpp').val(detail[0].sc_nota);
            $('#date_dtpp').val(detail[0].sc_datetop);
            $('#agent_dtpp').val(detail[0].c_name);
            $('#total_dtpp').val(parseInt(detail[0].sc_total)-terbayar);
            $('#modalDetailpp').modal('show');
        }).catch(function (error) {
            loadingHide();
            alert('error');
        });
    }
    // get detail payment
    function editPiutang(id, detailId) {
        $.ajax({
            url: '{{ route("mmapenerimaanpiutang.getDetailPayment") }}',
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
                $('#modalEditPembayaran #nota_paypp').val(resp.get_sales_comp.sc_nota);
                $('#modalEditPembayaran #date_tempo').val(resp.get_sales_comp.sc_datetop);
                $('#modalEditPembayaran #agent_paypp').val(resp.get_sales_comp.get_agent.c_name);
                $('#modalEditPembayaran #total_paypp').val(parseFloat(resp.remainingPayment));
                $('#modalEditPembayaran #bayarpaypp').val(parseFloat(resp.scp_pay));

                $('#modalEditPembayaran #table_bayarpp > tbody').empty();
                $.each(resp.get_sales_comp.get_sales_comp_dt, function (index, value) {
                    let no = "<td>"+ (index + 1) +"</td>";
                    let nama = "<td>"+value.get_item.i_name+"</td>";
                    let qty = "<td>"+value.scd_qty+"</td>";
                    let satuan = "<td>"+value.get_unit.u_name+"</td>";
                    let harga = "<td class='text-right rupiah'>"+ parseFloat(value.scd_value) +"</td>";
                    let diskon = "<td class='text-right rupiah'>"+ parseFloat(value.scd_discvalue) +"</td>";
                    let total = "<td class='text-right rupiah'>"+ parseFloat(value.scd_totalnet) +"</td>";
                    $('#modalEditPembayaran #table_bayarpp > tbody').append("<tr>" + no + nama + qty + satuan + harga + diskon + total + "</tr>");
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

                $('#modalEditPembayaran #paymentpp').empty();
                $('#modalEditPembayaran #paymentpp').append("<option value='disable'> == Pilih Metode Pembayaran == </option>");
                $.each(resp.method, function (index, value) {
                    if ($('#userType').val() == 'PUSAT') {
                        $('#modalEditPembayaran #paymentpp').append('<option value="'+ value.pm_id +'">'+ value.get_akun.ak_nomor +' - '+ value.pm_name +'</option>');
                    }
                    else {
                        $('#modalEditPembayaran #paymentpp').append("<option value='"+value.get_akun.ak_id +"'>"+ value.get_akun.ak_nama +"</option>");
                    }
                });

                $('#modalEditPembayaran #btnUpdate').off();
                $('#modalEditPembayaran #btnUpdate').on('click', function () {
                    updatePayment(resp.scp_salescomp, resp.scp_detailid);
                });

                $('#modalEditPembayaran').modal('show');
            },
            error: function (err) {
                messageWarning('Error', 'Terjadi kesalahan : '+ err.message);
            },
            complete: function () {
                loadingHide();
            }
        });
    }
    // update specific payment
    function updatePayment(salesCompId, paymentDetailId) {
        let nota = $('#modalEditPembayaran #nota_paypp').val();
        let bayar = $('#modalEditPembayaran #bayarpaypp').val();
        let tanggal = $('#modalEditPembayaran #datepaypp').val();
        let paymentmethod = $('#modalEditPembayaran #paymentpp').val();
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
            url: '{{ route("mmapenerimaanpiutang.updatePayment") }}',
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
                    $('#modalEditPembayaran').modal('hide');
                    table_history_penerimaan.ajax.reload();
                    messageSuccess('Berhasil', 'Pembayaran berhasil di perbarui !');
                }
                else {
                    messageFailed('Gagal', 'Gagal melakukan update pembayaran : ' + resp.message);
                }
            },
            error: function (err) {
                messageWarning('Error', 'Terjadi kesalahan : '+ err.message);
            },
            complete: function () {
                loadingHide();
            }
        });

        console.log('Update Payment: '+ salesCompId + ' / ' + paymentDetailId);
    }

</script>
@endsection
