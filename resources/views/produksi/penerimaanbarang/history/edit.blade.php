@extends('main')

@section('content')
@include('produksi.penerimaanbarang.history.modal-edit')

<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Edit Data Penerimaan Produksi </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Produksi</span>
            / <a href="{{route('penerimaan.index')}}"><span>History Penerimaan Produksi</span></a>
            / <span class="text-primary" style="font-weight: bold;"> Edit Data Penerimaan Produksi</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Edit Data Penerimaan Produksi </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('penerimaan.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <div class="card-block">
                        <section>
                            <input type="hidden" id="idPO" value="{{ $id }}">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered display nowrap w-100" cellspacing="0" id="table_listpenerimaan">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th width="1%">No</th>
                                            <th>Tanggal Penerimaan</th>
                                            <th width="40%">Item</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" id="btn_simpan" type="button">Simpan</button>
                        <a href="{{route('penerimaan.index')}}" class="btn btn-secondary">Kembali</a>
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
        tableListPenerimaan();

        $('.btn_updatePenerimaan').on('click', function() {
            updatePenerimaan();
        });
    });

    function tableListPenerimaan()
    {
        $('#table_listpenerimaan').DataTable().clear().destroy();
        tbl_listPenerimaan = $('#table_listpenerimaan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            // searching: false,
            // paging: false,
            ajax: {
                url: "{{ route('penerimaan.getListHistoryPO') }}",
                type: "get",
                data: {
                    "idPO": $('#idPO').val()
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'date'},
                {data: 'item'},
                {data: 'unit'},
                {data: 'qty'},
                {data: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']],
            drawCallback: function( settings ) {
                loadingHide();
            }
        });
    }

    function edit(id, detailid)
    {
        $.ajax({
            url: "{{ route('penerimaan.getDetailEditPO') }}",
            type: 'get',
            data: {
                id: id,
                detailid: detailid
            },
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                if (resp.detail.get_prod_code.length > 0) {
                    $('#table_listProdCode').DataTable().clear().destroy();

                    $.each(resp.detail.get_prod_code, function(idx, value) {
                        kode = '<td><input type="text" class="form-control form-control-sm"  style="text-transform: uppercase" name="prodCode[]" value="'+ value.poc_productioncode +'"></td>';
                        qty = '<td class="text-right"><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="'+ value.poc_qty +'"></td>';
                        if (idx == 0) {
                            aksi = '<td class="text-center"><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" title="Tambah Kode Produksi" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td>';
                        }
                        else {
                            aksi = '<td class="text-center"><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                        }
                        listProdCode = '<tr>' + kode + qty + aksi + '</tr>';
                        $('#modalEditHistory #table_listProdCode').append(listProdCode);
                    });
                }
                else {
                    $('#table_listProdCode').DataTable().clear().destroy();
                    tbl_listProdCode = $('#table_listProdCode').DataTable({
                        searching: false,
                        paging: false,
                    });
                }

                $('#modalEditHistory .ird_itemreceipt').val(resp.detail.ird_itemreceipt);
                $('#modalEditHistory .ird_detailid').val(resp.detail.ird_detailid);
                $('#modalEditHistory #itemName').val(resp.detail.get_item.i_name);
                $('#modalEditHistory #itemUnit').val(resp.detail.get_unit.u_name);
                $('#modalEditHistory #itemQty').val(resp.detail.ird_qty);

                getEventsReady();
                $('#modalEditHistory').modal('show');
            },
            error: function(err) {
                messageWarning('Error', 'Hubungi pengembang, error : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }

    function getEventsReady() {
        $('.btnAddProdCode').off();
        // event to add more row to insert production-code
        $('.btnAddProdCode').on('click', function() {
            prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
            qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input></td>';
            action = '<td class="text-center"><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
            $('#modalEditHistory #table_listProdCode').append(listProdCode);
            getEventsReady();
        });
        // event to remove an prod-code from table_listcodeprod
        $('.btnRemoveProdCode').on('click', function() {
            idxProdCode = $('.btnRemoveProdCode').index(this);
            $(this).parents('tr').remove();
        });
        // update total qty without production-code
        $('.qtyProdCode').on('keyup', function() {
            idxProdCode = $('.qtyProdCode').index(this);
            calculateProdCodeQty();
        });
        // inputmask-digits
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

    function updatePenerimaan() {
        data = $('.myForm').serialize();
        $.ajax({
            url: '{{ Route("penerimaan.updateDetailPO") }}',
            type: 'post',
            data: data,
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                if (resp.status == 'success') {
                    messageSuccess('Berhasil', 'Penerimaan berhasil di-update !');
                    tbl_listPenerimaan.ajax.reload();
                    $('#modalEditHistory').modal('hide');
                }
                else {
                    messageWarning('Gagal', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Hubungi pengembang, error : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }

    // check production code qty each item
    function calculateProdCodeQty()
    {
        let QtyH = parseInt($('#modalEditHistory #itemQty').val());
        let qtyWithProdCode = getQtyWithProdCode();
        let restQty = QtyH - qtyWithProdCode;

        if (restQty < 0) {
            $(':focus').val(0);
            qtyWithProdCode = getQtyWithProdCode();
            restQty = QtyH - qtyWithProdCode;
            messageWarning('Perhatian', 'Jumlah item untuk penetapan kode produksi tidak boleh melebihi jumlah item yang ada !');
        }
    }
    function getQtyWithProdCode()
    {
        qtyWithProdCode = 0;
        $.each($('#modalEditHistory .qtyProdCode'), function (key, val) {
            qtyWithProdCode += parseInt($(this).val());
        });
        return qtyWithProdCode;
    }

</script>

@endsection
