@extends('main')

@section('content')

@include('sdm.prosesrekruitmen.modal_calonkaryawan')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Proses Rekruitmen </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas SDM</span> / <span class="text-primary" style="font-weight: bold;">Proses Rekruitmen</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

                <ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#list_rekruitmen" aria-controls="list_rekruitmen" data-toggle="tab" role="tab">Rekruitmen</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#list_pelamarditerima" aria-controls="list_pelamarditerima" data-toggle="tab" role="tab">Daftar Pelamar Diterima</a>
                    </li>
                </ul>

                <div class="tab-content">

                	@include('sdm.prosesrekruitmen.tab_rekruitmen')
                	@include('sdm.prosesrekruitmen.tab_pelamarditerima')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){
		var table_sup = $('#table_rekruitmen').DataTable();
		var table_bar= $('#table_pelamard').DataTable();


		$(document).on('click','.btn-preview-rekruitmen',function(){
			window.location.href='{{route('rekruitmen.preview')}}'
		});
		$(document).on('click','.btn-proses-rekruitmen',function(){
			window.location.href='{{route('rekruitmen.process')}}'
		});

		$(document).on('click', '.btn-disable-rekruitmen', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-rekruitmen" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-rekruitmen', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Enable.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-primary" data-toggle="modal" data-target="#list_suplier_membawa" type="button" title="Preview"><i class="fa fa-search"></i></i></button>'+
											'<button class="btn btn-warning btn-edit-rekruitmen" type="button" title="Process"><i class="fa fa-file-powerpoint-o"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-rekruitmen" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
		})


		$(document).on('click','.btn-preview-pelamar',function(){
			window.location.href='{{route('rekruitmen.preview')}}'
		});

		$(document).on('click', '.btn-disable-pelamar', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-pelamar" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-pelamar', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Enable.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-primary" data-toggle="modal" data-target="#list_barang_dibawa" type="button" title="Preview"><i class="fa fa-search"></i></button>'+
											'<button class="btn btn-warning btn-edit-pelamar" type="button" title="Process"><i class="fa fa-file-powerpoint-o"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-pelamar" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
		})

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
</script>
@endsection