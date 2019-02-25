@extends('main')

@section('content')

@include('purchasing.orderpembelian.modal_detail')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Order Pembelian </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Purchasing</span> 
	    	/ <span class="text-primary font-weight-bold">Order Pembelian</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#list_barang" aria-controls="list_barang" data-toggle="tab" role="tab">Order Pembelian Dengan Rencana</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#po_tanpa" aria-controls="po_tanpa" data-toggle="tab" role="tab">Order Pembelian Tanpa Rencana</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#list_suplier" aria-controls="list_suplier" data-toggle="tab" role="tab">History Order Pembelian</a>
                    </li>
                </ul>


				<div class="tab-content">

					@include('purchasing.orderpembelian.tab_daftar')

					@include('purchasing.orderpembelian.tab_po_tanpa_rencana')

		            @include('purchasing.orderpembelian.tab_history')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">
	$('.btn_order_detail').click(function(){
		$('#modal_detail_order').modal('show');
	});
</script>
@endsection