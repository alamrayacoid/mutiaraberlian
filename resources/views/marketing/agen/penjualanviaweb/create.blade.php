@extends('main')

@section('extra_style')
    <style type="text/css">
        .txt-readonly {
            background-color: transparent;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Kelola Penjualan via Website </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Marketing</span>
                / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Kelola Penjualan via Website</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data Kelola Penjualan via Website </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <form class="myForm" autocomplete="off">
                            <div class="card-block">
                                <section>
                                    <div id="sectionsuplier" class="row">
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Tanggal Transaksi</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                </div>
                                                <input type="text" name="dateKPW" class="form-control form-control-sm datepicker" id="dateKPW" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>

                                        @if($type == 'PUSAT')
                                            <label class="col-2 form-group">Area Provinsi :</label>
                                            <div class="col-4 form-group">
                                                <select class="form-control form-control-sm select2" id="provKPW">
                                                    <option selected disabled>== Pilih Provinsi ==</option>
                                                    @foreach($provinsi as $prov)
                                                        <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <label for="area_kota" class="col-2 form-group">Area Kota :</label>
                                            <div class="col-4 form-group">
                                                <select class="form-control form-control-sm select2" id="citiesKPW">
                                                    <option value="">== Pilih Kota ==</option>
                                                </select>
                                            </div>
                                        @endif

                                        <label for="nama_agen" class="col-2 form-group select-agent">Nama Agen :</label>
                                        <div class="col-4 form-group select-agent">
                                            <input type="hidden" value="{{ $data['user'] }}" id="user">
                                            <select class="form-control form-control-sm select2" id="nama_agen">
                                                <option value="" selected disabled>Pilih Agen</option>
                                                @foreach($data['agents'] as $agent)
                                                    <option value="{{ $agent->c_id }}">{{ $agent->a_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <label for="nama_customer" class="col-2 form-group">Nama Customer :</label>
                                        <div class="col-4 form-group">
                                            <select class="form-control form-control-sm select2" id="nama_customer">
                                                <option value="" selected disabled>Pilih Member</option>
                                                @foreach($data['member'] as $member)
                                                    <option
                                                        value="{{ $member->m_code }}">{{ $member->m_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <label for="website" class="col-2 form-group">Website :</label>
                                        <div class="col-4 form-group">
                                            <input type="text" class="form-control-sm form-control" id="website">
                                        </div>

                                        <label for="website" class="col-2 form-group">Kode Transaksi :</label>
                                        <div class="col-4 form-group">
                                            <input type="text" class="form-control-sm form-control" id="transaksi"
                                            style="text-transform: uppercase">
                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <label for="produk" class="col-2 form-group">Produk :</label>
                                        <div class="col-10 form-group">
                                            <input type="text" class="form-control-sm form-control" id="produk"
                                            style="text-transform: uppercase">
                                            <input type="hidden" class="form-control-sm form-control" id="id_produk">
                                        </div>

                                        <label for="kuantitas" class="col-2 form-group">Kuantitas :</label>
                                        <div class="col-2 form-group">
                                            <input type="number" class="form-control-sm form-control" id="kuantitas" min="0" onkeyup="checkStock()">
                                        </div>

                                        <label for="satuan" class="col-2 form-group">Satuan :</label>
                                        <div class="col-2 form-group">
                                            <select class="select2" id="satuan">
                                                <!-- <option>== Pilih Satuan ==</option> -->
                                            </select>
                                        </div>

                                        <label for="harga" class="col-2 form-group">Harga/<span id="label-satuan">-</span> :</label>
                                        <div class="col-2 form-group">
                                            <input type="text" class="form-control-sm form-control rupiah" id="harga" onkeyup="setTotal()">
                                        </div>

                                        <label for="total" class="col-2 form-group">Total :</label>
                                        <div class="col-4 form-group">
                                            <input type="text" class="form-control-sm form-control rupiah" id="total" readonly>
                                        </div>
                                        <div class="col-md-6"></div>

                                        <label for="note" class="col-2 form-group">Catatan :</label>
                                        <div class="col-10 form-group">
                                            <textarea class="form-control form-control-sm" id="note"></textarea>
                                        </div>

                                        <div class="col-8 form-group">
                                            <input type="text" class="form-control-sm form-control" id="code" placeholder="Kode Produksi" style="text-transform: uppercase">
                                        </div>
                                        <div class="col-3 form-group">
                                            <input type="number" class="form-control-sm form-control" id="code_qty">
                                        </div>
                                        <div class="col-1 form-group">
                                            <button class="btn btn-primary" type="button" id="btnAddCodeKPW" onclick="addCode()"><i class="fa fa-plus"></i></button>
                                        </div>

                                        <div class="row col-md-12 form-group">
                                            <div class="table-responsive" style="padding: 0px 15px 0px 15px;">
                                                <table class="table table-hover table-striped display w-100" cellspacing="0" id="table_KPW">
                                                    <thead class="bg-primary">
                                                        <tr>
                                                            <th style="width: 70%">Kode</th>
                                                            <th style="width: 20%">Qty</th>
                                                            <th style="width: 10%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </section>
                            </div>
                        </form>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary" id="btn_simpan" type="button">Simpan</button>
                            <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        var table_kpw;

        $(document).ready(function () {
            table_kpw = $('#table_KPW').DataTable({
                bAutoWidth: true,
                responsive: true,
                info: false,
                searching: false,
                paging: false
            });
            table_kpw.columns.adjust();

            $('#provKPW').on('change', function () {
                getCitiesKPW();
            });
            $('#citiesKPW').on('change', function () {
                getAgen();
            });

            $('#nama_agen').on('select2:select', function() {
                getCustomer();
            });

            $('#satuan').change(function(){
                var selected = $(this).find('option:selected').data('nama');
                $('#label-satuan').html(selected);
            });

            $('#btn_simpan').on('click', function() {
                saveSalesWeb();
            })

        }); // end: document ready

        $( "#produk" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "{{ route('kelolapenjualanviawebsite.cariProduk') }}",
                    data: {
                        term: $("#produk").val()
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 1,
            select: function(event, data) {
                $('#id_produk').val(data.item.id);
                getUnit();
            }
        });

        function getUnit() {
            let item = $('#id_produk').val();
            axios.get(baseUrl + '/marketing/agen/kelolapenjualanlangsung/get-unit/' + item,).then(function (response) {
                $('#satuan').empty();
                $("#satuan").append('<option selected data-nama="'+response.data.get_unit1.u_name+'" value="' + response.data.get_unit1.u_id + '">' + response.data.get_unit1.u_name + '</option>');
                $('#satuan').trigger('change');
            }).catch(function (error) {
                alert("error");
            })
        }

        function getCustomer() {
            loadingShow();
            let agen = $('#nama_agen').val();
            axios.get('{{ route("kelolapenjualan.getMemberKPL") }}', {
                'agentCode': agen,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                loadingHide();
                $("#nama_customer").empty();
                $.each(response.data, function (key, val) {
                    $("#nama_customer").append('<option value="' + val.m_code + '">' + val.m_name + '</option>');
                });
                $('#nama_customer').focus();
                $('#nama_customer').select2('open');
            }).catch(function (error) {
                loadingHide();
                alert('error');
            })
        }

        function setTotal() {
            let qty = $('#kuantitas').val();
            let harga = $('#harga').val();

            let total = parseInt(qty) * parseInt(harga);
            $('#total').val(total);
        }

        function checkStock() {
            let qty = $('#kuantitas').val();
            let harga = $('#harga').val();
            let agen = $('#nama_agen').val();
            let item = $('#id_produk').val();

            axios.get("{{ route('kelolapenjualanviawebsite.getStockKPW') }}", {
                params:{
                    "qty": qty,
                    "posisi": agen,
                    "item": item
                }
            })
            .then(function (response) {
                if (response.data.status == 'sukses') {
                    let total = parseInt(qty) * parseInt(harga);
                    $('#total').val(total);
                }
                else {
                    messageWarning('Perhatian', 'Stock tersedia : '+ parseInt(response.data.stock));
                    $('#kuantitas').val(response.data.stock);
                    let total = parseInt(response.data.stock) * parseInt(harga);
                    $('#total').val(total);
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan !');
            });
        }

        function getCitiesKPW() {
            var id = $('#provKPW').val();
            $.ajax({
                url: "{{route('kelolapenjualan.getCitiesKPL')}}",
                type: "get",
                data: {
                    provId: id
                },
                success: function (response) {
                    $('#citiesKPW').empty();
                    $("#citiesKPW").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
                    $.each(response.get_cities, function (key, val) {
                        $("#citiesKPW").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#citiesKPW').focus();
                    $('#citiesKPW').select2('open');
                }
            });
        }
        // get penjual for KPW
        function getAgen() {
            $.ajax({
                url: baseUrl +'/marketing/agen/orderproduk/get-penjual/'+ $("#citiesKPW").val(),
                type: 'get',
                success: function( data ) {
                    $('#nama_agen').empty();
                    $('#nama_agen').append('<option value="" selected disabled> == Pilih Agen ==</option>')
                    $.each(data, function(index, val) {
                        $('#nama_agen').append('<option value="'+ val.c_id +'" data-code="'+ val.a_code +'">'+ val.a_name +'</option>');
                    });
                    $('#nama_agen').focus();
                    $('#nama_agen').select2('open');
                },
                error: function(e) {
                }
            });
        }

        function addFilterAgentKpw(agentCode, agentName) {
            $('#filter_agent_name_kpw').val(agentName);
            $('#filter_agent_code_kpw').val(agentCode);
            $('#searchAgenKpw').modal('hide');
        }

        function saveSalesWeb() {
            let kuantitas = $('#kuantitas').val();
            let qty = $("input[name='qtycode[]']")
            .map(function(){return $(this).val();}).get();
            let totalqty = 0;
            for (let i = 0; i < qty.length; i++) {
                totalqty = totalqty + parseInt(qty[i]);
            }

            if (parseInt(kuantitas) != parseInt(totalqty)){
                messageWarning('Perhatian', 'Kuantitas barang tidak sama dengan jumlah kode produksi !');
            }
            else {
                lanjutkan();
            }
        }

        function lanjutkan() {
            valid = 1;
            let dateKPW   = $('#dateKPW').val();
            let provinsi  = $('#provKPW').val();
            let kota      = $('#citiesKPW').val();
            let agen      = $('#nama_agen').val();
            let customer  = $('#nama_customer').val();
            let website   = $('#website').val();
            let transaksi = $('#transaksi').val();
            let produk    = $('#id_produk').val();
            let kuantitas = $('#kuantitas').val();
            let satuan    = $('#satuan').val();
            let harga     = $('#harga').val();
            let note      = $('#note').val();
            let kode      = $("input[name='code[]']")
            .map(function(){return $(this).val();}).get();

            let kodeqty = $("input[name='qtycode[]']")
            .map(function(){return $(this).val();}).get();

            // if (provinsi == '' || provinsi == null){
            //     valid = 0;
            //     messageWarning("Perhatian", "Provinsi harus diisi !");
            //     jc.close();
            //     $('#provKPW').focus();
            //     $('#provKPW').select2('open');
            //     return false;
            // }
            // if (kota == '' || kota == null){
            //     valid = 0;
            //     messageWarning("Perhatian", "Kota harus diisi !");
            //     jc.close();
            //     $('#citiesKPW').focus();
            //     $('#citiesKPW').select2('open');
            //     return false;
            // }
            if (agen == '' || agen == null){
                valid = 0;
                messageWarning("Perhatian", "Agen harus diisi !");
                jc.close();
                $('#nama_agen').focus();
                $('#nama_agen').select2('open');
                return false;
            }
            if (customer == '' || customer == null){
                valid = 0;
                messageWarning("Perhatian", "Customer harus diisi !");
                jc.close();
                $('#nama_customer').focus();
                $('#nama_customer').select2('open');
                return false;
            }
            if (website == '' || website == null){
                valid = 0;
                messageWarning("Perhatian", "Url Website harus diisi !");
                jc.close();
                $('#website').focus();
                return false;
            }
            if (transaksi == '' || transaksi == null){
                valid = 0;
                messageWarning("Perhatian", "Kode Transaksi harus diisi !");
                jc.close();
                $('#transaksi').focus();
                return false;
            }
            if (produk == '' || produk == null){
                valid = 0;
                messageWarning("Perhatian", "Produk terjual harus diisi !");
                jc.close();
                $('#produk').focus();
                return false;
            }
            if (kuantitas == '' || kuantitas == null){
                valid = 0;
                messageWarning("Perhatian", "Kuantitas Produk harus diisi !");
                jc.close();
                $('#kuantitas').focus();
                return false;
            }
            if (satuan == '' || satuan == null){
                valid = 0;
                messageWarning("Perhatian", "Satuan Produk harus diisi !");
                jc.close();
                $('#satuan').focus();
                $('#satuan').select2('open');
                return false;
            }
            if (harga == '' || harga == null){
                valid = 0;
                messageWarning("Perhatian", "Harga Produk harus diisi !");
                jc.close();
                $('#harga').focus();
                return false;
            }
            if (valid == 1){
                loadingShow();
                axios.post('{{ route("kelolapenjualanviawebsite.saveKPW") }}', {
                    "date": dateKPW,
                    "agen": agen,
                    "website": website,
                    "customer": customer,
                    "transaksi": transaksi.toUpperCase(),
                    "item": produk,
                    "qty": kuantitas,
                    "unit": satuan,
                    "price": harga,
                    "note": note,
                    "code": kode,
                    "qtycode": kodeqty,
                    "_token": '{{ csrf_token() }}'
                }).then(function (response) {
                    loadingHide();
                    if (response.data.status == 'success'){
                        messageSuccess("Berhasil", "Data berhasil disimpan");
                        location.reload();
                    } else if (response.data.status == 'gagal'){
                        messageFailed("Gagal", response.data.message);
                    }
                }).catch(function (error) {
                    loadingHide();
                    messageWarning('Error', 'Terjadi kesalahan : '+ error);
                })
            }
        }

        function addCode() {
            loadingShow();
            //cek stockdt
            let agen = $('#nama_agen').val();
            let code = $('#code').val();
            let item = $('#id_produk').val();
            axios.get('{{ route("kelolapenjualanviawebsite.cekProductionCode") }}', {
                params:{
                    "posisi": agen,
                    "kode": code,
                    "item": item
                }
            })
            .then(function (response) {
                loadingHide();
                code = code.toUpperCase();
                if (response.data.status == 'gagal'){
                    messageFailed('Peringatan', 'Kode tidak ditemukan');
                } else if (response.data.status == 'sukses'){
                    let qty = $('#code_qty').val();
                    if (qty == '' || qty == 0 || qty == null){
                        qty = 1;
                    } else if (true) {}{

                    }
                    let values = $("input[name='code[]']")
                    .map(function(){return $(this).val();}).get();
                    if (!values.includes(code)){
                        ++counter;
                        table_kpw.row.add([
                            "<input type='text' class='code form-control form-control-sm codeprod' name='code[]' value='"+code+"' readonly>",
                            "<input type='number' class='qtycode form-control form-control-sm text-right' name='qtycode[]' value='"+qty+"'>",
                            "<button class='btn btn-danger btn-sm btn-delete-"+counter+"'><i class='fa fa-close'></i></button>"
                        ]).draw(false);
                        $('#table_KPW tbody').on( 'click', '.btn-delete-'+counter, function () {
                            table_kpw.row( $(this).parents('tr') )
                            .remove()
                            .draw();
                        } );
                        $('#code').val('');
                        $('#code_qty').val('');
                        $('#code').focus();
                    }
                    else {
                        messageWarning("Perhatian", "Kode sudah ada");
                        let idx = values.indexOf(code);
                        let qtylama = $('.qtycode').eq(idx).val();
                        let total = parseInt(qty) + parseInt(qtylama);
                        $('.qtycode').eq(idx).val(total);
                        $('.qtycode').eq(idx).focus();
                    }
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan !');
            });
        }

    </script>
@endsection
