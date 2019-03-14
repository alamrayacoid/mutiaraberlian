@extends('main')

@section('content')

@include('inventory.manajemenstok.opname.opnamestock.modal')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Manajemen Penjualan Pusat  </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Inventory</span> / <span class="text-primary" style="font-weight: bold;">Pengelolaan Manajemen Stok</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

                <ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#opnamestock" aria-controls="opnamestock" data-toggle="tab" role="tab">Opname Stock</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#historyopname" aria-controls="historyopname" data-toggle="tab" role="tab">History Opname Stock</a>
					</li>
                </ul>

                <div class="tab-content">

					@include('inventory.manajemenstok.opname.opnamestock.index')
                    @include('inventory.manajemenstok.opname.historyopname.index')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){
		TableOpnameStock();
		// var table_sup = $('#table_opnamestock').DataTable();
		var table_bar= $('#table_historyopname').DataTable();

		$('#table_opnamestock tbody').on('click', '.btn-print', function(){
			window.open('{{route('opname.print')}}', '_blank');
		});

		$(document).on('click', '.btn-rejected', function(){
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
								text: 'Promosi Ditolak.',
								bgColor: '#0984e3',
								textColor: 'white',
								loaderBg: '#fdcb6e',
								icon: 'info'
							})
					        ini.parents('.btn-group').html('<button class="btn btn-danger btn-sm btn-cancel-reject">Batalkan Penelokan</button>');
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

		$(document).on('click', '.btn-cancel-reject', function(){
			$(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>'+
			'<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
		})

		$(document).on('click', '.btn-approval', function(){
			$.toast({
				heading: 'Information',
				text: 'Promosi Diterima.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-primary btn-sm btn-cancel-approve">Batalkan Penerimaan</button>')
		})

		$(document).on('click', '.btn-cancel-approve', function(){
			$(this).parents('.btn-group').html('<button class="btn btn-success btn-approval" type="button" title="approve"><i class="fa fa-check"></i></button>'+
			'<button class="btn btn-danger btn-rejected" type="button" title="reject"><i class="fa fa-close"></i></button>')
		})


	});


	var tb_opnamestock;
	// function to retrieve DataTable server side
	function TableOpnameStock()
	{
		$('#table_opnamestock').dataTable().fnDestroy();
		tb_opnamestock = $('#table_opnamestock').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('opname.list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}"
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'oa_date'},
				{data: 'oa_date'},
				{data: 'name'},
				{data: 'status'},
				{data: 'action', name: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// function to view detail
	function Detail(idx)
	{
		$.ajax({
			url: baseUrl + "/inventory/manajemenstok/opnamestock/show/" + idx,
			type: 'get',
			success: function(response) {
				$('#table_detail tbody').empty();
				$('#table_detail > tbody:last-child').append('<tr><td>'+ response.get_item.i_name +'</td><td>'+ response.get_owner.c_name +'</td><td>'+ response.get_position.c_name +'</td><td>'+ response.get_unit_system.u_name +'</td><td>'+ response.oa_qtysystem +'</td><td>'+ response.get_unit_real.u_name +'</td><td>'+ response.oa_qtyreal +'</td></tr>');
				$('#detail').modal('show');
			}
		})
	}
	// function to redirect page to edit page
	function Edit(idx)
	{
		window.location.href = baseUrl + "/inventory/manajemenstok/opnamestock/edit/" + idx;
	}
	// function to execute disable request
	function Delete(idx)
	{
		var url_hapus = baseUrl + "/inventory/manajemenstok/opnamestock/delete/" + idx;
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
									tb_opnamestock.ajax.reload();
									loadingHide();
								}
							},
							error : function(e){
				        messageWarning('Gagal', 'Error, hubungi pengembang !');
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
</script>
@endsection
