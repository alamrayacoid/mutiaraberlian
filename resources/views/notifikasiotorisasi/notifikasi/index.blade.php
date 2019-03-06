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
</style>
@endsection

@section('content')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Notifikasi </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Notifikasi & Otorisasi</span>
	    	 / <span class="text-primary font-weight-bold">Notifikasi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-md-4 col-sm-6 col-12">
				<a href="#">
					<div class="card text-center p-4 card-custom text-info">
						<img src="{{asset('assets/img/notiftransaksi.png')}}" height="128px" width="128px">
						<h6>Notifikasi Informasi Transaksi</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="#">
					<div class="card text-center p-4 card-custom text-secondary">
						<img src="{{asset('assets/img/graph.png')}}" height="128px" width="128px">
						<h6>Notifikasi Informasi Analisa Finansial</h6>
					</div>
				</a>
			</div>

			<div class="col-md-4 col-sm-6 col-12">
				<a href="#">
					<div class="card text-center p-4 card-custom text-success">
						<img src="{{asset('assets/img/caution.png')}}" height="128px" width="128px">
						<h6>Notifikasi Peringatan</h6>
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
