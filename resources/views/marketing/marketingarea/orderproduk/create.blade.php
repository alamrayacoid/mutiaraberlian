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
                        <section>

                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label>Area</label>
                                </div>

                                <div class="col-md-10 col-sm-6 col-xs-12">
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <select name="po_prov" id="prov" class="form-control form-control-sm select2" onchange="getProvId()">
                                                <option value="" selected="" disabled="">=== Pilih Provinsi ===</option>
                                                @foreach($provinsi as $prov)
                                                    <option value="{{$prov->wp_id}}">{{$prov->wp_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-6">
                                            <select name="po_city" id="city" class="form-control form-control-sm select2 city">
                                                <option value="" selected disabled>=== Pilih Kota ===</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label>Cabang</label>
                                </div>

                                <div class="col-md-10 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <select name="po_comp" id="comp" class="form-control form-control-sm select2">
                                            <option value="" selected="" disabled="">=== Pilih Cabang ===</option>
                                            @foreach($company as $comp)
                                                <option value="{{$comp->c_id}}">{{$comp->c_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label>Agen Pembeli</label>
                                </div>

                                <div class="col-md-10 col-sm-6 col-xs-12">
                                    <div class="form-group">                                        
                                        <input type="text" name="agen[]" class="form-control form-control-sm agen" id="dataAgen" style="text-transform:uppercase">
                                        <input type="hidden" id="idAgen" name="idAgen[]" class="idAgen">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label>Total Harga</label>
                                </div>

                                <div class="col-md-10 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" name="" readonly="">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" cellspacing="0" id="table_cabang">
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
                                            <tr>
                                                <td>
                                                    <input type="text" name="barang[]"
                                                                   class="form-control form-control-sm barang"
                                                                   style="text-transform:uppercase">
                                                    <input type="hidden" name="idItem[]" class="itemid">
                                                    <input type="hidden" name="kode[]" class="kode">
                                                </td>
                                                <td>                                                    
                                                    <select name="t_unit[]"
                                                            class="form-control form-control-sm select2 satuan">
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" value="0">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" value="" readonly="">
                                                </td>
                                                <td>
                                                    <button class="btn btn-success btn-tambah-cabang btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary btn-submit" type="button">Simpan</button>
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
    var idItem = [];
    var namaItem = null;
    var kode = null;
    var idxBarang = null;
    var icode = [];
    $(document).ready(function() {
        $('#dataAgen').autocomplete({
          source: baseUrl+'/marketing/marketingarea/orderproduk/cari-agen',
          minLength: 2,
          select: function(event, data){
              $('#idAgen').val(data.item.id);
          }
        });

        // AutoComplete Item --------------------------------
        $('.barang').on('click', function (e) {
            idxBarang = $('.barang').index(this);
            setArrayCode();
        });

        $(".barang").eq(idxBarang).on("keyup", function () {
            $(".itemid").eq(idxBarang).val('');
            $(".kode").eq(idxBarang).val('');
        });

        function setItem(info) {
            idItem = info.data.i_id;
            namaItem = info.data.i_name;
            kode = info.data.i_code;
            $(".kode").eq(idxBarang).val(kode);
            $(".itemid").eq(idxBarang).val(idItem);
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
                code = [].map.call(inputs, function (input) {
                    return input.value.toString();
                });

            for (var i = 0; i < code.length; i++) {
                if (code[i] != "") {
                    icode.push(code[i]);
                }
            }

            var item = [];
            var inpItemid = document.getElementsByClassName('itemid'),
                item = [].map.call(inpItemid, function (input) {
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

        // End AutoComplete Item ---------------------------------------

        $('#type_cus').change(function() {
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

        $('.btn-tambah-cabang').on('click', function() {
            $('#table_cabang')
                .append(
                    '<tr>' +
                        '<td>'+
                            '<input type="text" name="barang[]" class="form-control form-control-sm barang"'+
                            'style="text-transform:uppercase">'+
                            '<input type="hidden" name="idItem[]" class="itemid">'+
                            '<input type="hidden" name="kode[]" class="kode">'+
                        '</td>' +
                        '<td><select name="t_unit[]" class="form-control form-control-sm select2 satuan">'+
                            '</select>'+
                        '</td>' +
                        '<td><input type="number" class="form-control form-control-sm" value="0"></td>' +
                        '<td><input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"></td>' +
                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>' +
                        '<td><button class="btn btn-danger btn-hapus-order btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>' +
                    '</tr>'
                );

            $('.barang').on('click', function (e) {
                idxBarang = $('.barang').index(this);
                setArrayCode();
            });

            $(".barang").eq(idxBarang).on("keyup", function () {
                $(".itemid").eq(idxBarang).val('');
                $(".kode").eq(idxBarang).val('');
            });
        });

        $(document).on('click', '.btn-hapus-order', function () {
            $(this).parents('tr').remove();
        });              
    });

    function getProvId()
    {
        var id = document.getElementById("prov").value;
        $.ajax({
            url: "{{route('orderproduk.getCity')}}",
            type: "get",
            data:{
                provId: id
            },
            success: function (response) {
                $('#city').empty();
                $.each(response.data, function( key, val ) {
                    $("#city").append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
                });
                $('#city').focus();
                $('#city').select2('open');
            }
        });
    }

</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#select-order').change(function() {
            var ini, agen, cabang;
            ini = $(this).val();
            agen = $('#agen');
            cabang = $('#cabang');

            if (ini === '1') {
                agen.removeClass('d-none');
                cabang.addClass('d-none');
            } else if (ini === '2') {
                agen.addClass('d-none');
                cabang.removeClass('d-none');
            } else {
                agen.addClass('d-none');
                cabang.addClass('d-none');
            }
        });
    });

</script>
@endsection
