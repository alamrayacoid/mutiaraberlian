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

                        <div class="card-block">
                            <section>

                                <div class="row">

                                    <div class="col-md-2 col-sm-6 col-xs-12">
                                        <label>Order Ke</label>
                                    </div>

                                    <div class="col-md-10 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <select name="" id="select-order" class="form-control form-control-sm select2">
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
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    {{--<script type="text/javascript">--}}
        {{--$(document).ready(function(){--}}
            {{--$('#type_cus').change(function(){--}}
                {{--if($(this).val() === 'kontrak'){--}}
                    {{--$('#label_type_cus').text('Jumlah Bulan');--}}
                    {{--$('#jumlah_hari_bulan').val('');--}}
                    {{--$('#pagu').val('');--}}
                    {{--$('#armada').prop('selectedIndex', 0).trigger('change');--}}
                {{--} else if($(this).val() === 'harian'){--}}
                    {{--$('#label_type_cus').text('Jumlah Hari');--}}
                    {{--$('#armada').prop('selectedIndex', 0).trigger('change');--}}
                    {{--$('#pagu').val('');--}}
                    {{--$('#jumlah_hari_bulan').val('');--}}
                {{--} else {--}}
                    {{--$('#jumlah_hari_bulan').val('');--}}
                    {{--$('#armada').prop('selectedIndex', 0).trigger('change');--}}
                    {{--$('#pagu').val('');--}}
                {{--}--}}
            {{--});--}}

            {{--$(document).on('click', '.btn-hapus-agen', function(){--}}
                {{--$(this).parents('tr').remove();--}}
            {{--});--}}

            {{--$('.btn-tambah-agen').on('click',function(){--}}
                {{--$('#table_agen')--}}
                    {{--.append(--}}
                        {{--'<tr>'+--}}
                        {{--'<td><input type="text" class="form-control form-control-sm"></td>'+--}}
                        {{--'<td><select name="#" id="#" class="form-control form-control-sm select2"><option value=""></option></select></td>'+--}}
                        {{--'<td><input type="number" class="form-control form-control-sm" value="0"></td>'+--}}
                        {{--'<td><input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"></td>'+--}}
                        {{--'<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+--}}
                        {{--'<td><button class="btn btn-danger btn-hapus-agen btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>'+--}}
                        {{--'</tr>'--}}
                    {{--);--}}
            {{--});--}}

            {{--$(document).on('click', '.btn-hapus-cabang', function(){--}}
                {{--$(this).parents('tr').remove();--}}
            {{--});--}}

            {{--$('.btn-tambah-cabang').on('click',function(){--}}
                {{--$('#table_cabang')--}}
                    {{--.append(--}}
                        {{--'<tr>'+--}}
                        {{--'<td><input type="text" class="form-control form-control-sm"></td>'+--}}
                        {{--'<td><select name="#" id="#" class="form-control form-control-sm select2"><option value=""></option></select></td>'+--}}
                        {{--'<td><input type="number" class="form-control form-control-sm" value="0"></td>'+--}}
                        {{--'<td><input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 0"></td>'+--}}
                        {{--'<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+--}}
                        {{--'<td><button class="btn btn-danger btn-hapus-cabang btn-sm rounded-circle" type="button"><i class="fa fa-trash-o"></i></button></td>'+--}}
                        {{--'</tr>'--}}
                    {{--);--}}
            {{--});--}}
            {{--$(document).on('click', '.btn-submit', function(){--}}
                {{--$.toast({--}}
                    {{--heading: 'Success',--}}
                    {{--text: 'Data Berhasil di Simpan',--}}
                    {{--bgColor: '#00b894',--}}
                    {{--textColor: 'white',--}}
                    {{--loaderBg: '#55efc4',--}}
                    {{--icon: 'success'--}}
                {{--})--}}
            {{--})--}}
        {{--});--}}
    {{--</script>--}}
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
            changeSatuanAgen();
            changeJumlahAgen();
            changeHargaAgen();
            visibleTableItemAgen();

            $("#a_kota").on("change", function (evt) {
                evt.preventDefault();
                if ($("#a_kota").val() == "") {
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
                    $("#a_compapj").val('');
                    $("#a_apj").val('');
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
                    $("#a_compapb").val('');
                    $("#a_apb").val('');
                    $("#a_apj").attr("disabled", true);
                    $("#a_apb").attr("disabled", true);
                } else {
                    $("#a_apj").attr("disabled", false);
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
                    $("#a_compapj").val('');
                    $("#a_apj").val('');
                    $("#a_apj").focus();
                }
            })

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

            $("#a_apj").on("keyup", function (evt) {
                evt.preventDefault();
                if (evt.which == 8 || evt.which == 46)
                {
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
                    $("#a_compapj").val('');
                    $("#a_apb").attr("disabled", true);
                } else if (evt.which <= 90 && evt.which >= 48)
                {
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
                    $("#a_compapj").val('');
                    $("#a_apb").attr("disabled", true);
                }

            })

            $( "#a_apj" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: baseUrl+'/marketing/agen/orderproduk/cari-penjual/'+$("#a_prov").val()+'/'+$("#a_kota").val(),
                        data: {
                            term: $( "#a_apj" ).val()
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                minLength: 1,
                select: function(event, data) {
                    $( "#a_idapj" ).val(data.item.id);
                    $( "#a_kodeapj" ).val(data.item.kode);
                    $("#a_apb").attr("disabled", false);
                    $("#a_apb").focus();
                }
            });

            $("#a_apb").on("keyup", function (evt) {
                evt.preventDefault();
                if (evt.which == 8 || evt.which == 46)
                {
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
                    $("#a_compapb").val('');
                    visibleTableItemAgen();
                } else if (evt.which <= 90 && evt.which >= 48)
                {
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
                    $("#a_compapb").val('');
                    visibleTableItemAgen();
                }

            })

            $( "#a_apb" ).autocomplete({
                source: function( request, response ) {
                    $.ajax({
                        url: baseUrl+'/marketing/agen/orderproduk/cari-pembeli/'+$("#a_kodeapj").val(),
                        data: {
                            term: $( "#a_apb" ).val()
                        },
                        success: function( data ) {
                            response( data );
                        }
                    });
                },
                minLength: 1,
                select: function(event, data) {
                    $( "#a_idapb" ).val(data.item.id);
                    $( "#a_kodeapb" ).val(data.item.kode);
                    visibleTableItemAgen();
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
                    $("#a_apj").attr("disabled", true);
                    $("#a_apb").attr("disabled", true);
                }
            })
        }

        function changeSatuanAgen() {
            //
        }

        function changeJumlahAgen() {
            //
        }

        function changeHargaAgen() {
            //
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
    </script>
@endsection
