@extends('main')

@section('content')
<form class="formCodeProd">
    <!-- modal-code-production -->
    @include('marketing.agen.kelolapenjualan.modal-code-prod-base')
</form>


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
                                    <input type="hidden" id="userType" value="{{ Auth::user()->getCompany->c_type }}">
                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Tanggal</label>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" name="dateKPL" class="form-control form-control-sm datepicker" autocomplete="off" id="dateKPL">
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>

                                    <div class="col-md-2 col-sm-6 col-xs-12 select-agent">
                                        <label>Agen</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12 select-agent">
                                        <div class="form-group">
                                            <input type="hidden" value="{{ $data['user']->u_user }}" id="user">
                                            @if (Auth::user()->getCompany->c_type == 'PUSAT')
                                                <input type="hidden" name="agent" id="agent" value="{{ $data['kpl-agent']->getAgent->a_code }}">
                                            @else
                                                <input type="hidden" name="agent" id="agent" value="">
                                            @endif
                                            <select class="form-control form-control-sm select2" disabled>
                                                <option>{{ $data['kpl-agent']->c_name }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Member</label>
                                    </div>
                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="hidden" name="member" value="{{ $data['member']->m_code }}">
                                            <select class="form-control form-control-sm select2" disabled>
                                                <option value="" selected disabled>{{ $data['member']->m_name }}</option>
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
                                            <table class="table table-hover table-striped diplay nowrap w-100" id="table_create">
                                                <thead class="bg-primary">
                                                    <tr>
                                                        <th>Kode/Nama Barang</th>
                                                        <th width="10%">Satuan</th>
                                                        <th width="7%">Jumlah</th>
                                                        <th width="5%">Kode Produksi</th>
                                                        <th>Harga Satuan</th>
                                                        <th>Diskon @</th>
                                                        <th>Sub Total</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data['kpl']->getSalesDt as $key => $val)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="idItem[]" class="itemid" value="{{ $val->sd_item }}">
                                                            <input type="hidden" name="kode[]" class="kode" value="{{ $val->getItem->i_code }}">
                                                            <input type="hidden" name="idStock[]" class="idStock" value="{{ $val->stockId }}">
                                                            <input type="text" name="barang[]" class="form-control form-control-sm barang" value="{{ strtoupper($val->getItem->i_code) }} - {{ strtoupper($val->getItem->i_name) }}" autocomplete="off" @if($val->status == 'used') readonly @endif>
                                                        </td>
                                                        <td>
                                                            <select name="satuan[]" data-label="old" class="form-control form-control-sm select2 satuan">
                                                                    <option value="{{ $val->getItem->i_unit1 }}" data-unitcmp="{{ $val->getItem->i_unitcompare1 }}" @if($val->getUnit->u_id == $val->getItem->i_unit1) selected @endif>{{ $val->getItem->getUnit1->u_name }}</option>
                                                                @if ($val->getItem->i_unit2 != null && $val->getItem->i_unit2 != $val->getItem->i_unit1)
                                                                    <option value="{{ $val->getItem->i_unit2 }}" data-unitcmp="{{ $val->getItem->i_unitcompare2 }}" @if($val->getUnit->u_id == $val->getItem->i_unit2) selected @endif>{{ $val->getItem->getUnit2->u_name }}</option>
                                                                @endif
                                                                @if ($val->getItem->i_unit3 != null && $val->getItem->i_unit3 != $val->getItem->i_unit2 && $val->getItem->i_unit3 != $val->getItem->i_unit1)
                                                                    <option value="{{ $val->getItem->i_unit3 }}" data-unitcmp="{{ $val->getItem->i_unitcompare3 }}" @if($val->getUnit->u_id == $val->getItem->i_unit3) selected @endif>{{ $val->getItem->getUnit3->u_name }}</option>
                                                                @endif
                                                            </select>
                                                            <input type="hidden" name="oldSatuan" class="oldSatuan" value="{{ $val->getUnit->u_id }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" data-label="old" value="{{ $val->sd_qty }}">
                                                            <input type="hidden" name="qtyOld" class="qtyOld" value="{{ $val->sd_qty }}">
                                                            <!-- <input type="hidden" name="status[]" class="status" value="{{ $val->status }}"> -->
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button>
                                                        </td>
                                                        @if (Auth::user()->getCompany->c_type == 'CABANG')
                                                            <td>
                                                                <input type="text" name="harga[]" class="form-control form-control-sm rupiah harga" value="{{ (int)$val->sd_value }}">
                                                                <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;"> Harga tidak ditemukan!</p>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <input type="text" name="harga[]" class="form-control form-control-sm text-right harga" value="{{ Currency::addRupiah($val->sd_value) }}" readonly>
                                                                <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;"> Harga tidak ditemukan!</p>
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <input class="form-control form-control-sm diskon rupiah" id="diskon" name="diskon[]" value="{{ (int)$val->sd_discvalue }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="{{ Currency::addRupiah($val->sd_totalnet) }}" readonly>
                                                        </td>
                                                        <td>
                                                            @if ($key == 0)
                                                                <button type="button" class="btn btn-sm btn-success rounded-circle btn-tambahp"><i class="fa fa-plus"></i></button>
                                                                <button class="btn btn-danger rounded-circle btn-hapus btn-sm d-none" type="button">
                                                                    <i class="fa fa-remove" aria-hidden="true"></i>
                                                                </button>
                                                            @else
                                                                <button class="btn btn-danger rounded-circle btn-hapus btn-sm" type="button">
                                                                    <i class="fa fa-remove" aria-hidden="true"></i>
                                                                </button>
                                                            @endif
                                                        </td>
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
    var idStock = [];
    var idItem = [];
    var namaItem = null;
    var kode = null;
    var idxBarang = null;
    var icode = [];
    var checkitem = null;

    $(document).ready(function()
    {
        var dataKPL = {!! $data['kpl'] !!};
        salesdt = dataKPL.get_sales_dt;
        // set date kpl
        let dateKPL = dataKPL.s_date;
        dateKPL = dateKPL.split('-');
        $('#dateKPL').datepicker('setDate', new Date(dateKPL[2], parseInt(dateKPL[1]) - 1, dateKPL[0]));

        if ($('#userType').val() == 'PUSAT') {
            $('.select-agent').removeClass('d-none');
        } else {
            $('.select-agent').addClass('d-none');
        }

        // set modal production-code
        setModalCodeProdReady();
        // re-init events for some class or id
        getEventsReady();

        if ($(".itemid").eq(idxBarang).val() == "") {
            $(".jumlah").eq(idxBarang).attr("readonly", true);
            $(".satuan").eq(idxBarang).find('option').remove();
        }
        else {
            $(".jumlah").eq(idxBarang).attr("readonly", false);
        }

        // append a new row to insert more items
        $('.btn-tambahp').on('click',function(){
            tambah();
        });

        // check if there is any empty value in table
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

        $('#btn_simpan').on('click', function(evt) {
            evt.preventDefault();
            if (checkForm() == "cek form") {
                messageWarning('Peringatan', 'Lengkapi data penjualan langsung !');
            } else {
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Konfirmasi!',
                    content: 'Apakah anda yakin akan menyimpan data penjualan langsung ini ?',
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
        });

        $(".diskon").on('keyup', function (evt) {
            let idx = $('.diskon').index(this);
            let diskon = $('.diskon').eq(idx).val();
            let harga = $('.harga').eq(idx).val();
            let jumlah = $('.jumlah').eq(idx).val();
            let subharga = (parseInt(convertToAngka(harga)) - parseInt(diskon)) * parseInt(jumlah);
            $('.subtotal').eq(idx).val(convertToRupiah(subharga));
            updateTotalTampil();
        });
    }); // end: document ready

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
            // check stock using marketing controller
            axios.get(baseUrl +'/marketing/agen/orderproduk/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
            .then(function (resp) {
                loadingHide();
                $(".jumlah").eq(idx).val(resp.data);
                // trigger on-input 'jumlah'
                $(".jumlah").eq(idx).trigger('input');
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
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
        // skip get-prices if user is 'cabang'
        if ($('#userType').val() == 'CABANG') {
            // trigger diskon to 'keyup'
            $(".diskon").trigger('keyup');
            return false;
        }

        $.ajax({
            url : "{{ route('kelolapenjualan.getPrice') }}",
            data : {
                "itemId": $('.itemid').eq(idx).val(),
                "unitId": $('.satuan').eq(idx).val(),
                "agentCode": $('#agent').val(),
                "qty": qty
            },
            type : "get",
            dataType : 'json',
            success : function (response){
                var price = parseInt(response);;

                if (isNaN(price)) {
                    price = 0;
                }
                if (price == 0) {
                    $('.unknow').eq(idx).css('display', 'block');
                } else {
                    $('.unknow').eq(idx).css('display', 'none');
                }
                $('.harga').eq(idx).val(convertToRupiah(price));
                // trigger diskon to 'keyup'
                $(".diskon").trigger('keyup');

                updateTotalTampil();
            },
            error : function(e){
                console.error(e);
            }
        });
    }

    // check item stock
    function checkStock(idx, jumlah)
    {
        axios.get(baseUrl+'/marketing/konsinyasipusat/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
        .then(function (resp) {
            $(".jumlah").eq(idx).val(resp.data);

            getPrices(idx, jumlah);

        })
        .catch(function (error) {
            messageWarning("Error", error);
        })
    }
    // check item stock
    function checkStockOld(idx, qtyOld, jumlah)
    {
        axios.get(baseUrl+'/marketing/konsinyasipusat/cek-stok-old/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".oldSatuan").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+qtyOld+'/'+jumlah)
        .then(function (resp) {
            $(".jumlah").eq(idx).val(resp.data);

            getPrice(idx, jumlah);

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
        })
        .catch(function (error) {
            messageWarning("Error", error);
        })
    }

    function tambah() {
        var row = '';
        let harga = '';
        row = '<tr>' +
            '<td><input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"><input type="hidden" name="idStock[]" class="idStock"></td>' +
            '<td>' +
            '<select name="satuan[]" class="form-control form-control-sm select2 satuan">' +
            '</select>' +
            '</td>' +
            '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly></td>' +
            '<td><button class="btn btn-primary btnCodeProd btn-sm rounded" type="button">kode produksi</button></td>';

        if ($('#userType').val() == 'CABANG') {
            harga = '<td><input type="text" name="harga[]" class="form-control form-control-sm rupiah harga"><p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>';
        }
        else {
            harga = '<td><input type="text" name="harga[]" class="form-control form-control-sm text-right harga" value="Rp. 0" readonly><p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>';
        }

        row = row + harga + '<td><input type="text" name="diskon[]" style="text-align: right;" class="form-control form-control-sm diskon rupiah" value="Rp. 0"></td>'+
            '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>' +
            '<td>' +
            '<button class="btn btn-danger btn-hapus btn-sm" type="button">' +
            '<i class="fa fa-remove" aria-hidden="true"></i>' +
            '</button>' +
            '</td>' +
            '</tr>';
        $('#table_create tbody').append(row);
        // clone modal-code-production and insert new one
        $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
        // re-init events for some class or id
        getEventsReady();

        setArrayCode();

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

        $(".diskon").on('keyup', function (evt) {
            let idx = $('.diskon').index(this);
            let diskon = $('.diskon').eq(idx).val();
            let harga = $('.harga').eq(idx).val();
            let jumlah = $('.jumlah').eq(idx).val();
            let subharga = (parseInt(convertToAngka(harga)) - parseInt(diskon)) * parseInt(jumlah);
            $('.subtotal').eq(idx).val(convertToRupiah(subharga));
            updateTotalTampil();
        });

        updateTotalTampil();
    }

    // set modalCodeProd to be ready
    function setModalCodeProdReady()
    {
        $.each(salesdt, function (key, val) {
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
                    prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]" value="'+ val.sc_code +'"></input></td>';
                    qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="'+ val.sc_qty +'"></input></td>';
                    action = '';
                    if (idx == 0) {
                        action = '<td><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" title="Tambah Kode Produksi" type="button"><i class="fa fa-plus" aria-hidden="true"></button></td>';
                    }
                    else {
                        action = '<td><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                    }
                    listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
                    $('.modalCodeProd:eq('+ key +')').find('.table_listcodeprod').append(listProdCode);
                });
            }
            // rowBtnAdd = '<tr class="rowBtnAdd"><td colspan="3" class="text-center"><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>';
            // $('.modalCodeProd:eq('+ key +')').find('.table_listcodeprod').append(rowBtnAdd);
        });
    }

    function getEventsReady() {
        if ($('#userType').val() == 'CABANG') {
            $('.harga').on('keyup', function() {
                // trigger diskon to 'keyup'
                $(".diskon").trigger('keyup');
            });
        }

        // $('.barang').off();
        $(".satuan").off();
        $('.btn-hapus').off();
        $('.btnCodeProd').off();
        $('.btnAddProdCode').off();
        $('.btnRemoveProdCode').off();
        $('.qtyProdCode').off();

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
        changeJumlah();

        // remove a row from table
        $('.btn-hapus').on('click', function () {
            // get index of clicked element and delete a production-code-modal
            idxBarang = $('.btnRemoveItem').index(this);
            $('.modalCodeProd').eq(idxBarang).remove();
            $(this).parents('tr').remove();
            updateTotalTampil();
            setArrayCode();
        });
        // event to show modal to display list of code-production
        $('.btnCodeProd').on('click', function() {
            idxBarang = $('.btnCodeProd').index(this);
            // get unit-cmp from selected unit
            let unitCmp = parseInt($('.satuan').eq(idxBarang).find('option:selected').data('unitcmp')) || 0;
            let qty = parseInt($('.jumlah').eq(idxBarang).val()) || 0;
            let qtyUnit = qty * unitCmp;
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
            action = '<td><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
            listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
            // idxBarang is referenced from btnCodeProd above
            // $(listProdCode).insertBefore($('.modalCodeProd:eq('+ idxBarang +')').find('.table_listcodeprod .rowBtnAdd'));
            $('.modalCodeProd:eq('+ idxBarang +')').find('.table_listcodeprod').append(listProdCode);
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
        // $('.rupiah-without-dec').inputmask("currency", {
        //     radixPoint: ",",
        //     groupSeparator: ".",
        //     digits: 0,
        //     autoGroup: true,
        //     prefix: ' Rp ', //Space after $, this will not truncate the first character.
        //     rightAlign: true,
        //     autoUnmask: true,
        //     nullable: false,
        //     // unmaskAsNumber: true,
        // });
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

    function updateTotalTampil() {
        var total = 0;

        var inputs = document.getElementsByClassName('subtotal'),
        subtotal = [].map.call(inputs, function (input) {
            return input.value;
        });

        for (var i = 0; i < subtotal.length; i++) {
            total += parseInt(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
        }

        $("#total").val(convertToRupiah(total));

    }

    function setItem(info) {
        idStock = info.stock
        idItem = info.data.get_item.i_id;
        namaItem = info.data.get_item.i_name;
        kode = info.data.get_item.i_code;
        $(".kode").eq(idxBarang).val(kode);
        $(".itemid").eq(idxBarang).val(idItem);
        $(".idStock").eq(idxBarang).val(idStock);
        setArrayCode();
        $.ajax({
            url: '{{ url('/marketing/agen/kelolapenjualanlangsung/get-unit') }}'+'/'+idItem,
            type: 'GET',
            success: function( resp ) {
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

        let agentCode = $('#agent').val();

        $(".barang").eq(idxBarang).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: '{{ url('/marketing/agen/kelolapenjualanlangsung/find-item') }}',
                    data: {
                        idItem: item,
                        agent: agentCode,
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
    // // get member based on agent (used for Employee-Login)
    // function getMember()
    // {
    //     $.ajax({
    //         data : {
    //             "agentCode": $('#agent').val()
    //         },
    //         type : "get",
    //         url : "{{ route('kelolapenjualan.getMemberKPL') }}",
    //         dataType : 'json',
    //         success : function (response){
    //             if (! $.trim(response)) {
    //                 messageFailed('Perhatian', 'Tidak ada member terdaftar !');
    //             } else {
    //                 let optMember = '';
    //                 $.each(response, function(index, val) {
    //                     optMember += '<option value="'+ val.m_code +'">'+ val.m_name +'</option>';
    //                 })
    //                 $('#member').find('option').remove();
    //                 $('#member').append(optMember);
    //                 $('#member').selectedIndex = 0;
    //             }
    //         },
    //         error : function(e){
    //             console.error(e.message);
    //         }
    //     });
    // }

    function simpan() {
        loadingShow();
        var data = $('.myForm').serialize();
        // get list of production-code
        $.each($('.table_listcodeprod'), function(key, val) {
            // get length of production-code each items
            let prodCodeLength = $('.table_listcodeprod:eq('+ key +') :input.qtyProdCode').length;
            $('.modalCodeProd:eq('+ key +')').find('.prodcode-length').val(prodCodeLength);
            inputs = $('.table_listcodeprod:eq('+ key +') :input').serialize();
            data = data +'&'+ inputs;
        });

        axios.post('{{ url('/marketing/agen/kelolapenjualanlangsung/update/') }}' +'/'+ $('#salesId').val(), data)
        .then(function (response){
            loadingHide();
            if (response.data.status == 'berhasil') {
                messageSuccess("Berhasil", "Penjualan telah berhasil disimpan !");
                setInterval(function(){location.reload();}, 3500)
            }
            else if (response.data.status === 'invalid') {
                messageWarning('Perhatian', response.data.message);
            }
            else {
                messageFailed("Gagal", response.data.message);
            }

        })
        .catch(function (error) {
            loadingHide();
            messageWarning("Error", error);
        })
    }

</script>
@endsection
