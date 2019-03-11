@extends('main')

@section('content')



<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Pengelolaan Barang Keluar </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktivitas Inventory</span>
	    	 / <span class="text-primary font-weight-bold">Pengelolaan Barang Keluar</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Pengelolaan Barang Keluar </h3>
                        </div>
                        <div class="header-block pull-right">

                			<a class="btn btn-primary" href="{{ route('barangkeluar.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>

                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_barangkeluar">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th>No</th>
																				<th>Tanggal Keluar</th>
		                                		<th>Kode Barang</th>
		                                		<th>Nama Barang</th>
																				<th>Jumlah Barang</th>
		                                		<th>Satuan</th>
																				<th>Lokasi Keluar</th>
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
		</div>
	</section>
</article>

{{-- Modal Detail --}}
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Barang Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
									<div class="col-md-3">
										<label for="d_code">Kode</label>
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="d_code">
										</div>
									</div>

									<div class="col-md-3">
										<label for="d_name">Nama</label>
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="d_name">
										</div>
									</div>

									<div class="col-md-3">
										<label for="d_nota">Nota Pengeluaran</label>
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<input type="text" class="form-control form-control-sm w-100 bg-light" disabled value="" readonly id="d_nota">
										</div>
									</div>

                	<div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover display" cellspacing="0" id="table_detail">
                          <thead class="bg-primary">
                              <tr>
                              	<th>No</th>
																<th>Reff</th>
																<th>Qty</th>
																<th>Satuan</th>
                            		<th>HPP</th>
                            	</tr>
                          </thead>
                          <tbody>

                          </tbody>
                      </table>
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_script')
<script type="text/javascript">
	// set header token for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	var tb_barangkeluar;
	function TableBarangKeluar()
	{
		$('#table_barangkeluar').dataTable().fnDestroy();
		tb_barangkeluar = $('#table_barangkeluar').dataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('barangkeluar.list') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}"
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'io_date'},
				{data: 'code'},
				{data: 'name'},
				{data: 'io_qty'},
				{data: 'unit'},
				{data: 'mutcat'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function Detail(id, nota)
	{
		$.ajax({
			url: baseUrl + "/inventory/barangkeluar/detail/" + id,
			type: 'get',
			success: function(response) {
				console.log(response);
				unit_name = response.get_item.get_unit1.u_name;
				$('#d_code').val(response.get_item.i_code);
				$('#d_name').val(response.get_item.i_name);
				$('#d_nota').val(response.io_nota);
				$('#table_detail tbody').empty();
				$.each(response.get_mutation_detail, function(i, val) {
					// console.log(i+1, val, unit_name);
					index = i + 1;
					$('#table_detail > tbody:last-child').append('<tr><td>'+ index +'</td><td>'+ val.sm_reff +'</td><td>'+ val.sm_qty +'</td><td>'+ unit_name +'</td><td><span class="float-left">Rp </span><span class="float-right">'+ val.sm_hpp +'</span></td></tr>');
				});
				$('#modal_detail').modal('show');
			},

		})
	}

	$(document).ready(function(){
		TableBarangKeluar();

		//
		// $('#table_barangkeluar tbody').on('click', '.btn-edit', function(){
		//
		// 	window.location.href = '{{route("barangkeluar.edit", ['1'])}}';
		//
		// });
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
		// 		buttons: {
		// 			info: {
		// 				btnClass: 'btn-blue',
		// 				text:'Ya',
		// 				action : function(){
		// 					$.toast({
		// 						heading: 'Information',
		// 						text: 'Data Berhasil di Nonaktifkan.',
		// 						bgColor: '#0984e3',
		// 						textColor: 'white',
		// 						loaderBg: '#fdcb6e',
		// 						icon: 'info'
		// 					})
		// 					ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
		// 				}
		// 			},
		// 			cancel:{
		// 				text: 'Tidak',
		// 				action: function () {
		// 					// tutup confirm
		// 				}
		// 			}
		// 		}
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
		// 									'<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		// })
		// function table_hapus(a){
		// 	table.row($(a).parents('tr')).remove().draw();
		// }
	});
</script>
@endsection
