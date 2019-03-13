@extends('main')

@section('content')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Order Produksi </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Produksi</span>
                / <a href="{{route('order.index')}}"><span>Order Produksi</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Order Produksi</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title">Tambah Order Produksi</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('order.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <div class="card-block">
                            <form id="form">
                                <section>

                                    <div class="row">

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Tanggal</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="fa fa-calendar" aria-hidden="true"></i></span>
                                                </div>
                                                <input type="text" name="po_date"
                                                       class="form-control form-control-sm datepicker" autocomplete="off" id="tanggal"
                                                       value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}"
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Supplier</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="supplier" id="supplier"
                                                        class="form-control form-control-sm select2">
                                                    <option value="" disabled selected>== Pilih Supplier ==</option>
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{$supplier->s_id}}">{{$supplier->s_company}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Total Harga</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="total_harga" id="total_harga" readonly>
                                                <input type="hidden" name="tot_hrg" id="tot_hrg">
                                            </div>
                                        </div>
                                        <div class="container">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" cellspacing="0"
                                                       id="table_order">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th>Kode Barang/Nama Barang</th>
                                                        <th width="10%">Satuan</th>
                                                        <th width="10%">Jumlah</th>
                                                        <th>Harga @satuan</th>
                                                        <th>Sub Total</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text"
                                                                   name="barang[]"
                                                                   class="form-control form-control-sm barang">
                                                            <input type="hidden" name="idItem[]" class="itemid">
                                                            <input type="hidden" name="kode[]" class="kode">
                                                        </td>
                                                        <td>
                                                            <select name="satuan[]"
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
                                                            <input type="text"
                                                                   name="subtotal[]"
                                                                   style="text-align: right;"
                                                                   class="form-control form-control-sm subtotal"
                                                                   readonly>
                                                            <input type="hidden" name="sbtotal[]" class="sbtotal">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-success btn-tambah btn-sm"
                                                                    type="button">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span><strong>NB: </strong> Jika barang tidak ditemukan, cobalah untuk mendaftarkan barang tersebut pada supplier <span style="color: #0d47a1; text-decoration: underline; cursor: pointer" onclick="itemSupplier()">disini</span></span>
                                        </div>
                                        <div class="container">
                                            <hr style="border:0.7px solid grey; margin-bottom:30px;">
                                            <span class="pull-right">Sisa Pembayaran Rp. <strong id="sisapembayaran">0</strong></span>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bor table-hover" cellspacing="0"
                                                       id="table_order_termin">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th>Termin</th>
                                                        <th>Estimasi</th>
                                                        <th>Nominal</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <input type="text"
                                                                    name="termin[]"
                                                                    class="form-control form-control-sm termin"
                                                                    value="1" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="estimasi[]"
                                                                    class="form-control form-control-sm datepicker estimasi" autocomplete="off">
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="nominal[]"
                                                                    class="form-control form-control-sm input-rupiah nominal" value="Rp. 0">
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-success btn-tambah-termin btn-sm"
                                                                        type="button"><i class="fa fa-plus"
                                                                                        aria-hidden="true"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </section>
                            </form>

                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-submit" type="button" id="btn_submit">Simpan</button>
                            <a href="{{route('order.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
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
        var checktermin = null;

        $(document).ready(function () {
            changeJumlah();
            changeHarga();
            changeNominalTermin();
            visibleSimpan();

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

            $('.btn-tambah').on('click', function () {
                tambah();
            });

            $(document).on('click', '.btn-hapus-termin', function () {
                $(this).parents('tr').remove();
                updateSisaPembayaran();
                setTerimin();
            });

            $('.btn-tambah-termin').on('click', function () {
                var tbody = $(this).parents('tbody');
                var last_row = tbody.find('tr:last-child');
                var input = last_row.find('td:eq(0) input');
                var termin = input.val();
                termin = parseInt(termin);
                var next_termin = termin + 1;
                $('#table_order_termin')
                    .append(
                        '<tr>' +
                        '<td><input type="text" name="termin[]" class="form-control form-control-sm termin" readonly value="' + next_termin + '"></td>' +
                        '<td><input type="text" name="estimasi[]" class="form-control form-control-sm datepicker estimasi" autocomplete="off"></td>' +
                        '<td><input type="text" name="nominal[]" class="form-control form-control-sm input-rupiah nominal" value="Rp. 0"></td>' +
                        '<td><button class="btn btn-danger btn-sm btn-hapus-termin" type="button"><i class="fa fa-trash-o"></i></button></td>' +
                        '</tr>'
                    );
                $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    enableOnReadonly: false,
                    todayHighlight: true,
                    autoclose: true

                });
                $('.input-rupiah').maskMoney({
                    thousands: ".",
                    precision: 0,
                    decimal: ",",
                    prefix: "Rp. "
                });
                setTerimin();
                changeNominalTermin();
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

            function checkTermin() {
                var inpEstimasi = document.getElementsByClassName( 'estimasi' ),
                    estimasi  = [].map.call(inpEstimasi, function( input ) {
                        return input.value;
                    });
                var inpNominal = document.getElementsByClassName( 'nominal' ),
                    nominal  = [].map.call(inpNominal, function( input ) {
                        return input.value;
                    });

                for (var i=0; i < estimasi.length; i++) {
                    if (estimasi[i] == "" || nominal[i] == "Rp. 0") {
                        return "cek form";
                        break;
                    } else {
                        checktermin = "true";
                        continue;
                    }
                }
                return checktermin;
            }

            $(document).on('click', '.btn-submit', function (evt) {
                evt.preventDefault();

                if ($("#tanggal").val() == "") {
                    messageWarning('Peringatan', 'Kolom tanggal tidak boleh kosong');
                    $("#tanggal").focus();
                } else if ($("#tot_hrg").val() == "" || $("#tot_hrg").val() == 0 || checkForm() == "cek form" || checkTermin() == "cek form") {
                    messageWarning('Peringatan', 'Lengkapi data order produksi');
                } else {
                    loadingShow();
                    var data = $('#form').serialize();
                    axios.post(baseUrl+'/produksi/orderproduksi/create', data).then(function (response){

                        if(response.data.status == 'Success'){
                            loadingHide();
                            messageSuccess("Berhasil", "Data Order Produksi Berhasil Disimpan");
                            location.reload();
                        }else{
                            loadingHide();
                            messageFailed("Gagal", "Data Order Produksi Gagal Disimpan");
                        }

                    })

                }
            })
        });

        function changeNominalTermin() {
            $(".nominal").on('keyup', function (evt) {
                evt.preventDefault();
                updateSisaPembayaran();
            })
        }

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

                var inpSubtotal = document.getElementsByClassName( 'subtotal' ),
                    subtotal  = [].map.call(inpSubtotal, function( input ) {
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

                var inpSubtotal = document.getElementsByClassName( 'subtotal' ),
                    subtotal  = [].map.call(inpSubtotal, function( input ) {
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
                '<td><input type="text" name="barang[]" class="form-control form-control-sm barang"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"></td>'+
                '<td>'+
                '<select name="satuan[]" class="form-control form-control-sm select2 satuan">'+
                '</select>'+
                '</td>'+
                '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0"></td>'+
                '<td><input type="text" name="harga[]" class="form-control form-control-sm input-rupiah harga" value="Rp. 0"></td>'+
                '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>'+
                '<td>'+
                '<button class="btn btn-danger btn-hapus btn-sm" type="button">'+
                '<i class="fa fa-remove" aria-hidden="true"></i>'+
                '</button>'+
                '</td>'+
            '</tr>';
            $('#table_order').append(row);
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
        
        function visibleSimpan() {
            var inpNominal = document.getElementsByClassName( 'nominal' ),
                nominal  = [].map.call(inpNominal, function( input ) {
                    return input.value;
                });

            var tot_harga = $("#tot_hrg").val();

            var nomTot = 0;

            for (var i =0; i < nominal.length; i++) {
                var nomTermin = nominal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                nomTot += parseInt(nomTermin);
            }

            if (
                parseInt(nomTot) == parseInt(tot_harga.replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "")) &&
                parseInt(tot_harga.replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "")) != 0
            ) {
                $("#btn_submit").attr("disabled", false);
                $("#btn_submit").attr("style", "cursor: pointer");
            } else {
                $("#btn_submit").attr("disabled", true);
                $("#btn_submit").attr("style", "cursor: not-allowed");
            }
        }
        
        function updateSisaPembayaran() {
            var inpNominal = document.getElementsByClassName( 'nominal' ),
                nominal  = [].map.call(inpNominal, function( input ) {
                    return input.value;
                });

            var tot_harga = $("#tot_hrg").val();

            var nomTot = 0;

            for (var i =0; i < nominal.length; i++) {
                var nomTermin = nominal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                nomTot += parseInt(nomTermin);
            }

            var sisa = parseInt(tot_harga.replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "")) - parseInt(nomTot);

            $("#sisapembayaran").html(convertToCurrency(sisa));

            visibleSimpan();

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
                url: '{{ url('/produksi/orderproduksi/get-satuan/') }}'+'/'+idItem,
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

        function setTerimin() {
            var inputs = document.getElementsByClassName('termin'),
                termin  = [].map.call(inputs, function( input ) {
                    return parseInt(input.value);
                });

            for (var i=0; i < termin.length; i++) {
                $(".termin").eq(i).val('');
                $(".termin").eq(i).val(i+1);
            }
            changeNominalTermin();
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

            var item = [];
            var inpItemid = document.getElementsByClassName( 'itemid' ),
                item  = [].map.call(inpItemid, function( input ) {
                    return input.value;
                });

            var supp = $('#supplier').val();
            $( ".barang" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: '{{ url('/produksi/orderproduksi/cari-barang') }}',
                        data: {
                            idItem: item,
                            supp: supp,
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

        function itemSupplier() {
            window.open("{{ url('masterdatautama/suplier/index') }}");
        }
    </script>
@endsection
