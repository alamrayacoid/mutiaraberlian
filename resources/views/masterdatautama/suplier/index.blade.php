@extends('main')

@section('content')

@include('masterdatautama.suplier.datasuplier.modal')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Master Suplier </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Master Data Utama</span> 
	    	/ <span class="text-primary" style="font-weight: bold;">Master Suplier</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				<ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#datasuplier" aria-controls="datasuplier" data-toggle="tab" role="tab">Data Suplier</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#itemsuplier" aria-controls="itemsuplier" data-toggle="tab" role="tab">Item Suplier</a>
                    </li>
                </ul>				
		
				<div class="tab-content">		

					@include('masterdatautama.suplier.datasuplier.index')
					@include('masterdatautama.suplier.itemsuplier.index')


		        </div>
			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">

$(document).ready(function(){
	$('#jenisharga').change(function(){
		var ini, satuan, range;
		ini             = $(this).val();
		satuan     		= $('#satuan');
		range     		= $('#range');

		if (ini === '1') {
			satuan.removeClass('d-none');
			range.addClass('d-none');
		} else if(ini === '2'){
			satuan.addClass('d-none');
			range.removeClass('d-none');
		} else {
			satuan.addClass('d-none');
			range.addClass('d-none');
		}
	});
});
</script>
<script type="text/javascript">
	// set header token for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(document).ready(function () {
        
		$('#item_suplier').DataTable();

    })

	var sub;
	function TableItemSupplier(idSupp){
		$('#item_suplier').dataTable().fnDestroy();
		sub = $('#item_suplier').DataTable({
			responsive: true,
			autoWidth: false,
			serverSide: true,
			ajax: {
				url: "{{ route('itemsuplier.getitemdt') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}",
					"idSupp": idSupp
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'i_code'},
				{data: 'i_name'},
				{data: 's_company'},
				{data: 'aksi'}
			],
		});
	}

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
				{data: 'limit'},
				{data: 'hutang'},
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
	function DisableSupplier(idx)
	{
		var url_hapus = baseUrl + "/masterdatautama/suplier/disable/" + idx;

		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apakah anda yakin ingin menonaktifkan data ini ?',
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
									messageSuccess('Berhasil', 'Data berhasil dinonaktifkan !');
									loadingShow();
									tb_supplier.ajax.reload();
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

	// function to execute delete request
	function EnableSupplier(idx)
	{
		var url_hapus = baseUrl + "/masterdatautama/suplier/enable/" + idx;

		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apakah anda yakin ingin mengaktifkan data ini ?',
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
									messageSuccess('Berhasil', 'Data berhasil diaktifkan !');
									loadingShow();
									tb_supplier.ajax.reload();
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

	$( "#suppItemNama" ).autocomplete({
		source: function(request, response) {
			$.getJSON(baseUrl+'/masterdatautama/itemsuplier/autoItem', { idSupp: $("#suppId").val(), term: $("#suppItemNama").val() }, response);
		},
		minLength: 2,
		select: function(event, data) {
			$('#suppItemId').val(data.item.id);
			$('#suppItemNama').val(data.item.label);
		}
	});

	$('#suppId').on('change', function(){
		var Supp = $('#suppId').val();
		TableItemSupplier(Supp);
	})

	function tambah(){
		var idSupp = $('#suppId').val();
		var idItem = $('#suppItemId').val();
		var data = 'idSupp='+idSupp+'&idItem='+idItem;
		axios.post(baseUrl+'/masterdatautama/itemsuplier/tambah', data).then((response) => {
			if(response.data.status == 'sukses'){
				messageSuccess('Berhasil', 'Data berhasil ditambahkan !');
				loadingShow();
				sub.ajax.reload();
				loadingHide();

				$('#suppItemNama').val('');
				$('#suppItemId').val('');
			}else{

			}
		})
	}

	function hapus(itemId, suppId){
		axios.get(baseUrl+'/masterdatautama/itemsuplier/hapus'+'/'+itemId+'/'+suppId).then((response) => {
			if(response.data.status == 'sukses'){
				messageSuccess('Berhasil', 'Data berhasil dihapus !');
				loadingShow();
				sub.ajax.reload();
				loadingHide();
			}else{

			}
		})
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
