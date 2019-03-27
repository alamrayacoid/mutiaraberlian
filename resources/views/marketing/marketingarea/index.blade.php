@extends('main')

@section('content')

@include('marketing.marketingarea.keloladataorder.modal')
@include('marketing.marketingarea.keloladataorder.modal-search')
@include('marketing.marketingarea.monitoring.modal')

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
				<ul class="nav nav-pills mb-3" id="Tabzs">
					<li class="nav-item">
						<a href="#orderproduk" class="nav-link active" data-target="#orderproduk" aria-controls="orderproduk" data-toggle="tab" role="tab">Order Produk ke Cabang</a>
					</li>
					<li class="nav-item">
						<a href="#keloladataagen" class="nav-link" data-target="#keloladataagen" aria-controls="keloladataagen" data-toggle="tab" role="tab">Kelola Data Order Agen </a>
					</li>
					<li class="nav-item">
						<a href="#monitoringpenjualanagen" class="nav-link" data-target="#monitoringpenjualanagen" aria-controls="monitoringpenjualanagen" data-toggle="tab" role="tab">Monitoring Data Penjualan Agen</a>
					</li>
					<li class="nav-item">
						<a href="#datacanvassing" class="nav-link" data-target="#datacanvassing" aria-controls="datacanvassing" data-toggle="tab" role="tab">Kelola Data Canvassing</a>
					</li>
					<li class="nav-item">
						<a href="#datakonsinyasi" class="nav-link" data-target="#datakonsinyasi" aria-controls="datakonsinyasi" data-toggle="tab" role="tab">Kelola Data Konsinyasi </a>
					</li>
				</ul>
				<div class="tab-content">
					@include('marketing.marketingarea.orderproduk.index')
					@include('marketing.marketingarea.keloladataorder.index')
					@include('marketing.marketingarea.monitoring.index')
					@include('marketing.marketingarea.datacanvassing.index')
					@include('marketing.marketingarea.datakonsinyasi.index')
				</div>
			</div>
		</div>
	</section>
</article>

{{-- Modal Order Ke Cabang --}}
<div class="modal fade" id="modalOrderCabang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="cabang">Nama Cabang</label>
			      <input type="text" class="form-control bg-light" id="cabang" value="" readonly="" disabled="">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="nota">Nomer Nota</label>
			      <input type="text" class="form-control bg-light" id="nota" value="" readonly="" disabled="">
			    </div>
			  </div>
				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="agen">Nama Agen</label>
			      <input type="text" class="form-control bg-light" id="agen" value="" readonly="" disabled="">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="tanggal">Tanggal Order</label>
			      <input type="text" class="form-control bg-light" id="tanggal" value="" readonly="" disabled="">
			    </div>
			  </div>
			  <div class="table-responsive">
				  <table id="detailOrder" class="table table-sm table-hover table-bordered">
				  	<thead>
				  		<tr class="bg-primary text-light">
				  			<th>Nama Barang</th>
				  			<th>Satuan</th>
				  			<th>Qty</th>
				  			<th>Harga Satuan</th>
				  			<th>Total Harga</th>
				  		</tr>
				  	</thead>
				  	<tbody class="empty">
				  		
				  	</tbody>
				  </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('extra_script')
<script type="text/javascript">

	$(document).ready(function(){
		orderProdukList();
		var table_pus = $('#table_keloladataagen').DataTable();
		var table_bar = $('#table_monitoringpenjualanagen').DataTable();
		var table_rab = $('#table_canvassing').DataTable();
		var table_bro = $('#table_konsinyasi').DataTable();

		$(document).on('click','.btn-edit-order',function(){
			window.location.href='{{ route('keloladataorder.edit') }}'
		});

		$(document).on('click','.btn-edit-canv',function(){
			window.location.href='{{ route('datacanvassing.edit') }}'
		});

		$(document).on('click','.btn-edit-kons',function(){
			window.location.href='{{ route('datakonsinyasi.edit') }}'
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

		// order agen

		$(document).on('click', '.btn-disable-order', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-order" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-order', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-order" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-order" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		})

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

		// canvassing


		$(document).on('click', '.btn-disable-canv', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-canv" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

		$(document).on('click', '.btn-enable-canv', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			})
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-canv" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-canv" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		})

		// Konsinyasi


		$(document).on('click', '.btn-disable-kons', function(){
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
					        ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-kons" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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


		$(document).on('click', '.btn-enable-kons', function(){
			$.toast({
				heading: 'Information',
				text: 'Data Berhasil di Aktifkan.',
				bgColor: '#0984e3',
				textColor: 'white',
				loaderBg: '#fdcb6e',
				icon: 'info'
			});
			$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-kons" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
	                                		'<button class="btn btn-danger btn-disable-kons" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
		});
		
	});

	// Order Produk Ke Cabang -------------------------------
	function orderProdukList()
	{
    tb_order = $('#table_orderproduk').DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
          url: "{{ route('orderProduk.list') }}",
          type: "get",
          data: {
              "_token": "{{ csrf_token() }}"
          }
      },
      columns: [
          {data: 'po_date'},
          {data: 'po_nota'},
          {data: 'comp'},
          {data: 'agen'},
          {data: 'totalprice'},
          {data: 'action'}
      ],
      pageLength: 10,
      lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
	}

	function detailOrder(id)
	{
		$.ajax({
			url: "{{ url('/marketing/marketingarea/orderproduk/detail') }}"+"/"+id,
			type: "get",
			success:function(res) {
				$('#modalOrderCabang').modal('show');
				$('#cabang').val(res.data2.comp);
				$('#agen').val(res.data2.agen);
				$('#nota').val(res.data2.po_nota);
				$('#tanggal').val(res.data2.po_date);
        $('.empty').empty();
				$.each(res.data1, function(key, val){
					$('#detailOrder tbody').append('<tr>'+
																					'<td>'+val.barang+'</td>'+
																					'<td>'+val.unit+'</td>'+
																					'<td>'+val.qty+'</td>'+
																					'<td>'+val.price+'</td>'+
																					'<td>'+val.totalprice+'</td>'+
																				'</tr>');
				});				
			}
		});
	}

	function editOrder(id)
	{
		window.location.href='{{ url('/marketing/marketingarea/orderproduk/edit') }}'+"/"+id;
	}

	function printNota(id, dt)
	{
		var url = '{{ url('/marketing/marketingarea/orderproduk/nota') }}'+"/"+id+"/"+dt;
		window.open(url);
	}

	function deleteOrder(id, dt)
	{
		var hapus_order = "{{url('/marketing/marketingarea/orderproduk/delete-order')}}"+"/"+id+"/"+dt;
    $.confirm({
      animation: 'RotateY',
      closeAnimation: 'scale',
      animationBounce: 1.5,
      icon: 'fa fa-exclamation-triangle',
      title: 'Pesan!',
      content: 'Apakah anda yakin ingin menghapus data ini?',
      theme: 'disable',
      buttons: {
        info: {
          btnClass: 'btn-blue',
          text: 'Ya',
          action: function() {
            return $.ajax({
              type: "get",
              url: hapus_order,
              beforeSend: function() {
                loadingShow();
              },
              success: function(response) {
                if (response.status == 'sukses') {
                  loadingHide();
                  messageSuccess('Berhasil', 'Data berhasil dihapus!');
                  tb_order.ajax.reload();
                } else if (response.status == 'warning') {
                  loadingHide();
                  messageWarning('Peringatan', 'Data ini tidak boleh dihapus!');
                  tb_order.ajax.reload();
                } else {
                  loadingHide();
                  messageFailed('Gagal', response.message);
                }
              },
              error: function(e) {
                loadingHide();
                messageWarning('Peringatan', e.message);
              }
            });
          }
        },
        cancel: {
            text: 'Tidak',
            action: function(response) {
              loadingHide();
              messageWarning('Peringatan', 'Anda telah membatalkannya!');
            }
        }
      }
    });
	}
	// End Order Produk --------------------------------------------
</script>
<script type="text/javascript">

$(document).ready(function(){
	$(document).on('click', '.btn-accept', function(){
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
									text: 'Data Berhasil di Setujui.',
									bgColor: '#0984e3',
									textColor: 'white',
									loaderBg: '#fdcb6e',
									icon: 'info'
								})
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

	$(document).on('click', '.btn-reject', function(){
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
									text: 'Data Berhasil di Tolak.',
									bgColor: '#0984e3',
									textColor: 'white',
									loaderBg: '#fdcb6e',
									icon: 'info'
								})
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

	// Modal Kelola Data Order Agen
	$("#search-list-agen").on("click", function() {
			$(".table-modal").removeClass('d-none');
	});

});




</script>
@endsection
