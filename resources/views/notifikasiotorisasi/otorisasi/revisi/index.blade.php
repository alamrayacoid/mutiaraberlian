@extends('main')

@section('content')
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

	$(document).ready(function(){
		var table_sup = $('#table_dataproduk').DataTable();
		var table_bar = $('#table_datapenjualan').DataTable();
		var table_pus = $('#table_orderproduksi').DataTable();
	});
</script>

@endsection