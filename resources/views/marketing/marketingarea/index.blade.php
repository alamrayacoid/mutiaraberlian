@extends('main')
@section('tittle')
    Manajemen Marketing Area
@endsection
@section('extra_style')
    <style>
        @media (min-width: 992px) {
            .modal-xl {
                max-width: 1200px !important;
            }
        }
        #table_prosesorder td {
            padding-top: 2px;
            padding-bottom: 2px;
        }
        #table_prosesordercode td {
            padding-top: 2px;
            padding-bottom: 2px;
        }
        .btn-xs {
            padding: 0.20rem 0.4rem;
            font-size: 0.675rem;
            line-height: 1.3;
            border-radius: 0.2rem;
        }
        #table_prosesorder td.input-padding {
            padding: 1px !important;
        }
        .input-qty-proses{
            padding-right: 2px !important;
        }
        #table_prosesorder th.input-padding {
            width: 10% !important;
        }
    </style>
@stop
@section('content')

    @include('marketing.marketingarea.keloladataorder.modal')
    @include('marketing.marketingarea.keloladataorder.modal-search')
    @include('marketing.marketingarea.monitoring.modal-detail')
    @include('marketing.marketingarea.monitoring.modal-search')
    @include('marketing.marketingarea.datacanvassing.modal-create')
    @include('marketing.marketingarea.datacanvassing.modal-edit')
    @include('marketing.marketingarea.datacanvassing.modal-search')

    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Manajemen Marketing Area </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> /
                <span class="text-primary" style="font-weight: bold;">Manajemen Marketing Area</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-3" id="Tabzs">
                        <li class="nav-item">
                            <a href="#orderproduk" class="nav-link active" data-target="#orderproduk"
                               aria-controls="orderproduk" data-toggle="tab" role="tab">Order Produk ke Pusat</a>
                        </li>
                        <li class="nav-item">
                            <a href="#keloladataagen" class="nav-link" data-target="#keloladataagen"
                               aria-controls="keloladataagen" data-toggle="tab" role="tab" onclick="kelolaDataAgen()">Kelola
                                Data Order Agen </a>
                        </li>
                        <li class="nav-item">
                            <a href="#monitoringpenjualanagen" class="nav-link" data-target="#monitoringpenjualanagen"
                               aria-controls="monitoringpenjualanagen" data-toggle="tab" role="tab">Monitoring Data
                                Penjualan Agen</a>
                        </li>
                        <li class="nav-item">
                            <a href="#datacanvassing" class="nav-link" data-target="#datacanvassing"
                               aria-controls="datacanvassing" data-toggle="tab" role="tab">Kelola Data Canvassing</a>
                        </li>
                        <li class="nav-item">
                            <a href="#datakonsinyasi" class="nav-link" data-target="#datakonsinyasi"
                               aria-controls="datakonsinyasi" data-toggle="tab" role="tab">Kelola Data Konsinyasi </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        @include('marketing.marketingarea.orderproduk.index')
                        @include('marketing.marketingarea.keloladataorder.index')
                        @include('marketing.marketingarea.monitoring.index')
                        @include('marketing.marketingarea.datacanvassing.index')
                        @include('marketing.marketingarea.datakonsinyasi.index')
                    </div>
                </div>
            </div>
        </section>
    </article>

    {{-- Modal Order Ke Cabang --}}
    <div class="modal fade" id="modalOrderCabang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Order Ke Cabang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cabang">Nama Cabang</label>
                            <input type="text" class="form-control bg-light" id="cabang" value="" readonly=""
                                   disabled="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nota">Nomer Nota</label>
                            <input type="text" class="form-control bg-light" id="nota" value="" readonly="" disabled="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="agen">Nama Agen</label>
                            <input type="text" class="form-control bg-light" id="agen" value="" readonly="" disabled="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal">Tanggal Order</label>
                            <input type="text" class="form-control bg-light" id="tanggal" value="" readonly=""
                                   disabled="">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="detailOrder" class="table table-sm table-hover table-bordered">
                            <thead>
                            <tr class="bg-primary text-light">
                                <th class="text-center">Nama Barang</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <!-- <th>Harga Satuan</th>
                                <th>Total Harga</th> -->
                            </tr>
                            </thead>
                            <tbody class="empty">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Detail Kelola Data Agen --}}
    <div class="modal fade" id="modalOrderAgen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Kelola Data Agen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cabang">Nama Cabang</label>
                            <input type="text" class="form-control bg-light" id="cabang2" value="" readonly=""
                                   disabled="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nota">Nomer Nota</label>
                            <input type="text" class="form-control bg-light" id="nota2" value="" readonly=""
                                   disabled="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="agen">Nama Agen</label>
                            <input type="text" class="form-control bg-light" id="agen2" value="" readonly=""
                                   disabled="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal">Tanggal Order</label>
                            <input type="text" class="form-control bg-light" id="tanggal2" value="" readonly=""
                                   disabled="">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="detailAgen" class="table table-sm table-hover table-bordered">
                            <thead>
                            <tr class="bg-primary text-light">
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                            </thead>
                            <tbody class="emptyAgen">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Approval Kelola Data Agen --}}
    <div id="prosesorder" class="modal fade animated fadeIn" role="dialog">
        <div class="modal-dialog modal-xl">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-gradient-info">
                    <h4 class="modal-title">Detail Order</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <section>
                        <div class="row">
                            <div class="col-2">
                                <label for="">Nomor Nota</label>
                            </div>
                            <div class="col-4">
                                <input type="hidden" id="idProductOrder" value="">
                                <input type="text" class="form-control form-control-sm" id="nota_modaldt" readonly="">
                            </div>

                            <div class="col-2">
                                <label for="">Tanggal</label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm" id="tanggal_modaldt" readonly="">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-2">
                                <label for="">Agen</label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm" id="agen_modaldt" readonly="">
                                <input type="hidden" class="form-control form-control-sm" id="idagen_modaldt">
                            </div>

                            <div class="col-2">
                                <label for="">Total Pembelian</label>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm rupiah" id="total_modaldt" readonly="">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-2">
                                <label for="expedition">Jasa Ekspedisi</label>
                            </div>
                            <div class="col-4">
                                <select name="" id="expedition" class="form-control form-control-sm select2">
                                    <option value="" selected disabled>== Pilih Jasa Ekspedisi ==</option>
                                </select>
                            </div>
                            <div class="col-2">
                                <label for="jenis_exp">Jenis Ekspedisi</label>
                            </div>
                            <div class="col-4">
                                <select name="" id="jenis_exp" class="form-control form-control-sm select2">
                                    <option value="" selected="" disabled="">== Pilih Jenis Ekspedisi ==</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-2">
                                <label for="jenis_exp">Nama Kurir</label>
                            </div>
                            <div class="col-4">
                                <input type="text" id="kurir_name" class="form-control form-control-sm">
                            </div>
                            <div class="col-2">
                                <label for="expedition">Nomor Telepon</label>
                            </div>
                            <div class="col-4">
                                <input type="text" id="no_hpkurir" class="form-control form-control-sm hp">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div class="col-2">
                                <label for="jenis_exp">Nomor Resi</label>
                            </div>
                            <div class="col-4">
                                <input type="text" id="no_resi" class="form-control form-control-sm text-uppercase">
                            </div>
                            <div class="col-2">
                                <label for="jenis_exp">Biaya</label>
                            </div>
                            <div class="col-4">
                                <input type="text" id="biaya_kurir" class="form-control form-control-sm rupiah">
                            </div>
                        </div>
                    </section>
                    <div class="row" style="margin-top: 10px">
                        <div class="table-responsive col-8">
                            <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_prosesorder" width="100%">
                                <thead class="bg-primary">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
                                    <th>Harga @</th>
                                    <th>Harga Total</th>
                                    <th class="text-center">Kode</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-4" style="padding-right: 0px;">
                            <div class="row col-12" style="padding-right: 0px;">
                                <div class="col-8" style="padding-left: 0px !important;">
                                    <input type="text" onkeypress="pressCode(event)" style="width: 100%; text-transform: uppercase" class="inputkodeproduksi form-control form-control-sm" id="inputkodeproduksi" readonly>
                                    <input type="hidden" id="iditem_modaldt">
                                </div>
                                <div class="input-group col-4" style="width: 100%; padding-right: 0px;">
                                    <input type="number" onkeypress="pressCode(event)" class="inputqtyproduksi form-control form-control-sm" id="inputqtyproduksi" readonly>
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-addprodcode"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row col-12">
                                <p>Masukkan kode produksi untuk barang <span class="text-item">-</span> kemudian tekan Enter untuk memasukkan ke tabel distribusi</p>
                            </div>
                            <table class="table table-striped table-hover display table-bordered" cellspacing="0" id="table_prosesordercode" width="100%">
                                <thead class="bg-primary">
                                <tr>
                                    <th>Kode Produksi</th>
                                    <th>Kuantitas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-sm" onclick="approveAndSendItems()" id="btnApproveAndSend" style="color:white;">Setuju dan Kirim Barang</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('extra_script')
    <!-- ========================================================================-->
    <!-- script for Data-Konsinyasi etc -->
    <script type="text/javascript">
        $(document).ready(function () {
            getProv();
            getKota();
            // check user is PUSAT or not
            let userType = $('.userType').val();
            if (userType === 'PUSAT') {
                console.log('\'pusat\' logged in !');
                $('.filterBranch').removeClass('d-none');
            }
            else {
                console.log('\'non-pusat\' logged in !');
                $('.filterBranch').addClass('d-none');
                $('#branchCode').val("{{ Auth::user()->getCompany->c_id }}");
                console.log($('#branchCode').val());
            }

            $(".cityIdxDK").on("change", function (evt) {
                evt.preventDefault();
                if ($(".cityIdxDK").val() == "") {
                    $("#branchCode").val('');
                    $("#branch").val('');
                    $('#branch').find('option').remove();
                    $("#branch").attr("disabled", true);
                }
                else {
                    getBranch();
                    $("#branch").attr("disabled", false);
                    $("#branchCode").val('');
                    $("#branch").val('');
                    $("#branch").attr('autofocus', true);
                }
            })
            // on select branch
            $('#branch').on('select2:select', function() {
                $( "#branchCode" ).val($(this).find('option:selected').val());
                TableListDK();
            });

            getExpedition();
        });

        function getExpedition() {
            $.ajax({
                url: "{{url('/marketing/marketingarea/get-expedition')}}",
                type: "get",
                success:function(resp) {
                    $('#expedition').empty();
                    $('#expedition').append('<option value="" selected disabled>== Pilih Jasa Ekspedisi ==</option>');
                    $.each(resp.data, function(key, val){
                        $('#expedition').append('<option value="'+val.e_id+'">'+val.e_name+'</option>');
                    });
                }
            });
        }

        $('#expedition').on('change', function(){
            let id = $('#expedition').val();
            $.ajax({
                url: "{{url('/marketing/marketingarea/get-expeditionType')}}"+"/"+id,
                type: "get",
                success:function(resp) {
                    $('#jenis_exp').empty();
                    $('#jenis_exp').append('<option value="" selected disabled>== Pilih Jenis Ekspedisi ==</option>');
                    $.each(resp.data, function(key, val){
                        $('#jenis_exp').append('<option value="'+val.ed_detailid+'">'+val.ed_product+'</option>');
                    });
                    $('#jenis_exp').select2('open');
                }
            });
        });        // retrieve list
        function TableListDK() {
            // let start = $('#date_from_dk').val();
            // let end = $('#date_to_dk').val();

            $('#table_konsinyasi').DataTable().clear().destroy();
            table_konsinyasi = $('#table_konsinyasi').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datakonsinyasi.getListDK') }}",
                    type: "get",
                    data: {
                        branchCode: $('#branchCode').val()
                    }
                },
                columns: [
                    {data: 'date'},
                    {data: 'sc_nota'},
                    {data: 'agent'},
                    {data: 'total'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function getProv() {
            loadingShow();
            $(".provIdxDK").find('option').remove();
            $(".provIdxDK").attr("disabled", true);
            axios.get('{{ route('konsinyasipusat.getProv') }}')
            .then(function (resp) {
                $(".provIdxDK").attr("disabled", false);
                var option = '<option value="">Pilih Provinsi</option>';
                var prov = resp.data;
                prov.forEach(function (data) {
                    option += '<option value="'+data.wp_id+'">'+data.wp_name+'</option>';
                })
                $(".provIdxDK").append(option);
                loadingHide();
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error)
            })
        }
        function getKota() {
            $(".provIdxDK").on("change", function (evt) {
                evt.preventDefault();
                $("#branchCode").val('');
                $("#branch").val('');
                $('#branch').find('option').remove();
                $("#branch").attr("disabled", true);
                $(".cityIdxDK").find('option').remove();
                $(".cityIdxDK").attr("disabled", true);
                if ($(".provIdxDK").val() != "") {
                    loadingShow();
                    axios.get(baseUrl+'/marketing/konsinyasipusat/get-kota/'+$(".provIdxDK").val())
                    .then(function (resp) {
                        $(".cityIdxDK").attr("disabled", false);
                        var option = '<option value="">Pilih Kota</option>';
                        var kota = resp.data;
                        kota.forEach(function (data) {
                            option += '<option value="'+data.wc_id+'">'+data.wc_name+'</option>';
                        })
                        $(".cityIdxDK").append(option);
                        loadingHide();
                        $(".cityIdxDK").focus();
                        $(".cityIdxDK").select2('open');
                    })
                    .catch(function (error) {
                        loadingHide();
                        messageWarning("Error", error)
                    })
                }
            })
        }
        // get list of branc based on prov and city
        function getBranch() {
            if ($(".cityIdxDK").val() != '') {
                loadingShow();
                $.ajax({
                    url: "{{ route('datakonsinyasi.getBranchDK') }}",
                    data: {
                        prov: $("#provinsi").val(),
                        city: $("#kota").val()
                    },
                    type: 'get',
                    success: function( data ) {
                        // console.log(data);
                        $('#branch').find('option').remove();
                        $('#branch').append('<option value="" selected>Pilih Cabang</option>')
                        $.each(data, function(index, val) {
                            // console.log(val, val.a_id);
                            $('#branch').append('<option value="'+ val.c_id +'">'+ val.c_name +'</option>');
                        });
                        loadingHide();
                        $('#branch').focus();
                        $('#branch').select2('open');
                    },
                    error: function(e) {
                        loadingHide();
                        // console.log('get konsigner error: ');
                    }
                });
            }
        }

        // re-direct to edit page
        function editDK(id) {
            window.location = baseUrl +'/marketing/marketingarea/datakonsinyasi/edit/'+ id;
        }
        // delete record
        function deleteDK(id) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin menghapus data ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            loadingShow();
                            axios.post("{{ route('datakonsinyasi.deleteDK') }}", {
                                id: id
                            })
                            .then(function (response) {
                                if(response.data.status == 'Success'){
                                    loadingHide();
                                    messageSuccess("Berhasil", response.data.message);
                                    table_konsinyasi.ajax.reload();
                                }
                                else {
                                    loadingHide();
                                    messageFailed("Gagal", response.data.message);
                                }
                            })
                            .catch(function (error) {
                                loadingHide();
                                messageWarning("Error", error);
                            });
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

    </script>
    <!-- ========================================================================-->

    <!-- ========================================================================-->
    <!-- script for Kelola-Data-Order etc -->
    <script type="text/javascript">
        var table_agen, table_search, table_bar, table_rab, table_bro;

        var idAgen = [];
        var namaAgen = null;
        var kode = null;
        var icode = [];
        var idxProdCode = null;
        $(document).ready(function () {
            orderProdukList();
            table_search = $('#table_search_agen').DataTable();
            table_bar = $('#table_monitoringpenjualanagen').DataTable();
            table_rab = $('#table_canvassing').DataTable();
            table_bro = $('#table_konsinyasi').DataTable();

            // $(document).on('click', '.btn-disable', function () {
            //     var ini = $(this);
            //     $.confirm({
            //         animation: 'RotateY',
            //         closeAnimation: 'scale',
            //         animationBounce: 1.5,
            //         icon: 'fa fa-exclamation-triangle',
            //         title: 'Peringatan!',
            //         content: 'Apa anda yakin mau menonaktifkan data ini?',
            //         theme: 'disable',
            //         buttons: {
            //             info: {
            //                 btnClass: 'btn-blue',
            //                 text: 'Ya',
            //                 action: function () {
            //                     $.toast({
            //                         heading: 'Information',
            //                         text: 'Data Berhasil di Nonaktifkan.',
            //                         bgColor: '#0984e3',
            //                         textColor: 'white',
            //                         loaderBg: '#fdcb6e',
            //                         icon: 'info'
            //                     })
            //                     ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
            //                 }
            //             },
            //             cancel: {
            //                 text: 'Tidak',
            //                 action: function () {
            //                     // tutup confirm
            //                 }
            //             }
            //         }
            //     });
            // });
            //
            // $(document).on('click', '.btn-enable', function () {
            //     $.toast({
            //         heading: 'Information',
            //         text: 'Data Berhasil di Aktifkan.',
            //         bgColor: '#0984e3',
            //         textColor: 'white',
            //         loaderBg: '#fdcb6e',
            //         icon: 'info'
            //     })
            //     $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
            //         '<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
            // })
            //
            // $(document).ready(function () {
            //     $('#detail-monitoring').DataTable({
            //         "iDisplayLength": 5
            //     });
            // });

            // Konsinyasi
            $(document).on('click', '.btn-disable-kons', function () {
                var ini = $(this);
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Peringatan!',
                    content: 'Apa anda yakin mau menonaktifkan data ini?',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Ya',
                            action: function () {
                                $.toast({
                                    heading: 'Information',
                                    text: 'Data Berhasil di Nonaktifkan.',
                                    bgColor: '#0984e3',
                                    textColor: 'white',
                                    loaderBg: '#fdcb6e',
                                    icon: 'info'
                                })
                                ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-kons" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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
            });

            $(document).on('click', '.btn-enable-kons', function () {
                $.toast({
                    heading: 'Information',
                    text: 'Data Berhasil di Aktifkan.',
                    bgColor: '#0984e3',
                    textColor: 'white',
                    loaderBg: '#fdcb6e',
                    icon: 'info'
                });
                $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-kons" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                    '<button class="btn btn-danger btn-disable-kons" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
            });
            // End Code Dummy -----------------------------------------------

            $('.agen').on('click change', function () {
                setArrayAgen();
            });

            $(".agen").on("keyup", function () {
                $(".agenId").val('');
                $(".codeAgen").val('');
            });

            $('#btn-addprodcode').on('click', function () {
                addCodetoTable();
            });
        });
        // End Document Ready -------------------------------------------

        // Order Produk Ke Cabang -------------------------------
        function orderProdukList() {
            tb_order = $('#table_orderproduk').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('orderProduk.list') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'po_date'},
                    {data: 'po_nota'},
                    {data: 'comp'},
                    {data: 'agen'},
                    // {data: 'totalprice'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function detailOrder(id) {
            $.ajax({
                url: "{{ url('/marketing/marketingarea/orderproduk/detail') }}" + "/" + id,
                type: "get",
                beforeSend: function () {
                    loadingShow();
                },
                success: function (res) {
                    loadingHide();
                    $('#modalOrderCabang').modal('show');
                    $('#cabang').val(res.data2.comp);
                    $('#agen').val(res.data2.agen);
                    $('#nota').val(res.data2.po_nota);
                    $('#tanggal').val(res.data2.po_date);
                    $('.empty').empty();
                    $.each(res.data1, function (key, val) {
                        $('#detailOrder tbody').append('<tr>' +
                            '<td>' + val.barang + '</td>' +
                            '<td>' + val.unit + '</td>' +
                            '<td class="text-right">' + val.qty + '</td>' +
                            // '<td>' + val.price + '</td>' +
                            // '<td>' + val.totalprice + '</td>' +
                            '</tr>');
                    });
                }
            });
        }

        function editOrder(id) {
            window.location.href = '{{ url('/marketing/marketingarea/orderproduk/edit') }}' + "/" + id;
        }

        function printNota(id, dt) {
            var url = '{{ url('/marketing/marketingarea/orderproduk/nota') }}' + "/" + id + "/" + dt;
            window.open(url);
        }

        function deleteOrder(id, dt) {
            var hapus_order = "{{url('/marketing/marketingarea/orderproduk/delete-order')}}" + "/" + id + "/" + dt;
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin menghapus data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "get",
                                url: hapus_order,
                                beforeSend: function () {
                                    loadingShow();
                                },
                                success: function (response) {
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                        tb_order.ajax.reload();
                                    } else if (response.status == 'warning') {
                                        loadingHide();
                                        messageWarning('Peringatan', 'Data ini tidak boleh dihapus!');
                                        tb_order.ajax.reload();
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
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function (response) {
                            loadingHide();
                            messageWarning('Peringatan', 'Anda telah membatalkannya!');
                        }
                    }
                }
            });
        }

        // End Order Produk --------------------------------------------

        // Kelola Data Order Agen --------------------------------------
        function kelolaDataAgen() {
            var st = $("#status").val();
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var status = $('#status').val();
            var agen = $('.agenId').val();
            $('#table_dataAgen').DataTable().clear().destroy();
            table_agen = $('#table_dataAgen').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/marketing/marketingarea/keloladataorder/filter-agen') }}",
                    type: "get",
                    data: {
                        start_date: start,
                        end_date: end,
                        state: status,
                        agen: agen
                    },
                },
                columns: [
                    {data: 'date'},
                    {data: 'po_nota'},
                    {data: 'cabang'},
                    {data: 'c_name'},
                    {data: 'total_price'},
                    {data: 'action_agen'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function getProvId() {
            var id = document.getElementById("prov_agen").value;
            $.ajax({
                url: "{{route('orderProduk.getCity')}}",
                type: "get",
                data: {
                    provId: id
                },
                beforeSend: function () {
                    loadingShow();
                },
                success: function (response) {
                    loadingHide();
                    $('#city_agen').empty();
                    $("#city_agen").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
                    $.each(response.data, function (key, val) {
                        $("#city_agen").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#city_agen').focus();
                    $('#city_agen').select2('open');
                }
            });
        }

        // Autocomplete Data Agen -------------------------------------------
        function setArrayAgen() {
            var inputs = document.getElementsByClassName('codeAgen'),
                code = [].map.call(inputs, function (input) {
                    return input.value.toString();
                });

            for (var i = 0; i < code.length; i++) {
                if (code[i] != "") {
                    icode.push(code[i]);
                }
            }

            var agen = [];
            var inpAgenId = document.getElementsByClassName('agenId'),
                agen = [].map.call(inpAgenId, function (input) {
                    return input.value;
                });

            $(".agen").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ url('/marketing/marketingarea/keloladataorder/cari-agen') }}",
                        data: {
                            idAgen: agen,
                            term: $(".agen").val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    setAgen(data.item);
                }
            });
        }

        function setAgen(info) {
            idAgen = info.data.c_id;
            namaAgen = info.data.a_name;
            kode = info.data.a_code;
            $(".codeAgen").val(kode);
            $(".agenId").val(idAgen);
            setArrayAgen();
        }

        // End Autocomplete -----------------------------------------------------

        // Modal Kelola Data Order Agen -----------------------------------------
        function getAgen() {
            loadingShow();
            getDataAgen();
        }

        function getDataAgen() {
            loadingHide();
            $(".table-modal").removeClass('d-none');
            $('#table_search_agen').DataTable().clear().destroy();
            table_agen = $('#table_search_agen').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/marketing/marketingarea/keloladataorder/get-agen') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: $('#city_agen').val()
                    }
                },
                columns: [
                    {data: 'wp_name'},
                    {data: 'wc_name'},
                    {data: 'a_name'},
                    {data: 'a_type'},
                    {data: 'action_agen'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function chooseAgen(id, name, code) {
            $('#searchAgen').modal('hide');
            loadingShow();
            $('.agenId').val(id);
            loadingHide();
            $('.agen').val(name);
            $('.codeAgen').val(code);
        }

        function filterAgen() {
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var status = $('#status').val();
            var agen = $('.agenId').val();

            $('#table_dataAgen').DataTable().clear().destroy();
            table_agen = $('#table_dataAgen').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/marketing/marketingarea/keloladataorder/filter-agen') }}",
                    type: "get",
                    data: {
                        start_date: start,
                        end_date: end,
                        state: status,
                        agen: agen
                    },
                },
                columns: [
                    {data: 'date'},
                    {data: 'po_nota'},
                    {data: 'cabang'},
                    {data: 'c_name'},
                    {data: 'total_price'},
                    {data: 'action_agen'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function detailAgen(id) {
            $.ajax({
                url: "{{ url('/marketing/marketingarea/keloladataorder/detail-agen') }}" + "/" + id,
                type: "get",
                beforeSend: function () {
                    loadingShow();
                },
                success: function (res) {
                    loadingHide();
                    $('#modalOrderAgen').modal('show');
                    $('#cabang2').val(res.agen2.comp);
                    $('#agen2').val(res.agen2.agen);
                    $('#nota2').val(res.agen2.po_nota);
                    $('#tanggal2').val(res.agen2.po_date);
                    $('.emptyAgen').empty();
                    $.each(res.agen1, function (key, val) {
                        $('#detailAgen tbody').append('<tr>' +
                            '<td>' + val.barang + '</td>' +
                            '<td>' + val.unit + '</td>' +
                            '<td>' + val.qty + '</td>' +
                            '<td>' + val.price + '</td>' +
                            '<td>' + val.totalprice + '</td>' +
                            '</tr>');
                    });
                }
            });
        }

        function rejectAgen(id) {
            var reject_agen = "{{url('/marketing/marketingarea/keloladataorder/reject-agen')}}" + "/" + id;
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin menolak agen ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "post",
                                url: reject_agen,
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                beforeSend: function () {
                                    loadingShow();
                                },
                                success: function (response) {
                                    //var table_agen = $('#table_dataAgen').DataTable();
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Penolakan berhasil!');
                                        table_agen.ajax.reload();
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
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function (response) {
                            loadingHide();
                            messageWarning('Peringatan', 'Anda telah membatalkan!');
                        }
                    }
                }
            });
        }

        function activateAgen(id) {
            var aktif_agen = "{{url('/marketing/marketingarea/keloladataorder/activate-agen')}}" + "/" + id;
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin mengaktifkan agen ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "post",
                                url: aktif_agen,
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                beforeSend: function () {
                                    loadingShow();
                                },
                                success: function (response) {
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Agen berhasil diaktifkan!');
                                        table_agen.ajax.reload();
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
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function (response) {
                            loadingHide();
                            messageWarning('Peringatan', 'Anda telah membatalkan!');
                        }
                    }
                }
            });
        }

        var tb_listprosesorder;
        var tb_listcodeprosesorder;

        function approveAgen(id) {
            $('#prosesorder').modal('show');
            setTimeout(function(){
                $('#expedition').select2('open');
            }, 500)
            axios.get('{{ route("keloladataorder.getdetailorderagen") }}', {
                params:{
                    id: id
                }
            }).then(function (response) {
                let agen = response.data.data.c_name;
                let nota = response.data.data.po_nota;
                let tanggal = response.data.data.po_date;
                $('#idProductOrder').val(id);
                $('#nota_modaldt').val(nota);
                $('#agen_modaldt').val(agen);
                $('#tanggal_modaldt').val(tanggal);
                $('#idagen_modaldt').val(response.data.data.po_agen);
                $('#total_modaldt').val(convertToRupiah(response.data.data.pod_totalprice));
            }).catch(function (error) {

            });

            $('#table_prosesorder').dataTable().fnDestroy();
            tb_listprosesorder = $('#table_prosesorder').DataTable({
                responsive: true,
                serverSide: true,
                paging: false,
                searching: false,
                ajax: {
                    url: "{{ route('keloladataorder.getdetailorder') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    }
                },
                columns: [
                    {data: 'i_name'},
                    {data: 'input', "className": "input-padding", },
                    {data: 'u_name'},
                    {data: 'pod_price'},
                    {data: 'pod_totalprice'},
                    {data: 'kode'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function pressCode(e) {
            if (e.keyCode == 13){
                addCodetoTable();
            }
        }

        // get price items and get stock
        function getHargaGolongan(item) {
            let agen = $('#idagen_modaldt').val();
            let qty = $('.qty-modaldt-'+item).val();
            let id = $('#idProductOrder').val();
            axios.get('{{ route("orderProduk.getPrice") }}', {
                params:{
                    'agen': agen,
                    'qty': qty,
                    'item': item,
                    'id': id
                }
            }).then(function (response) {
                let harga = parseInt(response.data.price);
                let stock = parseInt(response.data.stock);
                $('.input-modaldtharga'+item).val(harga);
                $('.modaldtharga-'+item).html(convertToRupiah(harga));
                // set stock restriction
                if (parseInt(qty) > stock) {
                    messageWarning('Perhatian', 'Permintaan tidak boleh melebihi batas stok, stok tersedia : '+ stock);
                    $('.qty-modaldt-'+item).val(stock);
                }
                else if (parseInt(qty) < 0) {
                    messageWarning('Perhatian', 'Permintaan tidak boleh kurang dari 0');
                    $('.qty-modaldt-'+item).val(0);
                }
                updateSubtotal(item);
            }).catch(function (error) {

            })
        }

        function updateSubtotal(item){
            let qty = $('.qty-modaldt-'+item).val();
            let harga = $('.input-modaldtharga'+item).val();
            if (isNaN(qty)){
                qty = 0;
            }
            let total = parseInt(qty) * parseInt(harga);
            $('.modaldtsubharga-'+item).html(convertToRupiah(total));
            $('.input-modaldtsubharga'+item).val(total);
            let totalprice = 0;
            $('input[name^="subtotalmodaldt"]').each(function() {
                totalprice = totalprice + parseInt($(this).val());
            });
            $('#total_modaldt').val(convertToRupiah(totalprice));
        }

        function addCodeProd(id, item, nama){
            idxProdCode = $('.btnAddProdCode').index(this);
            console.log(idxProdCode);
            $('.text-item').html(nama);
            $('#inputkodeproduksi').removeAttr('readonly');
            $('#iditem_modaldt').val(item);
            $('#inputqtyproduksi').removeAttr('readonly');
            $('#table_prosesordercode').dataTable().fnDestroy();
            tb_listcodeprosesorder = $('#table_prosesordercode').DataTable({
                responsive: true,
                serverSide: true,
                paging: false,
                searching: false,
                ordering: false,
                ajax: {
                    url: "{{ route('keloladataorder.getdetailcodeorder') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "item": item
                    }
                },
                columns: [
                    {data: 'poc_code'},
                    {data: 'poc_qty'},
                    {data: 'aksi'},
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function addCodetoTable(){
            let qty = $('#inputqtyproduksi').val();
            let kode = $.trim($('#inputkodeproduksi').val());
            let nota = $('#nota_modaldt').val();
            let item = $('#iditem_modaldt').val();

            if (isNaN(qty) || qty == '' || qty == null){
                qty = 1;
            }
            if (kode == '' || kode == null) {
                messageWarning('Perhatian', 'Silahkan masukkan kode produksi terlebih dahulu !');
                return 0;
            }

            axios.get('{{ route("keloladataorder.setKode") }}', {
                params:{
                    "qty": qty,
                    "kode": kode,
                    "nota": nota,
                    "item": item
                }
            }).then(function (response) {
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Kode berhasil ditambahkan");
                    $('#inputkodeproduksi').val("");
                    $('#inputqtyproduksi').val("");
                    tb_listcodeprosesorder.ajax.reload();
                } else if (response.data.status == 'gagal'){
                    messageWarning("Gagal", response.data.message);
                }
            }).catch(function (error) {
                alert('error');
            })
        }

        function removeCodeOrder(id, item, kode) {
            axios.get('{{ route("keloladataorder.removeKode") }}', {
                params:{
                    "id": id,
                    "item": item,
                    "kode": kode
                }
            }).then(function (response) {
                if (response.data.status == 'success'){
                    messageSuccess("Berhasil", "Kode berhasil dihapus");
                    tb_listcodeprosesorder.ajax.reload();
                } else {
                    messageWarning("Gagal", "Kode gagal dihapus");
                }
            }).catch(function (error) {

            })
        }

        function rejectApproveAgen(id) {
            var reject_approve_agen = "{{url('/marketing/marketingarea/keloladataorder/reject-approve-agen')}}" + "/" + id;
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin membatalkan approve agen ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "post",
                                url: reject_approve_agen,
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                beforeSend: function () {
                                    loadingShow();
                                },
                                success: function (response) {
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Approve berhasil dibatalkan!');
                                        table_agen.ajax.reload();
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
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function (response) {
                            loadingHide();
                            // messageWarning('Peringatan', 'Anda telah membatalkan!');
                        }
                    }
                }
            });
        }

        function approveAndSendItems() {
            idProductOrder  = $('#idProductOrder').val();

            let listQty        = $('.input-qty-proses').serialize();
            let listItemsId    = $('.itemsId').serialize();
            let listUnits      = $('.units').serialize();
            let pd_nota        = $('#nota_modaldt').val();
            let pd_expedition  = $('#expedition').val();
            let pd_product     = $('#jenis_exp').val();
            let pd_resi        = $('#no_resi').val();
            let pd_couriername = $('#kurir_name').val();
            let pd_couriertelp = $('#no_hpkurir').val();
            let pd_price       = $('#biaya_kurir').val();

            let dataX = listQty +'&'+ listItemsId +'&'+ listUnits +'&'+ pd_nota +'&'+ pd_expedition +'&'+ pd_product +'&'+ pd_resi +'&'+ pd_couriername +'&'+ pd_couriertelp +'&'+ pd_price ;
            loadingShow();

            $.ajax({
                url: baseUrl + '/marketing/marketingarea/keloladataorder/approve-agen/'+ idProductOrder,
                data: dataX,
                type: 'post',
                success: function(resp) {
                    loadingHide();
                    if (resp.status == 'sukses') {
                        // close modal
                        $('#prosesorder').modal('hide');
                        messageSuccess('Berhasil', 'Data Order berhasil di \'Approve\'');
                        table_agen.ajax.reload();
                    }
                    else {
                        messageWarning('Gagal', resp.message);
                    }
                },
                error: function(e) {
                    loadingHide();
                    messageWarning('Gagal', e.message);
                }
            })
        }

        function receiveItemOrder(id) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin mengkonfirmasi penerimaan order ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "post",
                                url: baseUrl +'/marketing/marketingarea/keloladataorder/receive-item-order/'+ id,
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                beforeSend: function () {
                                    loadingShow();
                                },
                                success: function (response) {
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Konfirmasi penerimaan order berhasil dilakukan !');
                                        table_agen.ajax.reload();
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
                        }
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function (response) {
                            loadingHide();
                        }
                    }
                }
            });
        }

        // End Data Order Agen -----------------------------------------
    </script>
    <!-- ========================================================================-->
    <script type="text/javascript">

        $(document).ready(function () {
            $(document).on('click', '.btn-accept', function () {
                var ini = $(this);
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Peringatan!',
                    content: 'Apa anda yakin?',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Ya',
                            action: function () {
                                $.toast({
                                    heading: 'Information',
                                    text: 'Data Berhasil di Setujui.',
                                    bgColor: '#0984e3',
                                    textColor: 'white',
                                    loaderBg: '#fdcb6e',
                                    icon: 'info'
                                })
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
            });

            $(document).on('click', '.btn-reject', function () {
                var ini = $(this);
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Peringatan!',
                    content: 'Apa anda yakin?',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Ya',
                            action: function () {
                                $.toast({
                                    heading: 'Information',
                                    text: 'Data Berhasil di Tolak.',
                                    bgColor: '#0984e3',
                                    textColor: 'white',
                                    loaderBg: '#fdcb6e',
                                    icon: 'info'
                                })
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
            });
        });

    </script>

    <!-- ========================================================================-->
    <!-- script for Data-Monitoring-Penjualan-Agen -->
    <script type="text/javascript">
        $(document).ready(function () {
            const cur_date = new Date();
            const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
            const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
            $('#date_from_mpa').datepicker('setDate', first_day);
            $('#date_to_mpa').datepicker('setDate', last_day);

            $('#date_from_mpa').on('change', function () {
                TableListMPA();
            });
            $('#date_to_mpa').on('change', function () {
                TableListMPA();
            });
            $('#btn_search_date_mpa').on('click', function () {
                TableListMPA();
            });
            $('#btn_refresh_date_mpa').on('click', function () {
                $('#filter_agent_code_mpa').val('');
                $('#filter_agent_name_mpa').val('');
                $('#date_from_mpa').datepicker('setDate', first_day);
                $('#date_to_mpa').datepicker('setDate', last_day);
            });
            // retrieve data-table
            TableListMPA();
            // filter agent based on area (province and city)
            $('.provMPA').on('change', function () {
                getCitiesMPA();
            });
            $('.citiesMPA').on('change', function () {
                $(".table-modal").removeClass('d-none');
                appendListAgentsMPA();
            });
            // filter agent field
            $('#filter_agent_name_mpa').on('click', function () {
                $(this).val('');
            });
            $('#filter_agent_name_mpa').on('keyup', function () {
                findAgentsByAuMPA();
            });
            // btn applyt filter agent
            $('#btn_filter_mpa').on('click', function () {
                TableListMPA();
            });
        });

        // data-table -> function to retrieve DataTable server side
        var tb_listmpa;

        function TableListMPA() {
            $('#table_monitoringpenjualanagen').dataTable().fnDestroy();
            tb_listmpa = $('#table_monitoringpenjualanagen').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('manajemenpenjualanagen.getListMPA') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "date_from": $('#date_from_mpa').val(),
                        "date_to": $('#date_to_mpa').val(),
                        "agent_code": $('#filter_agent_code_mpa').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'name'},
                    {data: 'date'},
                    {data: 's_nota'},
                    {data: 'total'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // autocomple to find-agents
        function findAgentsByAuMPA() {
            $('#filter_agent_name_mpa').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: baseUrl + '/marketing/marketingarea/datacanvassing/find-agents-by-au',
                        data: {
                            "termToFind": $("#filter_agent_name_mpa").val()
                        },
                        dataType: 'json',
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $('#filter_agent_code_mpa').val(data.item.agent_code);
                }
            });
        }

        // this following func is using same source with Data-Canvassing
        // get cities for search-agent
        function getCitiesMPA() {
            var provId = $('.provMPA').val();
            setTimeout(function(){
                $.ajax({
                    url: "{{ route('datacanvassing.getCitiesDC') }}",
                    type: "get",
                    data: {
                        provId: provId
                    },
                    success: function (response) {
                        $('.citiesMPA').empty();
                        $(".citiesMPA").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
                        $.each(response.get_cities, function (key, val) {
                            $(".citiesMPA").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                        });
                        $('.citiesMPA').focus();
                        $('.citiesMPA').select2('open');
                    }
                });
            }, 1000);
        }

        // this following func is using same source with Data-Canvassing
        // append data to table-list-agens
        function appendListAgentsMPA() {
            $.ajax({
                url: "{{ route('datacanvassing.getAgentsDC') }}",
                type: 'get',
                data: {
                    cityId: $('.citiesMPA').val()
                },
                success: function (response) {
                    $('#table_search_mpa tbody').empty();
                    if (response.length <= 0) {
                        return 0;
                    }
                    $.each(response, function (index, val) {
                        listAgents = '<tr><td>' + val.get_province.wp_name + '</td>';
                        listAgents += '<td>' + val.get_city.wc_name + '</td>';
                        listAgents += '<td>' + val.a_name + '</td>';
                        listAgents += '<td>' + val.a_type + '</td>';
                        listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgentMPA(\'' + val.a_code + '\',\'' + val.a_name + '\')"><i class="fa fa-download"></i></button></td></tr>';
                    });
                    $('#table_search_mpa > tbody:last-child').append(listAgents);
                }
            });
        }

        // add filter-agent
        function addFilterAgentMPA(agentCode, agentName) {
            $('#filter_agent_name_mpa').val(agentCode + ' - ' + agentName);
            $('#filter_agent_code_mpa').val(agentCode);
            $('#modalSearchAgentMPA').modal('hide');
        }

        // show modal-detail MPA
        function detailMPA(id) {
            loadingShow();
            $.ajax({
                url: baseUrl + '/marketing/marketingarea/manajemenpenjualanagen/get-detail/' + id,
                type: 'get',
                success: function (response) {
                    $('#nota_dtmpa').val(response.detail.s_nota);
                    let newDate = getFormattedDate(response.detail.s_date);
                    $('#date_dtmpa').val(newDate);
                    if (response.detail.get_user.employee !== null) {
                        $('#agent_dtmpa').val(response.detail.get_user.employee.e_name);
                    } else if (response.detail.get_user.agen !== null) {
                        $('#agent_dtmpa').val(response.detail.get_user.agen.a_name);
                    } else {
                        $('#agent_dtmpa').val('( Agen tidak ditemukan ! )');
                    }
                    $('#total_dtmpa').val(parseInt(response.detail.s_total));

                    // append sales-dt to table list-items
                    $('#table_detailmpa tbody').empty();
                    $.each(response.detail.get_sales_dt, function (index, val) {
                        let idx = '<td>' + (index + 1) + '</td>';
                        let itemName = '<td>' + val.get_item.i_name + '</td>';
                        let itemQty = '<td class="digits">' + response.listQty[index] + '</td>';
                        let itemUnit = '<td>' + val.get_item.get_unit1.u_name + '</td>';
                        let itemPrice = '<td class="rupiah">' + parseInt(val.sd_value) + '</td>';
                        let itemSubTotal = '<td class="rupiah">' + parseInt(val.sd_totalnet) + '</td>';
                        $('#table_detailmpa > tbody:last-child').append('<tr>' + idx + itemName + itemQty + itemUnit + itemPrice + itemSubTotal + '</tr>');
                    });
                    // re-activate mask-money
                    $('.rupiah').inputmask("currency", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        prefix: ' Rp ', //Space after $, this will not truncate the first character.
                        rightAlign: true,
                        autoUnmask: true,
                        nullable: false,
                        // unmaskAsNumber: true,
                    });
                    // re-activate mask-digits
                    $('.digits').inputmask("currency", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 0,
                        autoGroup: true,
                        prefix: '', //Space after $, this will not truncate the first character.
                        rightAlign: true,
                        autoUnmask: true,
                        nullable: false,
                        // unmaskAsNumber: true,
                    });

                    // show detal modal
                    loadingHide();
                    $('#modalDetailMPA').modal('show');
                },
                error: function (e) {
                    messageWarning('Perhatian', 'Terjadi kesalahan, hubungi pengembang !');
                }
            });
        }

        // change date formate
        function getFormattedDate(str) {
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            var myDate = new Date(str);
            var month = myDate.getMonth();
            var day = myDate.getDate();
            var year = myDate.getFullYear();
            return day + " " + monthNames[month] + " " + year;
        }
    </script>

    <!-- ========================================================================-->
    <!-- script for public function -->
    <script type="text/javascript">
        $(document).ready(function () {
            if ($('.current_user_type').val() !== 'E') {
                $('.filter_agent').addClass('d-none');
            } else {
                $('.filter_agent').removeClass('d-none');
            }
        });
    </script>

    <!-- ========================================================================-->
    <!-- script for Data-Canvassing -->
    <script type="text/javascript">
        $(document).ready(function () {
            const cur_date = new Date();
            const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
            const last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
            $('#date_from_dc').datepicker('setDate', first_day);
            $('#date_to_dc').datepicker('setDate', last_day);

            $('#date_from_dc').on('change', function () {
                TableListDC();
            });
            $('#date_to_dc').on('change', function () {
                TableListDC();
            });
            $('#btn_search_date_dc').on('click', function () {
                TableListDC();
            });
            $('#btn_refresh_date_dc').on('click', function () {
                $('#filter_agent_code_dc').val('');
                $('#filter_agent_name_dc').val('');
                $('#date_from_dc').datepicker('setDate', first_day);
                $('#date_to_dc').datepicker('setDate', last_day);
            });
            // retrieve data-table
            TableListDC();
            // filter agent based on area (province and city)
            $('.provDC').on('change', function () {
                getCitiesDC();
            });
            $('.citiesDC').on('change', function () {
                $(".table-modal").removeClass('d-none');
                appendListAgentsDC();
            });
            // filter agent field
            $('#filter_agent_name_dc').on('click', function () {
                $(this).val('');
            });
            $('#filter_agent_name_dc').on('keyup', function () {
                findAgentsByAu();
            });
            // btn applyt filter agent
            $('#btn_filter_dc').on('click', function () {
                TableListDC();
            });
            // modal add-canvassing
            $('#modalAddCanvassing').on('shown.bs.modal', function () {
                $('#btn_simpan_addcanvassing').one('click', function () {
                    submitAddCanvassing();
                });
            });
            $('#modalAddCanvassing').on('hidden.bs.modal', function () {
                resetAddCanvassing();
            });
        });

        // data-table -> function to retrieve DataTable server side
        var tb_liscanvas;

        function TableListDC() {
            $('#table_canvassing').dataTable().fnDestroy();
            tb_liscanvas = $('#table_canvassing').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('datacanvassing.getListDC') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "date_from": $('#date_from_dc').val(),
                        "date_to": $('#date_to_dc').val(),
                        "agent_code": $('#filter_agent_code_dc').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'c_name'},
                    {data: 'c_email'},
                    {data: 'c_tlp'},
                    {data: 'c_address'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // submit-form add-new-canvassing
        function submitAddCanvassing() {
            myForm = $('#formAddCanvassing').serialize();
            loadingShow();
            $.ajax({
                data: myForm,
                type: "post",
                url: baseUrl + '/marketing/marketingarea/datacanvassing/store',
                dataType: 'json',
                success: function (response) {
                    loadingHide();
                    if (response.status == 'berhasil') {
                        messageSuccess('Berhasil', 'Data Canvassing berhasil ditambahkan !');
                        resetAddCanvassing();
                        $('#modalAddCanvassing').modal('hide');
                        tb_liscanvas.ajax.reload();
                    } else if (response.status == 'invalid') {
                        messageFailed('Perhatian', response.message);
                    } else if (response.status == 'gagal') {
                        messageWarning('Error', response.message);
                    }
                    // activate btn_simpan once again
                    $('#btn_simpan_addcanvassing').one('click', function () {
                        submitAddCanvassing();
                    });
                },
                error: function (e) {
                    loadingHide();
                    messageWarning('Gagal', 'Data gagal ditambahkan, hubungi pengembang !');
                    // activate btn_simpan once again
                    $('#btn_simpan_addcanvassing').one('click', function () {
                        submitAddCanvassing();
                    });
                }
            });
        }

        // show modal edit
        function editDataCanvassing(id) {
            loadingShow();
            $.ajax({
                type: "get",
                url: baseUrl + '/marketing/marketingarea/datacanvassing/edit/' + id,
                dataType: 'json',
                success: function (response) {
                    loadingHide();
                    $('#name_editdc').val(response.c_name);
                    $('#email_editdc').val(response.c_email);
                    $('#telp_editdc').val(response.c_tlp);
                    $('#address_editdc').val(response.c_address);
                    $('#note_editdc').val(response.c_note);
                    $('#btn_simpan_editcanvassing').attr('onclick', 'submitEditCanvassing(' + id + ')')
                    $('#modalEditCanvassing').modal('show');
                },
                error: function (e) {
                    loadingHide();
                    messageWarning('Gagal', 'Gagal mendapatkan data, hubungi pengembang !');
                }
            })
        }

        // submit-form edit-canvassing
        function submitEditCanvassing(id) {
            myForm = $('#formEditCanvassing').serialize();
            loadingShow();
            $.ajax({
                data: myForm,
                type: "post",
                url: baseUrl + '/marketing/marketingarea/datacanvassing/update/' + id,
                dataType: 'json',
                success: function (response) {
                    loadingHide();
                    if (response.status == 'berhasil') {
                        messageSuccess('Berhasil', 'Data Canvassing berhasil diperbarui !');
                        tb_liscanvas.ajax.reload();
                    } else if (response.status == 'invalid') {
                        messageFailed('Perhatian', response.message);
                    } else if (response.status == 'gagal') {
                        messageWarning('Error', response.message);
                    }
                },
                error: function (e) {
                    loadingHide();
                    messageWarning('Gagal', 'Data gagal diperbarui, hubungi pengembang !');
                }
            });
        }

        // delete canvassing
        function deleteDataCanvassing(id) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 2.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apakah anda yakin ingin menghapus data ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            loadingShow();
                            return $.ajax({
                                type: "post",
                                url: baseUrl + '/marketing/marketingarea/datacanvassing/delete/' + id,
                                success: function (response) {
                                    loadingHide();
                                    if (response.status == 'berhasil') {
                                        messageSuccess('Berhasil', 'Data berhasil hapus !');
                                        tb_liscanvas.ajax.reload();
                                    } else {
                                        messageWarning('Gagal', 'Data gagal dihapus !');
                                    }
                                },
                                error: function (e) {
                                    loadingHide();
                                    messageFailed('Peringatan', e.message);
                                }
                            });
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

        // resel modat-add-canavassing
        function resetAddCanvassing() {
            $('#formAddCanvassing')[0].reset();
        }

        // autocomple to find-agents
        function findAgentsByAu() {
            $('#filter_agent_name_dc').autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: baseUrl + '/marketing/marketingarea/datacanvassing/find-agents-by-au',
                        data: {
                            "termToFind": $("#filter_agent_name_dc").val()
                        },
                        dataType: 'json',
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $('#filter_agent_code_dc').val(data.item.agent_code);
                }
            });
        }

        // get cities for search-agent
        function getCitiesDC() {
            loadingShow();
            var provId = $('.provDC').val();
            $.ajax({
                url: "{{ route('datacanvassing.getCitiesDC') }}",
                type: "get",
                data: {
                    provId: provId
                },
                success: function (response) {
                    loadingHide();
                    $('.citiesDC').empty();
                    $(".citiesDC").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
                    $.each(response.get_cities, function (key, val) {
                        $(".citiesDC").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('.citiesDC').focus();
                    $('.citiesDC').select2('open');
                }
            });
        }

        // append data to table-list-agens
        function appendListAgentsDC() {
            loadingShow();
            $.ajax({
                url: "{{ route('datacanvassing.getAgentsDC') }}",
                type: 'get',
                data: {
                    cityId: $('.citiesDC').val()
                },
                success: function (response) {
                    loadingHide();
                    $('#table_search_dc tbody').empty();
                    if (response.length <= 0) {
                        return 0;
                    }
                    $.each(response, function (index, val) {
                        listAgents = '<tr><td>' + val.get_province.wp_name + '</td>';
                        listAgents += '<td>' + val.get_city.wc_name + '</td>';
                        listAgents += '<td>' + val.a_name + '</td>';
                        listAgents += '<td>' + val.a_type + '</td>';
                        listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgent(\'' + val.a_code + '\',\'' + val.a_name + '\')"><i class="fa fa-download"></i></button></td></tr>';
                    });
                    $('#table_search_dc > tbody:last-child').append(listAgents);
                }
            });
        }

        // add filter-agent
        function addFilterAgent(agentCode, agentName) {
            $('#filter_agent_name_dc').val(agentCode + ' - ' + agentName);
            $('#filter_agent_code_dc').val(agentCode);
            $('#modalSearchAgentDC').modal('hide');
        }

    </script>
@endsection
