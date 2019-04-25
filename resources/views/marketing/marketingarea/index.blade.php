@extends('main')

@section('content')

@include('marketing.marketingarea.keloladataorder.modal')
@include('marketing.marketingarea.keloladataorder.modal-search')
@include('marketing.marketingarea.monitoring.modal-detail')
@include('marketing.marketingarea.monitoring.modal-search')
@include('marketing.marketingarea.datacanvassing.modal-create')
@include('marketing.marketingarea.datacanvassing.modal-edit')
@include('marketing.marketingarea.datacanvassing.modal-search')

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
						<a href="#keloladataagen" class="nav-link" data-target="#keloladataagen" aria-controls="keloladataagen" data-toggle="tab" role="tab" onclick="kelolaDataAgen()">Kelola Data Order Agen </a>
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
				<h5 class="modal-title" id="exampleModalLabel">Detail Order Ke Cabang</h5>
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
{{-- Modal Kelola Data Agen --}}
<div class="modal fade" id="modalOrderAgen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Detail Kelola Data Agen</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="cabang">Nama Cabang</label>
			      <input type="text" class="form-control bg-light" id="cabang2" value="" readonly="" disabled="">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="nota">Nomer Nota</label>
			      <input type="text" class="form-control bg-light" id="nota2" value="" readonly="" disabled="">
			    </div>
			  </div>
				<div class="form-row">
			    <div class="form-group col-md-6">
			      <label for="agen">Nama Agen</label>
			      <input type="text" class="form-control bg-light" id="agen2" value="" readonly="" disabled="">
			    </div>
			    <div class="form-group col-md-6">
			      <label for="tanggal">Tanggal Order</label>
			      <input type="text" class="form-control bg-light" id="tanggal2" value="" readonly="" disabled="">
			    </div>
			  </div>
			  <div class="table-responsive">
				  <table id="detailAgen" class="table table-sm table-hover table-bordered">
				  	<thead>
				  		<tr class="bg-primary text-light">
				  			<th>Nama Barang</th>
				  			<th>Satuan</th>
				  			<th>Qty</th>
				  			<th>Harga Satuan</th>
				  			<th>Total Harga</th>
				  		</tr>
				  	</thead>
				  	<tbody class="emptyAgen">

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
	var table_agen, table_search, table_bar, table_rab, table_bro;

  var idAgen    = [];
  var namaAgen  = null;
  var kode      = null;
  var icode     = [];
	$(document).ready(function() {
	    orderProdukList();
			table_search = $('#table_search_agen').DataTable();
			table_bar    = $('#table_monitoringpenjualanagen').DataTable();
			table_rab    = $('#table_canvassing').DataTable();
			table_bro    = $('#table_konsinyasi').DataTable();
	    // Code Dummy --------------------------------------------------
	    $(document).on('click', '.btn-edit-kons', function() {
	        window.location.href = '{{ route('datakonsinyasi.edit') }}';
	    });

	    $(document).on('click', '.btn-disable', function() {
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
	                    text: 'Ya',
	                    action: function() {
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
	                cancel: {
	                    text: 'Tidak',
	                    action: function() {
	                        // tutup confirm
	                    }
	                }
	            }
	        });
	    });

	    $(document).on('click', '.btn-enable', function() {
	        $.toast({
	            heading: 'Information',
	            text: 'Data Berhasil di Aktifkan.',
	            bgColor: '#0984e3',
	            textColor: 'white',
	            loaderBg: '#fdcb6e',
	            icon: 'info'
	        })
	        $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
	            '<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
	    })

	    $(document).ready(function() {
	        $('#detail-monitoring').DataTable({
	            "iDisplayLength": 5
	        });
	    });

	    // canvassing
	    // $(document).on('click', '.btn-disable-canv', function() {
	    //     var ini = $(this);
	    //     $.confirm({
	    //         animation: 'RotateY',
	    //         closeAnimation: 'scale',
	    //         animationBounce: 1.5,
	    //         icon: 'fa fa-exclamation-triangle',
	    //         title: 'Peringatan!',
	    //         content: 'Apa anda yakin mau menonaktifkan data ini?',
	    //         theme: 'disable',
	    //         buttons: {
	    //             info: {
	    //                 btnClass: 'btn-blue',
	    //                 text: 'Ya',
	    //                 action: function() {
	    //                     $.toast({
	    //                         heading: 'Information',
	    //                         text: 'Data Berhasil di Nonaktifkan.',
	    //                         bgColor: '#0984e3',
	    //                         textColor: 'white',
	    //                         loaderBg: '#fdcb6e',
	    //                         icon: 'info'
	    //                     })
	    //                     ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-canv" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
	    //                 }
	    //             },
	    //             cancel: {
	    //                 text: 'Tidak',
	    //                 action: function() {
	    //                     // tutup confirm
	    //                 }
	    //             }
	    //         }
	    //     });
	    // });
		//
	    // $(document).on('click', '.btn-enable-canv', function() {
	    //     $.toast({
	    //         heading: 'Information',
	    //         text: 'Data Berhasil di Aktifkan.',
	    //         bgColor: '#0984e3',
	    //         textColor: 'white',
	    //         loaderBg: '#fdcb6e',
	    //         icon: 'info'
	    //     })
	    //     $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-canv" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
	    //         '<button class="btn btn-danger btn-disable-canv" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
	    // })

	    // Konsinyasi
	    $(document).on('click', '.btn-disable-kons', function() {
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
	                    text: 'Ya',
	                    action: function() {
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
	                cancel: {
	                    text: 'Tidak',
	                    action: function() {
	                        // tutup confirm
	                    }
	                }
	            }
	        });
	    });

	    $(document).on('click', '.btn-enable-kons', function() {
	        $.toast({
	            heading: 'Information',
	            text: 'Data Berhasil di Aktifkan.',
	            bgColor: '#0984e3',
	            textColor: 'white',
	            loaderBg: '#fdcb6e',
	            icon: 'info'
	        });
	        $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit-kons" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
	            '<button class="btn btn-danger btn-disable-kons" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
	    });
	    // End Code Dummy -----------------------------------------------

	  $('.agen').on('click change', function () {
	      setArrayAgen();
	  });

	  $(".agen").on("keyup", function () {
	      $(".agenId").val('');
	      $(".codeAgen").val('');
	  });
	});
	// End Document Ready -------------------------------------------

	// Order Produk Ke Cabang -------------------------------
	function orderProdukList() {
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

	function detailOrder(id) {
		$.ajax({
			url: "{{ url('/marketing/marketingarea/orderproduk/detail') }}"+"/"+id,
			type: "get",
      beforeSend: function () {
          loadingShow();
      },
			success:function(res) {
        loadingHide();
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

	function editOrder(id) {
		window.location.href='{{ url('/marketing/marketingarea/orderproduk/edit') }}'+"/"+id;
	}

	function printNota(id, dt) {
		var url = '{{ url('/marketing/marketingarea/orderproduk/nota') }}'+"/"+id+"/"+dt;
		window.open(url);
	}

	function deleteOrder(id, dt) {
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

	// Kelola Data Order Agen --------------------------------------
	function kelolaDataAgen() {
		var st = $("#status").val();

		$('#table_dataAgen').DataTable().clear().destroy();
    table_agen = $('#table_dataAgen').DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
          url: "{{ url('/marketing/marketingarea/keloladataorder/list-agen') }}"+"/"+st,
          type: "get",
          data: {
              "_token": "{{ csrf_token() }}"
          }
      },
      columns: [
          {data: 'po_date'},
          {data: 'po_nota'},
          {data: 'agen'},
          {data: 'totalprice'},
          {data: 'action_agen'}
      ],
      pageLength: 10,
      lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
	}

  function getProvId() {
    var id = document.getElementById("prov_agen").value;
    $.ajax({
        url: "{{route('orderProduk.getCity')}}",
        type: "get",
        data:{
            provId: id
        },
        beforeSend: function() {
            loadingShow();
        },
        success: function (response) {
          loadingHide();
          $('#city_agen').empty();
          $("#city_agen").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
          $.each(response.data, function( key, val ) {
              $("#city_agen").append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
          });
          $('#city_agen').focus();
          $('#city_agen').select2('open');
        }
    });
  }

  // Autocomplete Data Agen -------------------------------------------
  function setArrayAgen() {
    var inputs = document.getElementsByClassName('codeAgen'),
        code   = [].map.call(inputs, function (input) {
            return input.value.toString();
        });

    for (var i = 0; i < code.length; i++) {
        if (code[i] != "") {
            icode.push(code[i]);
        }
    }

    var agen = [];
    var inpAgenId = document.getElementsByClassName('agenId'),
        agen      = [].map.call(inpAgenId, function (input) {
            return input.value;
        });

    $(".agen").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ url('/marketing/marketingarea/keloladataorder/cari-agen') }}",
                data: {
                    idAgen: agen,
                    term: $(".agen").val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: function (event, data) {
            setAgen(data.item);
        }
    });
  }

  function setAgen(info) {
    idAgen = info.data.c_id;
    namaAgen = info.data.a_name;
    kode = info.data.a_code;
    $(".codeAgen").val(kode);
    $(".agenId").val(idAgen);
    setArrayAgen();
  }
  // End Autocomplete -----------------------------------------------------

	// Modal Kelola Data Order Agen -----------------------------------------
	function getAgen() {
    loadingShow();
		getDataAgen();
	}

	function getDataAgen() {
		loadingHide();
		$(".table-modal").removeClass('d-none');
		$('#table_search_agen').DataTable().clear().destroy();
    table_agen = $('#table_search_agen').DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
          url: "{{ url('/marketing/marketingarea/keloladataorder/get-agen') }}",
          type: "get",
          data: {
              "_token": "{{ csrf_token() }}",
              id : $('#city_agen').val()
          }
      },
      columns: [
          {data: 'wp_name'},
          {data: 'wc_name'},
          {data: 'a_name'},
          {data: 'a_type'},
          {data: 'action_agen'}
      ],
      pageLength: 10,
      lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
	}

	function chooseAgen(id, name, code) {
		$('#searchAgen').modal('hide');
		loadingShow();
		$('.agenId').val(id);
		loadingHide();
		$('.agen').val(name);
		$('.codeAgen').val(code);
	}

	function filterAgen() {
		var start  = $('#start_date').val();
		var end    = $('#end_date').val();
		var status = $('#status').val();
		var agen   = $('.agenId').val();

		$('#table_dataAgen').DataTable().clear().destroy();
    table_agen = $('#table_dataAgen').DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
        url: "{{ url('/marketing/marketingarea/keloladataorder/filter-agen') }}",
        type: "get",
        data: {
            start_date: start,
            end_date  : end,
            state: status,
            agen : agen
        },
      },
      columns: [
        {data: 'date'},
        {data: 'po_nota'},
        {data: 'c_name'},
        {data: 'total_price'},
        {data: 'action_agen'}
      ],
      pageLength: 10,
      lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
	}

	function detailAgen(id) {
		$.ajax({
			url: "{{ url('/marketing/marketingarea/keloladataorder/detail-agen') }}"+"/"+id,
			type: "get",
      beforeSend: function() {
          loadingShow();
      },
			success:function(res) {
				loadingHide();
				$('#modalOrderAgen').modal('show');
				$('#cabang2').val(res.agen2.comp);
				$('#agen2').val(res.agen2.agen);
				$('#nota2').val(res.agen2.po_nota);
				$('#tanggal2').val(res.agen2.po_date);
        $('.emptyAgen').empty();
				$.each(res.agen1, function(key, val){
					$('#detailAgen tbody').append('<tr>'+
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

	function rejectAgen(id) {
		var reject_agen = "{{url('/marketing/marketingarea/keloladataorder/reject-agen')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin menolak agen ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: reject_agen,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                        	//var table_agen = $('#table_dataAgen').DataTable();
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Penolakan berhasil!');
                                table_agen.ajax.reload();
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
                    messageWarning('Peringatan', 'Anda telah membatalkan!');
                }
            }
        }
    });
	}

	function activateAgen(id) {
		var aktif_agen = "{{url('/marketing/marketingarea/keloladataorder/activate-agen')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin mengaktifkan agen ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: aktif_agen,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Agen berhasil diaktifkan!');
                                table_agen.ajax.reload();
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
                    messageWarning('Peringatan', 'Anda telah membatalkan!');
                }
            }
        }
    });
	}

	function approveAgen(id) {
		var approve_agen = "{{url('/marketing/marketingarea/keloladataorder/approve-agen')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin approve agen ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: approve_agen,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Agen berhasil diapprove!');
                                table_agen.ajax.reload();
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
                    messageWarning('Peringatan', 'Anda telah membatalkan!');
                }
            }
        }
    });
	}

	function rejectApproveAgen(id) {
		var reject_approve_agen = "{{url('/marketing/marketingarea/keloladataorder/reject-approve-agen')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin approve agen ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: reject_approve_agen,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Approve berhasil dibatalkan!');
                                table_agen.ajax.reload();
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
                    messageWarning('Peringatan', 'Anda telah membatalkan!');
                }
            }
        }
    });
	}
	// End Data Order Agen -----------------------------------------
</script>
<!-- ========================================================================-->
<script type="text/javascript">

$(document).ready(function() {
  $(document).on('click', '.btn-accept', function() {
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
                  text: 'Ya',
                  action: function() {
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
              cancel: {
                  text: 'Tidak',
                  action: function() {
                      // tutup confirm
                  }
              }
          }
      });
  });

  $(document).on('click', '.btn-reject', function() {
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
                  text: 'Ya',
                  action: function() {
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
              cancel: {
                  text: 'Tidak',
                  action: function() {
                      // tutup confirm
                  }
              }
          }
      });
  });

});

</script>

<!-- ========================================================================-->
<!-- script for Data-Monitoring-Penjualan-Agen -->
<script type="text/javascript">
	$(document).ready(function() {
		const cur_date = new Date();
		const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
		const last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		$('#date_from_mpa').datepicker('setDate', first_day);
		$('#date_to_mpa').datepicker('setDate', last_day);

		$('#date_from_mpa').on('change', function() {
			TableListMPA();
		});
		$('#date_to_mpa').on('change', function() {
			TableListMPA();
		});
		$('#btn_search_date_mpa').on('click', function() {
			TableListMPA();
		});
		$('#btn_refresh_date_mpa').on('click', function() {
			$('#filter_agent_code_mpa').val('');
			$('#filter_agent_name_mpa').val('');
			$('#date_from_mpa').datepicker('setDate', first_day);
			$('#date_to_mpa').datepicker('setDate', last_day);
		});
		// retrieve data-table
		TableListMPA();
		// filter agent based on area (province and city)
		$('.provMPA').on('change', function() {
			getCitiesMPA();
		});
		$('.citiesMPA').on('change', function(){
			$(".table-modal").removeClass('d-none');
			appendListAgentsMPA();
		});
		// filter agent field
		$('#filter_agent_name_mpa').on('click', function() {
			$(this).val('');
		});
		$('#filter_agent_name_mpa').on('keyup', function() {
			findAgentsByAuMPA();
		});
		// btn applyt filter agent
		$('#btn_filter_mpa').on('click', function() {
			TableListMPA();
		});
	});

	// data-table -> function to retrieve DataTable server side
	var tb_listmpa;
	function TableListMPA()
	{
		$('#table_monitoringpenjualanagen').dataTable().fnDestroy();
		tb_listmpa = $('#table_monitoringpenjualanagen').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('manajemenpenjualanagen.getListMPA') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}",
					"date_from": $('#date_from_mpa').val(),
					"date_to": $('#date_to_mpa').val(),
					"agent_code": $('#filter_agent_code_mpa').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'name'},
				{data: 'date'},
				{data: 's_nota'},
				{data: 'total'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// autocomple to find-agents
	function findAgentsByAuMPA()
	{
	    $('#filter_agent_name_mpa').autocomplete({
	        source: function( request, response ) {
	            $.ajax({
	                url: baseUrl + '/marketing/marketingarea/datacanvassing/find-agents-by-au',
	                data: {
						"termToFind": $("#filter_agent_name_mpa").val()
					},
	                dataType: 'json',
	                success: function( data ) {
	                    response( data );
	                }
	            });
	        },
	        minLength: 1,
	        select: function(event, data) {
				$('#filter_agent_code_mpa').val(data.item.agent_code);
	        }
	    });
	}
	// this following func is using same source with Data-Canvassing
	// get cities for search-agent
	function getCitiesMPA()
	{
		var provId = $('.provMPA').val();
		$.ajax({
			url: "{{ route('datacanvassing.getCitiesDC') }}",
			type: "get",
			data:{
				provId: provId
			},
			success: function (response) {
				$('.citiesMPA').empty();
				$(".citiesMPA").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
				$.each(response.get_cities, function( key, val ) {
					$(".citiesMPA").append('<option value="'+ val.wc_id +'">'+ val.wc_name +'</option>');
				});
				$('.citiesMPA').focus();
				$('.citiesMPA').select2('open');
			}
		});
	}
	// this following func is using same source with Data-Canvassing
	// append data to table-list-agens
	function appendListAgentsMPA()
	{
		$.ajax({
			url: "{{ route('datacanvassing.getAgentsDC') }}",
			type: 'get',
			data: {
				cityId: $('.citiesMPA').val()
			},
			success: function(response) {
				$('#table_search_mpa tbody').empty();
				if (response.length <= 0) {
					return 0;
				}
				$.each(response, function(index, val) {
					listAgents = '<tr><td>'+ val.get_province.wp_name +'</td>';
					listAgents += '<td>'+ val.get_city.wc_name +'</td>';
					listAgents += '<td>'+ val.a_name +'</td>';
					listAgents += '<td>'+ val.a_type +'</td>';
					listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgentMPA(\''+ val.a_code +'\',\''+ val.a_name +'\')"><i class="fa fa-download"></i></button></td></tr>';
				});
				$('#table_search_mpa > tbody:last-child').append(listAgents);
			}
		});
	}
	// add filter-agent
	function addFilterAgentMPA(agentCode, agentName)
	{
		$('#filter_agent_name_mpa').val(agentCode+ ' - ' +agentName);
		$('#filter_agent_code_mpa').val(agentCode);
		$('#modalSearchAgentMPA').modal('hide');
	}
	// show modal-detail MPA
	function detailMPA(id)
	{
		loadingShow();
		$.ajax({
			url: baseUrl + '/marketing/marketingarea/manajemenpenjualanagen/get-detail/' + id,
			type: 'get',
			success: function(response) {
				$('#nota_dtmpa').val(response.detail.s_nota);
				let newDate = getFormattedDate(response.detail.s_date);
				$('#date_dtmpa').val(newDate);
				if (response.detail.get_user.employee !== null) {
					$('#agent_dtmpa').val(response.detail.get_user.employee.e_name);
				} else if (response.detail.get_user.agen !== null) {
					$('#agent_dtmpa').val(response.detail.get_user.agen.a_name);
				} else {
					$('#agent_dtmpa').val('( Agen tidak ditemukan ! )');
				}
				$('#total_dtmpa').val(parseInt(response.detail.s_total));

				// append sales-dt to table list-items
				$('#table_detailmpa tbody').empty();
				$.each(response.detail.get_sales_dt, function(index, val) {
					let idx = '<td>'+ (index + 1) +'</td>';
					let itemName = '<td>'+ val.get_item.i_name +'</td>';
					let itemQty = '<td class="digits">'+ response.listQty[index] +'</td>';
					let itemPrice = '<td class="rupiah">'+ parseInt(val.sd_value) +'</td>';
					let itemSubTotal = '<td class="rupiah">'+ parseInt(val.sd_totalnet) +'</td>';
					$('#table_detailmpa > tbody:last-child').append('<tr>'+ idx + itemName + itemQty + itemPrice + itemSubTotal +'</tr>');
				});
				// re-activate mask-money
				$('.rupiah').inputmask("currency", {
					radixPoint: ",",
					groupSeparator: ".",
					digits: 2,
					autoGroup: true,
					prefix: ' Rp ', //Space after $, this will not truncate the first character.
					rightAlign: true,
					autoUnmask: true,
					nullable: false,
					// unmaskAsNumber: true,
				});
				// re-activate mask-digits
				$('.digits').inputmask("currency", {
					radixPoint: ",",
					groupSeparator: ".",
					digits: 0,
					autoGroup: true,
					prefix: '', //Space after $, this will not truncate the first character.
					rightAlign: true,
					autoUnmask: true,
					nullable: false,
					// unmaskAsNumber: true,
				});

				// show detal modal
				loadingHide();
				$('#modalDetailMPA').modal('show');
			},
			error: function(e) {
				messageWarning('Perhatian', 'Terjadi kesalahan, hubungi pengembang !');
			}
		});
	}
	// change date formate
	function getFormattedDate(str) {
		const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
		"Juli", "Agustus", "September", "Oktober", "November", "Desember"];

		var myDate = new Date(str);
		var month = myDate.getMonth();
		var day = myDate.getDate();
		var year = myDate.getFullYear();
		return day + " " + monthNames[month] + " " + year;
	}
</script>

<!-- ========================================================================-->
<!-- script for public function -->
<script type="text/javascript">
	$(document).ready(function() {
		if ($('.current_user_type').val() !== 'E' ) {
			$('.filter_agent').addClass('d-none');
		} else {
			$('.filter_agent').removeClass('d-none');
		}
	});
</script>

<!-- ========================================================================-->
<!-- script for Data-Canvassing -->
<script type="text/javascript">
	$(document).ready(function() {
		const cur_date = new Date();
		const first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
		const last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
		$('#date_from_dc').datepicker('setDate', first_day);
		$('#date_to_dc').datepicker('setDate', last_day);

		$('#date_from_dc').on('change', function() {
			TableListDC();
		});
		$('#date_to_dc').on('change', function() {
			TableListDC();
		});
		$('#btn_search_date_dc').on('click', function() {
			TableListDC();
		});
		$('#btn_refresh_date_dc').on('click', function() {
			$('#filter_agent_code_dc').val('');
			$('#filter_agent_name_dc').val('');
			$('#date_from_dc').datepicker('setDate', first_day);
			$('#date_to_dc').datepicker('setDate', last_day);
		});
		// retrieve data-table
		TableListDC();
		// filter agent based on area (province and city)
		$('.provDC').on('change', function() {
			getCitiesDC();
		});
		$('.citiesDC').on('change', function(){
			$(".table-modal").removeClass('d-none');
			appendListAgentsDC();
		});
		// filter agent field
		$('#filter_agent_name_dc').on('click', function() {
			$(this).val('');
		});
		$('#filter_agent_name_dc').on('keyup', function() {
			findAgentsByAu();
		});
		// btn applyt filter agent
		$('#btn_filter_dc').on('click', function() {
			TableListDC();
		});
		// modal add-canvassing
		$('#modalAddCanvassing').on('shown.bs.modal', function() {
			$('#btn_simpan_addcanvassing').one('click', function() {
				submitAddCanvassing();
			});
		});
	    $('#modalAddCanvassing').on('hidden.bs.modal', function() {
			resetAddCanvassing();
	    });
	});

	// data-table -> function to retrieve DataTable server side
	var tb_liscanvas;
	function TableListDC()
	{
		$('#table_canvassing').dataTable().fnDestroy();
		tb_liscanvas = $('#table_canvassing').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ route('datacanvassing.getListDC') }}",
				type: "get",
				data: {
					"_token": "{{ csrf_token() }}",
					"date_from": $('#date_from_dc').val(),
					"date_to": $('#date_to_dc').val(),
					"agent_code": $('#filter_agent_code_dc').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'c_name'},
				{data: 'c_email'},
				{data: 'c_tlp'},
				{data: 'c_address'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// submit-form add-new-canvassing
	function submitAddCanvassing()
	{
	    myForm = $('#formAddCanvassing').serialize();

	    $.ajax({
	        data : myForm,
	        type : "post",
	        url : baseUrl + '/marketing/marketingarea/datacanvassing/store',
	        dataType : 'json',
	        success : function (response){
	            if(response.status == 'berhasil')
	            {
	                messageSuccess('Berhasil', 'Data Canvassing berhasil ditambahkan !');
					resetAddCanvassing();
					$('#modalAddCanvassing').modal('hide');
					tb_liscanvas.ajax.reload();
	            }
	            else if (response.status == 'invalid')
	            {
	                messageFailed('Perhatian', response.message);
	            }
	            else if (response.status == 'gagal')
	            {
	                messageWarning('Error', response.message);
	            }
	            // activate btn_simpan once again
				$('#btn_simpan_addcanvassing').one('click', function() {
					submitAddCanvassing();
				});
	        },
	        error : function(e){
	            messageWarning('Gagal', 'Data gagal ditambahkan, hubungi pengembang !');
	            // activate btn_simpan once again
				$('#btn_simpan_addcanvassing').one('click', function() {
					submitAddCanvassing();
				});
	        }
	    });
	}
	// show modal edit
	function editDataCanvassing(id)
	{
		$.ajax({
	        type : "get",
	        url : baseUrl + '/marketing/marketingarea/datacanvassing/edit/' + id,
	        dataType : 'json',
	        success : function (response){
				$('#name_editdc').val(response.c_name);
				$('#email_editdc').val(response.c_email);
				$('#telp_editdc').val(response.c_tlp);
				$('#address_editdc').val(response.c_address);
				$('#note_editdc').val(response.c_note);
				$('#btn_simpan_editcanvassing').attr('onclick', 'submitEditCanvassing('+ id +')')
				$('#modalEditCanvassing').modal('show');
	        },
	        error : function(e){
	            messageWarning('Gagal', 'Gagal mendapatkan data, hubungi pengembang !');
	        }
		})
	}
	// submit-form edit-canvassing
	function submitEditCanvassing(id)
	{
	    myForm = $('#formEditCanvassing').serialize();

	    $.ajax({
	        data : myForm,
	        type : "post",
	        url : baseUrl + '/marketing/marketingarea/datacanvassing/update/' + id,
	        dataType : 'json',
	        success : function (response){
	            if(response.status == 'berhasil')
	            {
	                messageSuccess('Berhasil', 'Data Canvassing berhasil diperbarui !');
					tb_liscanvas.ajax.reload();
	            }
	            else if (response.status == 'invalid')
	            {
	                messageFailed('Perhatian', response.message);
	            }
	            else if (response.status == 'gagal')
	            {
	                messageWarning('Error', response.message);
	            }
	        },
	        error : function(e){
	            messageWarning('Gagal', 'Data gagal diperbarui, hubungi pengembang !');
	        }
	    });
	}
	// delete canvassing
	function deleteDataCanvassing(id)
	{
		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 2.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apakah anda yakin ingin menghapus data ini?',
			theme: 'disable',
			buttons: {
				info: {
					btnClass: 'btn-blue',
					text: 'Ya',
					action: function () {
						return $.ajax({
							type: "post",
							url: baseUrl + '/marketing/marketingarea/datacanvassing/delete/' + id,
							success: function (response) {
								if (response.status == 'berhasil') {
									messageSuccess('Berhasil', 'Data berhasil hapus !');
									tb_liscanvas.ajax.reload();
								} else {
									messageWarning('Gagal', 'Data gagal dihapus !');
								}
							},
							error: function (e) {
								messageFailed('Peringatan', e.message);
							}
						});
					}
				},
				cancel: {
					text: 'Tidak',
					action: function () {
						// tutup confirm
					}
				}
			}
		});
	}
	// resel modat-add-canavassing
	function resetAddCanvassing()
	{
		$('#formAddCanvassing')[0].reset();
	}
	// autocomple to find-agents
	function findAgentsByAu()
	{
	    $('#filter_agent_name_dc').autocomplete({
	        source: function( request, response ) {
	            $.ajax({
	                url: baseUrl + '/marketing/marketingarea/datacanvassing/find-agents-by-au',
	                data: {
						"termToFind": $("#filter_agent_name_dc").val()
					},
	                dataType: 'json',
	                success: function( data ) {
	                    response( data );
	                }
	            });
	        },
	        minLength: 1,
	        select: function(event, data) {
				$('#filter_agent_code_dc').val(data.item.agent_code);
	        }
	    });
	}
	// get cities for search-agent
	function getCitiesDC()
	{
		var provId = $('.provDC').val();
		$.ajax({
			url: "{{ route('datacanvassing.getCitiesDC') }}",
			type: "get",
			data:{
				provId: provId
			},
			success: function (response) {
				$('.citiesDC').empty();
				$(".citiesDC").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
				$.each(response.get_cities, function( key, val ) {
					$(".citiesDC").append('<option value="'+ val.wc_id +'">'+ val.wc_name +'</option>');
				});
				$('.citiesDC').focus();
				$('.citiesDC').select2('open');
			}
		});
	}
	// append data to table-list-agens
	function appendListAgentsDC()
	{
		$.ajax({
			url: "{{ route('datacanvassing.getAgentsDC') }}",
			type: 'get',
			data: {
				cityId: $('.citiesDC').val()
			},
			success: function(response) {
				$('#table_search_dc tbody').empty();
				if (response.length <= 0) {
					return 0;
				}
				$.each(response, function(index, val) {
					listAgents = '<tr><td>'+ val.get_province.wp_name +'</td>';
					listAgents += '<td>'+ val.get_city.wc_name +'</td>';
					listAgents += '<td>'+ val.a_name +'</td>';
					listAgents += '<td>'+ val.a_type +'</td>';
					listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgent(\''+ val.a_code +'\',\''+ val.a_name +'\')"><i class="fa fa-download"></i></button></td></tr>';
				});
				$('#table_search_dc > tbody:last-child').append(listAgents);
			}
		});
	}
	// add filter-agent
	function addFilterAgent(agentCode, agentName)
	{
		$('#filter_agent_name_dc').val(agentCode+ ' - ' +agentName);
		$('#filter_agent_code_dc').val(agentCode);
		$('#modalSearchAgentDC').modal('hide');
	}

</script>
@endsection
