@extends('main')

@section('content')

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
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['stockdist']->getDistributionDt as $key => $value)
                                            <tr>
                                                <td>
                                                    @if ($data['status'][$key] == 'used')
                                                        <input type="text" name="itemsName[]" data-counter="0" class="form-control form-control-sm itemsName" value="{{ $value->getItem->i_code }} - {{ $value->getItem->i_name }}" disabled>
                                                    @else
                                                        <input type="text" name="itemsName[]" data-counter="0" class="form-control form-control-sm itemsName" value="{{ $value->getItem->i_code }} - {{ $value->getItem->i_name }}">
                                                    @endif
                                                    <input type="hidden" name="itemsId[]" value="{{ $value->getItem->i_id }}" class="itemsId">
                                                    <input type="hidden" name="distDetailid[]" value="{{ $value->sdd_detailid }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="qty[]" class="form-control form-control-sm digits qty" value="{{ $value->sdd_qty }}">
                                                    <input type="hidden" name="qtyUsed[]" class="form-control form-control-sm digits qtyUsed" value="{{ $data['qtyUsed'][$key] }}">
                                                    <input type="hidden" class="qtyStock" value="{{ $data['qtyStock'][$key] }}">
                                                </td>
                                                <td>
                                                    <select name="units[]" class="form-control form-control-sm select2 units">
                                                        <option value="" disabled selected>Pilih Satuan</option>
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
                                                @if ($data['status'][$key] == 'used')
                                                    <td class="badge badge-warning">Stock sudah digunakan ({{ $data['qtyUsed'][$key] }})</td>
                                                    <input type="hidden" name="status[]" class="status" value="{{ $data['status'][$key]}}">
                                                @else
                                                    <td class="badge badge-primary">Stock belum digunakan</td>
                                                    <input type="hidden" name="status[]" class="status" value="{{ $data['status'][$key] }}">
                                                @endif
                                                <td>
                                                    @if ($key == 0)
                                                        <button class="btn btn-success btnAddItem btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                        @if ($data['status'][$key] != 'used')
                                                            <button class="btn btn-danger btnClearItem btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                        @endif
                                                    @else
                                                        @if ($data['status'][$key] != 'used')
                                                            <button class="btn btn-danger btnRemoveItem btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
        // event field items inside table
        getFieldsReady();
        // add more item in table_items
        $('.btnAddItem').on('click', function() {
            $('#table_items').append(
            `<tr>
                <td>
                    <input type="text" name="itemsName[]" data-counter="0" class="form-control form-control-sm itemsName" style="text-transform:uppercase">
                    <input type="hidden" name="itemsId[]" value="" class="itemsId">
                    <input type="hidden" name="distDetailid[]" value="">
                </td>
                <td>
                    <input type="text" name="qty[]" class="form-control form-control-sm digits qty" value="">
                    <input type="hidden" name="qtyUsed[]" class="form-control form-control-sm digits qtyUsed" value="">
                    <input type="hidden" class="qtyStock" value="">
                </td>
                <td>
                    <select name="units[]" class="form-control form-control-sm select2 units">
                        <option value="" disabled selected>Pilih Satuan</option>
                    </select>
                </td>
                <td class="badge badge-primary">
                    Stock belum digunakan
                    <input type="hidden" name="status[]" class="status" value="">
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
        $('.itemsName').off();
        $('.qty').off();
        $('.btnRemoveItem').off();
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
            checkQtyLimit();
        });
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
    // check qty-limit
    function checkQtyLimit()
    {
        qty = parseInt($('.qty').eq(idxItem).val());
        qtyUsed = parseInt($('.qtyUsed').eq(idxItem).val());
        qtyStock = parseInt($('.qtyStock').eq(idxItem).val());
        if (qty > qtyStock)
        {
            message = 'Stock hanya tersisa : ' + qtyStock;
            messageWarning('Perhatian', message);
            $('.qty').eq(idxItem).val(qtyStock);
        }
        else if (qty < qtyUsed || isNaN(qty))
        {
            message = 'Jumlah item tidak boleh lebih kecil dari stock terpakai !';
            messageWarning('Perhatian', message);
            $('.qty').eq(idxItem).val(qtyUsed);
        }
    }
    // submit new-distribusibarang
    function submitForm()
    {
        data = $('#formEditDist').serialize();
        $.ajax({
            url: baseUrl + "/inventory/distribusibarang/update/" + editedItemId,
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
                console.log('response: '+ response);
            },
            error: function(e) {
                messageWarning('Perhatian', 'Terjadi kesalahan saat menyimpan distribusi, hubungi pengembang !');
            }
        });
    }

</script>
@endsection
