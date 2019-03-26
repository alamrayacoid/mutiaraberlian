@extends('main')

@section('content')

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
                                    <label>No. Nota</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <input type="hidden" name="q_idpo" id="q_idpo">
                                        <input type="text" name="q_nota" id="q_nota" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-md btn-primary" title="Pencarian No. Nota" id="btn_searchnota"><i class="fa fa-search"></i></button>
                                </div>

                        	</div>

                            <fieldset>
                                <div class="row">
                                    <div class="col-md-3 col-sm-6 col-12">
                                        <label>Metode Return</label>
                                    </div>
                                    <div class="col-md-9 col-sm-6 col-12">
                        			<div class="form-group">
                        				<select class="form-control form-control-sm" id="header-metodereturn">
                        					<option value="">--Pilih Metode Return--</option>
                        					<option value="GB">Ganti Barang</option>
                        					<option value="GU">Ganti Uang</option>
                        					<option value="PN">Potong Nota</option>
                        				</select>
                        			</div>
                                    </div>
                                    @include('produksi.returnproduksi.tab_potongnota')
                                    @include('produksi.returnproduksi.tab_gantiuang')
                                    @include('produksi.returnproduksi.tab_gantibarang')
                        		</div>
                            </fieldset>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button">Simpan</button>
                    	<a href="{{route('return.index')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	$(document).ready(function(){
        var tbl_gb, tbl_gu, tbl_pn;

        tbl_gb = $('#tabel_gb').DataTable();
        tbl_gu = $('#tabel_gu').DataTable();
        tbl_pn = $('#tabel_pn').DataTable();

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

        $("#q_nota").on("keyup", function () {
            $("#q_idpo").val('');
        });

        $("#q_nota").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: '{{ route('return.carinota') }}',
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

        $("#btn_searchnota").on("click", function (evt) {
            evt.preventDefault();
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
	});

	function detail(id) {
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
</script>
@endsection
