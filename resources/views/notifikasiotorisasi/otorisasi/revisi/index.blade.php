@extends('main')

@section('content')
    @include('notifikasiotorisasi.otorisasi.revisi.orderproduksi.detail')
<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Otorisasi Revisi Data </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Notifikasi & Otorisasi</span>
	    	 / <a href="{{route('otorisasi')}}">Otorisasi</a>
	    	 / <span class="text-primary font-weight-bold">Otorisasi Revisi Data</span>
	     </p>
	</div>

	<section class="section">

	<div class="row">

		<div class="col-12">

		<ul class="nav nav-pills mb-3">
			<li class="nav-item">
				<a href="" class="nav-link active" data-target="#dataproduk" aria-controls="dataproduk" data-toggle="tab" role="tab">Data Produk</a>
			</li>
			<li class="nav-item">
				<a href="" class="nav-link" data-target="#datapenjualan" aria-controls="datapenjualan" data-toggle="tab" role="tab">Data Penjualan</a>
			</li>
			<li class="nav-item">
				<a href="" class="nav-link" data-target="#orderproduksi" aria-controls="orderproduksi" data-toggle="tab" role="tab">Data Order Produksi</a>
			</li>
		</ul>

		<div class="tab-content">

			@include('notifikasiotorisasi.otorisasi.revisi.produk.index')
			@include('notifikasiotorisasi.otorisasi.revisi.penjualan.index')			
			@include('notifikasiotorisasi.otorisasi.revisi.orderproduksi.index')


		</div>

	</div>

</div>

</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
    var table_sup, table_bar, table_pus;

	$(document).ready(function(){
		table_sup = $('#table_dataproduk').DataTable();
		table_bar = $('#table_datapenjualan').DataTable();
		table_pus = $('#table_orderproduksi').DataTable({
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('getproduksi') }}",
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'date'},
                {data: 'supplier'},
                {data: 'nota'},
                {data: 'aksi'}
            ],
        });
	});

	function detailOrderProduksi(id) {
        if ($.fn.DataTable.isDataTable("#tbl_dtlprod") && $.fn.DataTable.isDataTable("#tbl_dtlprodtermin")) {
            $('#tbl_dtlprod').DataTable().clear().destroy();
            $('#tbl_dtlprodtermin').DataTable().clear().destroy();
        }

        $('#tbl_dtlprod').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching":     false,
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('getproduksidetailitem') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                }
            },
            columns: [
                {data: 'item'},
                {data: 'unit'},
                {data: 'qty'},
                {data: 'value'},
                {data: 'totalnet'}
            ],
            drawCallback: function( settings ) {
                hitungTotalNet();
            }
        });

        $('#tbl_dtlprodtermin').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching":     false,
            responsive: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ route('getproduksidetailtermin') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                }
            },
            columns: [
                {data: 'termin'},
                {data: 'date'},
                {data: 'value'}
            ],
            drawCallback: function( settings ) {
                hitungTotalTermin();
            }
        });

        $("#dtlordprod").modal('show');
    }

    function hitungTotalNet() {
        var inpTotNet = document.getElementsByClassName( 'totalnet' ),
            totNet  = [].map.call(inpTotNet, function( input ) {
                return parseInt(input.value);
            });

        var total = 0;
        for (var i =0; i < totNet.length; i++) {
            total += parseInt(totNet);
        }

        $("#totNet").html(convertToRupiah(total));
    }

    function hitungTotalTermin() {
        var inpTotTermin = document.getElementsByClassName( 'totaltermin' ),
            totTermin  = [].map.call(inpTotTermin, function( input ) {
                return parseInt(input.value);
            });

        var total = 0;
        for (var i =0; i < totTermin.length; i++) {
            total += parseInt(totTermin);
        }

        $("#totTermin").html(convertToRupiah(total));
    }
</script>

@endsection
