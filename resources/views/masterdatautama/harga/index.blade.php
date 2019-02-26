@extends('main')

@section('content')



<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Master Harga </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Master Data Utama</span> 
	    	/ <span class="text-primary" style="font-weight: bold;">Master Harga</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				<ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#satuan" aria-controls="satuan" data-toggle="tab" role="tab">Data Satuan</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#golongan" aria-controls="golongan" data-toggle="tab" role="tab">Data Golongan</a>
                    </li>
                </ul>				
		
				<div class="tab-content">		

					@include('masterdatautama.harga.satuan.index')
					@include('masterdatautama.harga.golongan.index')


		        </div>
			</div>

		</div>

	</section>

</article>

@endsection