@extends('main')

@section('content')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Penggunaan Bahan Baku </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Stok</span> 
	    	/ <span class="text-primary font-weight-bold">Penggunaan Bahan Baku</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">


			<div class="col-lg-12">

				<ul class="nav nav-pills mb-3">
	                <li class="nav-item">
	                    <a href="" class="nav-link active" data-target="#list" aria-controls="list" data-toggle="tab" role="tab">Penggunaan Bahan Baku</a>
	                </li>
	                <li class="nav-item">
	                    <a href="" class="nav-link" data-target="#history" aria-controls="history" data-toggle="tab" role="tab">History Penggunaan Bahan Baku</a>
	                </li>
	            </ul>
				
				<div class="tab-content">

					@include('stok.penggunaanbahanbaku.tab_penggunaanbahanbaku')
					@include('stok.penggunaanbahanbaku.tab_history_penggunaanbahanbaku')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection