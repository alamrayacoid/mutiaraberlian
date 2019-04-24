@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Order Produk ke Cabang </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Marketing</span>
                / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen </span></a>
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
                                <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formManagemenAgen" method="post">{{ csrf_field() }}
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
        var idStock = [];
        var idItem = [];
        var namaItem = null;
        var kode = null;
        var idxBarang = null;
        var icode = [];
        var checkitem = null;

        $(document).ready(function(){
            getProvAgen();
            getKotaAgen();
            getAPJAgen();
            changeAPJAgen();
            changeAPBAgen();
            changeSatuanAgen();
            changeJumlahAgen();
            changeHargaAgen();
            visibleTableItemAgen();

            if ($('#select-order').val() == "1") {
                $('#agen').removeClass('d-none');
                $('#cabang').addClass('d-none');
                $("#a_prov").focus();
                $("#a_prov").select2('open');
            } else if ($('#select-order').val() == "2") {
                $('#agen').addClass('d-none');
                $('#cabang').removeClass('d-none');
            } else {
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
            })
        });

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
                axios.get(baseUrl+'/marketing/agen/orderproduk/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
                    .then(function (resp) {
                        $(".jumlah").eq(idx).val(resp.data);

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
            })
        }

        function changeJumlahAgen() {
            $(".jumlah").on('input', function (evt) {
                var idx = $('.jumlah').index(this);
                var jumlah = $('.jumlah').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }
                axios.get(baseUrl+'/marketing/agen/orderproduk/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
                    .then(function (resp) {
                        $(".jumlah").eq(idx).val(resp.data);

                        var tmp_jumlah = $('.jumlah').eq(idx).val();

                        axios.get(baseUrl+'/marketing/agen/orderproduk/cek-harga/'+$("#a_kodeapj").val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+tmp_jumlah)
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


                    })
                    .catch(function (error) {
                        messageWarning("Error", error);
                    })
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

            $('.select2').select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                width: '100%'
            });

            $('.barang').on('click', function(e){
                idxBarang = $('.barang').index(this);
                setArrayCode();
            });

            $(".barang").on("keyup", function (evt) {
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
            var data = $('#formManagemenAgen').serialize();
            axios.post('{{ route('penempatanproduk.add') }}', data)
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
