@extends('main')

@section('extra_style')
    <style type="text/css">
        .cls-readonly {
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Distribusi Barang </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Inventory</span>
                / <a href="{{route('distribusibarang.index')}}"><span>Pengelolaan Distribusi Barang</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Distribusi Barang</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data Distribusi Barang</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('distribusibarang.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <form id="formDistribution">
                                <section>
                                    <div class="row">
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Cabang</label>
                                        </div>
                                        <div class="col-md-10 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="selectBranch" id="selectBranch" class="form-control form-control-sm select2">
                                                    <option value="" disabled selected>Pilih Cabang</option>
                                                    @foreach ($branch as $index => $val)
                                                    <option value="{{ $val->c_id }}">{{ $val->c_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" cellspacing="0" id="table_items">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th width="50%">Kode Barang/Nama Barang</th>
                                                    <th>Jumlah</th>
                                                    <th width="15%">Satuan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="items[]" class="form-control form-control-sm items" style="text-transform:uppercase" autocomplete="off">
                                                        <input type="hidden" name="itemsId[]" class="itemsId">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="qty[]" class="form-control form-control-sm qty digits" autocomplete="off">
                                                        <input type="hidden" class="qtyStock1">
                                                        <input type="hidden" class="qtyStock2">
                                                        <input type="hidden" class="qtyStock3">
                                                    </td>
                                                    <td>
                                                        <select name="units[]" class="form-control form-control-sm select2 units"></select>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-success btnAddItem btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </form>
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
    var idxItem = null;

    $(document).ready(function(){
        // event field items inside table
        getFieldsReady();
        // add more item in table_items
        $('.btnAddItem').on('click', function() {
            $('#table_items').append(
            `<tr>
                <td>
                    <input type="text" name="items[]" class="form-control form-control-sm items" style="text-transform:uppercase" autocomplete="off">
                    <input type="hidden" name="itemsId[]" class="itemsId">
                </td>
                <td>
                    <input type="text" name="qty[]" class="form-control form-control-sm qty digits" autocomplete="off">
                    <input type="hidden" class="qtyStock1">
                    <input type="hidden" class="qtyStock2">
                    <input type="hidden" class="qtyStock3">
                </td>
                <td>
                    <select name="units[]" class="form-control form-control-sm select2 units"></select>
                </td>
                <td>
                    <button class="btn btn-danger btnRemoveItem btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>`
            );
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
        $('.items').off();
        $('.qty').off();
        $('.units').off();
        $('.btnRemoveItem').off();
        // set event for field-items
        $('.items').on('click', function () {
            idxItem = $('.items').index(this);
            $('.items').eq(idxItem).val('');
            $('.itemsId').eq(idxItem).val('');
        });
        $('.items').on('keyup', function () {
            idxItem = $('.items').index(this);
            findItemAu();
        });
        // set event for field-qty
        $('.qty').on('keyup', function () {
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
            $(this).parents('tr').remove();
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
    // get list item using AutoComplete
    function findItemAu()
    {
        // get list of existed-items (id)
        let existedItems = [];
        $.each($('.itemsId'), function(index) {
            existedItems.push($(this).val());
        });

        $(".items").eq(idxItem).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('distribusibarang.getItem') }}",
                    data: {
                        existedItems: existedItems,
                        term: $(".items").eq(idxItem).val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            select: function (event, data) {
                $('.items').eq(idxItem).val(data.item.name);
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
            },
            error: function(xhr, status, error) {
                loadingHide();
				let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
            }
        });
    }
    // validate qty and unit
    function validateQty()
    {
        let stock = 0;
        // get stock-value
        if ($(".units").eq(idxItem).prop('selectedIndex') == 0) {
            stock = $('.qtyStock1').eq(idxItem).val();
        } else if ($(".units").eq(idxItem).prop('selectedIndex') == 1) {
            stock = $('.qtyStock2').eq(idxItem).val();
        } else if ($(".units").eq(idxItem).prop('selectedIndex') == 2) {
            stock = $('.qtyStock3').eq(idxItem).val();
        }

        stock = parseFloat(stock);

        if ($('.qty').eq(idxItem).val() > stock) {
            $('.qty').eq(idxItem).val(stock);
            messageWarning('Perhatian', 'Stock tersedia : ' + stock)
        } else if ($('.qty').eq(idxItem).val() < 0 || $('.qty').eq(idxItem).val() == '' || isNaN($('.qty').eq(idxItem).val())) {
            $('.qty').eq(idxItem).val(0);
        }
    }
    // submit new-distribusibarang
    function submitForm()
    {
        data = $('#formDistribution').serialize();
        $.ajax({
            url: "{{ route('distribusibarang.store') }}",
            data: data,
            type: "post",
            success: function(response) {
                if (response.status === 'berhasil') {
                    messageSuccess('Selamat', 'Distribusi berhasil disimpan !');
                    window.location.reload();
                } else if (response.status === 'invalid') {
                    messageWarning('Perhatian', response.message);
                } else if (response.status === 'gagal') {
                    messageWarning('Gagal', response.message);
                }
            },
            error: function(e) {
                messageWarning('Perhatian', 'Terjadi kesalahan saat menyimpan distribusi, hubungi pengembang !');
            }
        });
    }
</script>
@endsection
