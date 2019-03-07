@extends('main')

@section('content')

<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Manajemen Marketing Area  </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> / <span class="text-primary" style="font-weight: bold;">Manajemen Marketing Area</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

                <ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a href="" class="nav-link active" data-target="#orderprodukagenpusat" aria-controls="orderprodukagenpusat" data-toggle="tab" role="tab">Order Produk ke Agen / Pusat</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link" data-target="#keloladataagen" aria-controls="keloladataagen" data-toggle="tab" role="tab">Kelola Penjualan Langsung </a>
					</li>
					<li class="nav-item">
                        <a href="" class="nav-link" data-target="#monitoringpenjualanagen" aria-controls="monitoringpenjualanagen" data-toggle="tab" role="tab">Kelola Penjualan Via Website</a>
					</li>
					<li class="nav-item">
                        <a href="" class="nav-link" data-target="#datacanvassing" aria-controls="datacanvassing" data-toggle="tab" role="tab">Kelola Laporan Keuangan Sederhana </a>
					</li>
					<li class="nav-item">
                        <a href="" class="nav-link" data-target="#datakonsinyasi" aria-controls="datakonsinyasi" data-toggle="tab" role="tab">Kelola Data Inventory Agen</a>
					</li>
                </ul>

                <div class="tab-content">

					@include('marketing.agen.orderproduk.index')

	            </div>

			</div>

		</div>

	</section>

</article>

@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){
		var table_sup = $('#table_orderprodukagenpusat').DataTable();

		$(document).on('click','.btn-edit',function(){
			window.location.href='{{ route('orderagenpusat.edit') }}'
		});

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

			$(document).ready(function() {
				$('#modal-order').DataTable( {
					"iDisplayLength" : 5
				});
			});

			$(document).ready(function() {
				$('#detail-monitoring').DataTable( {
					"iDisplayLength" : 5
				});
			});	
		});


		$(document).on('click', '.btn-enable', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		})
	});
</script>
@endsection
