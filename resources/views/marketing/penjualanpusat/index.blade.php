@extends('main')
@section('extra_style')
    <style>
        @media (min-width: 992px) {
            .modal-lg .modal-xl {
                max-width: 1000px !important;
            }
        }

        .tolak {
            background-color: #d1d1d1;
            color: #8a8a8a;
        }
        #table_modalPr tr.tolak:hover{
            background-color: #eaeaea;
        }
    </style>
@stop
@section('content')

    <!-- Modal Terima Order -->
    @include('marketing.penjualanpusat.terimaorder.modal')
    @include('marketing.penjualanpusat.terimaorder.modal-process')
    @include('marketing.penjualanpusat.targetrealisasi.modal')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Manajemen Penjualan Pusat </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> /
                <span class="text-primary" style="font-weight: bold;">Manajemen Penjualan Pusat</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12" id="choosetab">

                    <ul class="nav nav-pills mb-3">
                        <li class="nav-item" id="tab1">
                            <a href="" class="nav-link active" data-target="#terimaorder" aria-controls="terimaorder"
                               data-toggle="tab" role="tab">Terima Order Penjualan</a>
                        </li>
                        <li class="nav-item" id="tab2">
                            <a href="" class="nav-link" data-target="#promosi_tahunan" aria-controls="promosi_tahunan"
                               data-toggle="tab" role="tab">Distribusi Penjualan</a>
                        </li>
                        <li class="nav-item" id="tab3">
                            <a href="" class="nav-link" data-target="#returnpenjualan" aria-controls="returnpenjualan"
                               data-toggle="tab" role="tab">Return Penjualan Agen </a>
                        </li>
                        <li class="nav-item" id="tab4">
                            <a href="" class="nav-link" data-target="#targetrealisasi" aria-controls="targetrealisasi"
                               data-toggle="tab" role="tab">Target & Realisasi Penjualan</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        @include('marketing.penjualanpusat.terimaorder.index')
                        @include('marketing.penjualanpusat.returnpenjualan.index')
                        @include('marketing.penjualanpusat.targetrealisasi.index')

                    </div>

                </div>

            </div>

        </section>

    </article>
    <!-- Modal -->
    <div id="edittarget" class="modal fade animated fadeIn" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-gradient-info">
                    <h4 class="modal-title">Edit Target</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Nama Barang : </label>
                            <span id="edit_namabarang">Agarillus</span>
                        </div>
                        <div class="col-md-12">
                            <label>Periode : </label>
                            <span id="edit_periode">Maret 2019</span>
                        </div>
                        <div class="col-md-12">
                            <label>Cabang : </label>
                            <span id="edit_cabang">Cabang</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label" for="edit_targetawal">Target Awal</label>
                            <input type="number" class="form-control" id="edit_targetawal" readonly>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label col-12" style="margin-left: -10px;"
                                   for="edit_satuanawal">Satuan</label>
                            <select class="form-control form-control-sm col-12 select2 satuan" style="width: 100%;"
                                    id="edit_satuanawal"></select>
                        </div>
                    </div>
                    <input type="hidden" class="edit_id">
                    <input type="hidden" class="edit_dt">
                    <form class="form-group row" id="form_updatetarget">
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label" for="edit_targetbaru">Target Baru</label>
                            <input type="number" class="form-control" id="edit_targetbaru" name="targetbaru">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label col-12" style="margin-left: -10px;"
                                   for="edit_satuanbaru">Satuan</label>
                            <select class="form-control form-control-sm col-12 select2 satuan" name="satuantarget"
                                    style="width: 100%;" id="edit_satuanbaru"></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateTarget()">Update</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('extra_script')
<script type="text/javascript">

    $(document).ready(function () {

        var table_bar = $('#table_tahunan').DataTable();
        var table_pus = $('#table_bulanan').DataTable();
        var table_par = $('#table_targetrealisasi').DataTable();

        $("#cari_namabarang").autocomplete({
            source: function (request, response) {
                var id = [''];
                $.ajax({
                    url: "{{ url('marketing/penjualanpusat/targetrealisasi/cari-barang') }}",
                    data: {
                        term: $("#cari_namabarang").val(),
                        idItem: id
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function (event, data) {
                $('#cari_idbarang').val(data.item.id);
            }
        });

        $(document).on('click', '.btn-rejected', function () {
            var ini = $(this);
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            $.toast({
                                heading: 'Information',
                                text: 'Promosi Ditolak.',
                                bgColor: '#0984e3',
                                textColor: 'white',
                                loaderBg: '#fdcb6e',
                                icon: 'info'
                            })
                            ini.parents('.btn-group').html('<button class="btn btn-danger btn-sm btn-cancel-reject">Batalkan Penelokan</button>');
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
        });

        $("#datepicker").datepicker({
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months"
        });

        $(document).on('click', '.btn-cancel-reject', function () {
            $(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>' +
                '<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
        })

        $(document).on('click', '.btn-approval', function () {
            $.toast({
                heading: 'Information',
                text: 'Promosi Diterima.',
                bgColor: '#0984e3',
                textColor: 'white',
                loaderBg: '#fdcb6e',
                icon: 'info'
            })
            $(this).parents('.btn-group').html('<button class="btn btn-primary btn-sm btn-cancel-approve">Batalkan Penerimaan</button>')
        })

        $(document).on('click', '.btn-cancel-approve', function () {
            $(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>' +
                '<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
        })
        targetReal();
    });

    function targetReal() {
        tb_target = $('#table_target').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ route('targetReal.list') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [
                {data: 'st_periode'},
                {data: 'c_name'},
                {data: 'i_name'},
                {data: 'target'},
                {data: 'realisasi'},
                {data: 'status'},
                {data: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function editTarget(st_id, dt_id) {
        loadingShow();
        $.ajax({
            data: {id: '"' + st_id + '"', dt_id: '"' + dt_id + '"'},
            type: "get",
            url: '{{ url("marketing/penjualanpusat/targetrealisasi/get-target") }}',
            success: function (response) {
                $('.edit_dt').val(dt_id);
                $('.edit_id').val(st_id);
                $("#edit_satuanawal").select2('destroy');
                $("#edit_satuanbaru").select2('destroy');
                $('#edit_satuanawal').find('option').remove();
                $('#edit_satuanbaru').find('option').remove();
                var data = response.data;
                var satuan = response.satuan;
                $('#edit_namabarang').html(data.i_name);
                $('#edit_periode').html(data.periode);
                $('#edit_cabang').html(data.c_name);
                $('#edit_targetawal').val(data.std_qty);
                $("#edit_satuanawal").select2({
                    data: satuan
                })
                $("#edit_satuanbaru").select2({
                    data: satuan
                })
                $('#edit_satuanawal').val(data.std_unit);
                $('#edit_satuanawal').trigger('change');
                loadingHide();
                $('#edittarget').modal('show');
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
        })
    }

    function updateTarget() {
        var dt = $('.edit_dt').val();
        var id = $('.edit_id').val();
        $.ajax({
            data: $('#form_updatetarget').serialize(),
            type: "post",
            url: '{{ url("marketing/penjualanpusat/targetrealisasi/updateTarget/") }}/' + id + '/' + dt,
            success: function (response) {
                if (response.status == 'sukses') {
                    messageSuccess('Berhasil', 'Data berhasil diperbarui');
                    $('#edittarget').modal('hide');
                    tb_target.ajax.reload();
                } else {
                    messageSuccess('Gagal', 'Silahkan coba beberapa saat lagi');
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
        })
    }

    function cariTarget() {
        var barang = $('#cari_idbarang').val();
        var periode = $('.cari_periode').val();
        tb_target.destroy();
        tb_target = $('#table_target').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: '{{ url("marketing/penjualanpusat/targetrealisasi/get-periode") }}',
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    barang: barang,
                    periode: periode
                }
            },
            columns: [
                {data: 'st_periode'},
                {data: 'c_name'},
                {data: 'i_name'},
                {data: 'target'},
                {data: 'realisasi'},
                {data: 'status'},
                {data: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function setNull(id) {
        $('#'+id).val('');
    }

</script>

<!-- script for 'terima order' -->
<script type="text/javascript">
    var idxItem = null;
    $(document).ready(function() {
        tableTOP();

        $('#modalProcessTOP').on('hidden.bs.modal', function() {
            $('#formModalPr')[0].reset();
        });
        $('#btn_submitProcess').on('click', function() {
            confirmProcessTOP();
        });
    });

    var table_top;
    function tableTOP()
    {
  		$('#table_terimaop').dataTable().fnDestroy();
        table_top = $('#table_terimaop').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: baseUrl + '/marketing/penjualanpusat/get-table-top',
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'tanggal'},
                {data: 'c_name'},
                {data: 'po_nota'},
                {data: 'total'},
                {data: 'action', name: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 100]]
        });
    }

    function getDetailTOP(id)
    {
        loadingShow();
        $.ajax({
            type: 'get',
            data: {id},
            dataType: 'JSON',
            url: "{{ route('penjualanpusat.getDetailTOP') }}",
            success : function(response){
                loadingHide();
                $('#dateModalDt').val(response.dateFormated);
                $('#agentModalDt').val(response.get_agent.c_name);
                $('#notaModalDt').val(response.po_nota);
                $('#totalModalDt').val(parseFloat(response.total));

                $('#table_modalDt tbody').empty();
                $.each(response.get_p_o_dt, function (key, val) {
                    let item = '<td>'+ val.get_item.i_code + ' - ' + val.get_item.i_name +'</td>';
                    let qty = '<td class="digits">'+ val.pod_qty +'</td>';
                    let unit = '<td>'+ val.get_unit.u_name +'</td>';
                    let price = '<td class="rupiah">'+ parseFloat(val.pod_price) +'</td>';
                    let subTotal = '<td class="rupiah">'+ parseFloat(val.pod_totalprice) +'</td>';
                    appendItem = '<tr>'+ item + qty + unit + price + subTotal +'</tr>';
                    // append data to table-row
                    $('#table_modalDt > tbody:last-child').append(appendItem);
                });
                //mask money
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
                //mask digits
                $('.digits').inputmask("currency", {
                    radixPoint: ",",
                    groupSeparator: ".",
                    digits: 0,
                    autoGroup: true,
                    prefix: '', //Space after $, this will not truncate the first character.
                    rightAlign: true,
                    autoUnmask: true,
                    nullable: false,
                    // unmaskAsNumber: true,
                });
            },
            error: function(xhr, status, error) {
                loadingHide();
				let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        });
    }

    function processTOP(id)
    {
        loadingShow();
        $.ajax({
            url: "{{route('penjualanpusat.getProsesTOP')}}",
            type: 'get',
            data: {id},
            dataType: 'JSON',
            success : function(response){
                // console.log(response.stockItem[0]);
                loadingHide();
                $('#idModalPr').val(response.po_id);
                $('#dateModalPr').val(response.dateFormated);
                $('#agentModalPr').val(response.get_agent.c_name);
                $('#idAgentModalPr').val(response.get_agent.c_id);
                $('#notaModalPr').val(response.po_nota);
                $('#totalModalPr').val(parseFloat(response.total));

                $('#table_modalPr tbody').empty();
                $.each(response.get_p_o_dt, function (key, val) {

                    let itemIdP = '<input type="hidden" class="idItem" name="itemId[]" value="'+ val.pod_item +'">';
                    let itemIdN = '<input type="hidden" value="'+ val.pod_item +'">';
                    let permintaan = parseInt(val.pod_qty);
                    let hargasatuan = val.pod_price;
                    let hargasubtotal = val.pod_totalprice;
                    if (parseInt(response.stockTable[key]) < permintaan){
                        permintaan = parseInt(response.stockTable[key]);
                        hargasubtotal = permintaan * parseInt(hargasatuan);
                    }
                    let qtyStock = '<input type="hidden" class="qtyStock" value="'+ response.stockItem[key] +'">';
                    let stok = '<td>' + response.stockTable[key] + '</td>';
                    let qtyP = '<td><input type="text" name="qty[]" class="form-control form-control-sm digits qtyModalPr" value="'+ permintaan +'">'+ qtyStock +'</td>';
                    let qtyN = '<td><input readonly type="text" class="form-control form-control-sm digits" value="'+ permintaan +'">'+ qtyStock +'</td>';

                    let unit1 = (val.get_item.get_unit1 != null) ? '<option value="'+ val.get_item.get_unit1.u_id +'" data-unitcmp="'+ parseInt(val.get_item.i_unitcompare1) +'">'+ val.get_item.get_unit1.u_name +'</option>' : '';
                    let unit2 = (val.get_item.get_unit2 != null) ? '<option value="'+ val.get_item.get_unit2.u_id +'" data-unitcmp="'+ parseInt(val.get_item.i_unitcompare2) +'">'+ val.get_item.get_unit2.u_name +'</option>' : '';
                    let unit3 = (val.get_item.get_unit3 != null) ? '<option value="'+ val.get_item.get_unit3.u_id +'" data-unitcmp="'+ parseInt(val.get_item.i_unitcompare3) +'">'+ val.get_item.get_unit3.u_name +'</option>' : '';
                    selectUnitP = '<select name="unit[]" class="form-control form-control-sm select2 unitModalPr"><option value="" disabled>Pilih Barang</option>'+ unit1 + unit2 + unit3 + '</select>';
                    selectUnitN = '<select readonly class="form-control form-control-sm select2"><option value="" disabled>Pilih Barang</option></select>';
                    let price = '<td><span class="unitprice-'+val.pod_item+'"> '+ convertToRupiah(val.pod_price.toString().replace(".00", "")) +'</span><input type="hidden" name="hargasatuan[]" class="hargasatuan" value="'+val.pod_price.toString().replace(".00", "")+'"></td>';
                    let subTotal = '<td><span class="subtotalprice-'+val.pod_item+'"> '+ convertToRupiah(hargasubtotal.toString().replace(".00", "")) +'</span><input type="hidden" name="hargasubtotal[]" class="hargasubtotal" value=""></td>';
                    let aksiP = '<td><button type="button" class="btn btn-sm btn-danger" onclick="changeStatus('+val.pod_productorder+', '+val.pod_detailid+', \'N\')"><i class="fa fa-close"></i></button></td>';
                    let aksiN = '<td><button type="button" class="btn btn-sm btn-success" onclick="changeStatus('+val.pod_productorder+', '+val.pod_detailid+', \'P\')"><i class="fa fa-check"></i></button></td>';
                    if (val.pod_isapproved == 'P'){
                        let item = '<td>'+ val.get_item.i_code + ' - ' + val.get_item.i_name + itemIdP +'</td>';
                        let unit = '<td>'+ selectUnitP +'</td>';
                        appendItem = '<tr>'+ item + stok + qtyP + unit + price + subTotal + aksiP +'</tr>';
                    } else if (val.pod_isapproved == 'N') {
                        let item = '<td>'+ val.get_item.i_code + ' - ' + val.get_item.i_name + itemIdN +'</td>';
                        let unit = '<td>'+ selectUnitN +'</td>';
                        appendItem = '<tr class="tolak">'+ item + stok + qtyN + unit + price + subTotal + aksiN +'</tr>';
                    }
                    // append data to table-row

                    $('#table_modalPr > tbody:last-child').append(appendItem);
                    // set unitModalPr selected item
                    $('.unitModalPr option[value='+ val.pod_unit +']').attr('selected', 'selected');
                });
                // show modal
                $('#modalProcessTOP').modal('show');
                getFieldsReady();
            },
            error: function(xhr, status, error) {
                loadingHide();
				let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        });
    }

    function changeStatus(id, detailid, status) {
        loadingShow();
        $.ajax({
            url: '{{ route("penjualanpusat.gantistatus") }}',
            type: "get",
            data: {id: id, detailid: detailid, status: status},
            success: function(response) {
                loadingHide();
                processTOP(id);
            },
            error: function(xhr, status, error) {
                loadingHide();
                let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        })
    }

    function getFieldsReady()
    {
        $('.qtyModalPr').off();
        $('.unitModalPr').off();
        // set event handler for qty
        $('.qtyModalPr').on('keyup', function() {
            idxItem = $('.qtyModalPr').index(this);
            validateQty();
            hitungTotal()
        });
        // set event handler for unit
        $('.unitModalPr').on('change', function() {
            idxItem = $('.unitModalPr').index(this);
            validateQty();
            changePrice();
            hitungTotal();
        });
        //mask money
        $('.rupiah').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 2,
            precision: 0,
            autoGroup: true,
            prefix: ' Rp ', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });
        //mask digits
        $('.digits').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: '', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });

    }
    // validate qty based on current stock
    function validateQty()
    {
        let stock = 0;
        // get stock-value
        stock = parseFloat($('.qtyStock').eq(idxItem).val());
        unitcmp = $('.unitModalPr').eq(idxItem).find('option:selected').data('unitcmp');
        stock = Math.floor(stock / unitcmp);
        // validate stock
        if ($('.qtyModalPr').eq(idxItem).val() > stock) {
            $('.qtyModalPr').eq(idxItem).val(stock);
            messageWarning('Perhatian', 'Stock tersedia : ' + stock)
        } else if ($('.qtyModalPr').eq(idxItem).val() < 0 || $('.qtyModalPr').eq(idxItem).val() == '' || isNaN($('.qtyModalPr').eq(idxItem).val())) {
            $('.qtyModalPr').eq(idxItem).val(0);
        }
    }

    function changePrice() {
        var agen = $('#idAgentModalPr').val();
        var satuan = $('.unitModalPr').eq(idxItem).val();
        var item = $('.idItem').eq(idxItem).val();
        var kuantitas = $('.qtyModalPr').eq(idxItem).val();
        $.ajax({
            url: "{{ route('penjualanpusat.gantisatuan') }}",
            type: "get",
            data: {item: item, satuan: satuan, agen: agen, kuantitas: kuantitas},
            success: function(response) {
                loadingHide();
                if (response.status == 'gagal'){
                    $('.hargasatuan').eq(idxItem).val(0);
                    if (response.pesan == 'harga tidak ditemukan'){
                        $('.unitprice-'+item).html("Tidak ditemukan");
                        $('.subtotalprice-'+item).html("Tidak ditemukan");
                    }
                } else {
                    $('.hargasatuan').eq(idxItem).val(response);
                    $('.unitprice-'+item).html(convertToRupiah(response));
                    var total = parseInt(response) * parseInt(kuantitas);
                    $('.subtotalprice-'+item).html(convertToRupiah(total));
                }
                hitungTotal();
            },
            error: function(xhr, status, error) {
                loadingHide();
                let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        })
    }

    function hitungTotal() {
        var hargasatuan = parseInt($('.hargasatuan').eq(idxItem).val());
        var kuantitas = parseInt($('.qtyModalPr').eq(idxItem).val());
        var item = $('.idItem').eq(idxItem).val();

        var subTotal = hargasatuan * kuantitas;
        $('.subtotalprice-'+item).html(convertToRupiah(subTotal));
    }

    function confirmProcessTOP()
    {
        loadingShow();
        id = $('#idModalPr').val();
        data = $('#formModalPr').serialize();

        $.ajax({
            url: baseUrl + "/marketing/penjualanpusat/confirm-process-top/" + id,
            type: "post",
            data: data,
            success: function(response) {
                loadingHide();
                console.log(response);
            },
            error: function(xhr, status, error) {
                loadingHide();
				let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        })
    }


</script>
@endsection
