@extends('main')

@section('content')
<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Data Produk </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	/ <span>Master Data Utama</span>
	    	/ <span class="text-primary font-weight-bold">Data Produk</span>
	     </p>
	</div>

	<section class="section">

		<ul class="nav nav-pills mb-3">
			<li class="nav-item">
				<a href="" class="nav-link active" data-toggle="tab" data-target="#tab-1">Data Produk</a>
			</li>
			<li class="nav-item">
				<a href="" class="nav-link" data-toggle="tab" data-target="#tab-2">Data Jenis Produk</a>
			</li>
		</ul>

		<div class="row">

			<div class="col-12">

				<div class="tab-content">
					<div class="tab-pane fade in active show" id="tab-1">
						<div class="card">
			              <div class="card-header bordered p-2">
			              	<div class="header-block">
			                    <h3 class="title"> Data Produk </h3>
			                </div>
			                <div class="header-block pull-right">
												<a class="btn btn-primary" href="{{route('dataproduk.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data Produk</a>
			                </div>
			              </div>
			              <div class="card-block">
			                  <section>

			                  	<div class="table-responsive">
			                        <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_produk">
			                            <thead class="bg-primary">
			                                <tr>
									                				<th>No</th>
			                                		<th>Kode Barang</th>
			                                		<th>Nama Barang</th>
			                                		<th>Satuan</th>
			                                		<th>Jenis Barang</th>
									                				<th>Aksi</th>
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

		            <div class="tab-pane fade in" id="tab-2">
		            	<div class="card">

		            		<div class="card-header p-2 bordered">
		            			<div class="header-block">
			            			<h3 class="title">Data Jenis Produk</h3>
			            		</div>
			            		<div class="header-block pull-right">
												<a class="btn btn-primary" href="{{route('datajenisproduk.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data Jenis Produk</a>
			            		</div>
		            		</div>

		            		<div class="card-block">
		            			<section>

			            			<div class="table-responsive">
			                            <table class="table table-striped table-hover" cellspacing="0" id="table_jenis_produk">
			                                <thead class="bg-primary">
			                                    <tr>
									                <th>No</th>
									                <th>Kode Jenis Produk</th>
									                <th>Nama Jenis Produk</th>
									                <th>Aksi</th>
									            </tr>
			                                </thead>
			                                <tbody>
			                                	<tr>
			                                		<td>1</td>
			                                		<td>JP/1</td>
			                                		<td>Herbal</td>
			                                		<td>
			                                			<div class="btn-group btn-group-sm">
			                                				<button class="btn btn-warning btn-edit" title="Edit" type="button"><i class="fa fa-pencil"></i></button>
			                                				<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
			                                			</div>
			                                		</td>
			                                	</tr>
			                                	<tr>
			                                		<td>2</td>
			                                		<td>JP/2</td>
			                                		<td>Obat-obatan</td>
			                                		<td>
			                                			<div class="btn-group btn-group-sm">
			                                				<button class="btn btn-warning btn-edit" title="Edit" type="button"><i class="fa fa-pencil"></i></button>
			                                				<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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

			</div>

		</div>

	</section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
	// set header token for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	var tb_produk;
	setTimeout(function() {
		TableProduk();
	}, 1500);
	// function to retrieve DataTable server side
	// retrieve data 'produk'
	function TableProduk()
	{
		$('#table_produk').dataTable().fnDestroy();
		tb_produk = $('#table_produk').DataTable({
			responsive: true,
			// language: dataTableLanguage,
			// processing: true,
			serverSide: true,
			ajax: {
				url: "{{ route('dataproduk.list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}"
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'i_code', name: 'i_code'},
				{data: 'i_name', name: 'i_name'},
				{data: 'i_unit1', name: 'i_unit1'},
				{data: 'type', name: 'type'},
				{data: 'action', name: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// function to redirect page to edit page
	function EditDataproduk(idx)
	{
		window.location.href = baseUrl + "/masterdatautama/produk/edit/" + idx;
	}
	// function to execute delete request
	function DeleteDataproduk(idx)
	{
		var url_hapus = baseUrl + "/masterdatautama/produk/delete/" + idx;

		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apakah anda yakin ingin menghapus data ini ?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text:'Ya',
					action : function(){
						return $.ajax({
							type : "post",
							url : url_hapus,
							success : function (response){
								if(response.status == 'berhasil'){
									$.toast({
										heading: 'Success',
										text: 'Data berhasil dihapus !',
										bgColor: '#00b894',
										textColor: 'white',
										loaderBg: '#55efc4',
										icon: 'success',
										stack: false
									});
									tb_produk.ajax.reload();
								}
							},
							error : function(e){
								$.toast({
									heading: 'Warning',
									text: e.message,
									bgColor: '#00b894',
									textColor: 'white',
									loaderBg: '#55efc4',
									icon: 'warning',
									stack: false
								});
							}
						});
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
	}

	// start: unused -> confirmed and deleted soon
	// $(document).ready(function(){
	// 	var table = $('#tabel_jenisproduk').DataTable();
	// 	var table1 = $('#tabel_produk').DataTable();
	//
	// 	$('#tabel_produk tbody').on('click', '.btn-edit', function(){
	//
	//
	// 	});
	//
	// 	$('#tabel_jenisproduk tbody').on('click', '.btn-edit', function(){
	//
	// 		window.location.href = '{{route("datajenisproduk.edit")}}';
	//
	// 	});
	//
	// 	$('#btn-tambah-produk').on('click', function(){
	//
	// 		window.location.href = '{{route("dataproduk.create")}}';
	//
	// 	});
	//
	// 	$('#btn-tambah-jenisproduk').on('click', function(){
	//
	// 		window.location.href = '{{route("datajenisproduk.create")}}';
	//
	// 	});
	//
	//
	// 	$(document).on('click', '.btn-disable', function(){
	// 		var ini = $(this);
	// 		$.confirm({
	// 			animation: 'RotateY',
	// 			closeAnimation: 'scale',
	// 			animationBounce: 1.5,
	// 			icon: 'fa fa-exclamation-triangle',
	// 			title: 'Peringatan!',
	// 			content: 'Apa anda yakin mau menonaktifkan data ini?',
	// 			theme: 'disable',
	// 			buttons: {
	// 				info: {
	// 					btnClass: 'btn-blue',
	// 					text:'Ya',
	// 					action : function(){
	// 						$.toast({
	// 							heading: 'Information',
	// 							text: 'Data Berhasil di Nonaktifkan.',
	// 							bgColor: '#0984e3',
	// 							textColor: 'white',
	// 							loaderBg: '#fdcb6e',
	// 							icon: 'info'
	// 						})
	// 						ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
	// 					}
	// 				},
	// 				cancel:{
	// 					text: 'Tidak',
	// 					action: function () {
	// 						// tutup confirm
	// 					}
	// 				}
	// 			}
	// 		});
	// 	});
	//
	// 	$(document).on('click', '.btn-enable', function(){
		// 	$.toast({
		// 		heading: 'Information',
		// 		text: 'Data Berhasil di Aktifkan.',
		// 		bgColor: '#0984e3',
		// 		textColor: 'white',
		// 		loaderBg: '#fdcb6e',
		// 		icon: 'info'
		// 	})
		// 	$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
		// 									'<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		// })
	// function table_hapus(a){
	// 	table.row($(a).parents('tr')).remove().draw();
	// }
	// });
	// end: unused -> confirmed and deleted soon

</script>
@endsection
