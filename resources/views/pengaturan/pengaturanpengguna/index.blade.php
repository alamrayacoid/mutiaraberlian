@extends('main')

@section('content')

@include('pengaturan.pengaturanpengguna.modal')
@include('pengaturan.pengaturanpengguna.level')

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
	var table
	$(document).ready(function(){
		$('#table_pengaturan').dataTable().fnDestroy();
		table = $('#table_pengaturan').DataTable({
				responsive: true,
				// language: dataTableLanguage,
				// processing: true,
				serverSide: true,
				ajax: {
						url: baseUrl + "/pengaturan/pengaturanpengguna/datatable",
						type: "get",
						data: {
								"_token": "{{ csrf_token() }}"
						}
				},
				columns: [
						{data: 'DT_RowIndex'},
						{data: 'name', name: 'name'},
						{data: 'u_username', name: 'u_username'},
						{data: 'jenis', name: 'jenis'},
						{data: 'c_name', name: 'c_name'},
						{data: 'm_name', name: 'm_name'},
						{data: 'action', name: 'action'}
				],
				pageLength: 10,
				lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	});

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

	function hapus(id) {
			$.confirm({
					animation: 'RotateY',
					closeAnimation: 'scale',
					animationBounce: 1.5,
					icon: 'fa fa-exclamation-triangle',
					title: 'Peringatan!',
					content: 'Apa anda yakin menghapus data ini?',
					theme: 'disable',
					buttons: {
							info: {
									btnClass: 'btn-blue',
									text: 'Ya',
									action: function () {
										loadingShow();
										$.ajax({
											type: 'get',
											data: {id},
											dataType: 'JSON',
											url: baseUrl + '/pengaturan/pengaturanpengguna/hapus',
											success : function(response){
												if (response.status == 'berhasil') {
													messageSuccess('Berhasil', 'Data berhasil dihapus!');
													loadingHide();
													table.ajax.reload();
												} else {
													messageFailed('Gagal', 'Data gagal dihapus!');
												}
											}
										});
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

	function changepass(id){
		$('#updatepassword').attr('onclick', 'updatepassword('+id+')')
	}

	function updatepassword(id) {
			$.confirm({
					animation: 'RotateY',
					closeAnimation: 'scale',
					animationBounce: 1.5,
					icon: 'fa fa-exclamation-triangle',
					title: 'Peringatan!',
					content: 'Apa anda yakin mengubah password?',
					theme: 'disable',
					buttons: {
							info: {
									btnClass: 'btn-blue',
									text: 'Ya',
									action: function () {
										if ($('#lama').val() == "" || $('#lama').val() == undefined) {
											messageFailed('Failed', 'password lama kosong, mohon lengkapi data!');
											return false;
										} else if ($('#baru').val() == "" || $('#baru').val() == undefined) {
											messageFailed('Failed', 'password baru kosong, mohon lengkapi data!');
											return false;
										} else if ($('#confirm').val() == "" || $('#confirm').val() == undefined) {
											messageFailed('Failed', 'password baru kosong, mohon lengkapi data!');
											return false;
										} else {
											var data = $('#datachange').serialize();
											loadingShow();
											$.ajax({
												type: 'get',
												dataType: 'JSON',
												url: baseUrl + '/pengaturan/pengaturanpengguna/updatepassword?'+data+'&id='+id,
												success : function(response){
													if (response.status == 'berhasil') {
														loadingHide();
														messageSuccess('Berhasil', 'Password berhasil diubah!');
													} else if (response.status == 'failed') {
														loadingHide();
														messageFailed('Gagal', response.ex+'!');
													} else {
														loadingHide();
														messageFailed('Gagal', 'Password gagal diubah!');
													}
												}
											});
										}
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

	function editlevel(id){
		$('#updatelevel').attr('onclick', 'updatelevel('+id+')');
		$('#level').modal('show');
	}

	function updatelevel(id){
		loadingShow();
		$.ajax({
			type: 'get',
			data: $('#datalevel').serialize()+'&id='+id,
			dataType: 'JSON',
			url: baseUrl + '/pengaturan/pengaturanpengguna/updatelevel',
			success : function(response){
				if (response.status == 'berhasil') {
					loadingHide();
					messageSuccess('Berhasil', 'Level berhasil diubah!');
				} else {
					loadingHide();
					messageFailed('Gagal', 'Level gagal diubah!');
				}
			}
		});
	}

	function akses(id){
		window.location.href = "{{route('pengaturanpengguna.akses')}}?id="+id;
	}
</script>
@endsection
