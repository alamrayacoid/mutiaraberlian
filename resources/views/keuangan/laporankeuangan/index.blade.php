@extends('main')

@section('content')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title">Pilih Laporan Keuangan</h1>
        <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Keuangan</span>
	    	 / <span class="text-primary font-weight-bold">Laporan Keuangan</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

            <div class="col-md-4 col-sm-6">
            <div class="card" onclick="window.location.href='{{ route('laporankeuangan.jurnal') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-clipboard"></i>
                    <p>Jurnal Umum</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

            <div class="col-md-4 col-sm-6">
            <div class="card" onclick="window.location.href='{{ route('laporankeuangan.bukubesar') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-book"></i>
                    <p>Buku Besar</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

            <div class="col-md-4 col-sm-6">
            <div class="card" onclick="window.location.href='{{ route('laporankeuangan.neraca') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-columns"></i>
                    <p>Neraca</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

            <div class="col-md-4 col-sm-6">
            <div class="card" onclick="window.location.href='{{ route('laporankeuangan.labarugi') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-bar-chart-o"></i>
                    <p>Laba Rugi</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

            <div class="col-md-4 col-sm-6">
            <div class="card" onclick="window.location.href='{{ route('laporankeuangan.aruskas') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-refresh"></i>
                    <p>Arus Kas</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

		</div>

	</section>

</article>

@endsection