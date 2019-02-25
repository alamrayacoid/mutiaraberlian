@extends('main')

@section('content')

@include('stok.opnamebahanbaku.detail_opnamebahanbaku')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Opname Bahan Baku </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Stok</span> 
	    	/ <span class="text-primary font-weight-bold">Opname Bahan Baku</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-lg-12">


				<ul class="nav nav-pills mb-3">
	                <li class="nav-item">
	                    <a href="" class="nav-link active" data-target="#opname" aria-controls="opname" data-toggle="tab" role="tab">Opname Bahan Baku</a>
	                </li>
	                <li class="nav-item">
	                    <a href="" class="nav-link" data-target="#list" aria-controls="list" data-toggle="tab" role="tab">List Opname Bahan Baku</a>
	                </li>
	            </ul>
					
				<div class="tab-content">

@include('stok.opnamebahanbaku.tab_opname')
@include('stok.opnamebahanbaku.tab_list_opname')

			</div>

		</div>

	</section>

</article>

@endsection