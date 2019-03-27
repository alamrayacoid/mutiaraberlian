@extends('main')

@section('content')

<article class="content animated fadeInLeft">
    <div class="title-block text-primary">
        <h1 class="title"> Tambah Data Order Produk ke Cabang</h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Marketing</span>
            / <a href="{{route('mngagen.index')}}"><span>Manajemen Marketing Area </span></a>
            / <span class="text-primary" style="font-weight: bold;"> Tambah Data Order Produk ke Cabang </span>
        </p>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Tambah Data Order Produk ke Cabang </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a href="{{route('marketingarea.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <form action="" id="formOrder">
                            <section>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <label for="cabang">Nama Cabang</label>
                                          <input type="text" class="form-control bg-light" id="cabang" value="{{$produk->comp}}" readonly="" disabled="">
                                        </div>
                                        <div class="form-group col-md-6">
                                          <label for="nota">Nomer Nota</label>
                                          <input type="text" class="form-control bg-light" id="nota" value="{{$produk->po_nota}}" readonly="" disabled="">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                          <label for="agen">Nama Agen</label>
                                          <input type="text" class="form-control bg-light" id="agen" value="{{$produk->agen}}" readonly="" disabled="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="tanggal">Total Sub Harga</label>
                                            <input type="text" class="form-control" name="total_harga[]" id="total_harga" readonly>
                                            <input type="hidden" name="tot_hrg[]" id="tot_hrg">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" cellspacing="0" id="table_order">
                                            <thead class="bg-primary">
                                                <tr>
                                                    <th width="30%">Kode Barang/Nama Barang</th>
                                                    <th width="10%">Satuan</th>
                                                    <th width="10%">Jumlah</th>
                                                    <th width="25%">Harga Satuan</th>
                                                    <th width="25%">Sub Total</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($detail as $key => $dt)
                                                <tr>
                                                    <td>
                                                        <input type="text" name="barang[]"
                                                        class="form-control form-control-sm barang"
                                                        style="text-transform:uppercase" autocomplete="off" value="{{$dt->i_code}} - {{$dt->i_name}}">
                                                        <input type="hidden" name="idItem[]" class="itemId" value="{{$dt->pod_item}}">
                                                        <input type="hidden" name="kode[]" class="kode" value="{{$dt->i_code}}">
                                                    </td>
                                                    <td>
                                                        <select name="po_unit[]"
                                                            class="form-control form-control-sm select2 satuan">
                                                            <option value="{{$dt->pod_unit}}" selected>{{$dt->u_name}}</option>
                                                            @if($dt->uid_1 != $dt->pod_unit)
                                                                <option value="{{$dt->uid_1}}">{{$dt->uname_1}}</option>
                                                            @endif
                                                            @if($dt->uid_2 != $dt->pod_unit)
                                                                <option value="{{$dt->uid_2}}">{{$dt->uname_2}}</option>
                                                            @endif
                                                            @if($dt->uid_3 != $dt->pod_unit)
                                                                <option value="{{$dt->uid_3}}">{{$dt->uname_3}}</option>
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="po_qty[]" min="0" class="form-control form-control-sm jumlah" value="{{$dt->pod_qty}}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="po_harga[]" class="form-control form-control-sm input-rupiah harga bg-light" value="{{Currency::addRupiah($dt->pod_price)}}" readonly disabled>
                                                        <input type="hidden" name="po_hrg[]" class="po_hrg" value="{{$dt->pod_price}}">
                                                        <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="{{Currency::addRupiah($dt->pod_totalprice)}}" readonly disabled>
                                                        <input type="hidden" name="sbtotal[]" class="sbtotal" value=" {{$dt->pod_totalprice}}">
                                                    </td>
                                                    <td>
                                                        @if($key > 0)
                                                            <button class="btn btn-danger btn-hapus-order btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button>
                                                        @else
                                                            <button class="btn btn-success btn-tambah-order btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                            </section>
                        </form>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="button" onclick="updateOrder('{{Crypt::encrypt($produk->po_id)}}')">Simpan</button>
                        <a href="{{route('marketingarea.index')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

@endsection

@section('extra_script')
<script type="text/javascript">
    var idItem    = [];
    var namaItem  = null;
    var kode      = null;
    var idxBarang = null;
    var icode     = [];
    // Document Ready -------------------------------------------------
    $(document).ready(function() {
        changeJumlah();
        changeSatuan();
        changeBarang();
        updateTotalTampil();
        setArrayCode();

        $('.barang').on('click change', function (e) {
            idxBarang = $('.barang').index(this);
            $(".jumlah").eq(idxBarang).attr("readonly", false);
            $(".jumlah").eq(idxBarang).attr("disabled", false);
            setArrayCode();
        });
        $('.jumlah').on('click input', function (e) {
            idxBarang = $('.jumlah').index(this);
            console.log(idxBarang);
            setArrayCode();
        });

        $('.satuan').on('change', function (e) {
            idxBarang = $('.satuan').index(this);
            setArrayCode();
        });

        $(".barang").eq(idxBarang).on("keyup", function () {
            $(".itemId").eq(idxBarang).val('');
            $(".kode").eq(idxBarang).val('');
        });

        function setItem(info) {
            idItem = info.data.i_id;
            namaItem = info.data.i_name;
            kode = info.data.i_code;
            $(".kode").eq(idxBarang).val(kode);
            $(".itemId").eq(idxBarang).val(idItem);
            setArrayCode();
            $.ajax({
                url: '{{ url('/marketing/marketingarea/orderproduk/get-satuan') }}' + '/' + idItem,
                type: 'GET',
                success: function (resp) {
                    $(".satuan").eq(idxBarang).find('option').remove();
                    var option = '';
                    if (resp.id1 != null) {
                        option += '<option value="' + resp.id1 + '">' + resp.unit1 + '</option>'
                    }
                    if (resp.id2 != null && resp.id2 != resp.id1) {
                        option += '<option value="' + resp.id2 + '">' + resp.unit2 + '</option>';
                    }
                    if (resp.id3 != null && resp.id3 != resp.id1) {
                        option += '<option value="' + resp.id3 + '">' + resp.unit3 + '</option>';
                    }
                    $(".satuan").eq(idxBarang).append(option);
                }
            });
        }

        function setArrayCode() {
            var inputs = document.getElementsByClassName('kode'),
                code   = [].map.call(inputs, function (input) {
                    return input.value.toString();
                });

            for (var i = 0; i < code.length; i++) {
                if (code[i] != "") {
                    icode.push(code[i]);
                }
            }

            var item = [];
            var inpItemId = document.getElementsByClassName('itemId'),
                item      = [].map.call(inpItemId, function (input) {
                    return input.value;
                });

            $(".barang").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ url('/marketing/marketingarea/orderproduk/cari-barang') }}",
                        data: {
                            idItem: item,
                            term: $(".barang").eq(idxBarang).val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    setItem(data.item);
                }
            });
        }

        // Tambah Form Order ------------------------------------------
        $('.btn-tambah-order').on('click', function() {
            $('#table_order')
                .append(
                    `<tr>
                        <td>
                            <input type="text" name="barang[]" class="form-control form-control-sm barang"
                            style="text-transform:uppercase" autocomplete="off">
                            <input type="hidden" name="idItem[]" class="itemId">
                            <input type="hidden" name="kode[]" class="kode">
                        </td>
                        <td><select name="po_unit[]" class="form-control form-control-sm select2 satuan">
                            </select>
                        </td>
                        <td><input type="number" name="po_qty[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly="" disabled=""></td>
                        <td>
                            <input type="text" name="po_harga[]" class="form-control form-control-sm input-rupiah harga bg-light" value="Rp. 0" readonly disabled>
                            <input type="hidden" name="po_hrg[]" class="po_hrg">
                            <p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p>
                        </td>
                        <td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" readonly>
                            <input type="hidden" name="sbtotal[]" class="sbtotal">
                        </td>
                        <td><button class="btn btn-danger btn-hapus-order btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>
                    </tr>`
                );

            $('.select2').select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                width: '100%'
            });

            $('.input-rupiah').maskMoney({
                thousands: ".",
                precision: 0,
                decimal: ",",
                prefix: "Rp. "
            });

            $('.barang').on('click change', function (e) {
                idxBarang = $('.barang').index(this);
                $(".jumlah").eq(idxBarang).attr("readonly", false);
                $(".jumlah").eq(idxBarang).attr("disabled", false);
                setArrayCode();
            });

            $('.jumlah').on('click input', function (e) {
                idxBarang = $('.jumlah').index(this);
                setArrayCode();             
            });

            $('.satuan').on('change', function (e) {
                idxBarang = $('.satuan').index(this);
                setArrayCode();
            });

            $(".barang").eq(idxBarang).on("keyup", function () {
                $(".itemId").eq(idxBarang).val('');
                $(".kode").eq(idxBarang).val('');
            });

            changeJumlah();
            changeSatuan();
            changeBarang()
        });
        // End Form Order ---------------------------------------------

        // Hapus Form -------------------------------------------------
        $(document).on('click', '.btn-hapus-order', function () {
            $(this).parents('tr').remove();
            updateTotalTampil();
        });
        // End Hapus Form
    });
    // End Document Ready ---------------------------------------------

    function changeBarang() {
        $(".barang").on('change', function (evt) {
            evt.preventDefault();
            $(".jumlah").eq(idxBarang).attr("readonly", false);
            $(".jumlah").eq(idxBarang).attr("disabled", false);
            everyChange();
        });
    }
    
    function changeJumlah() {
        $('.jumlah').on('click input', function (evt) {
            evt.preventDefault();
            everyChange();
        });
    }

    function changeSatuan() {
        $(".satuan").on('change', function (evt) {
            evt.preventDefault();
            everyChange();
        });
    }

    function everyChange()
    {        
        var inpBarang = document.getElementsByClassName( 'barang' ),
            barang    = [].map.call(inpBarang, function( input ) {
                return parseInt(input.value);
            });
        var inpSatuan = document.getElementsByClassName( 'satuan' ),
            satuan    = [].map.call(inpSatuan, function( input ) {
                return parseInt(input.value);
            });
        var inpJumlah = document.getElementsByClassName( 'jumlah' ),
            jumlah    = [].map.call(inpJumlah, function( input ) {
                return parseInt(input.value);
            });

        $.ajax({
            url: "{{url('/marketing/marketingarea/orderproduk/get-price')}}",
            type: "GET",
            data: {
                item : $('.itemId').eq(idxBarang).val(),
                unit: $('.satuan').eq(idxBarang).val(),
                qty: $('.jumlah').eq(idxBarang).val()
            },
            success:function(res)
            {
                var price = res.data;
                if (isNaN(price)) {
                    price = 0;
                }

                if (price == 0) {
                    $('.unknow').eq(idxBarang).css('display', 'block');
                } else {
                    $('.unknow').eq(idxBarang).css('display', 'none');
                }

                $('.harga').eq(idxBarang).val(convertToRupiah(price));
                $('.po_hrg').eq(idxBarang).val(price);

                var inpHarga = document.getElementsByClassName( 'harga' ),
                    harga    = [].map.call(inpHarga, function( input ) {
                        return input.value;
                    });

                var inpSubtotal = document.getElementsByClassName( 'subtotal' ),
                    subtotal    = [].map.call(inpSubtotal, function( input ) {
                        return input.value;
                    });                        

                var hasil = 0;
                var hrg = $('.harga').eq(idxBarang).val().replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                var jml = $('.jumlah').eq(idxBarang).val();

                if (jml == "") {
                    jml = 0;
                }

                hasil += parseInt(hrg) * parseInt(jml);

                if (isNaN(hasil)) {
                    hasil = 0;
                }
                hasil = convertToRupiah(hasil);
                $(".subtotal").eq(idxBarang).val(hasil);
                updateTotalTampil();
            }
        });
    }

    // Memperbarui Sub Total ------------------------------------------
    function updateTotalTampil() {
        var total = 0;

        var inputs   = document.getElementsByClassName('subtotal'),
            subtotal = [].map.call(inputs, function (input) {
                return input.value;
            });

        for (var i = 0; i < subtotal.length; i++) {
            total += parseInt(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
            $('.sbtotal').eq(i).val(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
        }
        $("#tot_hrg").val(total);
        if (isNaN(total)) {
            total = 0;
        }
        $("#total_harga").val(convertToRupiah(total));
    }
    // End Code -------------------------------------------------------

    // Menampilkan List Kota Berdasarkan Id Provinsi ------------------
    function getProvId() {
        var id = document.getElementById("prov").value;
        $.ajax({
            url: "{{route('orderProduk.getCity')}}",
            type: "get",
            data:{
                provId: id
            },
            success: function (response) {
                $('#city').empty();
                $("#city").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
                $.each(response.data, function( key, val ) {
                    $("#city").append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
                });
                $('#city').focus();
                $('#city').select2('open');
            }
        });
    }
    // End Code -------------------------------------------------------

    // Menampilkan List Agen Berdasarkan Id Kota ------------------
    function getCityId() {
        var id = document.getElementById("city").value;
        $.ajax({
            url: "{{url('/marketing/marketingarea/orderproduk/get-agen')}}",
            type: "get",
            data:{
                cityId: id
            },
            success: function (response) {
                $('#agen').empty();
                if (response.data.length == 0) {
                    $("#agen").append('<option value="" selected disabled>=== Pilih Agen ===</option>');
                } else {
                    $("#agen").append('<option value="" selected disabled>=== Pilih Agen ===</option>');
                    $.each(response.data, function( key, val ) {
                        $("#agen").append('<option value="'+val.c_id+'">'+val.c_name+'</option>');
                    });                    
                }
                $('#agen').focus();
                $('#agen').select2('open');
            }
        });
    }
    // End Code -------------------------------------------------------

    // Simpan Order Produk --------------------------------------------
    function updateOrder(id){
        $.ajax({
            url: "{{url('/marketing/marketingarea/orderproduk/update')}}"+"/"+id,
            type: "get",
            data: $('#formOrder').serialize(),
            beforeSend: function () {
                loadingShow();
            },
            success: function (response) {
                if (response.status == 'sukses') {
                    window.location.href = "{{route('marketingarea.index')}}";
                    loadingHide();
                    messageSuccess('Success', 'Data berhasil diperbarui!');
                } else {
                    loadingHide();
                    messageFailed('Gagal', response.message);
                }
            },
            error: function (e) {
                loadingHide();
                messageWarning('Peringatan', e.message);
            }
        });
    }
</script>
@endsection
