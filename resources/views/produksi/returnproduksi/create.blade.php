@extends('main')

@section('content')

    @include('produksi.returnproduksi.modal')

    @include('produksi.returnproduksi.modal-search')

    @include('produksi.returnproduksi.modal-detail')

@section('extra_style')
    <style type="text/css">
        .ui-autocomplete { z-index:2147483647; }
    </style>
@endsection


<article class="content animated fadeInLeft">

    <div class="title-block text-primary">
        <h1 class="title"> Tambah Return Produksi </h1>
        <p class="title-description">
            <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
            / <span>Aktifitas Produksi</span>
            / <a href="{{route('return.index')}}">Return Produksi</a>
            / <span class="text-primary font-weight-bold">Tambah Return Produksi</span>
        </p>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-12">

                <div class="card">
                    <div class="card-header bordered p-2">
                        <div class="header-block">
                            <h3 class="title"> Tambah Return Produksi </h3>
                        </div>
                        <div class="header-block pull-right">
                            <a class="btn btn-secondary btn-sm" href="{{route('return.index')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label>Pilih pencarian</label>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <select class="form-control select2" name="" id="searchBy">
                                        <option value="kodeproduksi" selected>Cari berdasarkan kode produksi</option>
                                        <option value="nota">Cari berdasarkan nota</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <!-- <div class="col-md-2 col-sm-6 col-12">
                                    <label>No. Nota</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <input type="hidden" name="q_idpo" id="q_idpo">
                                        <input type="text" name="q_nota" id="q_nota" class="form-control form-control-sm">

                                        <button class="btn btn-md btn-secondary" title="Pencarian No. Nota" id="btn_searchnota" style="border-left:none;"><i class="fa fa-search"></i></button>
                                    </div>
                                </div> -->
                                <div class="col-md-2 col-sm-6 col-12">
                                    <label id="searchLabel">Kode Produksi</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="input-group">
                                        <input type="hidden" name="q_idpo" id="q_idpo">
                                        <input type="hidden" name="q_nota" id="q_nota" class="form-control form-control-sm">
                                        <input type="text" name="q_prodcode" id="q_prodcode" class="form-control form-control-sm">

                                        <button class="btn btn-md btn-secondary d-none" title="Pencarian Kode Produksi" id="btn_searchsupplier" style="border-left:none;" disabled><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-md btn-primary" id="go">Lanjutkan</button>
                                </div>
                                <hr>
                            </div>
                            <br>
                            <div class="table-responsive table-returnp d-none">
                                <table class="table table-striped table-hover" cellspacing="0" id="table_rp">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Qty</th>
                                        <th>Harga @</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                    <!-- <div class="card-footer text-right">
                        <button class="btn btn-primary" type="button">Simpan</button>
                        <a href="{{route('return.index')}}" class="btn btn-secondary">Kembali</a>
                    </div> -->
                </div>

            </div>

        </div>

    </section>

</article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        var tbl_rp, tbl_gu, tbl_pn;
        $(document).ready(function(){

            tbl_rp = $('#table_rp').DataTable();
            tbl_gu = $('#tabel_gu').DataTable();
            tbl_pn = $('#tabel_pn').DataTable();

            $('#searchBy').on('change', function() {
                if ($(this).val() == 'nota')
                {
                    $('#q_nota').clone().attr('type', 'text').insertAfter('#q_nota').prev().remove();
                    $('#q_prodcode').clone().attr('type', 'hidden').insertAfter('#q_prodcode').prev().remove();
                    $('#btn_searchsupplier').attr('disabled', false);
                    $('#btn_searchsupplier').removeClass('d-none');
                    $('#searchLabel').text('No Nota');
                    $('#q_nota').val('');
                    $('#q_prodcode').val('');
                }
                else if ($(this).val() == 'kodeproduksi')
                {
                    $('#q_nota').clone().attr('type', 'hidden').insertAfter('#q_nota').prev().remove();
                    $('#q_prodcode').clone().attr('type', 'text').insertAfter('#q_prodcode').prev().remove();
                    $('#btn_searchsupplier').attr('disabled', true);
                    $('#btn_searchsupplier').addClass('d-none');
                    $('#searchLabel').text('Kode Produksi');
                    $('#q_nota').val('');
                    $('#q_prodcode').val('');
                }
                // re-initialize event (keyup or another)
                initEvents();
            });

            if ($("#q_idpo").val() == "") {
                $("#go").attr("disabled", true);
            } else {
                $("#go").attr("disabled", false);
            }

            // init event keyup for autocomplete
            initEvents();

            $("#go").on("click", function() {
                searchItems();
            });
            $("#supplier").on("keyup", function () {
                $("#idSupplier").val('');
            });
            $("#supplier").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: '{{ route('return.carisupplier') }}',
                        data: {
                            term: $("#supplier").val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $("#idSupplier").val(data.item.id);
                }
            });

            $("#btn_searchNotainTbl").on("click", function (evt) {
                evt.preventDefault();
                var dateStart = $("#dateStart").val(), dateEnd = $("#dateEnd").val(), supplier = $("#idSupplier").val();
                if (dateStart == "" && dateEnd == "" && supplier == "") {
                    messageWarning("Peringatan", "Masukkan parameter pencarian");
                    $("#dateStart").focus();
                } else {
                    loadingShow();
                    if ($.fn.DataTable.isDataTable("#tbl_nota")) {
                        $('#tbl_nota').DataTable().clear().destroy();
                    }
                    $('#tbl_nota').DataTable({
                        responsive: true,
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: "{{ route('return.getnota') }}",
                            data: {
                                dateStart: dateStart,
                                dateEnd: dateEnd,
                                supplier: supplier
                            },
                            type: "get"
                        },
                        columns: [
                            {data: 'supplier'},
                            {data: 'tanggal'},
                            {data: 'nota'},
                            {data: 'action'}
                        ],
                        drawCallback: function( settings ) {
                            loadingHide();
                        }
                    });
                }
            })
            $("#btn_searchsupplier").on("click", function (evt) {
                evt.preventDefault();
                console.log('abc');
                appendModalSearchSupplier();
            })

            $("#formCreateReturn").on("submit", function (evt) {
                evt.preventDefault();
                if ($("#qty_return").val() == "" || $("#qty_return").val() == 0) {
                    messageWarning("Peringatan", "Masukkan qty return");
                    $("#qty_return").focus();
                } else if ($("#satuan_return").val() == "") {
                    messageWarning("Peringatan", "Pilih satuan barang");
                    $("#satuan_return").focus();
                } else {
                    $.confirm({
                        animation: 'RotateY',
                        closeAnimation: 'scale',
                        animationBounce: 1.5,
                        icon: 'fa fa-exclamation-triangle',
                        title: 'Konfirmasi!',
                        content: 'Apakah anda yakin akan membuat return produksi untuk barang ini?',
                        theme: 'disable',
                        buttons: {
                            info: {
                                btnClass: 'btn-blue',
                                text: 'Ya',
                                action: function () {
                                    createReturn();
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

            $('#header-metodereturn').change(function(){
                var ini, potong_nota, ganti_uang, ganti_barang;
                ini             = $(this).val();
                potong_nota     = $('#potong_nota');
                ganti_uang     = $('#ganti_uang');
                ganti_barang     = $('#ganti_barang');
                if (ini === 'GB') {
                    potong_nota.addClass('d-none');
                    ganti_uang.addClass('d-none');
                    ganti_barang.removeClass('d-none');
                } else if(ini === 'GU'){
                    potong_nota.addClass('d-none');
                    ganti_uang.removeClass('d-none');
                    ganti_barang.addClass('d-none');
                } else if(ini === 'PN'){
                    potong_nota.removeClass('d-none');
                    ganti_uang.addClass('d-none');
                    ganti_barang.addClass('d-none');
                }
            });

            // reset modal-return when modal is hidden
            $('#createReturn').on('hidden.bs.modal', function() {
                $('#formCreateReturn')[0].reset();
            });
        });
        // init autocomplete for search by nota or prod-code
        function initEvents()
        {
            $("#q_prodcode").off();
            $("#q_prodcode").on("keyup", function () {
                $("#q_idpo").val('');
                $(".table-returnp").addClass('d-none');
                if ($("#q_idpo").val() == "") {
                    $("#go").attr("disabled", true);
                } else {
                    $("#go").attr("disabled", false);
                }
            });
            $("#q_prodcode").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ route('return.cariprodkode') }}",
                        data: {
                            term: $("#q_prodcode").val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $("#q_idpo").val(data.item.id);
                    $('#q_nota').val(data.item.nota);
                    $("#q_prodcode").val(data.item.prodCode);
                    $("#notaPO").val(data.item.nota);
                    $("#prodCode").val(data.item.prodCode);
                    searchItems();
                }
            });

            $("#q_nota").off();
            $("#q_nota").on("keyup", function () {
                $("#q_idpo").val('');
                $(".table-returnp").addClass('d-none');
                if ($("#q_idpo").val() == "") {
                    $("#go").attr("disabled", true);
                } else {
                    $("#go").attr("disabled", false);
                }
            });
            $("#q_nota").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ route('return.carinota') }}",
                        data: {
                            term: $("#q_nota").val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    $("#q_idpo").val(data.item.id);
                    $('#q_nota').val(data.item.nota);
                    $("#q_prodcode").val(data.item.prodCode);
                    $("#notaPO").val(data.item.nota);
                    $("#prodCode").val(data.item.prodCode);
                    searchItems();
                }
            });
        }

        function appendModalSearchSupplier()
        {
            loadingShow();
            if ($.fn.DataTable.isDataTable("#tbl_nota")) {
                $('#tbl_nota').DataTable().clear().destroy();
            }
            $('#tbl_nota').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('return.getnota') }}",
                    type: "get"
                },
                columns: [
                    {data: 'supplier'},
                    {data: 'tanggal'},
                    {data: 'nota'},
                    {data: 'action'}
                ],
                drawCallback: function( settings ) {
                    loadingHide();
                    $("#search-modal").modal({backdrop: 'static', keyboard: false});
                }
            });
        }

        function detail(id)
        {
            loadingShow();
            if ($.fn.DataTable.isDataTable("#tbl_detailnota")) {
                $('#tbl_detailnota').DataTable().clear().destroy();
            }
            $('#tbl_detailnota').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('/produksi/returnproduksi/detail-nota') }}"+"/"+id,
                    type: "get"
                },
                columns: [
                    {data: 'barang'},
                    {data: 'qty'},
                    {data: 'harga'}
                ],
                drawCallback: function( settings ) {
                    loadingHide();
                }
            });
            $("#detail").modal("show");
        }
        // apply search nota using modal-search by supplier
        function pilih(id, nota)
        {
            $("#q_nota").val(nota);
            $("#q_idpo").val(id);
            $("#notaPO").val(nota);
            loadingShow();
            if ($.fn.DataTable.isDataTable("#table_rp")) {
                $('#table_rp').DataTable().clear().destroy();
            }
            $('#table_rp').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('/produksi/returnproduksi/cari-barang-po') }}",
                    data: {
                        id: $("#q_idpo").val(),
                        prodCode: $('#q_prodcode').val(),
                        nota: $('#q_nota').val(),
                        searchBy: $('#searchBy').val()
                    },
                    type: "get"
                },
                columns: [
                    {data: 'barang'},
                    {data: 'qty'},
                    {data: 'harga'},
                    {data: 'total'},
                    {data: 'action'}
                ],
                drawCallback: function( settings ) {
                    loadingHide();
                    $(".table-returnp").removeClass('d-none');
                }
            });
            $("#search-modal").modal("hide");
            if ($("#q_idpo").val() == "") {
                $("#go").attr("disabled", true);
            } else {
                $("#go").attr("disabled", false);
            }
        }

        // fill list items in dataTable after using search nota or searh prod-code
        function searchItems()
        {
            loadingShow();
            if ($.fn.DataTable.isDataTable("#table_rp")) {
                $('#table_rp').DataTable().clear().destroy();
            }
            $('#table_rp').DataTable({
                responsive: true,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('/produksi/returnproduksi/cari-barang-po') }}",
                    data: {
                        id: $("#q_idpo").val(),
                        prodCode: $('#q_prodcode').val(),
                        nota: $('#q_nota').val(),
                        searchBy: $('#searchBy').val()
                    },
                    type: "get"
                },
                columns: [
                    {data: 'barang'},
                    {data: 'qty'},
                    {data: 'harga'},
                    {data: 'total'},
                    {data: 'action'}
                ],
                drawCallback: function( settings ) {
                    loadingHide();
                    $(".table-returnp").removeClass('d-none');
                }
            });
            $("#search-modal").modal("hide");
            if ($("#q_idpo").val() == "") {
                $("#go").attr("disabled", true);
            } else {
                $("#go").attr("disabled", false);
            }
        }
        // select an item and show the modal to create return
        function selectItem (searchMethod, poid, item, barang, qty, harga, total)
        {
            console.log(poid, item, barang, qty, harga, total);
            loadingShow();
            $("#searchMethod").val(searchMethod);
            $("#idPO").val(poid);
            $("#idItem").val(item);
            $("#txt_barang").val(barang);
            $("#txt_qty").val(qty);
            $("#txt_harga").val(parseFloat(harga));
            $("#txt_total").val(parseFloat(total))
            axios.get(baseUrl+"/produksi/returnproduksi/set-satuan/"+item)
                .then(function (resp) {
                    loadingHide();
                    $("#satuan_return").find('option').remove();
                    var option = '<option value="">Pilih Satuan</option>';
                    option += '<option value="'+resp.data.id1+'">'+resp.data.unit1+'</option>';
                    if (resp.data.id2 != null && resp.data.id2 != resp.data.id1) {
                        option += '<option value="'+resp.data.id2+'">'+resp.data.unit2+'</option>';
                    }
                    if (resp.data.id3 != null && resp.data.id3 != resp.data.id1) {
                        option += '<option value="'+resp.data.id3+'">'+resp.data.unit3+'</option>';
                    }
                    $("#satuan_return").append(option);
                    $("#createReturn").modal({backdrop: 'static', keyboard: false});
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error);
                })

        }
        // create return
        function createReturn()
        {
            loadingShow();
            axios.post('{{ route('return.add') }}', $("#formCreateReturn").serialize())
            .then(function (resp) {
                loadingHide();
                if (resp.data.status == "Failed") {
                    messageFailed("Gagal", resp.data.message);
                } else if (resp.data.status == "Success") {
                    messageSuccess("Berhasil", resp.data.message);
                    $("#qty_return").val(0);
                    $("#note_return").val('');
                    $("#createReturn").modal("hide");
                    window.open(baseUrl+'/produksi/returnproduksi/nota-return/'+resp.data.id+'/'+resp.data.detail);
                }
            })
            .catch(function (error) {
                loadingHide();
                messageWarning("Error", error);
            })
        }
    </script>
    <script>
    </script>
@endsection
