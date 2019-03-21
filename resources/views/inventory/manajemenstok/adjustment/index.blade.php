@extends('main')

@section('content')

<!-- Modal Terima Order -->
@include('marketing.penjualanpusat.terimaorder.modal')

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
                        <a href="" class="nav-link active" data-target="#adjustmentstock" aria-controls="adjustmentstock" data-toggle="tab" role="tab">Adjustment Stock</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#historyadjustment" aria-controls="historyadjustment" data-toggle="tab" role="tab">History Adjustment Stock</a>
					</li>
                </ul>

                <div class="tab-content">

					@include('inventory.manajemenstok.adjustment.adjustmentstock.index')
					@include('inventory.manajemenstok.adjustment.historyadjustment.index')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){

		cur_date = new Date();
		first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
		last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		$('#date_from').datepicker('setDate', first_day);
		$('#date_to').datepicker('setDate', last_day);

		TableAdjusmentHistory();
		TableAdjusment();

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

	function cetak(id){
		window.location.href = '{{route('adjustment.nota')}}?id='+id;
	}

	var tb_opnamehistory;

	function TableAdjusmentHistory()
	{
		$('#table_historyadjusment').dataTable().fnDestroy();
		tb_adjusmenthistory = $('#table_historyadjusment').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: baseUrl + "/notifikasiotorisasi/otorisasi/adjustment/getList",
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
				{data: 'a_nota'},
				{data: 'name'},
				{data: 'status'},
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function gettable(){
		TableAdjusmentHistory();
	}

	function TableAdjusment(){
		$('#table_adjustment').dataTable().fnDestroy();
		table_sup = $('#table_adjustment').DataTable({
				responsive: true,
				// language: dataTableLanguage,
				// processing: true,
				serverSide: true,
				ajax: {
						url: "{{ route('adjustment.list') }}",
						type: "POST",
						data: {
								"_token": "{{ csrf_token() }}"
						}
				},
				columns: [
						{data: 'DT_RowIndex'},
						{data: 'tanggal', name: 'tanggal'},
						{data: 'aa_nota', name: 'aa_nota'},
						{data: 'i_name', name: 'i_name'},
						{data: 'status', name: 'status'},
						{data: 'action', name: 'action'}
				],
				pageLength: 10,
				lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

</script>
@endsection
