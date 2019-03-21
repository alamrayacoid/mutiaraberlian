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
		<h1 class="title"> Pengelolaan Manajemen Stok </h1>
		<p class="title-description">
			<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
			/ <span>Aktivitas Inventory</span>
			/ <a href="#"><span>Pengelolaan Manajemen Stok</span></a>
		</p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-md-4 col-sm-6 col-12">
				<a href="{{route('opname.index')}}">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/quality.png')}}" height="128px" width="128px">
						<h6>Opname Stock</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="{{route('adjustment.index')}}">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/manufacture.png')}}" height="128px" width="128px">
						<h6>Adjustment Stock</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="#">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/warehouse.png')}}" height="128px" width="128px">
						<h6>Penglolaan Data Max/Min Stok, Savety Stok</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="#">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/worker-loading-boxes.png')}}" height="128px" width="128px">
						<h6>Penglolaan Data Re-Order Point, Repeat Order</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="#">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/increasing-stocks-graphic-of-bars.png')}}" height="128px" width="128px">
						<h6>Analisa Stock Turn Over</h6>
					</div>
				</a>
			</div>
<!--
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
			</div> -->
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
