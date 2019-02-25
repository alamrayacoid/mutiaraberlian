@extends('main')

@section('content')

@include('purchasing.rencanapembelian.modal_detail')
@include('purchasing.rencanapembelian.modal_edit')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Rencana Pembelian </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Purchasing</span> 
	    	/ <span class="text-primary font-weight-bold">Rencana Pembelian</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#daftar_rencana" aria-controls="daftar_rencana" data-toggle="tab" role="tab">Daftar Rencana Pembelian</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#history_rencana" aria-controls="history_rencana" data-toggle="tab" role="tab">History Rencana Pembelian</a>
                    </li>
                </ul>

                <div class="tab-content">

                	@include('purchasing.rencanapembelian.tab_daftar')
                	@include('purchasing.rencanapembelian.tab_history')



	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
	$('.rencana_detail').click(function(){
		$('#detail_rencana').modal('show');
	});
	$('.rencana_edit').click(function(){
		$('#detail_rencana_edit').modal('show');
	});
</script>
@endsection