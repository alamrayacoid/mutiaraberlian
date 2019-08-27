@extends('main')

@section('extra_style')
    <style type="text/css">
        .cls-readonly {
            cursor: not-allowed;
        }
        .txt-readonly {
            background-color: transparent;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')

<form class="formCodeProd">
    <!-- modal-code-production -->
    @include('inventory.distribusibarang.distribusi.modal-code-prod-base')
</form>

<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Persetujuan Order Barang dari Cabang </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Aktivitas Inventory</span>
            / <a href="{{route('distribusibarang.index')}}"><span>Pengelolaan Distribusi Barang</span></a>
            / <span class="text-primary" style="font-weight: bold;"> Persetujuan Order Barang dari Cabang</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Persetujuan Order Barang dari Cabang</h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('distribusibarang.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>

                    <div class="card-block">
                        <form id="formEditDist" autocomplete="off">
                            <section>
                                <div class="row">
                                    <input type="hidden" name="sd_nota" value="{{ $data['stockdist']->sd_nota }}">
                                    <input type="hidden" name="sd_id" value="{{ $data['stockdist']->sd_id }}">
                                    <input type="hidden" name="sd_from" value="{{ $data['stockdist']->sd_from }}">
                                    <input type="hidden" name="sd_destination" value="{{ $data['stockdist']->sd_destination }}">
                                    <input type="hidden" name="sd_date" value="{{ $data['stockdist']->sd_date }}">
                                    <input type="hidden" name="sd_user" value="{{ $data['stockdist']->sd_user }}">
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Cabang Tujuan</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="selectBranch" value="{{ $data['stockdist']->getDestination->c_name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Jasa Ekspedisi</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="expedition" id="expedition" class="form-control form-control-sm select2">
                                                <option value="" disabled selected>Pilih Ekspedisi</option>
                                                @foreach($data['expeditions'] as $idx => $expd)
                                                <option value="{{ $expd->e_id }}">{{ $expd->e_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Jenis Ekspedisi</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="expeditionType" id="expeditionType" class="form-control form-control-sm select2">
                                                <option value="" disabled selected>Pilih Jenis Ekspedisi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Nama Kurir</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" id="courierName" name="courierName" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>No Telp. Kurir</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm hp" id="courierTelp" name="courierTelp" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>No. Resi</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm text-uppercase" id="resi" name="resi" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Biaya Pengiriman</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm rupiah-without-comma" id="shippingCost" name="shippingCost" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Tanggal Pengiriman</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" name="dateSend" class="form-control form-control-sm datepicker" autocomplete="off" id="dateSend" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered data-table" cellspacing="0" id="table_items">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="40%">Kode Barang/Nama Barang</th>
                                                <th>Stock</th>
                                                <th>Jumlah</th>
                                                <th>Satuan</th>
                                                <th>Kode Produksi</th>
                                                <!-- <th>Status</th> -->
                                                <!-- <th width="15%">Aksi</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['stockdist']->getDistributionDt as $key => $value)
                                            <tr class="rowStatus">
                                                <td class="text-right" width="5%">{{ $key + 1 }}</td>
                                                <td width="35%">
                                                    <input type="text" data-counter="0" class="form-control form-control-sm itemsName" value="{{ $value->getItem->i_code }} - {{ $value->getItem->i_name }}" disabled>
                                                    <input type="hidden" name="itemsId[]" value="{{ $value->getItem->i_id }}" class="itemsId">
                                                    <input type="hidden" name="isDeleted[]" value="false" class="isDeleted">
                                                </td>
                                                <td width="15%" class="text-center">
                                                    {{ $value->kondisistock }}
                                                </td>
                                                <td width="15%">
                                                    <input type="text" name="qty[]" class="form-control form-control-sm digits qty" value="{{ $value->sdd_qty }}">
                                                    <input type="hidden" name="qtyUsed[]" class="form-control form-control-sm digits qtyUsed" value="{{ $value->qtyUsed }}">
                                                    <input type="hidden" class="qtyStock1" value="{{ $value->stockUnit1 }}">
                                                    <input type="hidden" class="qtyStock2" value="{{ $value->stockUnit2 }}">
                                                    <input type="hidden" class="qtyStock3" value="{{ $value->stockUnit3 }}">
                                                </td>
                                                <td width="15%">
                                                    <select name="units[]" class="form-control form-control-sm select2 units">
                                                        @if(isset($value->getItem->getUnit1))
                                                        <option value="{{ $value->getItem->getUnit1->u_id }}" data-unitcmp="{{ $value->getItem->i_unitcompare1 }}" @if($value->sdd_unit == $value->getItem->getUnit1->u_id) selected @endif>{{ $value->getItem->getUnit1->u_name }}</option>
                                                        @endif
                                                        @if(isset($value->getItem->getUnit2))
                                                        <option value="{{ $value->getItem->getUnit2->u_id }}" data-unitcmp="{{ $value->getItem->i_unitcompare2 }}" @if($value->sdd_unit == $value->getItem->getUnit2->u_id) selected @endif>{{ $value->getItem->getUnit2->u_name }}</option>
                                                        @endif
                                                        @if(isset($value->getItem->getUnit3))
                                                        <option value="{{ $value->getItem->getUnit3->u_id }}" data-unitcmp="{{ $value->getItem->i_unitcompare3 }}" @if($value->sdd_unit == $value->getItem->getUnit3->u_id) selected @endif>{{ $value->getItem->getUnit3->u_name }}</option>
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="text-center" width="15%">
                                                    <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="counter" value="{{ $key }}">
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </form>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="button" id="btn_simpan">Setujui Order dan Kirim</button>
                        <a href="{{route('distribusibarang.index')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

            </div>

        </div>

    </section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
    var editedItemId = 0;
    var distDt = 0;

    $(document).ready(function() {
        // retrive data directly from controller
        editedItemId = <?php echo $data['stockdist']->sd_id; ?>;
        distDt = {!! $data['stockdist'] !!};
        distDt = distDt.get_distribution_dt;
        // destroy DataTable
        if ( $.fn.DataTable.isDataTable('#table_items') ) {
          $('#table_items').DataTable().destroy();
        }
        // re-initialize DataTable
        $('#table_items').DataTable( {
            "paging":   false,
            "ordering": false,
            "searching": false,
            "info":     false
        });

        // find jenis ekspedisi
        $('#expedition').on('change', function() {
            getExpeditionType(true);
        });
        // get expeditionType
        getExpeditionType(false);
        // set modal production-code
        setModalCodeProdReady();
        // event field items inside table
        getFieldsReady();
        // // add more item in table_items
        // $('.btnAddItem').on('click', function() {
        //     $('#table_items').append(
        //     `<tr class="rowStatus">
        //         <td>
        //             <input type="text" data-counter="0" class="form-control form-control-sm itemsName" style="text-transform:uppercase">
        //             <input type="hidden" name="itemsId[]" value="" class="itemsId">
        //             <input type="hidden" name="isDeleted[]" value="false" class="isDeleted">
        //         </td>
        //         <td>
        //             <input type="text" name="qty[]" class="form-control form-control-sm digits qty" value="">
        //             <input type="hidden" name="qtyUsed[]" class="form-control form-control-sm digits qtyUsed" value="0">
        //             <input type="hidden" class="qtyStock1">
        //             <input type="hidden" class="qtyStock2">
        //             <input type="hidden" class="qtyStock3">
        //         </td>
        //         <td>
        //             <select name="units[]" class="form-control form-control-sm select2 units">
        //                 <option value="" disabled selected>Pilih Satuan</option>
        //             </select>
        //         </td>
        //         <td>
        //             <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button>
        //         </td>
        //         <td>
        //             <button class="btn btn-primary btnAppointItem btn-sm rounded-circle d-none" type="button"><i class="fa fa-power-off" aria-hidden="true"></i></button>
        //             <button class="btn btn-danger btnRemoveItem btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
        //         </td>
        //     </tr>`
        //     // <td>
        //     // <h5><span class="badge badge-primary">Stock belum digunakan</span></h5>
        //     // <input type="hidden" name="status[]" class="status" value="unused">
        //     // </td>
        //     );
        //     // clone modal-code-production and insert new one
        //     $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
        //     // re-declare event for field items inside table
        //     getFieldsReady();
        // });
        // submit-form by btn-submit
        $('#btn_simpan').on('click', function() {
            submitForm();
        });
    });

    function getFieldsReady()
    {
        // remove all event-handler and re-declare it
        // $('.itemsName').off();
        $('.qty').off();
        $('.units').off();
        // $('.btnRemoveItem').off();
        // $('.btnAppointItem').off();
        $('.btnCodeProd').off();
        $('.btnAddProdCode').off();
        $('.btnRemoveProdCode').off();
        $('.qtyProdCode').off();
        // // set event for field-items
        // $('.itemsName').on('click', function () {
        //     idxItem = $('.itemsName').index(this);
        //     $('.itemsName').eq(idxItem).val('');
        //     $('.itemsId').eq(idxItem).val('');
        // });
        // $('.itemsName').on('keyup', function () {
        //     idxItem = $('.itemsName').index(this);
        //     findItemAu();
        // });
        // set event for field-qty
        $('.qty').on('keyup', function() {
            idxItem = $('.qty').index(this);
            validateQty();
        });
        // set event for select-units
        $('.units').on('change', function () {
            idxItem = $('.units').index(this);
            validateQty();
        })
        // // event to remove an item from table_items
        // $('.btnRemoveItem').on('click', function() {
        //     idxItem = $('.btnRemoveItem').index(this);
        //     console.log('rmv: '+ idxItem);
        //     if (idxItem < distDt.length) {
        //         // set row status to disabled
        //         if (idxItem == 0) {
        //             $('.rowStatus').eq(idxItem).find(':input:not(.itemsId, .isDeleted, .btnAddItem, .btnAppointItem, .btnRemoveItem)').attr('disabled', true);
        //         } else {
        //             $('.rowStatus').eq(idxItem).find(':input:not(.itemsId, .isDeleted, .btnAppointItem, .btnRemoveItem)').attr('disabled', true);
        //         }
        //         $('.rowStatus').eq(idxItem).find('.isDeleted').val('true');
        //         $('.rowStatus').eq(idxItem).find('.btnRemoveItem').addClass('d-none');
        //         $('.rowStatus').eq(idxItem).find('.btnAppointItem').removeClass('d-none');
        //         $('.modalCodeProd').eq(idxItem).find(':input').attr('disabled', true);
        //         getFieldsReady();
        //     }
        //     else {
        //         $(this).parents('tr').remove();
        //         $('.modalCodeProd').eq(idxItem).remove();
        //     }
        // });
        // $('.btnAppointItem').on('click', function() {
        //     idxItem = $('.btnAppointItem').index(this);
        //     // set row status to enabled
        //     // item-id from database cannot be changed
        //     if (idxItem < distDt.length) {
        //         $('.rowStatus').eq(idxItem).find(':input:not(.itemsName, .itemsId, .isDeleted, .btnAppointItem, .btnRemoveItem)').attr('disabled', false);
        //     } else {
        //         $('.rowStatus').eq(idxItem).find(':input:not(.itemsId, .isDeleted, .btnAppointItem, .btnRemoveItem)').attr('disabled', false);
        //     }
        //     $('.rowStatus').eq(idxItem).find('.isDeleted').val('false');
        //     $('.rowStatus').eq(idxItem).find('.btnRemoveItem').removeClass('d-none');
        //     $('.rowStatus').eq(idxItem).find('.btnAppointItem').addClass('d-none');
        //     $('.modalCodeProd').eq(idxItem).find(':input').attr('disabled', false);
        //     getFieldsReady();
        // });
        // event to show modal to display list of code-production
        $('.btnCodeProd').on('click', function() {
            idxItem = $('.btnCodeProd').index(this);
            // get unit-cmp from selected unit
            let unitCmp = parseInt($('.units').eq(idxItem).find('option:selected').data('unitcmp')) || 0;
            let qty = parseInt($('.qty').eq(idxItem).val()) || 0;
            let qtyUnit = qty * unitCmp;
            console.log(idxItem, unitCmp, qty, qtyUnit);
            // pass qtyUnit to modal
            $('.modalCodeProd').eq(idxItem).find('.QtyH').val(qtyUnit);
            $('.modalCodeProd').eq(idxItem).find('.usedUnit').val($('.units').eq(idxItem).find('option:first-child').text());
            // console.log('usedUnit: '+ $('.units').eq(idxItem).find('option:first-child').text());
            // console.log($('.modalCodeProd').eq(idxItem).find('.usedUnit'));
            calculateProdCodeQty();
            $('.modalCodeProd').eq(idxItem).modal('show');
        });
        // event to add more row to insert production-code
        $('.btnAddProdCode').on('click', function() {
            prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
            qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input></td>';
            action = '<td><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
            // idxItem is referenced from btnCodeProd above
            // $(listProdCode).insertBefore($('.modalCodeProd:eq('+ idxItem +')').find('.table_listcodeprod .rowBtnAdd'));
            $('.modalCodeProd:eq('+ idxItem +')').find('.table_listcodeprod').append(listProdCode);
            getFieldsReady();
        });
        // event to remove an prod-code from table_listcodeprod
        $('.btnRemoveProdCode').on('click', function() {
            idxProdCode = $('.btnRemoveProdCode').index(this);
            $(this).parents('tr').remove();
            calculateProdCodeQty();
        });
        // update total qty without production-code
        $('.qtyProdCode').on('keyup', function() {
            idxProdCode = $('.qtyProdCode').index(this);
            calculateProdCodeQty();
        });
        // select2 class
        $('.select2').select2({
            theme: "bootstrap",
            dropdownAutoWidth: true,
            width: '100%'
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
    // get expedition type
    function getExpeditionType(openStatus) {
        var id = $('#expedition').val();
        $.ajax({
            url: "{{ route('distribusibarang.getExpeditionType') }}",
            type: "get",
            data: {
                id: id
            },
            success: function (response) {
                $('#expeditionType').empty();
                $("#expeditionType").append('<option value="" selected="" disabled="">Pilih Jenis Ekspedisi</option>');
                $.each(response, function (key, val) {
                    if (val.ed_detailid == $('#pd_product').val()) {
                        $("#expeditionType").append('<option value="' + val.ed_detailid + '" selected>' + val.ed_product + '</option>');
                    }
                    else {
                        $("#expeditionType").append('<option value="' + val.ed_detailid + '">' + val.ed_product + '</option>');
                    }
                });
                if (openStatus == true) {
                    $('#expeditionType').focus();
                    $('#expeditionType').select2('open');
                }
            }
        });
    }
    // set modalCodeProd to be ready
    function setModalCodeProdReady()
    {
        $.each(distDt, function (key, val) {
            // clone modal-code-production and insert new one
            if (key == 0) {
                $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertBefore($('#modalCodeProdBase'));
            }
            else {
                $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
            }
            $('.modalCodeProd:eq('+ key +')').find('.table_listcodeprod > tbody > tr').remove();
            if (val.get_prod_code.length > 0) {
                $.each(val.get_prod_code, function (idx, val) {
                    if (idx == 0) {
                        prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]" value="'+ val.sdc_code +'"></input></td>';
                        qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="'+ val.sdc_qty +'"></input></td>';
                        action = '<td><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" title="Tambah Kode Produksi" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td>';
                    }
                    else {
                        prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]" value="'+ val.sdc_code +'"></input></td>';
                        qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="'+ val.sdc_qty +'"></input></td>';
                        action = '<td><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    }
                    listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
                    $('.modalCodeProd:eq('+ key +')').find('.table_listcodeprod').append(listProdCode);
                });
            }
            else {
                prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
                qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]"></input></td>';
                action = '<td><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" title="Tambah Kode Produksi" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td>';
                listProdCode = '<tr class="rowBtnAdd">'+ prodCode + qtyProdCode + action +'</tr>';
                $('.modalCodeProd:eq('+ key +')').find('.table_listcodeprod').append(listProdCode);
            }
            // rowBtnAdd = '<tr class="rowBtnAdd"><td colspan="3" class="text-center"><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>';
            // $('.modalCodeProd:eq('+ key +')').find('.table_listcodeprod').append(rowBtnAdd);
        });
    }
    // // get list item using AutoComplete
    // function findItemAu()
    // {
    //     // get list of existed-items (id)
    //     let existedItems = [];
    //     $.each($('.itemsId'), function(index) {
    //         existedItems.push($(this).val());
    //     });
    //
    //     $(".itemsName").eq(idxItem).autocomplete({
    //         source: function (request, response) {
    //             $.ajax({
    //                 url: "{{ route('distribusibarang.getItem') }}",
    //                 data: {
    //                     existedItems: existedItems,
    //                     term: $(".itemsName").eq(idxItem).val()
    //                 },
    //                 success: function (data) {
    //                     response(data);
    //                 }
    //             });
    //         },
    //         minLength: 1,
    //         select: function (event, data) {
    //             $('.itemsName').eq(idxItem).val(data.item.name);
    //             $('.itemsId').eq(idxItem).val(data.item.id);
    //             setListUnit(data.item);
    //             setStockUnit(data.item.id);
    //         }
    //     });
    // }
    // // get list unit based on id item
    // function setListUnit(item)
    // {
    //     $(".units").eq(idxItem).empty();
    //     var option = '';
    //     if (item.unit1 != null) {
    //         option += '<option value="' + item.unit1.u_id + '" data-unitcmp="'+ item.unitcmp1 +'">' + item.unit1.u_name + '</option>';
    //     }
    //     if (item.unit2 != null && item.unit2.u_id != item.unit1.u_id) {
    //         option += '<option value="' + item.unit2.u_id + '" data-unitcmp="'+ item.unitcmp2 +'">' + item.unit2.u_name + '</option>';
    //     }
    //     if (item.unit3 != null && item.unit3.u_id != item.unit1.u_id && item.unit3.u_id != item.unit2.u_id) {
    //         option += '<option value="' + item.unit3.u_id + '" data-unitcmp="'+ item.unitcmp3 +'">' + item.unit3.u_name + '</option>';
    //     }
    //     $(".units").eq(idxItem).append(option);
    // }
    // // get stock of an item
    // function setStockUnit(itemId)
    // {
    //     $.ajax({
    //         url: baseUrl + "/inventory/distribusibarang/get-stock/" + itemId,
    //         type: "get",
    //         success: function(response) {
    //             $('.qtyStock1').eq(idxItem).val(response.unit1);
    //             $('.qtyStock2').eq(idxItem).val(response.unit2);
    //             $('.qtyStock3').eq(idxItem).val(response.unit3);
    //             // console.log($('.qtyStock1').eq(idxItem).val());
    //         },
    //         error: function(xhr, status, error) {
    //             loadingHide();
	// 			let err = JSON.parse(xhr.responseText);
    //             messageWarning('Error', err.message);
    //         }
    //     });
    // }
    // check qty-limit
    function validateQty()
    {
        let qty = parseInt($('.qty').eq(idxItem).val());
        let qtyUsed = parseInt($('.qtyUsed').eq(idxItem).val());
        let qtyStock = 0;
        // get stock-value
        if ($(".units").eq(idxItem).prop('selectedIndex') == 0) {
            qtyStock = $('.qtyStock1').eq(idxItem).val();
        } else if ($(".units").eq(idxItem).prop('selectedIndex') == 1) {
            qtyStock = $('.qtyStock2').eq(idxItem).val();
        } else if ($(".units").eq(idxItem).prop('selectedIndex') == 2) {
            qtyStock = $('.qtyStock3').eq(idxItem).val();
        }
        qtyStock = parseFloat(qtyStock);

        if (qty > qtyStock)
        {
            message = 'Stock hanya tersisa : ' + qtyStock;
            messageWarning('Perhatian', message);
            $('.qty').eq(idxItem).val(qtyStock);
        }
        else if (qty < qtyUsed || isNaN(qty)) {
            message = 'Jumlah item tidak boleh lebih kecil dari stock terpakai !';
            messageWarning('Perhatian', message);
            $('.qty').eq(idxItem).val(qtyUsed);
        }
    }
    // submit new-distribusibarang
    function submitForm()
    {
        loadingShow();
        data = $('#formEditDist').serialize();
        $.each($('.table_listcodeprod'), function(key, val) {
            // get length of production-code each items
            let prodCodeLength = $('.table_listcodeprod:eq('+ key +') :input.qtyProdCode').length;
            $('.modalCodeProd:eq('+ key +')').find('.prodcode-length').val(prodCodeLength);

            inputs = $('.table_listcodeprod:eq('+ key +') :input').serialize();
            data = data +'&'+ inputs;
        });

        $.ajax({
            url: baseUrl + "/inventory/distribusibarang/store-approval/" + editedItemId,
            data: data,
            type: "post",
            success: function(response) {
                loadingHide();
                console.log(response);
                if (response.status === 'berhasil') {
                    messageSuccess('Selamat', 'Order berhasil disetujui dan barang segera akan dikirimkan !');
                    window.location.href = '{{ route("distribusibarang.index") }}';
                } else if (response.status === 'invalid') {
                    messageWarning('Perhatian', response.message);
                } else if (response.status === 'gagal') {
                    messageWarning('Gagal', response.message);
                }
            },
            error: function(e) {
                loadingHide();
                messageWarning('Perhatian', e.message);
            }
        });
    }
    // check production code qty each item
    function calculateProdCodeQty()
    {
        // return qtyH to 0 if the default val is NaN
        let QtyH = parseInt($('.modalCodeProd').eq(idxItem).find('.QtyH').val()) || 0;
        let qtyWithProdCode = getQtyWithProdCode();
        let restQty = QtyH - qtyWithProdCode;
        // console.log('QtyH: '+ QtyH);
        // console.log('qtyWithProdCode: '+ qtyWithProdCode);
        // console.log('restQty: '+ restQty);

        if (restQty < 0) {
            $(':focus').val(0);
            qtyWithProdCode = getQtyWithProdCode();
            restQty = QtyH - qtyWithProdCode;
            $('.modalCodeProd').eq(idxItem).find('.restQty').val(restQty);
            messageWarning('Perhatian', 'Jumlah item untuk penetapan kode produksi tidak boleh melebihi jumlah item yang ada !');
        } else {
            $('.modalCodeProd').eq(idxItem).find('.restQty').val(restQty);
        }
    }
    function getQtyWithProdCode()
    {
        let qtyWithProdCode = 0;
        $.each($('.modalCodeProd:eq('+idxItem+') .table_listcodeprod').find('.qtyProdCode'), function (key, val) {
            qtyWithProdCode += parseInt($(this).val());
        });
        return (isNaN(qtyWithProdCode)) ? 0 : qtyWithProdCode;
    }
</script>
@endsection
