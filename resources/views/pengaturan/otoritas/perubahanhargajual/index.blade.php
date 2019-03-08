@extends('main')

@section('content')

@include('pengaturan.otoritas.perubahanhargajual.modal')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Perubahan Harga Jual </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Pengaturan</span>
             / <span>Otoritas</span>
	    	 / <span class="text-primary font-weight-bold">Perubahan Harga Jual</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title">Perubahan Harga Jual</h3>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_perubahan">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th width="5%">No</th>
	                                		<th width="15%">Tanggal</th>
	                                		<th>Nama Barang</th>
	                                		<th width="15%">Aksi</th>
	                                	</tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>1</td>
	                                		<td>07/09/2019</td>
											<td>Obat</td>
	                                		<td>
	                                			<div class="btn-group btn-group-sm">
													<button class="btn btn-primary btn-detail" data-toggle="modal" data-target="#detail" type="button" title="Detail"><i class="fa fa-list-alt"></i></button>
	                                				<button class="btn btn-success btn-approve" type="button" title="Approve"><i class="fa fa-check-circle"></i></button>
	                                				<button class="btn btn-danger btn-reject" type="button" title="Reject"><i class="fa fa-times-circle"></i></button>
	                                			</div>
	                                		</td>
	                                	</tr>
	                                </tbody>
	                            </table>
	                        </div>
                        </section>
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
		var table = $('#table_perubahan').DataTable();

	$(document).on('click', '.btn-approve', function(){
		var ini = $(this);
		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apa anda yakin?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text:'Ya',
					action : function(){
						$.toast({
							heading: 'Information',
							text: 'Data Berhasil di Diterima.',
							bgColor: '#0984e3',
							textColor: 'white',
							loaderBg: '#fdcb6e',
							icon: 'info'
						})
					}
				},
				cancel:{
					text: 'Tidak',
					action: function () {
						// tutup confirm
					}
				}
			}
		});
	});

	$(document).on('click', '.btn-reject', function(){
		var ini = $(this);
		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apa anda yakin?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text:'Ya',
					action : function(){
						$.toast({
							heading: 'Information',
							text: 'Data Berhasil Ditolak.',
							bgColor: '#0984e3',
							textColor: 'white',
							loaderBg: '#fdcb6e',
							icon: 'info'
						})
					}
				},
				cancel:{
					text: 'Tidak',
					action: function () {
						// tutup confirm
					}
				}
			}
		});
	});


	$(document).on('click', '.btn-approve2', function(){
		$.toast({
			heading: 'Information',
			text: 'Approve Success.',
			bgColor: '#0984e3',
			textColor: 'white',
			loaderBg: '#fdcb6e',
			icon: 'info'
		})
	})
	$(document).on('click', '.btn-reject2', function(){
		$.toast({
			heading: 'Information',
			text: 'Reject Success.',
			bgColor: '#0984e3',
			textColor: 'white',
			loaderBg: '#fdcb6e',
			icon: 'info'
		})
	})

		// function table_hapus(a){
		// 	table.row($(a).parents('tr')).remove().draw();
		// }
	});
</script>
@endsection
