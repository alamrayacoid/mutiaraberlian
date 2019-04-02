@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Penempatan Produk </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Marketing</span>
                / <a href="{{route('konsinyasipusat.index')}}"><span>Manajemen Konsinyasi Pusat </span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Penempatan Produk </span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data Penempatan Produk </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('konsinyasipusat.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formKonsinyasi">
                            <div class="card-block">
                                <section>

                                    <div id="sectionsuplier" class="row">

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Area</label>
                                        </div>

                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="provinsi" id="provinsi" class="form-control form-control-sm select2" disabled>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="kota" id="kota" class="form-control form-control-sm select2" disabled>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Konsigner</label>
                                        </div>

                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <input type="hidden" name="idKonsigner" id="idKonsigner">
                                                <input type="text" name="konsigner" id="konsigner" class="form-control form-control-sm" oninput="handleInput(event)" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Total</label>
                                        </div>

                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="total_harga" id="total_harga" value="Rp. 0" readonly>
                                                <input type="hidden" name="tot_hrg" id="tot_hrg">
                                            </div>
                                        </div>

                                        <div class="container" id="tbl_item" style="display: none;">
                                            <div class="table-responsive mt-3">
                                                <table class="table table-hover table-striped" id="table_rencana"
                                                       cellspacing="0">
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
                                                            <input type="hidden" name="idItem[]" class="itemid">
                                                            <input type="hidden" name="kode[]" class="kode">
                                                            <input type="text"
                                                                   name="barang[]"
                                                                   class="form-control form-control-sm barang"
                                                                   autocomplete="off">
                                                        </td>
                                                        <td><select name="satuan[]"
                                                                    class="form-control form-control-sm select2 satuan">
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                   name="jumlah[]"
                                                                   min="0"
                                                                   class="form-control form-control-sm jumlah"
                                                                   value="0">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="harga[]"
                                                                   class="form-control form-control-sm input-rupiah harga"
                                                                   value="Rp. 0">
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
                                <a href="{{route('konsinyasipusat.index')}}" class="btn btn-secondary">Kembali</a>
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
        var idItem = [];
        var namaItem = null;
        var kode = null;
        var idxBarang = null;
        var icode = [];
        var checkitem = null;
        $(document).ready(function () {
            getProv();
            getKota();
            changeJumlah();
            changeHarga();

            $("#kota").on("change", function (evt) {
                evt.preventDefault();
                if ($("#kota").val() == "") {
                    $("#idKonsigner").val('');
                    $("#konsigner").val('');
                    $("#konsigner").attr("disabled", true);
                } else {
                    $("#konsigner").attr("disabled", false);
                    $("#idKonsigner").val('');
                    $("#konsigner").val('');
                }
            })

            $( "#konsigner" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: baseUrl+'/marketing/konsinyasipusat/cari-konsigner/'+$("#provinsi").val()+'/'+$("#kota").val(),
                        data: {
                            term: $( "#konsigner" ).val()
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                minLength: 1,
                select: function(event, data) {
                    $( "#idKonsigner" ).val(data.item.id);
                }
            });

            $('.barang').on('click', function(e){
                idxBarang = $('.barang').index(this);
                setArrayCode();
            });

            $(".barang").eq(idxBarang).on("keyup", function () {
                $(".itemid").eq(idxBarang).val('');
                $(".kode").eq(idxBarang).val('');
                setArrayCode();
            });

            $(document).on('click', '.btn-hapus', function () {
                $(this).parents('tr').remove();
                updateTotalTampil();
                updateSisaPembayaran();
                setArrayCode();
            });

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

            $(document).on('click', '.btn-submit', function () {
                $.toast({
                    heading: 'Success',
                    text: 'Data Berhasil di Simpan',
                    bgColor: '#00b894',
                    textColor: 'white',
                    loaderBg: '#55efc4',
                    icon: 'success'
                })
            })
        });

        function changeJumlah() {
            $(".jumlah").on('input', function (evt) {
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
                updateSisaPembayaran();
            })
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
                updateSisaPembayaran();
            })
        }

        function tambah() {
            var row = '';
            row = '<tr>' +
                '<td><input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"></td>'+
                '<td>'+
                '<select name="satuan[]" class="form-control form-control-sm select2 satuan">'+
                '</select>'+
                '</td>'+
                '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0"></td>'+
                '<td><input type="text" name="harga[]" class="form-control form-control-sm input-rupiah harga" value="Rp. 0"></td>'+
                '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>'+
                '<td>'+
                '<button class="btn btn-danger btn-hapus btn-sm" type="button">'+
                '<i class="fa fa-remove" aria-hidden="true"></i>'+
                '</button>'+
                '</td>'+
                '</tr>';
            $('#table_rencana tbody').append(row);
            changeJumlah();
            changeHarga();

            $('.select2').select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                width: '100%'
            });

            $('.barang').on('click', function(e){
                idxBarang = $('.barang').index(this);
            });

            $(".barang").on("keyup", function () {
                $(".itemid").eq(idxBarang).val('');
                $(".kode").eq(idxBarang).val('');
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
            idItem = info.data.i_id;
            namaItem = info.data.i_name;
            kode = info.data.i_code;
            $(".kode").eq(idxBarang).val(kode);
            $(".itemid").eq(idxBarang).val(idItem);
            setArrayCode();
            $.ajax({
                url: '{{ url('/marketing/konsinyasipusat/get-satuan/') }}'+'/'+idItem,
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

            $( ".barang" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: '{{ url('/marketing/konsinyasipusat/cari-barang') }}',
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
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
                }
            })
        }
    </script>
@endsection
