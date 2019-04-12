@extends('main')
@section('extra_style')
<style type="text/css">
	.w-5 {
		width: 5% !important;
	}
	.w-10 {
		width: 10% !important;
	}
	.w-15 {
		width: 15% !important;
	}
	.w-35 {
		width: 35% !important;
	}
</style>
@endsection
@section('content')

@include('sdm.prosesrekruitmen.modal_calonkaryawan')

@include('sdm.prosesrekruitmen.kelolarekrutmen.modal_create')

@include('sdm.prosesrekruitmen.kelolarekrutmen.modal_edit')

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
				<ul class="nav nav-pills mb-3" id="Tabzs">
					<li class="nav-item">
						<a href="#list_rekruitmen" class="nav-link active" data-target="#list_rekruitmen" aria-controls="list_rekruitmen" data-toggle="tab" role="tab">Rekruitmen</a>
					</li>
					<li class="nav-item">
						<a href="#list_pelamarditerima" class="nav-link" data-target="#list_pelamarditerima" aria-controls="list_pelamarditerima" data-toggle="tab" role="tab">Daftar Pelamar Diterima</a>
					</li>
					<li class="nav-item">
						<a href="#kelola_rekruitment" class="nav-link" data-target="#kelola_rekruitment" aria-controls="kelola_rekruitment" data-toggle="tab" role="tab">Kelola Rekruitment</a>
					</li>
				</ul>
				<div class="tab-content">
					@include('sdm.prosesrekruitmen.rekrutmen.tab_rekruitmen')
					@include('sdm.prosesrekruitmen.pelamarditerima.tab_pelamarditerima')
					@include('sdm.prosesrekruitmen.kelolarekrutmen.kelola_rekruitment')
				</div>
			</div>
		</div>
	</section>
</article>

@endsection
@section('extra_script')
<script type="text/javascript">
	// set header token for ajax request
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	// Document Ready ------------------------------------------------------------------------------
	var tb_rekrutmen, tb_diterima, kelola_rekrutmen;
	$(document).ready(function(){

		var cur_date = new Date();
		$("#rekrut_from").datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth(), 1));
		$("#rekrut_to").datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth()+1, 0));
		$("#diterima_from").datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth(), 1));
		$("#diterima_to").datepicker("setDate", new Date(cur_date.getFullYear(), cur_date.getMonth()+1, 0));

		TableRekrutmen();
		TableDiterima();
		kelolaRekrutmen();
	});
	// End Document Ready --------------------------------------------------------------------------

	$(document).on('click', '.btn-accepted', function(){
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
							text: 'Data Berhasil di Terima.',
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
							text: 'Data Berhasil di Terima.',
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
	
	// Recruitment ---------------------------------------------------------------------------------
	function TableRekrutmen() {
		if ($.fn.DataTable.isDataTable("#table_rekrutmen")) {
    	$('#table_rekrutmen').dataTable().fnDestroy();
    }
		tb_rekrutmen = $('#table_rekrutmen').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ url('/sdm/prosesrekruitmen/listRecruitment') }}",
				type: "get",
				data: {
					"date_from": $('#rekrut_from').val(),
					"date_to"  : $('#rekrut_to').val(),
					"education": $('#education').val(),
					"status"   : $('#statusRec').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'tgl_apply'},
				{data: 'p_name'},
				{data: 'p_tlp'},
				{data: 'p_email'},
				{data: 'status'},
				{data: 'tanggal'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function detail(id) {
		window.location.href='{{url('/sdm/prosesrekruitmen/detail')}}'+'/'+id;
	}

	function proses(id) {
		window.location.href='{{url('/sdm/prosesrekruitmen/proses')}}'+'/'+id;
	}

	function deletePelamar(id) {
		var delete_ = "{{url('/sdm/prosesrekruitmen/delete-pelamar')}}"+"/"+id;
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
                        url: delete_,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                tb_rekrutmen.ajax.reload();
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
	// End Code -----------------------------------------------------------------------------------

	// Daftar Recruitment Diterima ----------------------------------------------------------------
	function TableDiterima() {
		if ($.fn.DataTable.isDataTable("#table_diterima")) {
    	$('#table_diterima').dataTable().fnDestroy();
    }
		tb_diterima = $('#table_diterima').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ url('/sdm/prosesrekruitmen/listTerima') }}",
				type: "get",
				data: {
					"date_from": $('#diterima_from').val(),
					"date_to": $('#diterima_to').val(),
					"education": $('#terima_edu').val(),
					"position": $('#terima_position').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'tgl_apply'},
				{data: 'p_name'},
				{data: 'p_tlp'},
				{data: 'p_email'},
				{data: 'status'},
				{data: 'approval'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// End Code -----------------------------------------------------------------------------------

	// Kelola Data Recruitment --------------------------------------------------------------------
	function kelolaRekrutmen() {
		if ($.fn.DataTable.isDataTable("#kelola_rekrutmen")) {
    	$('#kelola_rekrutmen').dataTable().fnDestroy();
    }
		kelola_rekrutmen = $('#kelola_rekrutmen').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ url('/sdm/prosesrekruitmen/listLoker') }}",
				type: "get"
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'j_name'},
				{data: 'start'},
				{data: 'end'},
				{data: 'status'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}

	function simpanLoker() {
		$.ajax({
			url: "{{url('/sdm/prosesrekruitmen/simpanLoker')}}",
			type: "get",
			data: $('#simpanLoker').serialize(),
      beforeSend: function () {
          loadingShow();
      },
      success: function (response) {
        if (response.status == 'sukses') {
            loadingHide();
            messageSuccess('Success', 'Data berhasil ditambahkan!');
            kelola_rekrutmen.ajax.reload();
        } else {
            loadingHide();
            messageFailed('Gagal', response.message);
        }
      },
      error: function (e) {
          loadingHide();
          messageWarning('Peringatan', e.message);
      }
		});
	}

	function activateLoker(id) {
		var active_loker = "{{url('/sdm/prosesrekruitment/activateLoker/')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin aktifkan data ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: active_loker,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Pengaktifan berhasil!');
                                kelola_rekrutmen.ajax.reload();
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

	function nonLoker(id) {
		var non_loker = "{{url('/sdm/prosesrekruitment/nonLoker/')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin Nonaktifkan data ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: non_loker,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Data berhasil dinonaktifkan!');
                                kelola_rekrutmen.ajax.reload();
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

	function deleteLoker(id) {
		var non_loker = "{{url('/sdm/prosesrekruitment/deleteLoker/')}}"+"/"+id;
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
                        url: non_loker,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                kelola_rekrutmen.ajax.reload();
                            } else if (response.status == 'warning') {
                                loadingHide();
                                messageWarning('Peringatan', 'Data ini masih aktif!');
                                kelola_rekrutmen.ajax.reload();
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

	function editLoker(id) {
		var modal_edit = "{{url('/sdm/prosesrekruitment/editLoker/')}}"+"/"+id;
		$.ajax({
			url: modal_edit,
			type: "get",
			success:function(res) {
				$('#editLoker').modal('show');
				$('#id_loker').val(res.data1.a_id);
				$('#start_date_edit').val(res.data1.start_date);
				$('#end_date_edit').val(res.data1.end_date);
				$('#position_edit').find('option').remove();
				$('#position_edit').append('<option value="" disabled>=== Pilih Posisi/Jabatan ===</option>'+
					'<option value="'+res.data1.a_position+'" selected>'+res.data1.j_name+'</option>');
				$.each(res.data2, function(key, val){
					$('#position_edit').append('<option value="'+val.j_id+'">'+val.j_name+'</option>');
				});
			}
		})
	}

	function updateLoker() {
		$.ajax({
			url: "{{url('/sdm/prosesrekruitment/updateLoker')}}",
			type: "get",
			data: $('#formEdit').serialize(),
      beforeSend: function () {
          loadingShow();
      },
      success: function (response) {
        if (response.status == 'sukses') {
        		$('#editLoker').modal('hide');
            loadingHide();
            messageSuccess('Success', 'Data berhasil diperbarui!');
            kelola_rekrutmen.ajax.reload();
        } else {
            loadingHide();
            messageFailed('Gagal', response.message);
        }
      },
      error: function (e) {
          loadingHide();
          messageWarning('Peringatan', e.message);
      }
		});
	}
	// End Code ----------------------------------------------------------------------------------

	$("#diterima_from").on('change', function() {
		TableDiterima();
	});
	$("#diterima_to").on('change', function() {
		TableDiterima();
	});
</script>
@endsection
