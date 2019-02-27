@extends('main')

@section('content')



<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Master Harga </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Master Data Utama</span> 
	    	/ <span class="text-primary" style="font-weight: bold;">Master Harga</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">
				<ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#golongan" aria-controls="golongan" data-toggle="tab" role="tab">Data Golongan</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#default" aria-controls="default" data-toggle="tab" role="tab">Harga Default</a>
                    </li>
                </ul>				
		
				<div class="tab-content">		

					@include('masterdatautama.harga.golongan.index')
					@include('masterdatautama.harga.default.index')


		        </div>
			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){
	    $('#table_golonganharga').DataTable({
			"paging":   false,
			"ordering": false,
			"info":     false
    	});

		$(document).on('click','.btn-edit-golonganharga',function(){
			window.location.href='{{route('golonganharga.edit')}}'
		});

		$(document).on('click', '.btn-disable-golonganharga', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-golonganharga" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-golonganharga', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Enable.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-golonganharga" title="Edit" type="button"><i class="fa fa-pencil"></i></button>'+
											'<button class="btn btn-danger btn-disable-golonganharga" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
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
@endsection