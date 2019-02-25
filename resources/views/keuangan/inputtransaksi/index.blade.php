@extends('main')

@section('content')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title">Pilih Transaksi</h1>
        <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Keuangan</span>
	    	 / <span class="text-primary font-weight-bold">Manajemen Input Transaksi</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

            <div class="col-md-4 col-sm-6">
            <div class="card" onclick="window.location.href='{{ route('transaksikas.create') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-usd"></i>
                    <p>Transaksi Kas</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

            <div class="col-md-4 col-sm-6">
            <div class="card bank-card" onclick="window.location.href='{{ route('transaksibank.create') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-bank"></i>
                    <p>Transaksi Bank</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

            <div class="col-md-4 col-sm-6">
            <div class="card" onclick="window.location.href='{{ route('transaksimemorial.create') }}'" style="cursor:pointer;">
            <div class="card-block text-center">
            <fieldset style="padding:10px 30px 10px 30px;">
                <div class="kas-section">
                    <i class="fa fa-suitcase"></i>
                    <p>Transaksi Memorial</p>
                </div>
            </fieldset>
            </div>
            </div>
            </div>

		</div>

	</section>

</article>

@endsection