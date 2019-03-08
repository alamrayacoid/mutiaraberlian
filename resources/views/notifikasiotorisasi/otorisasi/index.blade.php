@extends('main')

@section('extra_style')
<style type="text/css">
	a:not(.btn){
		text-decoration: none;
	}
	.card img{
		margin: auto;
	}
	.card-custom{
		min-height: calc(100vh / 2);
	}
	.card-custom:hover,
	.card-custom:focus-within{
		background-color: rgba(255,255,255,.6);
	}
</style>
@endsection

@section('content')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Otorisasi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Notifikasi & Otorisasi</span>
	    	 / <span class="text-primary font-weight-bold">Otorisasi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-md-4 col-sm-6 col-12">
				<a href="{{route('perubahanhargajual')}}">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/rupiah.png')}}" height="128px" width="128px">
						<h6>Otorisasi Perubahan Harga Jual</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="{{route('pengeluaranlebih')}}">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/comparison1.png')}}" height="128px" width="128px">
						<h6>Otorisasi Pengeluaran Lebih Dari Nilai Tertentu</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="{{route('opname_otorisasi')}}">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/box.png')}}" height="128px" width="128px">
						<h6>Otorisasi Opname Item Produk</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="{{route('adjustment')}}">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/manufacture.png')}}" height="128px" width="128px">
						<h6>Otorisasi Adjustment Item Produk</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="{{route('revisi')}}">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/checklist.png')}}" height="128px" width="128px">
						<h6>Otorisasi Revisi Data</h6>
					</div>
				</a>
			</div>
		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
@endsection
