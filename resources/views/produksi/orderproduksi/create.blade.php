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
                                                <input type="text" class="form-control form-control-sm datepicker">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 col-xs-12">
                                            <label>Supplier</label>
                                        </div>

                                        <div class="col-md-9 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="supplier" id="supplier"
                                                        class="form-control form-control-sm select2">
                                                    @foreach($suppliers as $supplier)
                                                        <option value="{{$supplier->s_id}}">{{$supplier->s_name}}</option>
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
                                            </div>
                                        </div>
                                        <div class="container">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" cellspacing="0">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th>Kode Barang/Nama Barang</th>
                                                        <th width="10%">Satuan</th>
                                                        <th width="10%">Jumlah</th>
                                                        <th>Harga</th>
                                                        <th>Sub Total</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text"
                                                                   name="cari-barang"
                                                                   id="barang"
                                                                   class="form-control form-control-sm cari-barang">
                                                        </td>
                                                        <td>
                                                            <select name="cari-satuan" id="satuan"
                                                                    class="form-control form-control-sm select2">
                                                                @foreach($units as $unit)
                                                                    <option
                                                                        value="{{$unit->u_id}}">{{$unit->u_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                   name="cari-jumlah"
                                                                   id="jumlah"
                                                                   min="0"
                                                                   class="form-control form-control-sm"
                                                                   value="0">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="cari-harga"
                                                                   id="harga"
                                                                   class="form-control form-control-sm input-rupiah">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="cari-subtotal"
                                                                   id="subtotal"
                                                                   style="text-align: right;"
                                                                   class="form-control form-control-sm" readonly>
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
                                                        <th>Harga</th>
                                                        <th>Sub Total</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="container">
                                            <hr style="border:0.7px solid grey; margin-bottom:30px;">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" cellspacing="0"
                                                       id="table_order_termin">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th>Termin</th>
                                                        <th>Estimasi</th>
                                                        <th>Nominal</th>
                                                        <th>Tanggal</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="text"
                                                                   name="termin[]"
                                                                   class="form-control form-control-sm termin"
                                                                   value="1">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="estimasi[]"
                                                                   class="form-control form-control-sm datepicker">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="nominal[]"
                                                                   class="form-control form-control-sm input-rupiah">
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                   name="tanggal[]"
                                                                   class="form-control form-control-sm datepicker">
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
                            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
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

        $(document).ready(function () {
            $('#type_cus').change(function () {
                if ($(this).val() === 'kontrak') {
                    $('#label_type_cus').text('Jumlah Bulan');
                    $('#jumlah_hari_bulan').val('');
                    $('#pagu').val('');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('.120mm').removeClass('d-none');
                    $('.125mm').addClass('d-none');
                    $('.122mm').removeClass('d-none');
                } else if ($(this).val() === 'harian') {
                    $('#label_type_cus').text('Jumlah Hari');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('#pagu').val('');
                    $('#jumlah_hari_bulan').val('');
                    $('.122mm').addClass('d-none');
                    $('.120mm').removeClass('d-none');
                    $('.125mm').removeClass('d-none');
                } else {
                    $('#jumlah_hari_bulan').val('');
                    $('#armada').prop('selectedIndex', 0).trigger('change');
                    $('#pagu').val('');
                    $('.122mm').addClass('d-none');
                    $('.120mm').addClass('d-none');
                    $('.125mm').addClass('d-none');
                }
            });

            $(document).on('click', '.btn-hapus', function () {
                $(this).parents('tr').remove();
                updateTotalTampil();
            });

            $( ".cari-barang" ).autocomplete({
                source: baseUrl+'/produksi/orderproduksi/cari-barang',
                minLength: 1,
                select: function(event, data) {
                    setItem(data.item);
                }
            });

            $('.btn-tambah').on('click', function () {
                tambah();
            });

            $("#jumlah").on('input', function (evt) {
                var harga = $("#harga").val().replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                var qty = $(this).val();
                if (harga == "") {
                    harga = 0;
                }
                var hasil = parseInt(harga) * parseInt(qty);
                if (isNaN(hasil)) {
                    hasil = 0;
                }
                hasil = convertToRupiah(hasil);
                $("#subtotal").val(hasil);
            })

            $("#harga").on('keyup', function (evt) {
                var harga = $(this).val().replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                var qty = $("#jumlah").val();
                var hasil = parseInt(harga) * parseInt(qty);
                hasil = convertToRupiah(hasil);
                $("#subtotal").val(hasil);
            })

            function setItem(info) {
                idItem = info.data[0].i_id;
                namaItem = info.data[0].i_name;
            }

            $(document).on('click', '.btn-hapus-termin', function () {
                $(this).parents('tr').remove();
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
                        '<td><input type="text" name="termin[]" class="form-control form-control-sm termin" value="' + next_termin + '"></td>' +
                        '<td><input type="text" name="estimasi[]" class="form-control form-control-sm datepicker"></td>' +
                        '<td><input type="text" name="nominal[]" class="form-control form-control-sm input-rupiah"></td>' +
                        '<td><input type="text" name="tanggal[]" class="form-control form-control-sm datepicker"></td>' +
                        '<td><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>' +
                        '</tr>'
                    );
                $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    enableOnReadonly: false,
                    autoclose: true

                });
                $('.input-rupiah').maskMoney({
                    thousands: ".",
                    decimal: ",",
                    prefix: "Rp. "
                });
            });

            $(document).on('click', '.btn-submit', function (evt) {
                evt.preventDefault();
                $.ajax({
                    url   : "{{route('order.create')}}",
                    type  : "post",
                    data  : $('#form').serialize(),
                    dataType : "json",
                    beforeSend: function() {
                        loadingShow();
                    },
                    success : function (response){
                        if(response.status == 'sukses'){
                            loadingHide();
                            messageSuccess('Success', 'Data berhasil ditambahkan!');
                            window.location.href = "{{route('cabang.create')}}";
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
            })
        });

        function resetForm() {
            $("#barang").val('');
            $("#jumlah").val(0);
            $("#harga").val('');
            $("#subtotal").val('');
        }

        function tambah() {
            var row = '';
            var satuanVal = $("#satuan").val();
            var satuan = $("#satuan option:selected").text();
            var jumlah = $("#jumlah").val();
            var harga = $("#harga").val();
            var subtotal = $("#subtotal").val();
            row = '<tr>' +
                '<td>'+namaItem+'<input type="hidden" name="idItem[]" value="'+idItem+'"></td>' +
                '<td>'+satuan+'<input type="hidden" name="satuan[]" value="'+satuanVal+'"></td>' +
                '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm" value="'+jumlah+'"></td>' +
                '<td><input type="text" name="hrg[]" class="form-control form-control-sm input-rupiah" value="'+harga+'"><input type="hidden" name="harga[]" value="'+harga.replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "")+'"></td>' +
                '<td><input type="text" name="sbtotal[]" class="form-control form-control-sm" style="text-align: right;" readonly value="'+subtotal+'"><input type="hidden" name="subtotal[]" class="subtotal" value="'+subtotal.replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "")+'"></td>' +
                '<td><button class="btn btn-danger btn-sm btn-hapus" type="button"><i class="fa fa-trash-o"></i></button></td>' +
                '</tr>';
            $('#table_order').append(row);
            $('.input-rupiah').maskMoney({
                thousands: ".",
                precision: 0,
                decimal: ",",
                prefix: "Rp. "
            });
            resetForm();
            updateTotalTampil();
        }

        function updateTotalTampil() {
            var total = 0;

            var inputs = document.getElementsByClassName( 'subtotal' ),
                subtotal  = [].map.call(inputs, function( input ) {
                    return input.value;
                });

            for (var i = 0; i < subtotal.length; i++){
                total += parseInt(subtotal[i]);
            }

            $("#total_harga").val(convertToRupiah(total));

        }
    </script>
@endsection
