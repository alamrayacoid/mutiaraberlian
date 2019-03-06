@extends('main')

@section('content')

@include('pengaturan.pengaturanpengguna.modal')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Pengaturan Pengguna </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Pengaturan</span>
	    	 / <span class="text-primary font-weight-bold">Pengaturan Pengguna</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title">Pengaturan Pengguna</h3>
                        </div>
						<div class="header-block pull-right">
                    		<a class="btn btn-primary" href="{{route('pengaturanpengguna.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_pengaturan">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th width="5%">No</th>
	                                		<th width="25%">Nama User</th>
	                                		<th width="20%">Username</th>
											<th width="20%">Jenis</th>
                                            <th width="20%">Cabang</th>
                                            <th width="15">level</th>
	                                		<th width="15%">Aksi</th>
	                                	</tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>1</td>
	                                		<td>Bambang</td>
											<td>Agen</td>
											<td>BradPit666</td>
                                            <td>MUTIARA A</td>
                                            <TD>Admin</TD>
	                                		<td>
	                                			<div class="btn-group btn-group-sm">
													<button class="btn btn-success btn-akses" onclick="window.location.href='{{ route('pengaturanpengguna.akses') }}'" title="Akses"><i class="fa fa-wrench"></i></button>
	                                				<button class="btn btn-warning btn-edit" onclick="window.location.href='{{ route('pengaturanpengguna.edit') }}'" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
	                                				<button class="btn btn-primary btn-change" data-toggle="modal" data-target="#change" type="button" title="Ganti Password"><i class="fa fa-exchange"></i></button>
                                                    <button class="btn btn-danger btn-nonaktif" type="button" title="Nonaktif"><i class="fa fa-times-circle"></i></button>
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
		var table = $('#table_pengaturan').DataTable();

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
