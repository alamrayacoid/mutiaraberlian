@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Kelola Penjualan Langsung </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Marketing</span>
         / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Data Kelola Penjualan Langsung</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Data Kelola Penjualan Langsung </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <form class="myForm" autocomplete="off">
                        <div class="card-block">
                            <section>
                                <div id="sectionsuplier" class="row">

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Member</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="member" id="member" class="form-control form-control-sm select2">
                                                <option value="" selected disabled>Pilih Member</option>
                                                @foreach($data['member'] as $member)
                                                <option value="{{ $member->m_code }}">{{ $member->m_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Total</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm rupiah" name="total" id="total">
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
                                                            <input type="text"  class="form-control form-control-sm find-item" name="termToFind">
                                                            <input name="itemListId[]" type="hidden" class="item-id">
                                                            <input type="hidden" class="item-stock">
                                                            <input type="hidden" class="item-owner" name="itemOwner[]">
                                                        </td>
                                                        <td>
                                                            <select name="itemUnit[]" class="form-control form-control-sm select2 satuan" onchange="displayPrice(0)"></select>
                                                            <input type="hidden" class="item-unitcmp" name="itemUnitCmp[]">
                                                        </td>
                                                        <td><input name="itemQty[]" type="text" min="0" value="0" class="form-control form-control-sm digits item-qty"  onchange="sumSubTotalItem(0)"></td>
                                                        <td><input name="itemPrice[]" type="text" class="form-control form-control-sm rupiah item-price" readonly></td>
                                                        <td><input name="itemSubTotal[]" type="text" class="form-control form-control-sm rupiah item-sub-total" readonly></td>
                                                        <td><button type="button" class="btn btn-sm btn-success btn-tambahp rounded-circle"><i class="fa fa-plus"></i></button></td>
                                                    </tr>
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
    });

    // append a new row to insert more items
    $('.btn-tambahp').on('click',function(){
        tableRows = document.getElementsByTagName("tr");
        rowLength = tableRows.length;
        $('#table_create tbody')
        .append(
            '<tr>'+
            '<td><input type="text" class="form-control form-control-sm find-item" name="termToFind"><input name="itemListId[]" type="hidden" class="item-id"><input type="hidden" class="item-stock"><input type="hidden" class="item-owner" name="itemOwner[]"></td>'+
            '<td><select name="itemUnit[]" class="form-control form-control-sm select2 satuan" onchange="displayPrice('+ (rowLength - 1) +')"></select><input type="hidden" class="item-unitcmp" name="itemUnitCmp[]"></td>'+
            '<td><input name="itemQty[]" type="text" min="0" value="0" class="form-control form-control-sm digits item-qty" onchange="sumSubTotalItem('+ (rowLength - 1) +')"></td>'+
            '<td><input name="itemPrice[]" type="text" class="form-control form-control-sm rupiah item-price" readonly></td>'+
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
    console.log(itemListId);
    $('.find-item').autocomplete({
        source: function( request, response ) {
            dataToSend = $(".find-item").eq(rowIndex).serialize() +'&'+ itemListId;
            console.log(dataToSend);
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
            // console.log(data.item);
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
        url : "{{ route('kelolapenjulan.getItemStock') }}",
        dataType : 'json',
        success : function (response){
            if (! $.trim(response)) {
                messageFailed('Perhatian', 'Stock item tidak ditemukan !');
                $('.find-item').eq(rowIndex).trigger('click');
                console.log('stock: null');
                $('.item-stock').eq(rowIndex).val('');
                $('.item-owner').eq(rowIndex).val('');
            } else {
                console.log('stock: ' + response.s_qty);
                $('.item-stock').eq(rowIndex).val(response.s_qty);
                $('.item-owner').eq(rowIndex).val(response.s_comp);
                displayPrice(rowIndex);
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

// set price based on selected option (satuan)
function displayPrice(rowIndex)
{
    let selectedOpt = $('.satuan').eq(rowIndex).find('option:selected');
    unitcmp = selectedOpt.data('unitcmp');
    $('.item-unitcmp').eq(rowIndex).val(unitcmp);

    $.ajax({
        url: baseUrl + '/marketing/agen/kelolapenjualanlangsung/get-price',
        type: 'get',
        data: {
            "_token": "{{ csrf_token() }}",
            "itemId" : $('.item-id').eq(rowIndex).val(),
            "unitId" : $('.satuan').eq(rowIndex).val()
        },
        success: function(response) {
            if (! $.trim(response.get_price_class_dt)) {
                messageFailed('Perhatian', 'Harga item belum ditentukan !');
                $('.item-price').eq(rowIndex).val('0');
            } else {
                $('.item-price').eq(rowIndex).val(parseInt(response.get_price_class_dt[0].pcd_price));
            }
        },
        error: function(e) {
            console.log('getPrice error: ' + e);
        }
    });
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
        // console.log(i, subTotal, $('.item-sub-total').eq(i).val());
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
        url : baseUrl + '/marketing/agen/kelolapenjualanlangsung/store',
        dataType : 'json',
        success : function (response){
            console.log('submit form: ' + response);
            if(response.status == 'berhasil')
            {
                messageSuccess('Berhasil', 'Penjualan berhasil ditambahkan !');
                resetAllInput();
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
