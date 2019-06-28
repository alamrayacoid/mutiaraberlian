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

@include('notifikasiotorisasi.otorisasi.adjustment.modal')

<article class="content">
	<div class="title-block text-primary">
		<h1 class="title"> Otorisasi Adjustment Item Produk </h1>
		<p class="title-description">
			<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
			/ <span>Notifikasi & Otorisasi</span>
			/ <a href="{{route('otorisasi')}}">Otorisasi</a>
			/ <span class="text-primary font-weight-bold">Otorisasi Adjustment Item Produk</span>
		</p>
	</div>
	<section class="section">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header bordered p-2">
						<div class="header-block">
							<h3 class="title">Otorisasi Adjustment Item Produk</h3>
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
										<th>Nota</th>
										<th>Unit System</th>
										<th>QTY System </th>
										<th>Unit Real</th>
										<th>QTY Real</th>
										<th>Sisa</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</article>
{{-- Modal detail--}}
<div id="modalDetailApp" class="modal fade animated fadeIn" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Detail</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-6 border-right">
						<div class="form-group">
							<label for="item">Nama Barang</label>
							<input type="text" class="form-control bg-light" id="itemS" disabled="">
							<input type="hidden" id="item" >
							<input type="hidden" id="idAdjAuth">
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<label for="item">Qty System</label>
									<input type="text" class="form-control bg-light" id="qtyS" disabled="">
									<input type="hidden" id="qty_s">
								</div>
								<div class="col-6">
									<label for="item">Qty Real</label>
									<input type="text" class="form-control bg-light" id="qtyR" disabled="">
									<input type="hidden" id="qty_r">
								</div>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="table-responsive">
							<table class="table table-striped table-hover table-bordered" id="table_detail" cellspacing="0">
								<thead class="bg-primary">
									<tr>
										<th>Kode Produksi</th>
										<th>Qty</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-approve">Approve</button>
				<button type="button" class="btn btn-primary bg-danger btn-reject">Reject</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('extra_script')
<script type="text/javascript">
var table1, table2;
	$(document).ready(function(){

		table1 = $('#table_otorisasi').DataTable({
				responsive: true,
				// language: dataTableLanguage,
				// processing: true,
				serverSide: true,
				ajax: {
						url: "{{ url('/notifikasiotorisasi/otorisasi/adjustment/getadjusment') }}",
						type: "get",
						data: {
								"_token": "{{ csrf_token() }}"
						}
				},
				columns: [
						{data: 'DT_RowIndex', className: 'text-center'},
						{data: 'item', name: 'item'},
						{data: 'nota', name: 'nota'},
						{data: 'unitreal', name: 'unitreal'},
						{data: 'aa_qtyreal', name: 'aa_qtyreal', className: 'text-right'},
						{data: 'unitsystem', name: 'unitsystem'},
						{data: 'aa_qtysystem', name: 'aa_qtysystem', className: 'text-right'},
						{data: 'selisih', name: 'selisih', className: 'text-right'},
						{data: 'aksi', name: 'aksi'}
				],
				pageLength: 10,
				lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});

		table2 = $('#table_detail').DataTable();

		$('#table_otorisasi tbody').on('click', '.btn-detail' ,function(){
			$('#detail').modal('show');
		})
	});

	function showDetailApp(id) {
		loadingShow();
		$.ajax({
			url: "{{url('/notifikasiotorisasi/otorisasi/adjustment/show-detail-approve')}}"+"/"+id,
			type: "get",
			dataType: "json",
			success:function(resp){
				$('#idAdjAuth').val(resp.id_auth);
				$('#itemS').val(''+resp.auth.i_code+' - '+resp.auth.i_name+'');
				$('#item').val(resp.auth.aa_item);
				$('#item').val(resp.auth.i_id);
				$('#qtyS').val(resp.auth.aa_qtysystem);
				$('#qtyR').val(resp.auth.aa_qtyreal);
				
				$('#table_detail tbody').empty();
				$.each(resp.code, function(key, val){
					$('#table_detail tbody').append(`<tr>
																						<td>`+val.aca_code+`</td>
																						<td>`+val.aca_qty+`</td>
																					</tr>`);
				});

				loadingHide();
			}
		})
		$('#modalDetailApp').modal('show');
	}

	function approve(id) {
		$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apakah anda yakin akan menyetujui data ini?',
				theme: 'sukses',
				buttons: {
						info: {
								btnClass: 'btn-blue',
								text: 'Ya',
								action: function () {
										loadingShow();
										axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/adjustment/agreeadjusment'+'/'+id).then(function(response) {
												if(response.status == 'berhasil'){
														loadingHide();
														messageSuccess("Berhasil", "Data Stock Opname Berhasil Disetujui");
														table1.ajax.reload();
												}else{
														loadingHide();
														messageFailed("Gagal", "Data Stock Opname Berhasil Disetujui");
												}
										})
								}
						},
						cancel: {
								text: 'Tidak',
								action: function () {
										// tutup confirm
								}
						}
				}
		});
	}

	$('.btn-approve').on('click', function(){
		var id = $('#idAdjAuth').val();
		$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apakah anda yakin akan menyetujui data ini?',
				theme: 'sukses',
				buttons: {
						info: {
								btnClass: 'btn-blue',
								text: 'Ya',
								action: function () {
										loadingShow();
										axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/adjustment/agreeadjusment'+'/'+id).then(function(response) {
												if(response.status == 'berhasil'){
														loadingHide();
														messageSuccess("Berhasil", "Data Stock Opname Berhasil Disetujui");
														table1.ajax.reload();
												}else{
														loadingHide();
														messageFailed("Gagal", "Data Stock Opname Berhasil Disetujui");
												}
										})
								}
						},
						cancel: {
								text: 'Tidak',
								action: function () {
										// tutup confirm
								}
						}
				}
		});
	});

	function rejected(id) {
		$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apakah anda yakin akan menolak data ini?',
				theme: 'disable',
				buttons: {
						info: {
								btnClass: 'btn-blue',
								text: 'Ya',
								action: function () {
										loadingShow();
										axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/adjustment/rejectadjusment'+'/'+id).then(function(response) {
												if(response.status == 'berhasil'){
														loadingHide();
														messageSuccess("Berhasil", "Data Stock Opname Berhasil Ditolak");
														table1.ajax.reload();
												}else{
														loadingHide();
														messageFailed("Gagal", "Data Stock Opname Berhasil Ditolak");
												}
										})
								}
						},
						cancel: {
								text: 'Tidak',
								action: function () {
										// tutup confirm
								}
						}
				}
		});
	}

	$('.btn-reject').on('click', function() {
		var id = $('#idAdjAuth').val();
		$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apakah anda yakin akan menolak data ini?',
				theme: 'disable',
				buttons: {
						info: {
								btnClass: 'btn-blue',
								text: 'Ya',
								action: function () {
										loadingShow();
										axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/adjustment/rejectadjusment'+'/'+id).then(function(response) {
												if(response.status == 'berhasil'){
														loadingHide();
														messageSuccess("Berhasil", "Data Stock Opname Berhasil Ditolak");
														table1.ajax.reload();
												}else{
														loadingHide();
														messageFailed("Gagal", "Data Stock Opname Berhasil Ditolak");
												}
										})
								}
						},
						cancel: {
								text: 'Tidak',
								action: function () {
										// tutup confirm
								}
						}
				}
		});
	});
</script>
@endsection
