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

@include('notifikasiotorisasi.otorisasi.opname.modal')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Otorisasi Opname Item Produk </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Notifikasi & Otorisasi</span>
	    	 / <a href="{{route('otorisasi')}}">Otorisasi</a>
	    	 / <span class="text-primary font-weight-bold">Otorisasi Opname Item Produk</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">
			
			<div class="col-12">
				<div class="card">

					<div class="card-header bordered p-2">
						<div class="header-block">
							<h3 class="title">Otorisasi Opname Item Produk</h3>
						</div>
						<div class="header-block pull-right">
							<a class="btn btn-secondary btn-sm" href="{{route('otorisasi')}}"><i class="fa fa-arrow-left"></i></a>
						</div>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							
							<table class="table table-bordered table-striped table-hover" id="table_otorisasi" cellspacing="0">

								<thead class="bg-primary">
									<tr>
										<th width="1%">No</th>
										<th>Nama Barang</th>
										<th>Qty Real</th>
										<th>Qty Sistem</th>
										<th>Opname</th>
										<th>User</th>
										<th width="20%">Aksi</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td>1</td>
										<td>01001 - Barang</td>
										<td>10</td>
										<td>10</td>
										<td>Penerimaan Barang</td>
										<td>Foxtrot</td>
										<td align="center">
											<div class="btn-group btn-group-sm">
												<button class="btn btn-info btn-detail"><i class="fa fa-list"></i></button>
												<button class="btn btn-success" type="button" title="Setuju"><i class="fa fa-check-circle"></i></button>
												<button class="btn btn-danger" type="button" title="Tolak"><i class="fa fa-times-circle"></i></button>
											</div>
										</td>
									</tr>
								</tbody>
								
							</table>

						</div>
					</div>
					
				</div>
			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	$(document).ready(function(){
		var table1, table2;

		table1 = $('#table_otorisasi').DataTable();
		table2 = $('#table_detail').DataTable();

		$('#table_otorisasi tbody').on('click', '.btn-detail' ,function(){
			$('#detail').modal('show');
		})
	});
</script>
@endsection
