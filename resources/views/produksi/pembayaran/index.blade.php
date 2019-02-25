@extends('main')

@section('content')

@include('produksi.pembayaran.modal')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Pembayaran </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Aktifitas Produksi</span>
	    	 / <span class="text-primary" style="font-weight: bold;">Pembayaran</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Pembayaran </h3>
                        </div>
                        <!-- <div class="header-block pull-right">
                        	
                			<a class="btn btn-primary" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                        </div> -->
                    </div>
                    <div class="card-block">
                        <section>
						<div class="row">
						<div class="col-2 ml-3">
						<label for="">Pilih Order</label>
						</div>
						<div class="col-6">
						<select name="" id="" class="form-control form-control-sm select2">
							<option value="">-</option>
							<option value="">001533903</option>
							<option value="">001433953</option>
						</select>
						</div>
						<div class="">
						<button class="btn btn-primary btn-go">Go</button>
						</div>
						</div>
						<hr style="border:0.5px solid grey">
                        	<div class="table-responsive termin-table">
							<div>
							<button type="button" class="btn btn-primary btn-tambah-termin-gan">Tambah Data</button>
							</div>
							<table class="table table-striped table-hover" cellspacing="0" id="table_pembayaran">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th>Termin</th>
	                                		<th>Estimasi</th>
	                                		<th>Nominal</th>
											<th>Tanggal</th>
											<th>Kekurangan</th>
											<th>Status</th>
											<th>Detail Order</th>
	                                	</tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>
											<input type="text" class="form-control form-control-sm" readonly="" value="1">
											</td>
	                                		<td>
											<input type="text" class="form-control form-control-sm" readonly="" value="19-02-2019">
											</td>
	                                		<td>
											<input type="text" class="form-control form-control-sm input-rupiah" readonly="" value="Rp. 1.000.000,00">
											</td>
	                                		<td>
											<input type="text" class="form-control form-control-sm" readonly="" value="11-02-2019">
											</td>
											<td>
											<input type="text" class="form-control form-control-sm" readonly="" value="0">
											</td>
											<td>
											<div class="status-termin-lunas"><p>Lunas</p></div>
											</td>
											<td width="15%"><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>
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
		table = $('#table_pembayaran').DataTable();

		$(document).on('click', '.btn-disable', function(){
			var ini = $(this);
			$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apa anda yakin mau menonaktifkan data ini?',
				theme: 'disable',
			    buttons: {
			        info: {
						btnClass: 'btn-blue',
			        	text:'Ya',
			        	action : function(){
							$.toast({
								heading: 'Information',
								text: 'Data Berhasil di Nonaktifkan.',
								bgColor: '#0984e3',
								textColor: 'white',
								loaderBg: '#fdcb6e',
								icon: 'info'
							})
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		// Show Table 
		$( ".btn-go" ).click(function() {
			$( ".termin-table" ).show();
		});
		// End
		
		// Looping Table
		td1 = '<input type="text" class="form-control form-control-sm" readonly="">'
		td2 = '<input type="text" class="form-control form-control-sm" readonly="">'
		td3 = '<input type="text" class="form-control form-control-sm" readonly="">'
		td4 = '<input type="text" class="form-control form-control-sm" readonly="">'
		td5 = '<input type="text" class="form-control form-control-sm" readonly="">'
		td6 = '<div class="status-termin-belum"><p>Belum</p></div>'
		td7 = '<td width="15%"><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>'
		tbody = $('tbody')
		num_row = 5;
		for(x = 0; x < num_row;x++) {
		table.row.add([td1, td2, td3, td4, td5, td6, td7])
		}
		table.draw()
		// End

		// Tambah Table
		$(document).on('click', '.btn-hapus-termin-gan', function(){
		$(this).parents('tr').remove();
		});

		$(document).on('click', '.btn-tambah-termin-gan', function(){
			var ini = $(this);
			$.confirm({
				animation: 'RotateY',
				closeAnimation: 'scale',
				animationBounce: 1.5,
				icon: 'fa fa-exclamation-triangle',
				title: 'Peringatan!',
				content: 'Apa anda yakin mau menambah data?',
				theme: 'disable',
			    buttons: {
			        info: {
						btnClass: 'btn-blue',
			        	text:'Ya',
			        	action : function(){
							$('#table_pembayaran')
							.append(
								'<tr>'+
								'<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
								'<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
								'<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
								'<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
								'<td><input type="text" class="form-control form-control-sm" readonly=""></td>'+
								'<div class="status-termin-belum"><p>Belum</p></div>'+
								'<td width="15%"><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>'+
								'</tr>'
								);
					        // ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		// $('.btn-tambah-termin-gan').on('click',function(){

		// End


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
	    //                             		'<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		// })

		// function table_hapus(a){
		// 	table.row($(a).parents('tr')).remove().draw();
		// }
	});
</script>
@endsection
