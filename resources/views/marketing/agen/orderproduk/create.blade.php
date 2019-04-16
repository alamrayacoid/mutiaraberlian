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
        $(document).ready(function(){
            getProvAgen();
            getKotaAgen();

            if ($('#select-order').val() == "1") {
                $('#agen').removeClass('d-none');
                $('#cabang').addClass('d-none');
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
                } else if (evt.which <= 90 && evt.which >= 48)
                {
                    $("#a_idapj").val('');
                    $("#a_kodeapj").val('');
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
                }
            });

            $("#a_apb").on("keyup", function (evt) {
                evt.preventDefault();
                if (evt.which == 8 || evt.which == 46)
                {
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
                } else if (evt.which <= 90 && evt.which >= 48)
                {
                    $("#a_idapb").val('');
                    $("#a_kodeapb").val('');
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
                }
            })
        }
    </script>
@endsection
