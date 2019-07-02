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
										<th>Nota</th>
										<th>Nama Barang</th>
										<th>Qty Real</th>
										<th>Qty Sistem</th>
										<th>Selisih</th>
										<th width="20%">Aksi</th>
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

@endsection

@section('extra_script')
<script type="text/javascript">
	var table1, table2;
	$(document).ready(function(){
		// console.log('OpnameAuth')
		$('#table_otorisasi').dataTable().fnDestroy();
		table1 = $('#table_otorisasi').DataTable({
				responsive: true,
				// language: dataTableLanguage,
				// processing: true,
				serverSide: true,
				ajax: {
						url: "{{url('notifikasiotorisasi/otorisasi/opname/getdataopname')}}",
						type: "GET",
						data: {
								"_token": "csrf_token()"
						}
				},
				columns: [
						{data: 'DT_RowIndex', className: 'text-center'},
						{data: 'item', name: 'item'},
						{data: 'nota', name: 'nota'},
						{data: 'oa_qtyreal', name: 'oa_qtyreal', className: 'text-right'},
						{data: 'oa_qtysystem', name: 'oa_qtysystem', className: 'text-right'},
						{data: 'selisih', name: 'selisih', className: 'text-right'},
						{data: 'aksi', name: 'aksi', className: 'text-center'}
				],
				pageLength: 10,
				lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});

		table2 = $('#table_detail').DataTable();

		$('#table_otorisasi tbody').on('click', '.btn-detail' ,function(){
			$('#detail').modal('show');
		})
	});

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
                        axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/opname/approveopname'+'/'+id).then(function(response) {
                            if(response.data.status == 'berhasil'){
                                loadingHide();
                                messageSuccess("Berhasil", "Data Stock Opname Berhasil Disetujui");
                                table1.ajax.reload();
                            }else{
                                loadingHide();
                                messageFailed("Gagal", "Data Stock Opname Gagal Disetujui");
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
						axios.get(baseUrl+'/notifikasiotorisasi/otorisasi/opname/rejectedopname'+'/'+id).then(function(response) {
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
</script>
@endsection
