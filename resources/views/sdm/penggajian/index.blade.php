@extends('main')

@section('content')

<!-- modal scoreboard pegawai -->
@include('sdm.penggajian.payrollmanajemen.modal_tambah')
<!-- end -->

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Kelola Penggajian </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas SDM</span> / <span class="text-primary" style="font-weight: bold;">Kelola Pengajian</span>
	     </p>
	</div>

	<section class="section">

	<div class="row">

		<div class="col-12">

		<ul class="nav nav-pills mb-3">
			<li class="nav-item">
				<a href="" class="nav-link active" data-target="#list_manajemen" aria-controls="list_manajemen" data-toggle="tab" role="tab">Manajemen</a>
			</li>
			<li class="nav-item">
				<a href="" class="nav-link" data-target="#list_tunjangan" aria-controls="list_tunjangan" data-toggle="tab" role="tab">Tunjangan</a>
			</li>
			<li class="nav-item">
				<a href="" class="nav-link" data-target="#list_produksi" aria-controls="list_produksi" data-toggle="tab" role="tab">Produksi</a>
			</li>
			<li class="nav-item">
				<a href="" class="nav-link" data-target="#list_payrollmanajemen" aria-controls="list_payrollmanajemen" data-toggle="tab" role="tab">Payroll Manajemen</a>
			</li>
		</ul>

		<div class="tab-content">

			@include('sdm.penggajian.manajemen.tab_manajemen')
			@include('sdm.penggajian.tunjangan.tab_tunjangan')
			@include('sdm.penggajian.produksi.tab_produksi')
			@include('sdm.penggajian.payrollmanajemen.tab_payrollmanajemen')

		</div>

	</div>

</div>

</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){
		var table_sup = $('#table_manajemen').DataTable();
		var table_bar = $('#table_tunjangan').DataTable();
		var table_pus = $('#table_produksi').DataTable();
		var table_rab = $('#table_payrollmanajemen').DataTable();

// MANAJEMEN
$(document).on('click', '.btn-disable-manajemen', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-manajemen" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-manajemen', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-manajemen" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-manajemen" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
											
		})
        $('#table_manajemen tbody').on('click','.btn-edit-manajemen', function(){
			window.location.href='{{route('manajemen.edit')}}'
		})	

// END
// TUNJANGAN
$(document).on('click', '.btn-disable-tunjangan', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-tunjangan" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-tunjangan', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-tunjangan" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-tunjangan" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
											
		})
        $('#table_tunjangan tbody').on('click','.btn-edit-tunjangan', function(){
			window.location.href='{{route('tunjangan.edit')}}'
		})
// END
// PRODUKSI
$(document).on('click', '.btn-disable-produksi', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-produksi" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-produksi', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-produksi" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-produksi" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
											
		})
        $('#table_produksi tbody').on('click','.btn-edit-produksi', function(){
			window.location.href='{{route('produksi.edit')}}'
		})
// END
// PAYROLLMANAJEMEN
$(document).on('click', '.btn-disable-payrollmanajemen', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-payrollmanajemen" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-payrollmanajemen', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-produksi" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-produksi" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
											
		})
        // $('#table_payrollmanajement body').on('click','.btn-edit-payrollmanajemen', function(){
		// 	window.location.href='{{route('produksi.edit')}}'
		// })
// END


		$(document).on('click', '.btn-simpan-modal', function(){
			$.toast({
				heading: 'Success',
				text: 'Data Berhasil di Simpan',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		})
	});

	$(document).ready(function() {
            $('.datepicker').datepicker();
        })
</script>
@endsection