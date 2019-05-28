@extends('main')

@section('content')

<form class="formCodeProd">
    <!-- modal-code-production -->
    @include('inventory.distribusibarang.distribusi.modal-code-prod-base')
</form>

<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Edit Data Distribusi Barang </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Aktivitas Inventory</span>
            / <a href="{{route('distribusibarang.index')}}"><span>Pengelolaan Distribusi Barang</span></a>
            / <span class="text-primary" style="font-weight: bold;"> Edit Data Distribusi Barang</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Edit Data Distribusi Barang</h3>
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

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover data-table" cellspacing="0" id="table_items">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>Kode Barang/Nama Barang</th>
                                                <th>Jumlah</th>
                                                <th>Satuan</th>
                                                <th>Kode Produksi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['stockdist']->getDistributionDt as $key => $value)
                                            <tr class="rowStatus">
                                                <td>
                                                    <input type="text" data-counter="0" class="form-control form-control-sm itemsName" value="{{ $value->getItem->i_code }} - {{ $value->getItem->i_name }}" disabled>
                                                    <input type="hidden" name="itemsId[]" value="{{ $value->getItem->i_id }}" class="itemsId">
                                                    <input type="hidden" name="isDeleted[]" value="false" class="isDeleted">
                                                </td>
                                                <td>
                                                    <input type="text" name="qty[]" class="form-control form-control-sm digits qty" value="{{ $value->sdd_qty }}">
                                                    <input type="hidden" name="qtyUsed[]" class="form-control form-control-sm digits qtyUsed" value="{{ $value->qtyUsed }}">
                                                    <input type="hidden" class="qtyStock1" value="{{ $value->stockUnit1 }}">
                                                    <input type="hidden" class="qtyStock2" value="{{ $value->stockUnit2 }}">
                                                    <input type="hidden" class="qtyStock3" value="{{ $value->stockUnit3 }}">
                                                </td>
                                                <td>
                                                    <select name="units[]" class="form-control form-control-sm select2 units">
                                                        <option value="{{ $value->getItem->getUnit1->u_id}}"
                                                            @if($value->sdd_unit == $value->getItem->getUnit1->u_id)
                                                            selected
                                                            @endif
                                                            >{{ $value->getItem->getUnit1->u_name }}
                                                        </option>
                                                        <option value="{{ $value->getItem->getUnit2->u_id}}"
                                                            @if($value->sdd_unit == $value->getItem->getUnit2->u_id)
                                                            selected
                                                            @endif
                                                            >{{ $value->getItem->getUnit2->u_name }}
                                                        </option>
                                                        <option value="{{ $value->getItem->getUnit3->u_id}}"
                                                            @if($value->sdd_unit == $value->getItem->getUnit3->u_id)
                                                            selected
                                                            @endif
                                                            >{{ $value->getItem->getUnit3->u_name }}
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button>
                                                </td>
                                                @if ($value->status == 'used')
                                                    <td class="badge badge-warning">Stock sudah digunakan ({{ $value->qtyUsed }})</td>
                                                    <input type="hidden" name="status[]" class="status" value="{{ $value->status }}">
                                                @else
                                                    <td class="badge badge-primary">Stock belum digunakan</td>
                                                    <input type="hidden" name="status[]" class="status" value="{{ $value->status }}">
                                                @endif
                                                <td>
                                                    @if ($key == 0)
                                                        <button class="btn btn-success btnAddItem btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                        @if ($value->status != 'used')
                                                            <button class="btn btn-danger btnRemoveItem btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        @endif
                                                    @else
                                                        @if ($value->status != 'used')
                                                            <button class="btn btn-danger btnRemoveItem btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        @else
                                                            <button class="btn btn-danger btnRemoveItem btn-sm rounded-circle" type="button" disabled><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            <input type="hidden" name="counter" value="{{ $key }}">
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="button" id="btn_simpan">Simpan</button>
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
    $(document).ready(function(){
        editedItemId = <?php echo $data['stockdist']->sd_id; ?>;
        // set modal production-code
        setModalCodeProdReady();
        // event field items inside table
        getFieldsReady();
        // add more item in table_items
        $('.btnAddItem').on('click', function() {
            $('#table_items').append(
            `<tr class="rowStatus">
                <td>
                    <input type="text" data-counter="0" class="form-control form-control-sm itemsName" style="text-transform:uppercase">
                    <input type="hidden" name="itemsId[]" value="" class="itemsId">
                    <input type="hidden" name="isDeleted[]" value="false" class="isDeleted">
                </td>
                <td>
                    <input type="text" name="qty[]" class="form-control form-control-sm digits qty" value="">
                    <input type="hidden" name="qtyUsed[]" class="form-control form-control-sm digits qtyUsed" value="0">
                    <input type="hidden" class="qtyStock1">
                    <input type="hidden" class="qtyStock2">
                    <input type="hidden" class="qtyStock3">
                </td>
                <td>
                    <select name="units[]" class="form-control form-control-sm select2 units">
                        <option value="" disabled selected>Pilih Satuan</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button>
                </td>
                <td class="badge badge-primary">
                    Stock belum digunakan
                    <input type="hidden" name="status[]" class="status" value="unused">
                </td>
                <td>
                    <button class="btn btn-danger btnRemoveItem btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>`
            );
            // clone modal-code-production and insert new one
            $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
            // re-declare event for field items inside table
            getFieldsReady();
        });
        // submit-form by btn-submit
        $('#btn_simpan').on('click', function() {
            submitForm();
        });
    });

    function getFieldsReady()
    {
        // remove all event-handler and re-declare it
        $('.itemsName').off();
        $('.qty').off();
        $('.units').off();
        $('.btnRemoveItem').off();
        $('.btnAppointItem').off();
        $('.btnCodeProd').off();
        $('.btnAddProdCode').off();
        $('.btnRemoveProdCode').off();
        $('.qtyProdCode').off();
        // set event for field-items
        $('.itemsName').on('click', function () {
            idxItem = $('.itemsName').index(this);
            $('.itemsName').eq(idxItem).val('');
            $('.itemsId').eq(idxItem).val('');
        });
        $('.itemsName').on('keyup', function () {
            idxItem = $('.itemsName').index(this);
            findItemAu();
        });
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
        // event to remove an item from table_items
        $('.btnRemoveItem').on('click', function() {
            idxItem = $('.btnRemoveItem').index(this);
            // $(this).parents('tr').remove();
            // set row status to disabled
            if (idxItem != 0) {
                $('.rowStatus').eq(idxItem).find(':input:not(.itemsId, .isDeleted, .btnRemoveItem)').attr('disabled', true);
                $('.rowStatus').eq(idxItem).find('.isDeleted').val('true');
                $('.rowStatus').eq(idxItem).find('.btnRemoveItem').addClass('btnAppointItem').removeClass('btnRemoveItem');
                $('.modalCodeProd').eq(idxItem).find(':input').attr('disabled', true);
            }
            getFieldsReady();
        });
        $('.btnAppointItem').on('click', function() {
            idxItem = $('.btnRemoveItem').index(this);
            $('.rowStatus').eq(idxItem).find(':input:not(.itemsId, .isDeleted, .btnRemoveItem)').attr('disabled', false);
            $('.rowStatus').eq(idxItem).find('.isDeleted').val('false');
            $('.rowStatus').eq(idxItem).find('.btnAppointItem').addClass('btnRemoveItem').removeClass('btnAppointItem');
            $('.modalCodeProd').eq(idxItem).find(':input').attr('disabled', false);
        });
        // event to show modal to display list of code-production
        $('.btnCodeProd').on('click', function() {
            idxItem = $('.btnCodeProd').index(this);
            // pass qty to modal
            $('.modalCodeProd').eq(idxItem).find('.QtyH').val($('.qty').eq(idxItem).val());
            calculateProdCodeQty();
            $('.modalCodeProd').eq(idxItem).modal('show');
        });
        // event to add more row to insert production-code
        $('.btnAddProdCode').on('click', function() {
            prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
            qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input></td>';
            action = '<td><button class="btn btn-success btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
            // idxItem is referenced from btnCodeProd above
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
    // set modalCodeProd to be ready
    function setModalCodeProdReady()
    {
        // retrive data directly from controller
        distDt = {!! $data['stockdist'] !!};
        distDt = distDt.get_distribution_dt;
        console.log(distDt);

        $.each(distDt, function (key, val) {
            // clone modal-code-production and insert new one
            if (key == 0) {
                $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertBefore($('#modalCodeProdBase'));
            }
            else {
                $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
            }
            console.log('key DT: '+ key);
            if (val.get_code_prod.length > 0) {
                $.each(val.get_code_prod, function (idx, val) {
                    console.log(idx +': '+ val);
                    prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]" value="'+ val.sdc_code +'"></input></td>';
                    qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="'+ val.sdc_qty +'"></input></td>';
                    action = '<td><button class="btn btn-success btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
                    $('.modalCodeProd:eq('+ key +')').find('.table_listcodeprod').append(listProdCode);
                });
            }
        });
    }
    // get list item using AutoComplete
    function findItemAu()
    {
        // get list of existed-items (id)
        let existedItems = [];
        $.each($('.itemsId'), function(index) {
            existedItems.push($(this).val());
        });

        $(".itemsName").eq(idxItem).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('distribusibarang.getItem') }}",
                    data: {
                        existedItems: existedItems,
                        term: $(".itemsName").eq(idxItem).val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function (event, data) {
                $('.itemsName').eq(idxItem).val(data.item.name);
                $('.itemsId').eq(idxItem).val(data.item.id);
                setListUnit(data.item);
                setStockUnit(data.item.id);
            }
        });
    }
    // get list unit based on id item
    function setListUnit(item)
    {
        $(".units").eq(idxItem).empty();
        var option = '';
        if (item.unit1 != null) {
            option += '<option value="' + item.unit1.u_id + '">' + item.unit1.u_name + '</option>';
        }
        if (item.unit2 != null && item.unit2.u_id != item.unit1.u_id) {
            option += '<option value="' + item.unit2.u_id + '">' + item.unit2.u_name + '</option>';
        }
        if (item.unit3 != null && item.unit3.u_id != item.unit1.u_id && item.unit3.u_id != item.unit2.u_id) {
            option += '<option value="' + item.unit3.u_id + '">' + item.unit3.u_name + '</option>';
        }
        $(".units").eq(idxItem).append(option);
    }
    // get stock of an item
    function setStockUnit(itemId)
    {
        $.ajax({
            url: baseUrl + "/inventory/distribusibarang/get-stock/" + itemId,
            type: "get",
            success: function(response) {
                $('.qtyStock1').eq(idxItem).val(response.unit1);
                $('.qtyStock2').eq(idxItem).val(response.unit2);
                $('.qtyStock3').eq(idxItem).val(response.unit3);
                console.log($('.qtyStock1').eq(idxItem).val());
            },
            error: function(xhr, status, error) {
                loadingHide();
				let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        });
    }
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
        console.log(qtyStock);
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
            url: baseUrl + "/inventory/distribusibarang/update/" + editedItemId,
            data: data,
            type: "post",
            success: function(response) {
                loadingHide();
                if (response.status === 'berhasil') {
                    messageSuccess('Selamat', 'Distribusi berhasil disimpan !');
                    window.location.reload();
                } else if (response.status === 'invalid') {
                    messageWarning('Perhatian', response.message);
                } else if (response.status === 'gagal') {
                    messageWarning('Gagal', response.message);
                }
                console.log('response: '+ response);
            },
            error: function(e) {
                loadingHide();
                messageWarning('Perhatian', e);
            }
        });
    }
    // check production code qty each item
    function calculateProdCodeQty()
    {
        let QtyH = parseInt($('.modalCodeProd').eq(idxItem).find('.QtyH').val());
        let qtyWithProdCode = getQtyWithProdCode();
        console.log('qty: '+ QtyH);
        console.log('qtyWithProdcode: '+ qtyWithProdCode);
        let restQty = QtyH - qtyWithProdCode;

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
        console.log('--');
        let qtyWithProdCode = 0;
        $.each($('.modalCodeProd:eq('+idxItem+') .table_listcodeprod').find('.qtyProdCode'), function (key, val) {
            console.log($(this).val());
            qtyWithProdCode += parseInt($(this).val());
        });
        return qtyWithProdCode;
    }
</script>
@endsection
