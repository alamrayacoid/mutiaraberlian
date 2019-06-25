@extends('main')

@section('content')
    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Order Produk ke Agen/Cabang </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Marketing</span>
                / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen </span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Order Produk ke Agen/Cabang </span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data Order Produk ke Agen/Cabang </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formManagemenAgen">{{ csrf_field() }}
                            <div class="card-block">
                                <section>

                                    <div class="row">

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Order Ke</label>
                                        </div>

                                        <div class="col-md-10 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="select_order" id="select-order" class="form-control form-control-sm select2">
                                                    <option value="0">Pilih</option>
                                                    <option value="1">Agen</option>
                                                    <option value="2">Cabang</option>
                                                </select>
                                            </div>
                                        </div>
                                        @include('marketing.agen.orderproduk.agen')
                                        @include('marketing.agen.orderproduk.cabang')
                                    </div>
                                </section>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                                <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary">Kembali</a>
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
        //Agen
        var idStock = [];
        var idItem = [];
        var namaItem = null;
        var kode = null;
        var idxBarang = null;
        var icode = [];
        var checkitem = null;

        //Cabang
        // var c_idStock = [];
        var c_idItem = [];
        var c_namaItem = null;
        var c_kode = null;
        var c_icode = [];

        $(document).ready(function(){
            // start ====
            // below is function for agent ====
            getProvAgen();
            getKotaAgen();
            getAPJAgen();
            changeAPJAgen();
            changeAPBAgen();
            changeSatuanAgen();
            changeJumlahAgen();
            changeHargaAgen();
            visibleTableItemAgen();
            // below is function for branch ====
            getProvCabang();
            getKotaCabang();
            getCabang();
            getPembeliCabang();
            changeCabang();
            changePembeliCabang();
            changeSatuanCabang();
            changeJumlahCabang();
            changeHargaCabang();
            // end ====

            if ($('#select-order').val() == "1") {
                $('#agen').removeClass('d-none');
                $('#cabang').addClass('d-none');
                $("#a_prov").focus();
                $("#a_prov").select2('open');
            }
            else if ($('#select-order').val() == "2") {
                $('#agen').addClass('d-none');
                $('#cabang').removeClass('d-none');
            }
            else {
                $('#agen').addClass('d-none');
                $('#cabang').addClass('d-none');
            }

            $('#select-order').change(function(){
                var ini, agen, cabang;
                ini         = $(this).val();
                agen        = $('#agen');
                cabang      = $('#cabang');

                if (ini === '1') {
                    agen.removeClass('d-none');
                    cabang.addClass('d-none');
                } else if(ini === '2'){
                    agen.addClass('d-none');
                    cabang.removeClass('d-none');
                } else {
                    agen.addClass('d-none');
                    cabang.addClass('d-none');
                }
            });

            // start: for agent
            $('.barang').on('click', function(e){
                e.preventDefault();
                idxBarang = $('.barang').index(this);
                setArrayCode();
            });

            $(".barang").eq(idxBarang).on("keyup", function (evt) {
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
                    }else{
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".subtotal").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        updateTotalTampil();
                    }
                } else if (evt.which <= 90 && evt.which >= 48)
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

            $('.btn-tambah-agen').on('click', function () {
                tambahAgen();
            });

            $(document).on('click', '.btn-hapus-agen', function () {
                $(this).parents('tr').remove();
                updateTotalTampil();
                setArrayCode();
            });
            // end: for agents

            // start: for branch
            $('.c_barang').on('click', function(e){
                e.preventDefault();
                idxBarang = $('.c_barang').index(this);
                setArrayCodeCabang();
            });

            $(".c_barang").eq(idxBarang).on("keyup", function (evt) {
                if (evt.which == 8 || evt.which == 46)
                {
                    $(".c_itemid").eq(idxBarang).val('');
                    $(".c_kode").eq(idxBarang).val('');
                    //$(".c_idStock").eq(idxBarang).val('');
                    setArrayCodeCabang();
                    if ($(".c_itemid").eq(idxBarang).val() == "") {
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_subtotal").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", true);
                        $(".c_satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampilCabang();
                    }else{
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_subtotal").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", false);
                        updateTotalTampilCabang();
                    }
                } else if (evt.which <= 90 && evt.which >= 48)
                {
                    $(".c_itemid").eq(idxBarang).val('');
                    $(".c_kode").eq(idxBarang).val('');
                    //$(".c_idStock").eq(idxBarang).val('');
                    setArrayCodeCabang();
                    if ($(".c_itemid").eq(idxBarang).val() == "") {
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", true);
                        $(".c_satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampilCabang();
                    }else{
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", false);
                        updateTotalTampilCabang();
                    }
                }

            });

            $('.btn-tambah-cabang').on('click', function () {
                tambahCabang();
            });

            $(document).on('click', '.btn-hapus-cabang', function () {
                $(this).parents('tr').remove();
                updateTotalTampilCabang();
                setArrayCodeCabang();
            });
            // end: for branch

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

            function checkFormCabang() {
                var inpItemid = document.getElementsByClassName( 'c_itemid' ),
                    item  = [].map.call(inpItemid, function( input ) {
                        return input.value;
                    });
                var inpHarga = document.getElementsByClassName( 'c_harga' ),
                    harga  = [].map.call(inpHarga, function( input ) {
                        return input.value;
                    });
                var inpJumlah = document.getElementsByClassName( 'c_jumlah' ),
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
                if ($("#select-order").val() == "1") {
                    if (checkForm() == "cek form") {
                        messageWarning('Peringatan', 'Lengkapi data order produk ke agen/cabang');
                    } else {
                        $.confirm({
                            animation: 'RotateY',
                            closeAnimation: 'scale',
                            animationBounce: 1.5,
                            icon: 'fa fa-exclamation-triangle',
                            title: 'Konfirmasi!',
                            content: 'Apakah anda yakin akan menyimpan data order produk ke agen/cabang ini?',
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
                } else if ($("#select-order").val() == "2") {
                    if (checkFormCabang() == "cek form") {
                        messageWarning('Peringatan', 'Lengkapi data order produk ke agen/cabang');
                    } else {
                        $.confirm({
                            animation: 'RotateY',
                            closeAnimation: 'scale',
                            animationBounce: 1.5,
                            icon: 'fa fa-exclamation-triangle',
                            title: 'Konfirmasi!',
                            content: 'Apakah anda yakin akan menyimpan data order produk ke agen/cabang ini?',
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
                }

            })
        });

        // start: functions for branch ===========

        function getProvCabang() {
            loadingShow();
            $("#c_prov").find('option').remove();
            $("#c_prov").attr("disabled", true);
            axios.get('{{ route('orderagenpusat.getprovinsi') }}')
                .then(function (resp) {
                    $("#c_prov").attr("disabled", false);
                    var option = '<option value="">Pilih Provinsi</option>';
                    var prov = resp.data;
                    prov.forEach(function (data) {
                        option += '<option value="'+data.wp_id+'">'+data.wp_name+'</option>';
                    })
                    $("#c_prov").append(option);
                    loadingHide();
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error)
                })
        }

        function getKotaCabang() {
            $("#c_prov").on("change", function (evt) {
                evt.preventDefault();
                $("#c_kota").find('option').remove();
                $("#c_kota").attr("disabled", true);
                $("#c_idapb").val('');
                $("#c_kodeapb").val('');
                $("#c_compapb").val('');
                $("#c_apb").val('');
                $("#c_apb").find('option').remove();
                $("#c_apb").attr("disabled", true);
                visibleTableItemCabang();
                if ($("#c_prov").val() != "") {
                    loadingShow();
                    axios.get(baseUrl+'/marketing/agen/orderproduk/get-kota/'+$("#c_prov").val())
                        .then(function (resp) {
                            $("#c_kota").attr("disabled", false);
                            var option = '<option value="">Pilih Kota</option>';
                            var kota = resp.data;
                            kota.forEach(function (data) {
                                option += '<option value="'+data.wc_id+'">'+data.wc_name+'</option>';
                            })
                            $("#c_kota").append(option);
                            loadingHide();
                            $("#c_kota").focus();
                            $("#c_kota").select2('open');
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
                } else if ($('#c_prov').val() == "") {
                    $("#c_idapb").val('');
                    $("#c_kodeapb").val('');
                    $("#c_compapb").val('');
                    $("#c_apb").val('');
                    $("#c_apb").find('option').remove();
                    $("#c_apb").attr("disabled", true);
                    visibleTableItemCabang();
                }
            })
        }

        function getCabang() {
            $("#c_apb").on("change", function (evt) {
                evt.preventDefault();
                $("#c_cabang").find('option').remove();
                $("#c_cabang").attr("disabled", true);
                loadingShow();

                axios.get(baseUrl+'/marketing/agen/orderproduk/get-cabang', {params: {
                    agen: $('#c_apb').val()
                    }})
                    .then(function (resp) {
                        $("#c_cabang").attr("disabled", false);
                        var option = '<option value="">Pilih Cabang</option>';
                        var cabang = resp.data;
                        cabang.forEach(function (data) {
                            option += '<option value="'+data.c_id+'">'+data.c_name+'</option>';
                        })
                        $("#c_cabang").append(option);
                        loadingHide();
                    })
                    .catch(function (error) {
                        loadingHide();
                        messageWarning("Error", error)
                    })
            })
        }

        function getPembeliCabang() {
            $("#c_kota").on("change", function (evt) {
                evt.preventDefault();
                $("#c_idapb").val('');
                $("#c_kodeapb").val('');
                $("#c_compapb").val('');
                $("#c_apb").val('');
                $("#c_apb").find('option').remove();
                $("#c_apb").attr("disabled", true);
                visibleTableItemCabang();

                if ($("#c_prov").val() != "" && $("#c_kota").val() != "") {
                    loadingShow();
                    axios.get(baseUrl+'/marketing/agen/orderproduk/get-pembeli-cabang/'+$("#c_prov").val()+'/'+$("#c_kota").val())
                        .then(function (resp) {
                            $("#c_apb").attr("disabled", false);
                            var option = '<option value="">Pilih Agen Pembeli</option>';
                            var pembeli = resp.data;
                            pembeli.forEach(function (data) {
                                option += '<option value="'+data.a_id+'" data-code="'+data.a_code+'" data-comp="'+data.c_id+'">'+data.a_name+'</option>';
                            })
                            $("#c_apb").append(option);
                            $("#c_apb").focus();
                            $("#c_apb").select2('open');
                            loadingHide();
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
                }  else if ($("#c_prov").val() == "" && $("#c_kota").val() == "") {
                    $("#c_idapb").val('');
                    $("#c_kodeapb").val('');
                    $("#c_compapb").val('');
                    $("#c_apb").val('');
                    $("#c_apb").find('option').remove();
                    $("#c_apb").attr("disabled", true);
                    visibleTableItemCabang();
                }
            })
        }

        function changePembeliCabang() {
            $("#c_apb").on("change", function (evt) {
                evt.preventDefault();
                var id  = $(this).val();
                var kode = $(this).select2().find(":selected").data("code");
                var comp = $(this).select2().find(":selected").data("comp");

                if (id != "") {
                    $("#c_idapb").val(id);
                    $("#c_kodeapb").val(kode);
                    $("#c_compapb").val(comp);
                    visibleTableItemCabang();
                } else {
                    $("#c_idapb").val('');
                    $("#c_kodeapb").val('');
                    $("#c_compapb").val('');
                    visibleTableItemCabang();
                }
            })
        }

        function changeCabang() {
            $("#c_cabang").on("change", function (evt) {
                evt.preventDefault();
                if ($(this).val() == "") {
                    $("#c_idapb").val('');
                    $("#c_kodeapb").val('');
                    $("#c_compapb").val('');
                    $("#c_apb").val('');
                    $('#c_apb').select2().trigger('change');
                    visibleTableItemCabang();
                } else {
                    visibleTableItemCabang();
                }
            })
        }

        function changeSatuanCabang() {
            $(".c_satuan").on("change", function (evt) {
                var idx = $('.c_satuan').index(this);
                var jumlah = $('.c_jumlah').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }
                // trigger jumlah to get new price
                $('.c_jumlah').trigger('input');
            })
        }

        function changeJumlahCabang() {
            $(".c_jumlah").on('input', function (evt) {
                var idx = $('.c_jumlah').index(this);
                var jumlah = $('.c_jumlah').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }

                // axios.get(baseUrl+'/marketing/agen/orderproduk/cek-stok/'+0+'/'+$(".c_itemid").eq(idx).val()+'/'+$(".c_satuan").eq(idx).val()+'/'+jumlah)
                //     .then(function (resp) {
                //         $(".c_jumlah").eq(idx).val(resp.data);
                //
                //         var tmp_jumlah = $('.c_jumlah').eq(idx).val();
                //
                //
                //
                //
                //     })
                //     .catch(function (error) {
                //         messageWarning("Error", error);
                //     })

                axios.get(baseUrl+'/marketing/agen/orderproduk/cek-harga/'+$("#c_kodeapb").val()+'/'+$(".c_itemid").eq(idx).val()+'/'+$(".c_satuan").eq(idx).val()+'/'+jumlah)
                .then(function (res) {
                    var price = res.data;

                    if (isNaN(price)) {
                        price = 0;
                    }
                    if (price == 0) {
                        $('.c_unknow').eq(idx).css('display', 'block');
                    } else {
                        $('.c_unknow').eq(idx).css('display', 'none');
                    }
                    $('.c_harga').eq(idx).val(convertToRupiah(price));

                    var inpJumlah = document.getElementsByClassName( 'c_jumlah' ),
                    jumlah  = [].map.call(inpJumlah, function( input ) {
                        return parseInt(input.value);
                    });

                    var inpHarga = document.getElementsByClassName( 'c_harga' ),
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
                        $(".c_subtotal").eq(i).val(hasil);

                    }
                    updateTotalTampilCabang();
                }).catch(function (error) {
                    messageWarning("Error", error);
                })
            })
        }

        function changeHargaCabang() {
            $(".c_harga").on('keyup', function (evt) {
                var inpJumlah = document.getElementsByClassName( 'c_jumlah' ),
                jumlah  = [].map.call(inpJumlah, function( input ) {
                    return parseInt(input.value);
                });

                var inpHarga = document.getElementsByClassName( 'c_harga' ),
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
                    $(".c_subtotal").eq(i).val(hasil);
                }
                updateTotalTampilCabang();
            })
        }

        function visibleTableItemCabang() {
            if ($("#c_prov").val() != "" && $("#c_kota").val() != "" && $("#c_cabang").val() != "" && $("#c_idapb").val() != "" && $("#c_cabang").val() != "") {
                $("#tbl_item_cabang").show('slow');
                $(".btn-submit").attr("disabled", false);
                $(".btn-submit").css({"cursor":"pointer"});
            }else{
                $("#tbl_item_cabang").hide('slow');
                $(".btn-submit").attr("disabled", true);
                $(".btn-submit").css({"cursor":"not-allowed"});
            }
        }

        function setArrayCodeCabang() {
            var inputs = document.getElementsByClassName('c_kode'),
                code  = [].map.call(inputs, function( input ) {
                    return input.value.toString();
                });

            for (var i=0; i < code.length; i++) {
                if (code[i] != "") {
                    c_icode.push(code[i]);
                }
            }

            var inpItemid = document.getElementsByClassName( 'c_itemid' ),
                item  = [].map.call(inpItemid, function( input ) {
                    return input.value;
                });

            $( ".c_barang" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: '{{ url('/marketing/agen/orderproduk/cari-barang') }}',
                        data: {
                            idItem: item,
                            comp: $("#c_cabang").val(),
                            term: $(".c_barang").eq(idxBarang).val()
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                minLength: 1,
                select: function(event, data) {
                    setItemCabang(data.item);
                }
            });
        }

        function setItemCabang(info) {
            //c_idStock = info.stock
            c_idItem = info.data.i_id;
            c_namaItem = info.data.i_name;
            c_kode = info.data.i_code;
            $(".c_kode").eq(idxBarang).val(c_kode);
            $(".c_itemid").eq(idxBarang).val(c_idItem);
            //$(".c_idStock").eq(idxBarang).val(c_idStock);
            setArrayCodeCabang();
            $.ajax({
                url: '{{ url('/marketing/agen/orderproduk/get-satuan/') }}'+'/'+c_idItem,
                type: 'GET',
                success: function( resp ) {
                    $(".c_satuan").eq(idxBarang).find('option').remove();
                    var option = '';
                    option += '<option value="'+resp.id1+'">'+resp.unit1+'</option>';
                    if (resp.id2 != null && resp.id2 != resp.id1) {
                        option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
                    }
                    if (resp.id3 != null && resp.id3 != resp.id1) {
                        option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
                    }
                    $(".c_satuan").eq(idxBarang).append(option);
                    if ($(".c_itemid").eq(idxBarang).val() == "") {
                        $(".c_jumlah").eq(idxBarang).attr("readonly", true);
                        $(".c_satuan").eq(idxBarang).find('option').remove();
                    }else{
                        $(".c_jumlah").eq(idxBarang).attr("readonly", false);
                    }
                }
            });
        }

        function updateTotalTampilCabang() {
            var total = 0;

            var inputs = document.getElementsByClassName('c_subtotal'),
                subtotal = [].map.call(inputs, function (input) {
                    return input.value;
                });

            for (var i = 0; i < subtotal.length; i++) {
                total += parseInt(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
            }
            $("#c_tot_hrg").val(total);
            if (isNaN(total)) {
                total = 0;
            }
            $("#c_th").val(convertToRupiah(total));

        }

        function tambahCabang() {
            var row = '';
            row = '<tr>' +
                '<td><input type="text" name="c_barang[]" class="form-control form-control-sm c_barang" autocomplete="off"><input type="hidden" name="c_idItem[]" class="c_itemid"><input type="hidden" name="c_kode[]" class="c_kode"></td>'+
                '<td>'+
                '<select name="c_satuan[]" class="form-control form-control-sm select2 c_satuan">'+
                '</select>'+
                '</td>'+
                '<td><input type="number" name="c_jumlah[]" min="0" class="form-control form-control-sm c_jumlah" value="0" readonly></td>'+
                '<td><input type="text" name="c_harga[]" class="form-control form-control-sm text-right c_harga" value="Rp. 0" readonly><p class="text-danger c_unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>'+
                '<td><input type="text" name="c_subtotal[]" style="text-align: right;" class="form-control form-control-sm c_subtotal" value="Rp. 0" readonly><input type="hidden" name="c_sbtotal[]" class="c_sbtotal"></td>'+
                '<td>'+
                '<button class="btn btn-danger btn-hapus btn-hapus-cabang btn-sm" type="button">'+
                '<i class="fa fa-remove" aria-hidden="true"></i>'+
                '</button>'+
                '</td>'+
                '</tr>';
            $('#table_cabang tbody').append(row);
            changeSatuanCabang();
            changeJumlahCabang();
            changeHargaCabang();

            $('.select2').select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                width: '100%'
            });

            $('.c_barang').on('click', function(e){
                idxBarang = $('.c_barang').index(this);
                setArrayCodeCabang();
            });

            $(".c_barang").on("keyup", function (evt) {
                if (evt.which == 8 || evt.which == 46)
                {
                    $(".c_itemid").eq(idxBarang).val('');
                    $(".c_kode").eq(idxBarang).val('');
                    //$(".c_idStock").eq(idxBarang).val('');
                    setArrayCodeCabang();
                    if ($(".c_itemid").eq(idxBarang).val() == "") {
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_subtotal").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", true);
                        $(".c_satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampilCabang();
                    }else{
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_subtotal").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", false);
                        updateTotalTampilCabang();
                    }
                } else if (evt.which <= 90 && evt.which >= 48)
                {
                    $(".c_itemid").eq(idxBarang).val('');
                    $(".c_kode").eq(idxBarang).val('');
                    //$(".c_idStock").eq(idxBarang).val('');
                    setArrayCodeCabang();
                    if ($(".c_itemid").eq(idxBarang).val() == "") {
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", true);
                        $(".c_satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampilCabang();
                    }else{
                        $(".c_jumlah").eq(idxBarang).val(0);
                        $(".c_harga").eq(idxBarang).val("Rp. 0");
                        $(".c_jumlah").eq(idxBarang).attr("readonly", false);
                        updateTotalTampilCabang();
                    }
                }
            });

            setArrayCodeCabang();

            $('.input-rupiah').maskMoney({
                thousands: ".",
                precision: 0,
                decimal: ",",
                prefix: "Rp. "
            });
            updateTotalTampilCabang();
        }

        // end: functions for branch ===========
        // start: functions for agents ===========

        function getProvAgen() {
            loadingShow();
            $("#a_prov").find('option').remove();
            $("#a_prov").attr("disabled", true);
            axios.get('{{ route('orderagenpusat.getprovinsi') }}')
                .then(function (resp) {
                    $("#a_prov").attr("disabled", false);
                    var option = '<option value="">Pilih Provinsi</option>';
                    var prov = resp.data;
                    prov.forEach(function (data) {
                        option += '<option value="'+data.wp_id+'">'+data.wp_name+'</option>';
                    })
                    $("#a_prov").append(option);
                    loadingHide();
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error)
                })
        }

        function getKotaAgen() {
            $("#a_prov").on("change", function (evt) {
                evt.preventDefault();
                $("#a_kota").find('option').remove();
                $("#a_kota").attr("disabled", true);
                $("#a_idapj").val('');
                $("#a_kodeapj").val('');
                $("#a_compapj").val('');
                $("#a_apj").val('');
                $("#a_idapb").val('');
                $("#a_kodeapb").val('');
                $("#a_compapb").val('');
                $("#a_apb").val('');
                $("#a_apj").find('option').remove();
                $("#a_apj").attr("disabled", true);
                $("#a_apb").find('option').remove();
                $("#a_apb").attr("disabled", true);
                visibleTableItemAgen();
                if ($("#a_prov").val() != "") {
                    loadingShow();
                    axios.get(baseUrl+'/marketing/agen/orderproduk/get-kota/'+$("#a_prov").val())
                        .then(function (resp) {
                            $("#a_kota").attr("disabled", false);
                            var option = '<option value="">Pilih Kota</option>';
                            var kota = resp.data;
                            kota.forEach(function (data) {
                                option += '<option value="'+data.wc_id+'">'+data.wc_name+'</option>';
                            })
                            $("#a_kota").append(option);
                            loadingHide();
                            $("#a_kota").focus();
                            $("#a_kota").select2('open');
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
                } else if ($('#a_prov').val() == "") {
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
                    $("#a_compapj").val('');
                    $("#a_apj").val('');
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
                    $("#a_compapb").val('');
                    $("#a_apb").val('');
                    $("#a_apj").find('option').remove();
                    $("#a_apj").attr("disabled", true);
                    $("#a_apb").find('option').remove();
                    $("#a_apb").attr("disabled", true);
                    visibleTableItemAgen();
                }
            })
        }

        function getAPJAgen() {
            $("#a_kota").on("change", function (evt) {
                evt.preventDefault();
                $("#a_idapj").val('');
                $("#a_kodeapj").val('');
                $("#a_compapj").val('');
                $("#a_apj").val('');
                $("#a_idapb").val('');
                $("#a_kodeapb").val('');
                $("#a_compapb").val('');
                $("#a_apb").val('');
                $("#a_apj").find('option').remove();
                $("#a_apj").attr("disabled", true);
                $("#a_apb").find('option').remove();
                $("#a_apb").attr("disabled", true);
                visibleTableItemAgen();
                if ($("#a_prov").val() != "" && $("#a_kota").val() != "") {
                    loadingShow();
                    axios.get(baseUrl+'/marketing/agen/orderproduk/get-penjual/'+$("#a_prov").val()+'/'+$("#a_kota").val())
                        .then(function (resp) {
                            $("#a_apj").attr("disabled", false);
                            var option = '<option value="">Pilih Agen Penjual</option>';
                            var penjual = resp.data;
                            penjual.forEach(function (data) {
                                option += '<option value="'+data.a_id+'" data-code="'+data.a_code+'" data-comp="'+data.c_id+'">'+data.a_name+'</option>';
                            })
                            $("#a_apj").append(option);
                            loadingHide();
                            $("#a_apj").focus();
                            $("#a_apj").select2('open');
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
                } else if ($("#a_prov").val() == "" && $("#a_kota").val() == "") {
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
                    $("#a_compapj").val('');
                    $("#a_apj").val('');
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
                    $("#a_compapb").val('');
                    $("#a_apb").val('');
                    $("#a_apj").find('option').remove();
                    $("#a_apj").attr("disabled", true);
                    $("#a_apb").find('option').remove();
                    $("#a_apb").attr("disabled", true);
                    visibleTableItemAgen();
                }
            })
        }

        function changeAPJAgen() {
            $("#a_apj").on("change", function(evt){
                evt.preventDefault();
                var id  = $(this).val();
                var kode = $(this).select2().find(":selected").data("code");
                var comp = $(this).select2().find(":selected").data("comp");
                if (id != "") {
                    $("#a_idapj").val(id);
                    $("#a_kodeapj").val(kode);
                    $("#a_compapj").val(comp);
                    loadingShow();
                    $("#a_apb").find('option').remove();
                    axios.get(baseUrl+'/marketing/agen/orderproduk/get-pembeli/'+$("#a_kodeapj").val())
                        .then(function (resp) {
                            $("#a_apb").attr("disabled", false);
                            var option = '<option value="">Pilih Agen Pembeli</option>';
                            var pembeli = resp.data;
                            pembeli.forEach(function (data) {
                                option += '<option value="'+data.a_id+'" data-code="'+data.a_code+'" data-comp="'+data.c_id+'">'+data.a_name+'</option>';
                            })
                            $("#a_apb").append(option);
                            loadingHide();
                            $("#a_apb").focus();
                            $("#a_apb").select2('open');
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
                        .then(function () {
                            visibleTableItemAgen();
                        })
                } else {
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
                    $("#a_compapj").val('');
                    $("#a_apb").find('option').remove();
                    $("#a_apb").attr("disabled", true);
                    visibleTableItemAgen();
                }
            })
        }

        function changeAPBAgen() {
            $("#a_apb").on("change", function(evt){
                evt.preventDefault();
                var id  = $(this).val();
                var kode = $(this).select2().find(":selected").data("code");
                var comp = $(this).select2().find(":selected").data("comp");

                if (id != "") {
                    $("#a_idapb").val(id);
                    $("#a_kodeapb").val(kode);
                    $("#a_compapb").val(comp);
                    visibleTableItemAgen();
                } else {
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
                    $("#a_compapb").val('');
                    visibleTableItemAgen();
                }
            })
        }

        function changeSatuanAgen() {
            $(".satuan").on("change", function (evt) {
                var idx = $('.satuan').index(this);
                var jumlah = $('.jumlah').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }
                // trigger jumlah to get new price
                $(".jumlah").trigger('input');
                // axios.get(baseUrl+'/marketing/agen/orderproduk/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
                //     .then(function (resp) {
                //         $(".jumlah").eq(idx).val(resp.data);
                //
                //         var inpJumlah = document.getElementsByClassName( 'jumlah' ),
                //             jumlah  = [].map.call(inpJumlah, function( input ) {
                //                 return parseInt(input.value);
                //             });
                //
                //         var inpHarga = document.getElementsByClassName( 'harga' ),
                //             harga  = [].map.call(inpHarga, function( input ) {
                //                 return input.value;
                //             });
                //
                //         for (var i = 0; i < jumlah.length; i++) {
                //             var hasil = 0;
                //             var hrg = harga[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                //             var jml = jumlah[i];
                //
                //             if (jml == "") {
                //                 jml = 0;
                //             }
                //
                //             hasil += parseInt(hrg) * parseInt(jml);
                //
                //             if (isNaN(hasil)) {
                //                 hasil = 0;
                //             }
                //             hasil = convertToRupiah(hasil);
                //             $(".subtotal").eq(i).val(hasil);
                //
                //         }
                //         updateTotalTampil();
                //     })
                //     .catch(function (error) {
                //         messageWarning("Error", error);
                //     })
            })
        }

        function changeJumlahAgen() {
            $(".jumlah").on('input', function (evt) {
                var idx = $('.jumlah').index(this);
                var jumlah = $('.jumlah').eq(idx).val();
                if (jumlah == "") {
                    jumlah = 0;
                }

                axios.get(baseUrl+'/marketing/agen/orderproduk/cek-harga/'+$("#a_kodeapj").val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
                    .then(function (res) {
                        var price = res.data;

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
                    })

                // axios.get(baseUrl+'/marketing/agen/orderproduk/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
                //     .then(function (resp) {
                //         $(".jumlah").eq(idx).val(resp.data);
                //
                //         var tmp_jumlah = $('.jumlah').eq(idx).val();
                //
                //
                //
                //
                //     })
                //     .catch(function (error) {
                //         messageWarning("Error", error);
                //     })

            })
        }

        function changeHargaAgen() {
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

        function visibleTableItemAgen() {
            if ($("#a_prov").val() != "" && $("#a_kota").val() != "" && $("#a_idapj").val() != "" && $("#a_idapb").val() != "") {
                $("#tbl_item").show('slow');
                $(".btn-submit").attr("disabled", false);
                $(".btn-submit").css({"cursor":"pointer"});
            }else{
                $("#tbl_item").hide('slow');
                $(".btn-submit").attr("disabled", true);
                $(".btn-submit").css({"cursor":"not-allowed"});
            }
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

            $( ".barang" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: '{{ url('/marketing/agen/orderproduk/cari-barang') }}',
                        data: {
                            idItem: item,
                            comp: $("#a_compapj").val(),
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
                url: '{{ url('/marketing/agen/orderproduk/get-satuan/') }}'+'/'+idItem,
                type: 'GET',
                success: function( resp ) {
                    $(".satuan").eq(idxBarang).find('option').remove();
                    var option = '';
                    option += '<option value="'+resp.id1+'">'+resp.unit1+'</option>';
                    if (resp.id2 != null && resp.id2 != resp.id1) {
                        option += '<option value="'+resp.id2+'">'+resp.unit2+'</option>';
                    }
                    if (resp.id3 != null && resp.id3 != resp.id1) {
                        option += '<option value="'+resp.id3+'">'+resp.unit3+'</option>';
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

        function updateTotalTampil() {
            var total = 0;

            var inputs = document.getElementsByClassName('subtotal'),
                subtotal = [].map.call(inputs, function (input) {
                    return input.value;
                });

            for (var i = 0; i < subtotal.length; i++) {
                total += parseInt(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
            }
            $("#a_tot_hrg").val(total);
            if (isNaN(total)) {
                total = 0;
            }
            $("#a_th").val(convertToRupiah(total));

        }

        function tambahAgen() {
            var row = '';
            row = '<tr>' +
                '<td><input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"><input type="hidden" name="idStock[]" class="idStock"></td>'+
                '<td>'+
                '<select name="satuan[]" class="form-control form-control-sm select2 satuan">'+
                '</select>'+
                '</td>'+
                '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly></td>'+
                '<td><input type="text" name="harga[]" class="form-control form-control-sm text-right harga" value="Rp. 0" readonly><p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>'+
                '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>'+
                '<td>'+
                '<button class="btn btn-danger btn-hapus btn-hapus-agen btn-sm" type="button">'+
                '<i class="fa fa-remove" aria-hidden="true"></i>'+
                '</button>'+
                '</td>'+
                '</tr>';
            $('#table_agen tbody').append(row);
            changeSatuanAgen();
            changeJumlahAgen();
            changeHargaAgen();

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

            setArrayCode();

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

            updateTotalTampil();
        }

        // end: functions for agents ===========

        function simpan() {
            loadingShow();
            var data = $('#formManagemenAgen :input').serialize();

            axios.post('{{ route('orderagenpusat.add') }}', data)
                .then(function (response){
                    if(response.data.status == 'Success'){
                        loadingHide();
                        messageSuccess("Berhasil", response.data.message);
                        setInterval(function(){location.reload();}, 3500)
                    }else{
                        loadingHide();
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
