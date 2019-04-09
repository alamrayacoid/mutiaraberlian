@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Edit Data Penempatan Produk </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Marketing</span>
                / <a href="{{route('konsinyasipusat.index')}}"><span>Manajemen Konsinyasi Pusat </span></a>
                / <span class="text-primary" style="font-weight: bold;"> Edit Data Penempatan Produk </span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Edit Data Penempatan Produk </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('konsinyasipusat.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <form id="formKonsinyasi" method="post">{{ csrf_field() }}
                            <input type="hidden" name="idSales" id="idSales" value="{{ $ids }}">
                            <div class="card-block">
                                <section>

                                    <div id="sectionsuplier" class="row">

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Area</label>
                                        </div>

                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="provinsi" id="provinsi" class="form-control form-control-sm select2">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="kota" id="kota" class="form-control form-control-sm select2">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Konsigner</label>
                                        </div>

                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <input type="hidden" name="idKonsigner" id="idKonsigner" value="{{ $detail->c_id }}">
                                                <input type="hidden" name="kodeKonsigner" id="kodeKonsigner" value="{{ $detail->c_user }}">
                                                <input type="hidden" name="nota" id="nota" value="{{ $detail->s_nota }}">
                                                <input type="text" name="konsigner" id="konsigner" class="form-control form-control-sm"
                                                       value="{{ strtoupper($detail->c_name) }}" oninput="handleInput(event)">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Total</label>
                                        </div>

                                        <div class="col-md-10 col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="total_harga"
                                                       id="total_harga" value="{{ Currency::addRupiah($detail->s_total) }}" readonly>
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
                                                    @foreach($data_item as $key => $data)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="idItem[]" class="itemid" value="{{ $data->itemId }}">
                                                            <input type="hidden" name="kode[]" class="kode" value="{{ $data->itemCode }}">
                                                            <input type="hidden" name="idStock[]" class="idStock" value="{{ $data->stock }}">
                                                            <input type="text"
                                                                   name="barang[]"
                                                                   class="form-control form-control-sm barang"
                                                                   value="{{ strtoupper($data->item) }}"
                                                                   autocomplete="off">
                                                        </td>
                                                        <td><select name="satuan[]"
                                                                    data-label="old"
                                                                    class="form-control form-control-sm select2 satuan">
                                                                <option value="{{ $data->id1 }}" @if($data->unit == $data->id1) selected @endif>{{ $data->unit1 }}</option>
                                                                @if ($data->id2 != null && $data->id2 != $data->id1)
                                                                <option value="{{ $data->id2 }}" @if($data->unit == $data->id2) selected @endif>{{ $data->unit2 }}</option>
                                                                @endif
                                                                @if ($data->id3 != null && $data->id3 != $data->id1 && $data->id3 != $data->id2)
                                                                    <option value="{{ $data->id3 }}" @if($data->unit == $data->id3) selected @endif>{{ $data->unit3 }}</option>
                                                                @endif
                                                            </select>
                                                            <input type="hidden" name="oldSatuan" class="oldSatuan" value="{{ $data->unit }}">
                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                   name="jumlah[]"
                                                                   min="0"
                                                                   class="form-control form-control-sm jumlah"
                                                                   data-label="old"
                                                                   value="{{ $data->qty }}">
                                                            <input type="hidden" name="qtyOld" class="qtyOld" value="{{ $data->qty }}">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="harga[]"
                                                                   class="form-control form-control-sm input-rupiah harga"
                                                                   value="{{ Currency::addRupiah($data->harga) }}" >
                                                        </td>
                                                        <td>
                                                            <input type="text" name="subtotal[]" style="text-align: right;"
                                                                   class="form-control form-control-sm subtotal"
                                                                   value="{{ Currency::addRupiah($data->totalnet) }}" readonly>
                                                        </td>
                                                        <td>
                                                            @if ($key == 0)
                                                                <button type="button"
                                                                        class="btn btn-sm btn-success rounded-circle btn-tambahp"><i
                                                                        class="fa fa-plus"></i></button>
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
        var idStock = [];
        var idItem = [];
        var namaItem = null;
        var kode = null;
        var idxBarang = null;
        var icode = [];
        var checkitem = null;
        var selectProv = '{{ $detail->a_provinsi }}';
        var selectKota = '{{ $detail->a_kabupaten }}';
        $(document).ready(function () {
            getProv();
            getKota();
            changeSatuan();
            changeJumlah();
            changeHarga();
            visibleTableItem();

            $("#kota").on("change", function (evt) {
                evt.preventDefault();
                if ($("#kota").val() == "") {
                    $("#idKonsigner").val('');
                    $("#kodeKonsigner").val('');
                    $("#konsigner").val('');
                    $("#konsigner").attr("disabled", true);
                } else {
                    $("#konsigner").attr("disabled", false);
                    $("#idKonsigner").val('');
                    $("#kodeKonsigner").val('');
                    $("#konsigner").val('');
                    $("#konsigner").attr('autofocus', true);
                }
            })

            $("#konsigner").on("keyup", function (evt) {
                evt.preventDefault();
                if (evt.which == 8 || evt.which == 46)
                {
                    $("#idKonsigner").val('');
                    $("#kodeKonsigner").val('');
                    visibleTableItem();
                } else if (evt.which <= 90 && evt.which >= 48)
                {
                    $("#idKonsigner").val('');
                    $("#kodeKonsigner").val('');
                    visibleTableItem();
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
                    $( "#kodeKonsigner" ).val(data.item.kode);
                    visibleTableItem();
                }
            });

            $('.barang').on('click', function(e){
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
                        $(".harga").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampil();
                    }else{
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".subtotal").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        $(".harga").eq(idxBarang).attr("readonly", false);
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
                        $(".harga").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampil();
                    }else{
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        $(".harga").eq(idxBarang).attr("readonly", false);
                        updateTotalTampil();
                    }
                }

            });

            $(document).on('click', '.btn-hapus', function () {
                $(this).parents('tr').remove();
                updateTotalTampil();
                setArrayCode();
            });

            if ($(".itemid").eq(idxBarang).val() == "") {
                $(".jumlah").eq(idxBarang).attr("readonly", true);
                $(".harga").eq(idxBarang).attr("readonly", true);
                $(".satuan").eq(idxBarang).find('option').remove();
            }else{
                $(".jumlah").eq(idxBarang).attr("readonly", false);
                $(".harga").eq(idxBarang).attr("readonly", false);
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

            $('#formKonsinyasi').data('serialize',$('#formKonsinyasi').serialize()); // On load save form current state
            $(window).bind('beforeunload', function(e){
                if($('#formKonsinyasi').serialize() != $('#formKonsinyasi').data('serialize'))return true;
                else e=null; // i.e; if form state change show box not.
            });
        });

        function changeSatuan() {
            $(".satuan").on("change", function (evt) {
                var idx = $('.satuan').index(this);
                var jumlah = $('.jumlah').eq(idx).val();
                var qtyOld = $('.qtyOld').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }
                if (qtyOld == "") {
                    qtyOld = null;
                }

                if ($('.jumlah').eq(idx).attr("data-label") == "old") {
                    axios.get(baseUrl+'/marketing/konsinyasipusat/cek-stok-old/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".oldSatuan").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+qtyOld+'/'+jumlah)
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
                } else {
                    axios.get(baseUrl+'/marketing/konsinyasipusat/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
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
                }
            })
        }

        function changeJumlah() {
            $(".jumlah").on('input', function (evt) {
                var idx = $('.jumlah').index(this);
                var jumlah = $('.jumlah').eq(idx).val();
                var qtyOld = $('.qtyOld').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }
                if (qtyOld == "") {
                    qtyOld = null;
                }
                
                if ($('.jumlah').eq(idx).attr("data-label") == "old") {
                    axios.get(baseUrl+'/marketing/konsinyasipusat/cek-stok-old/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".oldSatuan").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+qtyOld+'/'+jumlah)
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
                }else{
                    axios.get(baseUrl+'/marketing/konsinyasipusat/cek-stok/'+$(".idStock").eq(idx).val()+'/'+$(".itemid").eq(idx).val()+'/'+$(".satuan").eq(idx).val()+'/'+jumlah)
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
                }

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
            })
        }

        function tambah() {
            var row = '';
            row = '<tr>' +
                '<td><input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"><input type="hidden" name="idStock[]" class="idStock"></td>'+
                '<td>'+
                '<select name="satuan[]" class="form-control form-control-sm select2 satuan" data-label="new">'+
                '</select>'+
                '</td>'+
                '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" data-label="new" value="0" readonly></td>'+
                '<td><input type="text" name="harga[]" class="form-control form-control-sm input-rupiah harga" value="Rp. 0" readonly></td>'+
                '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>'+
                '<td>'+
                '<button class="btn btn-danger btn-hapus btn-sm" type="button">'+
                '<i class="fa fa-remove" aria-hidden="true"></i>'+
                '</button>'+
                '</td>'+
                '</tr>';
            $('#table_rencana tbody').append(row);
            changeSatuan();
            changeJumlah();
            changeHarga();

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
                        $(".harga").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampil();
                    }else{
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".subtotal").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        $(".harga").eq(idxBarang).attr("readonly", false);
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
                        $(".harga").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampil();
                    }else{
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        $(".harga").eq(idxBarang).attr("readonly", false);
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
            var data = $('#formKonsinyasi').serialize();
            axios.post(baseUrl+'/marketing/konsinyasipusat/penempatanproduk/edit/'+$("#idSales").val(), data)
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
                    if ($(".itemid").eq(idxBarang).val() == "") {
                        $(".jumlah").eq(idxBarang).attr("readonly", true);
                        $(".harga").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                    }else{
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        $(".harga").eq(idxBarang).attr("readonly", false);
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

        function visibleTableItem() {
            if ($("#provinsi").val() != "" && $("#kota").val() != "" && $("#idKonsigner").val() != "") {
                $("#tbl_item").show('slow');
                $(".btn-submit").attr("disabled", false);
                $(".btn-submit").css({"cursor":"pointer"});
            }else{
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
                        if (selectProv == data.wp_id) {
                            option += '<option value="'+data.wp_id+'" selected>'+data.wp_name+'</option>';
                        }else{
                            option += '<option value="'+data.wp_id+'">'+data.wp_name+'</option>';
                        }
                    })
                    $("#provinsi").append(option);
                    axios.get(baseUrl+'/marketing/konsinyasipusat/get-kota/'+$("#provinsi").val())
                        .then(function (resp) {
                            $("#kota").attr("disabled", false);
                            var option = '<option value="">Pilih Kota</option>';
                            var kota = resp.data;
                            kota.forEach(function (data) {
                                if (selectKota == data.wc_id) {
                                    option += '<option value="'+data.wc_id+'" selected>'+data.wc_name+'</option>';
                                }else{
                                    option += '<option value="'+data.wc_id+'">'+data.wc_name+'</option>';
                                }
                            })
                            $("#kota").append(option);
                            loadingHide();
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error)
                        })
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
    </script>
@endsection
