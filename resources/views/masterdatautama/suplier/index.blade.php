@extends('main')

@section('content')

@include('masterdatautama.suplier.modal')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Master Suplier </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Master Data Utama</span> / <span class="text-primary" style="font-weight: bold;">Kelola Data Suplier</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
	                        <h3 class="title"> Data Suplier </h3>
	                    </div>
	                    <div class="header-block pull-right">
                			<button class="btn btn-primary" data-toggle="modal" data-target="#tambah" onclick="window.location.href='{{route('suplier.create')}}'"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>

                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover table-bordered display nowrap" cellspacing="0" id="table_supplier">
	                                <thead class="bg-primary">
	                                    <tr align="center">
													                <th width="1%">No</th>
																					<th>Nama Perusahaan</th>
																					<th width="15%">Telepon</th>
																					<th width="10%">Limit</th>
																					<th width="10%">Hutang</th>
																					<th width="5%">Aksi</th>
													            </tr>
	                                </thead>
	                                <tbody>
	                                	<!-- <tr align="center">
	                                		<td>1</td>
																			<td>BradCompany</td>
	                                		<td>Brad</td>
	                                		<td>123123</td>
	                                		<td>JL.Rh</td>
	                                		<td>012312</td>
																			<td>-</td>
																			<td>-</td>
																			<td>0123212</td>
																			<th>Bang</th>
																			<td>-</td>
																			<td><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>
																			<td>-</td>
																			<td>-</td>
																			<td class="input-rupiah">0.00</td>
																			<td class="input-rupiah">0.00</td>
	                                		<td>
	                                			<div class="btn-group btn-group-sm">
	                                				<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
	                                				<button class="btn btn-danger btn-disable" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>
	                                			</div>
	                                		</td>
	                                	</tr>
																		 -->
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
	// set header token for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	var tb_supplier;
	// function to retrieve DataTable server side
	function TableSupplier()
	{
		$('#table_supplier').dataTable().fnDestroy();
		tb_supplier = $('#table_supplier').DataTable({
			responsive: true,
			// language: dataTableLanguage,
			// processing: true,
			serverSide: true,
			ajax: {
				url: "{{ route('suplier.list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}"
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 's_company'},
				{data: 'phone'},
				{data: 's_limit'},
				{data: 's_hutang'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	// function to redirect page to edit page
	function EditSupplier(idx)
	{
		window.location.href = baseUrl + "/masterdatautama/suplier/edit/" + idx;
	}
	// function to execute delete request
	function DeleteSupplier(idx)
	{
		var url_hapus = baseUrl + "/masterdatautama/suplier/delete/" + idx;

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
									messageSuccess('Berhasil', 'Data berhasil dihapus !');
									loadingShow();
									tb_supplier.ajax.reload();
									loadingHide();
								}
							},
							error : function(e){
								messageWarning('Gagal', 'Data gagal dihapus, hubungi pengembang !');
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

	$(document).ready(function(){
		TableSupplier();

		// $(document).on('click', '.btn-disable', function(){
		// 	var ini = $(this);
		// 	$.confirm({
		// 		animation: 'RotateY',
		// 		closeAnimation: 'scale',
		// 		animationBounce: 1.5,
		// 		icon: 'fa fa-exclamation-triangle',
		// 		title: 'Peringatan!',
		// 		content: 'Apa anda yakin mau menonaktifkan data ini?',
		// 		theme: 'disable',
		// 	    buttons: {
		// 	        info: {
		// 				btnClass: 'btn-blue',
		// 	        	text:'Ya',
		// 	        	action : function(){
		// 					$.toast({
		// 						heading: 'Information',
		// 						text: 'Data Berhasil di Nonaktifkan.',
		// 						bgColor: '#0984e3',
		// 						textColor: 'white',
		// 						loaderBg: '#fdcb6e',
		// 						icon: 'info'
		// 					})
		// 			        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="enable"><i class="fa fa-check-circle"></i></button>');
		// 		        }
		// 	        },
		// 	        cancel:{
		// 	        	text: 'Tidak',
		// 			    action: function () {
    // 			            // tutup confirm
    // 			        }
    // 			    }
		// 	    }
		// 	});
		// });
		// $(document).on('click', '.btn-enable', function(){
		// 	$.toast({
		// 		heading: 'Information',
		// 		text: 'Data Berhasil di Aktifkan.',
		// 		bgColor: '#0984e3',
		// 		textColor: 'white',
		// 		loaderBg: '#fdcb6e',
		// 		icon: 'info'
		// 	})
		// 	$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	  //                               		'<button class="btn btn-danger btn-disable" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
		// })
		// $('#table_suplier tbody').on('click','.btn-edit', function(){
		// })
	});
</script>
@endsection
