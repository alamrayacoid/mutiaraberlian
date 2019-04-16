@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Edit Data Kelola Penjualan Langsung </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Marketing</span>
         / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Edit Data Kelola Penjualan Langsung</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Edit Data Kelola Penjualan Langsung </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <form class="myForm" autocomplete="off">
                        <div class="card-block">
                            <section>
                                <div id="sectionsuplier" class="row">
                                    <input type="hidden" id="salesId" value="{{ $data['kpl']->s_id }}">
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Member</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="member" id="member" class="form-control form-control-sm select2">
                                                <option value="" selected disabled>Pilih Member</option>
                                                @foreach($data['member'] as $member)
                                                @if($member->m_code == $data['kpl']->s_member)
                                                <option value="{{ $member->m_code }}" selected>{{ $member->m_name }}</option>
                                                @else
                                                <option value="{{ $member->m_code }}">{{ $member->m_name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Total</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm rupiah" name="total" id="total" value="{{ (int)$data['kpl']->s_total }}" readonly>
                                        </div>
                                    </div>

                                    <div class="container">
                                        <div class="table-responsive mt-3">
                                            <table class="table table-hover table-striped diplay nowrap" id="table_create">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th>Kode/Nama Barang</th>
                                                        <th width="10%">Satuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Harga Satuan</th>
                                                        <th>Sub Total</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text"  class="form-control form-control-sm find-item" name="termToFind" value="{{ $data['kpl']->getSalesDt[0]->getItem->i_code }} - {{ $data['kpl']->getSalesDt[0]->getItem->i_name }}">
                                                            <input name="itemListId[]" type="hidden" class="item-id" value="{{ $data['kpl']->getSalesDt[0]->sd_item }}">
                                                            <input type="hidden" class="item-stock">
                                                            <input type="hidden" class="item-owner" name="itemOwner[]" value="{{ $data['kpl']->getSalesDt[0]->sd_comp }}">
                                                        </td>
                                                        <td>
                                                            <input type="hidden" class="itemUnitHidden" value="{{ $data['kpl']->getSalesDt[0]->sd_unit }}">
                                                            <select name="itemUnit[]" class="form-control form-control-sm select2 satuan" onchange="setUnitCmp(0)">
                                                                @if($data['kpl']->getSalesDt[0]->getItem->getUnit1->u_id == $data['kpl']->getSalesDt[0]->sd_unit)
                                                                    <option value="{{ $data['kpl']->getSalesDt[0]->getItem->getUnit1->u_id }}" data-unitcmp="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare1 }}" selected>{{ $data['kpl']->getSalesDt[0]->getItem->getUnit1->u_name }}</option>
                                                                @else
                                                                    <option value="{{ $data['kpl']->getSalesDt[0]->getItem->getUnit1->u_id }}" data-unitcmp="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare1 }}">{{ $data['kpl']->getSalesDt[0]->getItem->getUnit1->u_name }}</option>
                                                                @endif
                                                                @if($data['kpl']->getSalesDt[0]->getItem->getUnit2->u_id == $data['kpl']->getSalesDt[0]->sd_unit)
                                                                    <option value="{{ $data['kpl']->getSalesDt[0]->getItem->getUnit2->u_id }}" data-unitcmp="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare2 }}" selected>{{ $data['kpl']->getSalesDt[0]->getItem->getUnit2->u_name }}</option>
                                                                @else
                                                                    <option value="{{ $data['kpl']->getSalesDt[0]->getItem->getUnit2->u_id }}" data-unitcmp="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare2 }}">{{ $data['kpl']->getSalesDt[0]->getItem->getUnit2->u_name }}</option>
                                                                @endif
                                                                @if($data['kpl']->getSalesDt[0]->getItem->getUnit3->u_id == $data['kpl']->getSalesDt[0]->sd_unit)
                                                                    <option value="{{ $data['kpl']->getSalesDt[0]->getItem->getUnit3->u_id }}" data-unitcmp="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare3 }}" selected>{{ $data['kpl']->getSalesDt[0]->getItem->getUnit3->u_name }}</option>
                                                                @else
                                                                    <option value="{{ $data['kpl']->getSalesDt[0]->getItem->getUnit3->u_id }}" data-unitcmp="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare3 }}" >{{ $data['kpl']->getSalesDt[0]->getItem->getUnit3->u_name }}</option>
                                                                @endif
                                                            </select>
                                                            @if($data['kpl']->getSalesDt[0]->getItem->getUnit1->u_id == $data['kpl']->getSalesDt[0]->sd_unit)
                                                                <input type="hidden" class="item-unitcmp" name="itemUnitCmp[]" value="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare1 }}">
                                                            @elseif($data['kpl']->getSalesDt[0]->getItem->getUnit2->u_id == $data['kpl']->getSalesDt[0]->sd_unit)
                                                                <input type="hidden" class="item-unitcmp" name="itemUnitCmp[]" value="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare2 }}">
                                                            @elseif($data['kpl']->getSalesDt[0]->getItem->getUnit3->u_id == $data['kpl']->getSalesDt[0]->sd_unit)
                                                                <input type="hidden" class="item-unitcmp" name="itemUnitCmp[]" value="{{ (int)$data['kpl']->getSalesDt[0]->getItem->i_unitcompare3 }}">
                                                            @endif
                                                        </td>
                                                        <td><input name="itemQty[]" type="text" min="0" value="{{ $data['kpl']->getSalesDt[0]->sd_qty }}" class="form-control form-control-sm digits item-qty" onchange="sumSubTotalItem(0)"></td>
                                                        <td><input name="itemPrice[]" type="text" class="form-control form-control-sm rupiah item-price" value="{{ (int)$data['kpl']->getSalesDt[0]->sd_value }}" onchange="sumSubTotalItem(0)"></td>
                                                        <td><input name="itemSubTotal[]" type="text" value="{{ (int)$data['kpl']->getSalesDt[0]->sd_value }}" class="form-control form-control-sm rupiah item-sub-total" readonly></td>
                                                        <td><button type="button" class="btn btn-sm btn-success btn-tambahp rounded-circle"><i class="fa fa-plus"></i></button></td>
                                                    </tr>
                                                    @foreach($data['kpl']->getSalesDt as $index => $salesDt)
                                                    @if($index < 1)
                                                    @continue
                                                    @endif
                                                    <tr>
                                                        <td>
                                                            <input type="text"  class="form-control form-control-sm find-item" name="termToFind" value="{{ $salesDt->getItem->i_code }} - {{ $salesDt->getItem->i_name }}">
                                                            <input name="itemListId[]" type="hidden" class="item-id" value="{{ $salesDt->sd_item }}">
                                                            <input type="hidden" class="item-stock">
                                                            <input type="hidden" class="item-owner" name="itemOwner[]" value="{{ $salesDt->sd_comp }}">
                                                        </td>
                                                        <td>
                                                            <input type="hidden" class="itemUnitHidden" value="{{ $salesDt->sd_unit }}">
                                                            <select name="itemUnit[]" class="form-control form-control-sm select2 satuan" onchange="setUnitCmp({{ $index }})">
                                                                @if($salesDt->getItem->getUnit1->u_id == $salesDt->sd_unit)
                                                                    <option value="{{ $salesDt->getItem->getUnit1->u_id }}" data-unitcmp="{{ (int)$salesDt->getItem->i_unitcompare1 }}" selected>{{ $salesDt->getItem->getUnit1->u_name }}</option>
                                                                @else
                                                                    <option value="{{ $salesDt->getItem->getUnit1->u_id }}" data-unitcmp="{{ (int)$salesDt->getItem->i_unitcompare1 }}">{{ $salesDt->getItem->getUnit1->u_name }}</option>
                                                                @endif
                                                                @if($salesDt->getItem->getUnit2->u_id == $salesDt->sd_unit)
                                                                    <option value="{{ $salesDt->getItem->getUnit2->u_id }}" data-unitcmp="{{ (int)$salesDt->getItem->i_unitcompare2 }}" selected>{{ $salesDt->getItem->getUnit2->u_name }}</option>
                                                                @else
                                                                    <option value="{{ $salesDt->getItem->getUnit2->u_id }}" data-unitcmp="{{ (int)$salesDt->getItem->i_unitcompare2 }}">{{ $salesDt->getItem->getUnit2->u_name }}</option>
                                                                @endif
                                                                @if($salesDt->getItem->getUnit3->u_id == $salesDt->sd_unit)
                                                                    <option value="{{ $salesDt->getItem->getUnit3->u_id }}" data-unitcmp="{{ (int)$salesDt->getItem->i_unitcompare3 }}" selected>{{ $salesDt->getItem->getUnit3->u_name }}</option>
                                                                @else
                                                                    <option value="{{ $salesDt->getItem->getUnit3->u_id }}" data-unitcmp="{{ (int)$salesDt->getItem->i_unitcompare3 }}">{{ $salesDt->getItem->getUnit3->u_name }}</option>
                                                                @endif
                                                            </select>
                                                            @if($salesDt->getItem->getUnit1->u_id == $salesDt->sd_unit)
                                                            <input type="hidden" class="item-unitcmp" name="itemUnitCmp[]" value="{{ (int)$salesDt->getItem->i_unitcompare1 }}">
                                                            @elseif($salesDt->getItem->getUnit2->u_id == $salesDt->sd_unit)
                                                            <input type="hidden" class="item-unitcmp" name="itemUnitCmp[]" value="{{ (int)$salesDt->getItem->i_unitcompare2 }}">
                                                            @elseif($salesDt->getItem->getUnit3->u_id == $salesDt->sd_unit)
                                                            <input type="hidden" class="item-unitcmp" name="itemUnitCmp[]" value="{{ (int)$salesDt->getItem->i_unitcompare3 }}">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <input name="itemQty[]" type="text" min="0" value="{{ $salesDt->sd_qty }}" class="form-control form-control-sm digits item-qty" onchange="sumSubTotalItem({{ $index }})">
                                                        </td>
                                                        <td>
                                                            <input name="itemPrice[]" type="text" class="form-control form-control-sm rupiah item-price" value="{{ (int)$salesDt->sd_value }}" onchange="sumSubTotalItem({{ $index }})">
                                                        </td>
                                                        <td>
                                                            <input name="itemSubTotal[]" type="text" value="{{ (int)$salesDt->sd_value }}" class="form-control form-control-sm rupiah item-sub-total" readonly>
                                                        </td>
                                                        <td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </section>
                            </div>
                    </form>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary" id="btn_simpan" type="button">Simpan</button>
                      <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

      </div>

    </div>

  </section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">

$(document).ready(function()
{
    initFunction();

    $(document).on('click', '.btn-hapus', function(){
        $(this).parents('tr').remove();
        sumTotalBruto();
    });

    // append a new row to insert more items
    $('.btn-tambahp').on('click',function(){
        tableRows = document.getElementsByTagName("tr");
        rowLength = tableRows.length;
        $('#table_create tbody')
        .append(
            '<tr>'+
            '<td><input type="text" class="form-control form-control-sm find-item" name="termToFind"><input name="itemListId[]" type="hidden" class="item-id"><input type="hidden" class="item-stock"><input type="hidden" class="item-owner" name="itemOwner[]"></td>'+
            '<td><select name="itemUnit[]" class="form-control form-control-sm select2 satuan" onchange="setUnitCmp('+ (rowLength - 1) +')"></select><input type="hidden" class="item-unitcmp" name="itemUnitCmp[]"></td>'+
            '<td><input name="itemQty[]" type="text" min="0" value="0" class="form-control form-control-sm digits item-qty" onchange="sumSubTotalItem('+ (rowLength - 1) +')"></td>'+
            '<td><input name="itemPrice[]" type="text" class="form-control form-control-sm rupiah item-price" onchange="sumSubTotalItem('+ (rowLength - 1) +')"></td>'+
            '<td><input name="itemSubTotal[]" type="text" class="form-control form-control-sm rupiah item-sub-total" readonly></td>'+
            '<td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
            '</tr>'
        );
        // re-initialize som function after append a new row
        initFunction();
    });

    $('#btn_simpan').one('click', function() {
        submitForm();
    });
}); // end: document ready

// init some function
function initFunction()
{
    $('.select2').select2({
        theme: "bootstrap",
        dropdownAutoWidth: true,
        width: '100%'
    });
    $('.rupiah').inputmask("currency", {
        radixPoint: ",",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        prefix: ' Rp ', //Space after $, this will not truncate the first character.
        rightAlign: true,
        autoUnmask: true,
        nullable: false,
        // unmaskAsNumber: true,
    });
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
    $('.find-item').on('click', function() {
        rowIndex = $('.find-item').index(this);
        // clear row input
        $('.find-item').eq(rowIndex).val('');
        $('.item-id').eq(rowIndex).val('');
        $('.item-stock').eq(rowIndex).val('');
        $('.item-owner').eq(rowIndex).val('');
        $('.satuan').eq(rowIndex).find('option').remove();
        $('.item-qty').eq(rowIndex).val('');
        $('.item-price').eq(rowIndex).val('');
        $('.item-sub-total').eq(rowIndex).val('');
        findItem(rowIndex);
    });
}

// find-item autocomplete in rowIndex
function findItem(rowIndex)
{
    let itemListId = $('.item-id[value != ""]').serialize();
    $('.find-item').autocomplete({
        source: function( request, response ) {
            dataToSend = $(".find-item").eq(rowIndex).serialize() +'&'+ itemListId;
            $.ajax({
                url: baseUrl + '/marketing/agen/kelolapenjualanlangsung/find-item',
                data: dataToSend,
                dataType: 'json',
                success: function( data ) {
                    response( data );
                }
            });
        },
        minLength: 1,
        select: function(event, data) {
            $('.item-id').eq(rowIndex).val(data.item.data.i_id);
            getItemStock(rowIndex);
            appendOptSatuan(rowIndex, data.item.data);
        }
    });
}

// get item stock
function getItemStock(rowIndex)
{
    $.ajax({
        data : {
            "itemId": $('.item-id').eq(rowIndex).val()
        },
        type : "get",
        url : "{{ route('kelolapenjualan.getItemStock') }}",
        dataType : 'json',
        success : function (response){
            if (! $.trim(response)) {
                messageFailed('Perhatian', 'Stock item tidak ditemukan !');
                $('.find-item').eq(rowIndex).trigger('click');
                $('.item-stock').eq(rowIndex).val('');
                $('.item-owner').eq(rowIndex).val('');
            } else {
                $('.item-stock').eq(rowIndex).val(response.s_qty);
                $('.item-owner').eq(rowIndex).val(response.s_comp);
                // displayPrice(rowIndex);
            }
        },
        error : function(e){
            console.error(e);
        }
    });
}

// append option to select in rowIndex
function appendOptSatuan(rowIndex, item)
{
    $('.satuan').eq(rowIndex).find('option').remove();
    $('.item-unitcmp').eq(rowIndex).val(1);
    let optSatuan = '';
    optSatuan += '<option value="'+ item.get_unit1.u_id +'" data-unitcmp="'+ parseInt(item.i_unitcompare1) +'" selected>'+ item.get_unit1.u_name +'</option>';
    if (item.get_unit2 != null && item.get_unit2.u_id !== item.get_unit1.u_id) {
        optSatuan += '<option value="'+ item.get_unit2.u_id +'" data-unitcmp="'+ parseInt(item.i_unitcompare2) +'">'+ item.get_unit2.u_name +'</option>';
    }
    if (item.get_unit3 != null && item.get_unit3.u_id !== item.get_unit1.u_id && item.get_unit3.u_id !== item.get_unit2.u_id) {
        optSatuan += '<option value="'+ item.get_unit3.u_id +'" data-unitcmp="'+ parseInt(item.i_unitcompare3) +'">'+ item.get_unit3.u_name +'</option>';
    }
    $('.satuan').eq(rowIndex).append(optSatuan);
}

function setUnitCmp(rowIndex)
{
    let selectedOpt = $('.satuan').eq(rowIndex).find('option:selected');
    unitcmp = selectedOpt.data('unitcmp');
    $('.item-unitcmp').eq(rowIndex).val(unitcmp);
    sumSubTotalItem(rowIndex);
}

// sum sub-total item in a row
function sumSubTotalItem(rowIndex)
{
    qty = parseInt($('.item-qty').eq(rowIndex).val());
    price = parseInt($('.item-price').eq(rowIndex).val());
    unitcmp = parseInt($('.item-unitcmp').eq(rowIndex).val());

    if (qty < 0 || isNaN(qty)) {
        qty = 0;
        $('.item-qty').eq(rowIndex).val(0)
    }

    qtyUnit1 = qty * unitcmp;
    subTotal = qtyUnit1 * price;
    $('.item-sub-total').eq(rowIndex).val(subTotal);
    sumTotalBruto();
}

// sum total-bruto
function sumTotalBruto()
{
    tableRows = document.getElementsByTagName("tr");
    rowLength = tableRows.length;
    subTotal = 0;
    for (let i = 0; i < (rowLength-1); i++) {
        subTotal += parseInt($('.item-sub-total').eq(i).val());
    }
    $('#total').val(subTotal);
}

// submit form
function submitForm()
{
    myForm = $('.myForm').serialize();

    $.ajax({
        data : myForm,
        type : "post",
        url : baseUrl + '/marketing/agen/kelolapenjualanlangsung/update/' + $('#salesId').val(),
        dataType : 'json',
        success : function (response){
            if(response.status == 'berhasil')
            {
                messageSuccess('Berhasil', 'Penjualan berhasil ditambahkan !');
                // resetAllInput();
                // $('#modal_bayar').modal('hide');
            }
            else if (response.status == 'invalid')
            {
                messageFailed('Perhatian', response.message);
            }
            else if (response.status == 'gagal')
            {
                messageWarning('Error', response.message);
            }
            // activate btn_simpan once again
            $('#btn_simpan').one('click', function() {
                submitForm();
            });
        },
        error : function(e){
            messageWarning('Gagal', 'Data gagal ditambahkan, hubungi pengembang !');
            // activate btn_simpan once again
            $('#btn_simpan').one('click', function() {
                submitForm();
            });
        }
    });
}

// reset all input
function resetAllInput()
{
    $('.myForm')[0].reset();
    $('.satuan').find('option').remove();
    $('#member').find('option:first').attr('selected', 'selected');
    $('#table_create tbody').find('tr:gt(0)').remove();
    $('.find-item').eq(0).trigger('click');
}


</script>
@endsection
