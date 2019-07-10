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
    @media (min-width: 992px) {
        .modal-xl {
            max-width: 1200px !important;
        }
    }
    .btn-xs {
        padding: 0.20rem 0.4rem;
        font-size: 0.675rem;
        line-height: 1.3;
        border-radius: 0.2rem;
    }
    .select2-container--bootstrap.select2-container--open {
        width: auto !important;
    }
</style>
@stop
@section('content')

    <!-- Modal Terima Order -->
    @include('marketing.penjualanpusat.terimaorder.modal')
    @include('marketing.penjualanpusat.distribusi.modaldistribusi')
    @include('marketing.penjualanpusat.terimaorder.modal-process')
    @include('marketing.penjualanpusat.targetrealisasi.modal')
    @include('marketing.penjualanpusat.penerimaanpiutang.modalnota')
    @include('marketing.penjualanpusat.penerimaanpiutang.modalpayment')

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
                            <a href="" class="nav-link" data-target="#distribusipenjualan" aria-controls="distribusipenjualan"
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
                        <li class="nav-item" id="tab4">
                            <a href="" class="nav-link" data-target="#terimapiutang" aria-controls="terimapiutang"
                               data-toggle="tab" role="tab">Penerimaan Piutang</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        @include('marketing.penjualanpusat.terimaorder.index')
                        @include('marketing.penjualanpusat.distribusi.index')
                        @include('marketing.penjualanpusat.returnpenjualan.index')
                        @include('marketing.penjualanpusat.targetrealisasi.index')
                        @include('marketing.penjualanpusat.penerimaanpiutang.index')

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
    var table_distribusi;
    $(document).ready(function () {
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
        tableDistribusi();
    });

    function targetReal() {
        setTimeout(function () {
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
        }, 750);
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

<!-- script for return sales agent -->
<script type="text/javascript">
    $(document).ready(function() {
        returnagen();
    })

    function returnagen() {
        setTimeout(function () {
            tb_return = $('#table_return').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('returnpenjualanagen.index') }}",
                    type: "get"
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'tanggal'},
                    {data: 'r_nota'},
                    {data: 'r_reff'},
                    {data: 'r_code'},
                    {data: 'type'},
                    {data: 'agen'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }, 750);
    }

    function deleteReturn(id) {
        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apa anda yakin akan menghapus data ini ?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        loadingShow();
                        $.ajax({
                            url: baseUrl+ "/marketing/penjualanpusat/returnpenjualan/hapus/" + id,
                            type: 'post',
                            success: function(resp) {
                                loadingHide();
                                if (resp.status == 'berhasil') {
                                    messageSuccess('Berhasil', 'Data berhasil dihapus !');
                                }
                                else {
                                    messageWarning('Perhatian', resp.message);
                                }
                                tb_return.ajax.reload();
                            },
                            error: function(e) {
                                loadingHide();
                                messageWarning('Gagal', 'Terjad kesalahan : '+ e.message);
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
  		setTimeout(function () {
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
        }, 500);
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
                    let priceP = '<td><span class="unitprice-'+val.pod_item+'"> '+ convertToRupiah(val.pod_price.toString().replace(".00", "")) +'</span><input type="hidden" name="hargasatuan[]" class="hargasatuan" value="'+val.pod_price.toString().replace(".00", "")+'"></td>';
                    let priceN = '<td><span class="unitprice-'+val.pod_item+'"> '+ convertToRupiah(val.pod_price.toString().replace(".00", "")) +'</span></td>';
                    let subTotalP = '<td><span class="subtotalprice-'+val.pod_item+'"> '+ convertToRupiah(hargasubtotal.toString().replace(".00", "")) +'</span><input type="hidden" name="hargasubtotal[]" class="hargasubtotal hargasubtotal-'+val.pod_item+'" value="'+hargasubtotal.toString().replace(".00", "")+'"></td>';
                    let subTotalN = '<td><span class="subtotalprice-'+val.pod_item+'"> '+ convertToRupiah(hargasubtotal.toString().replace(".00", "")) +'</span>';
                    let aksiP = '<td><button type="button" class="btn btn-sm btn-danger" onclick="changeStatus('+val.pod_productorder+', '+val.pod_detailid+', \'N\')"><i class="fa fa-close"></i></button></td>';
                    let aksiN = '<td><button type="button" class="btn btn-sm btn-success" onclick="changeStatus('+val.pod_productorder+', '+val.pod_detailid+', \'P\')"><i class="fa fa-check"></i></button></td>';
                    let diskon = '<td><input type="text" class="form-control form-control-sm diskon diskon-rupiah text-right" name="diskon[]" onkeyup="setTotalTransaksi()"></td>';
                    if (val.pod_isapproved == 'P'){
                        let item = '<td>'+ val.get_item.i_code + ' - ' + val.get_item.i_name + itemIdP +'</td>';
                        let unit = '<td>'+ selectUnitP +'</td>';
                        appendItem = '<tr>'+ item + stok + qtyP + unit + priceP + diskon + subTotalP + aksiP +'</tr>';
                    } else if (val.pod_isapproved == 'N') {
                        let item = '<td>'+ val.get_item.i_code + ' - ' + val.get_item.i_name + itemIdN +'</td>';
                        let unit = '<td>'+ selectUnitN +'</td>';
                        appendItem = '<tr class="tolak">'+ item + stok + qtyN + unit + priceN + diskon + subTotalN + aksiN +'</tr>';
                    }
                    // append data to table-row

                    $('#table_modalPr > tbody:last-child').append(appendItem);
                    // set unitModalPr selected item
                    $('.unitModalPr option[value='+ val.pod_unit +']').attr('selected', 'selected');
                    $('.diskon').inputmask("currency", {
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

                });
                // show modal
                $('#modalProcessTOP').modal('show');
                getFieldsReady();
                setTotalTransaksi();
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
        setTotalTransaksi();
    }

    function getFieldsReady()
    {
        $('.qtyModalPr').off();
        $('.unitModalPr').off();
        // set event handler for qty
        $('.qtyModalPr').on('keyup', function() {
            idxItem = $('.qtyModalPr').index(this);
            validateQty();
            hitungTotal();
            changePrice();
        });

        $('.diskon').on('keyup', function() {
            idxItem = $('.diskon').index(this);
            hitungTotal();
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
                    $('.hargasatuan').eq(idxItem).val('novalue');
                    if (response.pesan == 'harga tidak ditemukan'){
                        $('.unitprice-'+item).html("Tidak ditemukan");
                        $('.subtotalprice-'+item).html("Tidak ditemukan");
                        $('.hargasubtotal-'+item).val(0);
                    }
                } else {
                    $('.hargasatuan').eq(idxItem).val(response);
                    $('.unitprice-'+item).html(convertToRupiah(response));
                    var total = parseInt(response) * parseInt(kuantitas);
                    if (isNaN(total)){
                        total = 0;
                    }
                    $('.subtotalprice-'+item).html(convertToRupiah(total));
                    $('.hargasubtotal-'+item).val(total);
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
        var diskon = parseInt(convertToAngka($('.diskon').eq(idxItem).val()));
        var item = $('.idItem').eq(idxItem).val();

        var subTotal = (hargasatuan - diskon) * kuantitas;
        if (isNaN(subTotal)){
            subTotal = 0;
        }
        $('.subtotalprice-'+item).html(convertToRupiah(subTotal));
        $('.hargasubtotal-'+item).val(subTotal);
        setTotalTransaksi();
    }

    function confirmProcessTOP()
    {
        loadingShow();
        id = $('#idModalPr').val();
        data = $('#formModalPr').serialize();
        var order = $('.qtyModalPr').serializeArray();
        for (let i = 0; i < order.length; i++) {
            if (order[i].value == 0){
                loadingHide();
                messageWarning('Peringatan', "Disable jumlah order = 0 !!");
                i = order.length + 1;
                return false;
            }
        }
        let harga = $('.hargasatuan').serializeArray();
        for (let i = 0; i < harga.length; i++) {
            if (harga[i].value === "novalue"){
                loadingHide();
                messageWarning('Peringatan', "ada barang yang tidak memiliki harga");
                i = harga.length + 1;
                return false;
            }
        }
        $.ajax({
            url: baseUrl + "/marketing/penjualanpusat/confirm-process-top/" + id,
            type: "post",
            data: data,
            success: function(response) {
                loadingHide();
                if (response.status == 'success'){
                    messageSuccess("Berhasil", "Data berhasil disimpan");
                    table_top.ajax.reload();
                    table_distribusi.ajax.reload();
                    $('#modalProcessTOP').modal('hide');
                } else if (response.status == 'gagal'){
                    messageFailed("Gagal", "data gagal disimpan");
                    console.log(response.message);
                }
            },
            error: function(xhr, status, error) {
                loadingHide();
				let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        })
    }

    function setTotalTransaksi(){
        let allprice = $('.hargasubtotal').serializeArray();
        let alldiskon = $('.diskon').serializeArray();
        var total = 0;

        for (let i = 0; i < allprice.length; i++) {
            if (alldiskon[i].value == ""){
                alldiskon[i].value = "Rp. 0";
            }
            total = total + parseInt(allprice[i].value);
        }
        $('#totalModalPr').val(convertToRupiah(total));
    }

    // Distribusi penjualan

    function tableDistribusi() {
        let status = $('#status_distribusi').val();
        setTimeout(function () {
            $('#table_distribusi').dataTable().fnDestroy();
            table_distribusi = $('#table_distribusi').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                "bAutoWidth": false,
                ajax: {
                    url: baseUrl + '/marketing/penjualanpusat/get-table-distribusi',
                    type: "get",
                    data: {
                        "status": status,
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
        }, 250);
    }

    function distribusiPenjualan(id) {
        loadingShow();
        $.ajax({
            type: 'get',
            data: {id},
            dataType: 'JSON',
            url: "{{ route('penjualanpusat.getDetailSend') }}",
            success : function(response){
                loadingHide();
                $('#dateModalSend').val(response.dateFormated);
                $('#agentModalSend').val(response.get_agent.c_name);
                $('#notaModalSend').val(response.po_nota);
                $('#totalModalSend').val(parseFloat(response.total));

                $('#table_senddistribution tbody').empty();
                $.each(response.get_p_o_dt, function (key, val) {
                    let item = '<td>'+ val.get_item.i_code + ' - ' + val.get_item.i_name +'</td>';
                    let qty = '<td class="digits">'+ val.pod_qty +'</td>';
                    let unit = '<td>'+ val.get_unit.u_name +'</td>';
                    let price = '<td class="rupiah">'+ parseFloat(val.pod_price) +'</td>';
                    let diskon = '<td class="rupiah">'+ parseFloat(val.pod_discvalue) +'</td>';
                    let subTotal = '<td class="rupiah">'+ parseFloat(val.pod_totalprice) +'</td>';
                    let aksi = '<td class="text-center"><button type="button" onclick="addCodeProd('+response.po_id+', '+val.pod_item+', \''+val.get_item.i_name+'\')" class="btn btn-info btn-xs btnAddProdCode"><i class="fa fa-plus"></i> Kode Produksi</button></td>';
                    appendItem = '<tr>'+ item + qty + unit + price + diskon + subTotal + aksi +'</tr>';
                    // append data to table-row
                    $('#table_senddistribution > tbody:last-child').append(appendItem);
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

                //ekspedisi
                $('#ekspedisi').empty();
                $("#ekspedisi").append('<option value="" selected="" disabled="">=== Pilih Ekspedisi ===</option>');
                $.each(response.ekspedisi, function (key, val) {
                    $("#ekspedisi").append('<option value="' + val.e_id + '">' + val.e_name + '</option>');
                });
                $('#ekspedisi').focus();
                $('#ekspedisi').select2('open');
            },
            error: function(xhr, status, error) {
                loadingHide();
                let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        });
    }

    $('#ekspedisi').on('change', function () {
        let id = $('#ekspedisi').val();
        axios.get('{{ route("penjualanpusat.getProdukEkspedisi") }}', {
            params:{
                "id": id
            }
        }).then(function (response) {
            $('#jenis_ekspedisi').empty();
            $("#jenis_ekspedisi").append('<option value="" selected="" disabled="">=== Pilih Jenis ===</option>');
            $.each(response.data, function (key, val) {
                $("#jenis_ekspedisi").append('<option value="' + val.ed_detailid + '">' + val.ed_product + '</option>');
            });
            $('#jenis_ekspedisi').focus();
            $('#jenis_ekspedisi').select2('open');
        }).catch(function (error) {
            alert('error');
        })
    });

    $('#jenis_ekspedisi').on('change select2:select', function() {
        $('#nama_kurir').focus();
    });

    function addCodeProd(id, item, nama){
        idxProdCode = $('.btnAddProdCode').index(this);
        $('.text-item').html(nama);
        $('#inputkodeproduksi').removeAttr('readonly');
        $('#iditem_modaldt').val(item);
        $('#inputqtyproduksi').removeAttr('readonly');
        $('#table_prosesordercode').dataTable().fnDestroy();
        tb_listcodeprosesorder = $('#table_prosesordercode').DataTable({
            responsive: true,
            serverSide: true,
            paging: false,
            searching: false,
            ordering: false,
            ajax: {
                url: "{{ route('keloladataorder.getdetailcodeorder') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "item": item
                }
            },
            columns: [
                {data: 'poc_code'},
                {data: 'poc_qty'},
                {data: 'aksi'},
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function pressCode(e) {
        if (e.keyCode == 13){
            addCodetoTable();
        }
    }

    function addCodetoTable(){
        let qty = $('#inputqtyproduksi').val();
        let kode = $.trim($('#inputkodeproduksi').val());
        let nota = $('#notaModalSend').val();
        let item = $('#iditem_modaldt').val();

        if (isNaN(qty) || qty == '' || qty == null){
            qty = 1;
        }
        if (kode == '' || kode == null) {
            messageWarning('Perhatian', 'Silahkan masukkan kode produksi terlebih dahulu !');
            return 0;
        }

        axios.get('{{ route("keloladataorder.setKode") }}', {
            params:{
                "qty": qty,
                "kode": kode,
                "nota": nota,
                "item": item
            }
        }).then(function (response) {
            if (response.data.status == 'success'){
                messageSuccess("Berhasil", "Kode berhasil ditambahkan");
                $('#inputkodeproduksi').val("");
                $('#inputqtyproduksi').val("");
                tb_listcodeprosesorder.ajax.reload();
            } else if (response.data.status == 'gagal'){
                messageWarning("Gagal", response.data.message);
            }
        }).catch(function (error) {
            alert('error');
        })
    }

    function removeCodeOrder(id, item, kode) {
        axios.get('{{ route("keloladataorder.removeKode") }}', {
            params:{
                "id": id,
                "item": item,
                "kode": kode
            }
        }).then(function (response) {
            if (response.data.status == 'success'){
                messageSuccess("Berhasil", "Kode berhasil dihapus");
                tb_listcodeprosesorder.ajax.reload();
            } else {
                messageWarning("Gagal", "Kode gagal dihapus");
            }
        }).catch(function (error) {

        })
    }

    // function hapus(id){
    //     $.ajax({
    //        type: 'get',
    //        data: {id},
    //        dataType: 'json',
    //        url: baseUrl + '/marketing/penjualanpusat/returnpenjualan/hapus',
    //        success : function(response){
    //            if
    //        }
    //     });
    // }

    function kirim() {
        let nota = $('#notaModalSend').val();
        let ekspedisi = $('#ekspedisi').val();
        let produk = $('#jenis_ekspedisi').val();
        let nama = $('#nama_kurir').val();
        let tlp = $('#tlp_kurir').val();
        let resi = $('#resi_kurir').val();
        let harga = $('#biaya_kurir').val();

        loadingShow();
        axios.post('{{ route("penjualanpusat.sendOrder") }}', {
            'nota': nota,
            "ekspedisi": ekspedisi,
            "produk": produk,
            "nama": nama,
            "tlp": tlp,
            "resi": resi,
            "harga": harga
        }).then(function (response) {
            loadingHide();
            if (response.data.status == 'success'){
                messageSuccess("Berhasil", "Data berhasil disimpan");
                $('#modal_distribusi').modal('hide');
                table_distribusi.ajax.reload();
            } else if (response.data.status == 'gagal'){
                messageFailed("Gagal", response.data.message);
            }
        }).catch(function (error) {
            loadingHide();
            alert('error');
        })
    }
    // Penerimaan Piutang ->
    var tb_piutang, tb_getNota;
    $(document).ready(function(){
        $('#table_piutang').DataTable({
            searching: false,
        });
        $('#table_getNota').DataTable();
        $('#nota_s').css('text-transform', 'uppercase');
        getProvinsi();
    });

    function getNota(){
        $('#modal_nota').modal('show');
        // $('#provId').select2('open');
    }

    function getProvinsi() {
        $.ajax({
            url: "{{url('/get-provinsi')}}",
            type: "get",
            success:function(resp) {
                $('#provId').empty()
                $('#provId').append('<option value="" selected disabled>Pilih Provinsi</option>');
                $.each(resp.data, function(key, val){
                    $('#provId').append('<option value="'+val.wp_id+'">'+val.wp_name+'</option>');
                });
            }
        })
    }

    $('#provId').on('change', function(){
        let id = $('#provId').val();
        getCity(id);
    });

    function getCity(id) {
        $.ajax({
            url:"{{url('/get-city')}}"+"/"+id,
            type: "get",
            success:function(resp) {
                $('#kabId').empty()
                $('#kabId').append('<option value="" selected disabled>Pilih Kabupaten / Kota</option>');
                $.each(resp.data, function(key, val){
                    $('#kabId').append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
                });
                $('#kabId').select2('open');
            }
        })
    }

    $('#kabId').on('change', function(){
        let id = $('#kabId').val();
        getAgen(id);
    });

    function getAgen(id) {
        $.ajax({
            url: "{{url('/marketing/penjualanpusat/penerimaanpiutang/get-agen')}}"+"/"+id,
            type: "get",
            success:function(resp) {
                // console.log(resp);
                $('#agen').empty();
                $('#agen').append('<option value="" selected disabled>Pilih Agen</option>');
                $.each(resp.data, function(key, val){
                    $('#agen').append('<option value="'+val.a_code+'">'+val.a_name+'</option>');
                });
                $('#agen').select2('open');
            }
        })
    }

    $('#agen').on('change', function(){
        var code = $('#agen').val();
        $('#table_getNota').dataTable().fnDestroy();
        tb_piutang = $('#table_getNota').DataTable({
            searching: false,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{url('/marketing/penjualanpusat/penerimaanpiutang/get-nota-agen')}}"+"/"+code,
                type: "get",
            },
            columns: [
                {data: 'sc_nota'},
                {data: 'sc_datetop'},
                {data: 'sisa'},
                {data: 'action', className: 'text-center'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    });

    function get_list(nota){
        // var nota = subString(nota);
        $('#nota_s').val(nota);
        $('#table_piutang').dataTable().fnDestroy();
        tb_piutang = $('#table_piutang').DataTable({
            searching: false,
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{url('/marketing/penjualanpusat/penerimaanpiutang/get-list?')}}"+"nota="+nota,
                type: "get",
            },
            columns: [
                // {data: 'DT_RowIndex'},
                {data: 'sc_nota'},
                {data: 'deadline'},
                {data: 'sisa', className: 'text-right'},
                {data: 'bayar', className: 'text-center'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
        $('#modal_nota').modal('hide');
    }

    function toPayment(nota) {
        $('#sc_nota').val(nota);
        $('#modal_payment').modal('show');
    }

    $('#nominal').keypress(function(e){
        if(e.which == 13) {
          e.preventDefault();
          savePayment();
        }
    });

    function savePayment() {
        alert($('#nominal').val());
    }
</script>
@endsection
