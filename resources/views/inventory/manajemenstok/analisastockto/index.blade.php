@extends('main')

@section('content')

<article class="content animated fadeInLeft">
	<div class="title-block text-primary">
		<h1 class="title"> Manajemen Penjualan Stok  </h1>
		<p class="title-description">
			<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Inventory</span> / <span class="text-primary" style="font-weight: bold;">Pengelolaan Manajemen Stok</span>
		</p>
	</div>
	<section class="section">
		<div class="row">
			<div class="col-12">
				<div class="tab-content">
					<div class="tab-pane animated fadeIn active show" id="adjustmentstock">
						<div class="card">
							<div class="card-header bordered p-2">
								<div class="header-block">
									<h3 class="title">Analisa Stock Turn Over</h3>
								</div>
					        <div class="header-block pull-right">
					          <a class="btn btn-primary" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
					        </div>
								<div class=""></div>
							</div>
							<div class="card-block">
								<section>
									<div class="table-responsive">
										<table class="table table-hover table-striped w-100" cellspacing="0" id="table_analisastock">
											<thead class="bg-primary">
												<tr>
													{{-- <th width="1%">No</th> --}}
													<th>Nama Barang</th>
													<th>Rata-rata HPP</th>
													<th>Transaksi</th>
					                <th>Turn Over</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								</section>
							</div>
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
	var tb_analisa;
	$(document).ready(function(){
		console.log('analisaTO');
		getListAnalisa();
	});

	function getListAnalisa() {
		$('#table_analisastock').dataTable().fnDestroy();
		tb_analisa = $('#table_analisastock').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ url('/inventory/analisaturnover/get-list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}"
				}
			},
			columns: [
				// {data: 'DT_RowIndex'},
				{data: 'i_name'},
				{data: 'sc_member'},
				{data: 'sc_nota'},
				{data: 'scd_value'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
</script>
@endsection