@extends('main')

@section('content')
    <form class="formCodeProd">
        <!-- modal-code-production -->
        @include('marketing.marketingarea.datakonsinyasi.modal-code-prod')
        @include('marketing.marketingarea.datakonsinyasi.modal-code-prod-base')

    </form>

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Konsinyasi </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Marketing</span>
         / <a href="{{route('marketingarea.index')}}"><span>Manajemen Marketing Area </span></a>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Data Konsinyasi </span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Data Konsinyasi </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('marketingarea.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <form id="formKonsinyasi" method="post">
                        {{ csrf_field() }}
                        <div class="card-block">
                            <section>
                                <div class="row">
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Nama</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm agent" name="agentName">
                                            <input type="hidden" class="agentCode" name="agentCode" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Email</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm email" name="email">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>No Telp</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm hp" name="telp">
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Alamat</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <textarea name="address" id="" class="form-control form-control-sm"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Total</label>
                                    </div>

                                    <div class="col-md-10 col-sm-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-sm" name="total_harga" id="total_harga" value="Rp. 0" readonly>
                                            <input type="hidden" name="tot_hrg" id="tot_hrg">
                                        </div>
                                    </div>

                                    <div class="container" id="tbl_item">
                                        <div class="table-responsive mt-3">
                                            <table class="table table-hover table-striped" id="table_rencana" cellspacing="0">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th>Kode/Nama Barang</th>
                                                        <th width="10%">Satuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Kode Produksi</th>
                                                        <th>Harga Satuan</th>
                                                        <th>Sub Total</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="idItem[]" class="itemid">
                                                            <input type="hidden" name="kode[]" class="kode">
                                                            <input type="hidden" name="idStock[]" class="idStock">
                                                            <input type="text"
                                                                name="barang[]"
                                                                class="form-control form-control-sm barang"
                                                                autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <select name="satuan[]" class="form-control form-control-sm select2 satuan"></select>
                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                name="jumlah[]"
                                                                min="0"
                                                                class="form-control form-control-sm jumlah"
                                                                value="0" readonly>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button>
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                name="harga[]"
                                                                class="form-control form-control-sm text-right harga"
                                                                value="Rp. 0" readonly>
                                                            <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly>
                                                            <input type="hidden" name="sbtotal[]" class="sbtotal">
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-success rounded-circle btn-tambahp"><i
                                                                class="fa fa-plus"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                            <a href="{{route('marketingarea.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>

      </div>

    </div>

  </section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
    var idStock = [];
    var idItem = [];
    var namaItem = null;
    var kode = null;
    var idxBarang = null;
    var icode = [];
    var checkitem = null;

    $(document).ready(function() {
        changeJumlah();
        changeHarga();
        visibleTableItem();

        $(document).on('click', '.btn-hapus', function () {
            // get index of clicked element and delete a production-code-modal
            idxBarang = $('.btnRemoveItem').index(this);
            $('.modalCodeProd').eq(idxBarang).remove();
            $(this).parents('tr').remove();
            updateTotalTampil();
            setArrayCode();
        });

        // re-init events for some class or id
        getEventsReady();

        if ($(".itemid").eq(idxBarang).val() == "") {
            $(".jumlah").eq(idxBarang).attr("readonly", true);
            $(".satuan").eq(idxBarang).find('option').remove();
        }
        else{
            $(".jumlah").eq(idxBarang).attr("readonly", false);
        }

        $('.btn-tambahp').on('click', function () {
            tambah();
        });

        function checkForm() {
            var inpItemid = document.getElementsByClassName( 'itemid' ),
            item  = [].map.call(inpItemid, function( input ) {
                return input.value;
            });
            var inpHarga = document.getElementsByClassName( 'harga' ),
            harga  = [].map.call(inpHarga, function( input ) {
                return input.value;
            });
            var inpJumlah = document.getElementsByClassName( 'jumlah' ),
            jumlah  = [].map.call(inpJumlah, function( input ) {
                return parseInt(input.value);
            });

            for (var i=0; i < item.length; i++) {
                if (item[i] == "" || harga[i] == "Rp. 0" || jumlah[i] == 0) {
                    return "cek form";
                    break;
                } else {
                    checkitem = "true";
                    continue;
                }
            }
            return checkitem;
        }

        $(document).on('click', '.btn-submit', function (evt) {
            evt.preventDefault();
            if (checkForm() == "cek form") {
                messageWarning('Peringatan', 'Lengkapi data penempatan produk');
            } else {
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Konfirmasi!',
                    content: 'Apakah anda yakin akan menyimpan data penempatan produk ini?',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Ya',
                            action: function () {
                                simpan();
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
        })
    });

    function getEventsReady() {
        // $('.barang').off();
        $(".satuan").off();
        $('.btnCodeProd').off();
        $('.btnAddProdCode').off();
        $('.btnRemoveProdCode').off();
        $('.qtyProdCode').off();
        // agent autocomplete
        $(".agent").autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "{{ route('datakonsinyasi.getAgentsDK') }}",
                    data: {
                        term: $(".agent").val()
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 1,
            select: function(event, data) {
                $('.agentCode').val(data.item.id);
                visibleTableItem();
            }
        });

        $('.barang').on('click', function(e){
            idxBarang = $('.barang').index(this);
            setArrayCode();
        });
        $('.barang').on('keyup', function (evt) {
            idxBarang = $('.barang').index(this);
            if (evt.which == 8 || evt.which == 46)
            {
                $(".itemid").eq(idxBarang).val('');
                $(".kode").eq(idxBarang).val('');
                $(".idStock").eq(idxBarang).val('');
                setArrayCode();
                if ($(".itemid").eq(idxBarang).val() == "") {
                    $(".jumlah").eq(idxBarang).val(0);
                    $(".harga").eq(idxBarang).val("Rp. 0");
                    $(".subtotal").eq(idxBarang).val("Rp. 0");
                    $(".jumlah").eq(idxBarang).attr("readonly", true);
                    $(".satuan").eq(idxBarang).find('option').remove();
                    updateTotalTampil();
                }
                else{
                    $(".jumlah").eq(idxBarang).val(0);
                    $(".harga").eq(idxBarang).val("Rp. 0");
                    $(".subtotal").eq(idxBarang).val("Rp. 0");
                    $(".jumlah").eq(idxBarang).attr("readonly", false);
                    updateTotalTampil();
                }
            }
            else if (evt.which <= 90 && evt.which >= 48)
            {
                $(".itemid").eq(idxBarang).val('');
                $(".kode").eq(idxBarang).val('');
                $(".idStock").eq(idxBarang).val('');
                setArrayCode();
                if ($(".itemid").eq(idxBarang).val() == "") {
                    $(".jumlah").eq(idxBarang).val(0);
                    $(".harga").eq(idxBarang).val("Rp. 0");
                    $(".jumlah").eq(idxBarang).attr("readonly", true);
                    $(".satuan").eq(idxBarang).find('option').remove();
                    updateTotalTampil();
                }else{
                    $(".jumlah").eq(idxBarang).val(0);
                    $(".harga").eq(idxBarang).val("Rp. 0");
                    $(".jumlah").eq(idxBarang).attr("readonly", false);
                    updateTotalTampil();
                }
            }
        });
        changeSatuan();

        // event to show modal to display list of code-production
        $('.btnCodeProd').on('click', function() {
            idxBarang = $('.btnCodeProd').index(this);
            // get unit-cmp from selected unit
            let unitCmp = parseInt($('.satuan').eq(idxBarang).find('option:selected').data('unitcmp')) || 0;
            let qty = parseInt($('.jumlah').eq(idxBarang).val()) || 0;
            let qtyUnit = qty * unitCmp;
            console.log('unitCmp: '+ unitCmp);
            console.log('qty: '+ qty);
            console.log('qtyUnit: '+ qtyUnit);
            // pass qtyUnit to modal
            $('.modalCodeProd').eq(idxBarang).find('.QtyH').val(qtyUnit);
            $('.modalCodeProd').eq(idxBarang).find('.usedUnit').val($('.satuan').eq(idxBarang).find('option:first-child').text());
            calculateProdCodeQty();
            $('.modalCodeProd').eq(idxBarang).modal('show');
        });
        // event to add more row to insert production-code
        $('.btnAddProdCode').on('click', function() {
            prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
            qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input></td>';
            action = '<td><button class="btn btn-success btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
            // idxBarang is referenced from btnCodeProd above
            $(listProdCode).insertBefore($('.modalCodeProd:eq('+ idxBarang +')').find('.table_listcodeprod .rowBtnAdd'));
            // $('.modalCodeProd:eq('+ idxBarang +')').find('.table_listcodeprod').append(listProdCode);
            getEventsReady();
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

    function changeSatuan() {
        // set-off first to prevent duplicate request from the previous item
        // $(".satuan").off();
        $(".satuan").on("change", function (evt) {
            loadingShow();
            var idx = $('.satuan').index(this);
            var jumlah = $('.jumlah').eq(idx).val();
            if (jumlah == "") {
                jumlah = null;
            }
            $.ajax({
                url: "{{ route('datakonsinyasi.checkItemStockDK') }}",
                data: {
                    idStock: $(".idStock").eq(idx).val(),
                    itemId: $(".itemid").eq(idx).val(),
                    unit: $(".satuan").eq(idx).val(),
                    qty: jumlah
                },
                type: 'get',
                success: function (resp) {
                    console.log('changeStauan: '+ resp);
                    loadingHide();
                    $(".jumlah").eq(idx).val(resp.data);
                    // trigger on-input 'jumlah'
                    $(".jumlah").eq(idx).trigger('input');

                    var inpJumlah = document.getElementsByClassName( 'jumlah' ),
                    jumlah  = [].map.call(inpJumlah, function( input ) {
                        return parseInt(input.value);
                    });

                    var inpHarga = document.getElementsByClassName( 'harga' ),
                    harga  = [].map.call(inpHarga, function( input ) {
                        return input.value;
                    });

                    for (var i = 0; i < jumlah.length; i++) {
                        var hasil = 0;
                        var hrg = harga[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                        var jml = jumlah[i];

                        if (jml == "") {
                            jml = 0;
                        }

                        hasil += parseInt(hrg) * parseInt(jml);

                        if (isNaN(hasil)) {
                            hasil = 0;
                        }
                        hasil = convertToRupiah(hasil);
                        $(".subtotal").eq(i).val(hasil);
                    }
                    updateTotalTampil();
                },
                error: function (e) {
                    loadingHide();
                    messageWarning('Error', e.message)
                }
            });
        })
    }

    function changeJumlah() {
        // set-off first to prevent duplicate request from the previous item
        $(".jumlah").off();
        $(".jumlah").on('input', function (evt) {
            var idx = $('.jumlah').index(this);
            var jumlah = $('.jumlah').eq(idx).val();
            if (jumlah == "") {
                jumlah = null;
            }
            // get item price
            getPrices(idx, jumlah);
        })
    }
    // get price from selected item and count all sub-total
    function getPrices(idx, qty) {
        let jumlah = qty;
        $.ajax({
            url: "{{ route('datakonsinyasi.checkItemStockDK') }}",
            data: {
                idStock: $(".idStock").eq(idx).val(),
                itemId: $(".itemid").eq(idx).val(),
                unit: $(".satuan").eq(idx).val(),
                qty: jumlah
            },
            type: 'get',
            success: function (resp) {
                $(".jumlah").eq(idx).val(resp);

                var tmp_jumlah = $('.jumlah').eq(idx).val();
                $.ajax({
                    url: "{{ route('datakonsinyasi.checkHargaDK') }}",
                    data: {
                        agentCode: $(".agentCode").val(),
                        itemId: $(".itemid").eq(idx).val(),
                        unit: $(".satuan").eq(idx).val(),
                        qty: tmp_jumlah
                    },
                    type: 'get',
                    success: function (response) {
                        var price = response;

                        if (isNaN(price)) {
                            price = 0;
                        }
                        if (price == 0) {
                            $('.unknow').eq(idx).css('display', 'block');
                        } else {
                            $('.unknow').eq(idx).css('display', 'none');
                        }
                        $('.harga').eq(idx).val(convertToRupiah(price));

                        var inpJumlah = document.getElementsByClassName( 'jumlah' ),
                        jumlah  = [].map.call(inpJumlah, function( input ) {
                            return parseInt(input.value);
                        });

                        var inpHarga = document.getElementsByClassName( 'harga' ),
                        harga  = [].map.call(inpHarga, function( input ) {
                            return input.value;
                        });

                        for (var i = 0; i < jumlah.length; i++) {
                            var hasil = 0;
                            var hrg = harga[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                            var jml = jumlah[i];

                            if (jml == "") {
                                jml = 0;
                            }

                            hasil += parseInt(hrg) * parseInt(jml);

                            if (isNaN(hasil)) {
                                hasil = 0;
                            }
                            hasil = convertToRupiah(hasil);
                            $(".subtotal").eq(i).val(hasil);
                        }
                        updateTotalTampil();
                    },
                    error: function (err) {
                        messageWarning('Error', err.message);
                    }
                });
            },
            error: function (e) {
                messageWarning('Error', e.message);
            }
        })
        // axios.get(baseUrl+'/marketing/konsinyasipusat/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
        // .then(function (resp) {
        //     $(".jumlah").eq(idx).val(resp.data);
        //
        //     var tmp_jumlah = $('.jumlah').eq(idx).val();
        //
        //     axios.get(baseUrl+'/marketing/konsinyasipusat/cek-harga/'+$("#kodeKonsigner").val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+tmp_jumlah)
        //     .then(function (res) {
        //
        //     });
        // })
        // .catch(function (error) {
        //     messageWarning("Error", error);
        // });
    }

    function changeHarga() {
        $(".harga").on('keyup', function (evt) {
            var inpJumlah = document.getElementsByClassName( 'jumlah' ),
                jumlah  = [].map.call(inpJumlah, function( input ) {
                    return parseInt(input.value);
                });

            var inpHarga = document.getElementsByClassName( 'harga' ),
                harga  = [].map.call(inpHarga, function( input ) {
                    return input.value;
                });

            for (var i = 0; i < harga.length; i++) {
                var hasil = 0;
                var hrg = harga[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                var jml = jumlah[i];

                if (jml == "") {
                    jml = 0;
                }

                hasil += parseInt(hrg) * parseInt(jml);

                if (isNaN(hasil)) {
                    hasil = 0;
                }
                hasil = convertToRupiah(hasil);
                $(".subtotal").eq(i).val(hasil);
            }
            updateTotalTampil();
        })
    }

    function tambah() {
        var row = '';
        row = '<tr>' +
            '<td><input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"><input type="hidden" name="idStock[]" class="idStock"></td>'+
            '<td>'+
            '<select name="satuan[]" class="form-control form-control-sm select2 satuan">'+
            '</select>'+
            '</td>'+
            '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly></td>'+
            '<td><button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button></td>' +
            '<td><input type="text" name="harga[]" class="form-control form-control-sm text-right harga" value="Rp. 0" readonly><p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>'+
            '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>'+
            '<td>'+
            '<button class="btn btn-danger btn-hapus btn-sm" type="button">'+
            '<i class="fa fa-remove" aria-hidden="true"></i>'+
            '</button>'+
            '</td>'+
            '</tr>';
        $('#table_rencana tbody').append(row);
        // clone modal-code-production and insert new one
        $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
        // re-init events for some class or id
        getEventsReady();

        changeJumlah();
        changeHarga();

        setArrayCode();

        $('.input-rupiah').maskMoney({
            thousands: ".",
            precision: 0,
            decimal: ",",
            prefix: "Rp. "
        });
        updateTotalTampil();
    }

    function simpan() {
        loadingShow();
        var data = $('#formKonsinyasi').serialize();
        // get list of production-code
        $.each($('.table_listcodeprod'), function(key, val) {
            // get length of production-code each items
            let prodCodeLength = $('.table_listcodeprod:eq('+ key +') :input.qtyProdCode').length;
            $('.modalCodeProd:eq('+ key +')').find('.prodcode-length').val(prodCodeLength);
            inputs = $('.table_listcodeprod:eq('+ key +') :input').serialize();
            data = data +'&'+ inputs;
        });

        $.ajax({
            url: "{{ route('datakonsinyasi.storeDK') }}",
            data: data,
            type: 'post',
            success: function (response) {
                loadingHide();
                if (response.status == 'Success') {
                    messageSuccess("Berhasil", response.message);
                    setInterval(function(){location.reload();}, 3500)
                }
                else if (response.status === 'invalid') {
                    messageWarning('Perhatian', response.message);
                }
                else {
                    messageFailed("Gagal", response.message);
                }
            },
            error: function (err) {
                loadingHide();
                messageWarning('Error', err.message);
            }
        });

        //
        // axios.post('{{ route('penempatanproduk.add') }}', data)
        //     .then(function (response){
        //
        //
        //     })
        //     .catch(function (error) {
        //         loadingHide();
        //         messageWarning("Error", error);
        //     })
    }

    function updateTotalTampil() {
        var total = 0;

        var inputs = document.getElementsByClassName('subtotal'),
            subtotal = [].map.call(inputs, function (input) {
                return input.value;
            });

        for (var i = 0; i < subtotal.length; i++) {
            total += parseInt(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
        }
        $("#tot_hrg").val(total);
        if (isNaN(total)) {
            total = 0;
        }
        $("#total_harga").val(convertToRupiah(total));
    }

    function setItem(info) {
        idStock = info.stock
        idItem = info.data.i_id;
        namaItem = info.data.i_name;
        kode = info.data.i_code;
        $(".kode").eq(idxBarang).val(kode);
        $(".itemid").eq(idxBarang).val(idItem);
        $(".idStock").eq(idxBarang).val(idStock);
        setArrayCode();
        $.ajax({
            url: baseUrl+ '/marketing/marketingarea/datakonsinyasi/get-satuan/' +idItem,
            type: 'GET',
            success: function( resp ) {
                console.log('getSatuan: '+ resp);
                $(".satuan").eq(idxBarang).find('option').remove();
                var option = '';
                option += '<option value="'+resp.i_unit1+'" data-unitcmp="'+ resp.i_unitcompare1 +'">'+resp.get_unit1.u_name+'</option>';
                if (resp.i_unit2 != null && resp.i_unit2 != resp.i_unit1) {
                    option += '<option value="'+resp.i_unit2+'" data-unitcmp="'+ resp.i_unitcompare2 +'">'+resp.get_unit2.u_name+'</option>';
                }
                if (resp.i_unit3 != null && resp.i_unit3 != resp.i_unit2 && resp.i_unit3 != resp.i_unit1) {
                    option += '<option value="'+resp.i_unit3+'" data-unitcmp="'+ resp.i_unitcompare3 +'">'+resp.get_unit3.u_name+'</option>';
                }
                $(".satuan").eq(idxBarang).append(option);
                if ($(".itemid").eq(idxBarang).val() == "") {
                    $(".jumlah").eq(idxBarang).attr("readonly", true);
                    $(".satuan").eq(idxBarang).find('option').remove();
                }else{
                    $(".jumlah").eq(idxBarang).attr("readonly", false);
                }
            }
        });
    }
    // get items
    function setArrayCode() {
        var inputs = document.getElementsByClassName('kode'),
            code  = [].map.call(inputs, function( input ) {
                return input.value.toString();
            });

        for (var i=0; i < code.length; i++) {
            if (code[i] != "") {
                icode.push(code[i]);
            }
        }

        var inpItemid = document.getElementsByClassName( 'itemid' ),
            item  = [].map.call(inpItemid, function( input ) {
                return input.value;
            });

        $(".barang").eq(idxBarang).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "{{ route('datakonsinyasi.getItemsDK') }}",
                    data: {
                        idItem: item,
                        term: $(".barang").eq(idxBarang).val()
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 1,
            select: function(event, data) {
                setItem(data.item);
            }
        });
    }

    function visibleTableItem() {
        if ($(".agentCode").val() != "") {
            $("#tbl_item").show('slow');
            $(".btn-submit").attr("disabled", false);
            $(".btn-submit").css({"cursor":"pointer"});
        }
        else {
            $("#tbl_item").hide('slow');
            $(".btn-submit").attr("disabled", true);
            $(".btn-submit").css({"cursor":"not-allowed"});
        }
    }

    function getProv() {
        loadingShow();
        $("#provinsi").find('option').remove();
        $("#provinsi").attr("disabled", true);
        axios.get('{{ route('konsinyasipusat.getProv') }}')
            .then(function (resp) {
                $("#provinsi").attr("disabled", false);
                var option = '<option value="">Pilih Provinsi</option>';
                var prov = resp.data;
                prov.forEach(function (data) {
                    option += '<option value="'+data.wp_id+'">'+data.wp_name+'</option>';
                })
                $("#provinsi").append(option);
                loadingHide();
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error)
            })
    }

    function getKota() {
        $("#provinsi").on("change", function (evt) {
            evt.preventDefault();
            $("#idKonsigner").val('');
            $("#kodeKonsigner").val('');
            $("#konsigner").val('');
            $("#kota").find('option').remove();
            $("#kota").attr("disabled", true);
            $("#konsigner").attr("disabled", true);
            if ($("#provinsi").val() != "") {
                loadingShow();
                axios.get(baseUrl+'/marketing/konsinyasipusat/get-kota/'+$("#provinsi").val())
                    .then(function (resp) {
                        $("#kota").attr("disabled", false);
                        var option = '<option value="">Pilih Kota</option>';
                        var kota = resp.data;
                        kota.forEach(function (data) {
                            option += '<option value="'+data.wc_id+'">'+data.wc_name+'</option>';
                        })
                        $("#kota").append(option);
                        loadingHide();
                        $("#kota").focus();
                    })
                    .catch(function (error) {
                        loadingHide();
                        messageWarning("Error", error)
                    })
            }
        })
    }

    // check production code qty each item
    function calculateProdCodeQty()
    {
        let QtyH = parseInt($('.modalCodeProd').eq(idxBarang).find('.QtyH').val());
        let qtyWithProdCode = getQtyWithProdCode();
        let restQty = QtyH - qtyWithProdCode;

        if (restQty < 0) {
            $(':focus').val(0);
            qtyWithProdCode = getQtyWithProdCode();
            restQty = QtyH - qtyWithProdCode;
            $('.modalCodeProd').eq(idxBarang).find('.restQty').val(restQty);
            messageWarning('Perhatian', 'Jumlah item untuk penetapan kode produksi tidak boleh melebihi jumlah item yang ada !');
        } else {
            $('.modalCodeProd').eq(idxBarang).find('.restQty').val(restQty);
        }
    }
    function getQtyWithProdCode()
    {
        qtyWithProdCode = 0;
        $.each($('.modalCodeProd:eq('+idxBarang+') .table_listcodeprod').find('.qtyProdCode'), function (key, val) {
            qtyWithProdCode += parseInt($(this).val());
        });
        return qtyWithProdCode;
    }

</script>
@endsection
