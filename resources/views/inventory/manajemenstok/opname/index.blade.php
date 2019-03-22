@extends('main')

@section('content')

@include('inventory.manajemenstok.opname.opnamestock.modal')

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
		TableOpnameHistory();

		cur_date = new Date();
		first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
		last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		$('#date_from').datepicker('setDate', first_day);
		$('#date_to').datepicker('setDate', last_day);
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
				{data: 'date'},
				{data: 'oa_nota'},
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

	$('#date_from').on('change', function() {
		TableOpnameHistory();
	})
	$('#date_to').on('change', function() {
		TableOpnameHistory();
	})

	var tb_opnamehistory;
	// function to retrieve DataTable server side
	function TableOpnameHistory()
	{
		$('#table_historyopname').dataTable().fnDestroy();
		tb_opnamehistory = $('#table_historyopname').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('history.list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}",
					"date_from" : $('#date_from').val(),
					"date_to" : $('#date_to').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'date'},
				{data: 'o_nota'},
				{data: 'name'},
				{data: 'status'},
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
</script>
@endsection
