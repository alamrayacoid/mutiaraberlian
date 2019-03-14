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

@include('notifikasiotorisasi.otorisasi.perubahanhargajual.modal')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Otorisasi Perubahan Harga Jual </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Notifikasi & Otorisasi</span>
	    	 / <a href="{{route('otorisasi')}}">Otorisasi</a>
	    	 / <span class="text-primary font-weight-bold">Otorisasi Perubahan Harga Jual</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">
			
			<div class="col-12">
				<div class="card">

					<div class="card-header bordered p-2">
						<div class="header-block">
							<h3 class="title">Otorisasi Perubahan Harga Jual</h3>
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
                                        <th>Golongan</th>
										<th>Nama Barang</th>
										<th>Jenis</th>
										<th>Qty</th>
                                        <th>Harga</th>
										<th width="20%">Aksi</th>
									</tr>
								</thead>



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

        table1 = $('#table_otorisasi').DataTable({
            responsive: true,
            // language: dataTableLanguage,
            // processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('notifikasiotorisasi/otorisasi/perubahanhargajual/getdataperubahan') }}",
                type: "get",
                data: {
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'pc_name', name: 'pc_name'},
                {data: 'nama', name: 'name'},
                {data: 'pcad_payment', name: 'pcad_payment'},
                {data: 'qty', name: 'qty'},
                {data: 'pcad_price', name: 'pcad_price'},
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

	function approve(id, detailid) {
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
                        /*axios.get(baseUrl+'/produksi/orderproduksi/hapus'+'/'+id).then(function(response) {
                            loadingShow();
                            if(response.data.status == 'Success'){
                                loadingHide();
                                messageSuccess("Berhasil", "Data Order Produksi Berhasil Dihapus");
                                TableIndex();
                            }else{
                                loadingHide();
                                messageFailed("Gagal", "Data Order Produksi Gagal Dihapus");
                            }
                        })*/
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
