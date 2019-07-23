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
	.fa-disabled {
		opacity: 0.6;
		cursor: not-allowed;
	}
</style>
@endsection
@section('content')

@include('sdm.prosesrekruitmen.modal_calonkaryawan')

@include('sdm.prosesrekruitmen.kelolarekrutmen.modal_create')

@include('sdm.prosesrekruitmen.kelolarekrutmen.modal_edit')

@include('sdm.prosesrekruitmen.penggajuansdm.modal_create')

@include('sdm.prosesrekruitmen.penggajuansdm.modal_edit')


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
                        <a href="#penggajuan_sdms" class="nav-link" data-target="#penggajuan_sdms" aria-controls="penggajuan_sdms" data-toggle="tab" role="tab">Pengajuan SDM</a>
                    </li>
					<li class="nav-item">
						<a href="#kelola_rekruitment" class="nav-link" data-target="#kelola_rekruitment" aria-controls="kelola_rekruitment" data-toggle="tab" role="tab">Publikasi Rekruitment</a>
					</li>
					<li class="nav-item">
						<a href="#manage_position_sdm" class="nav-link" data-target="#manage_position_sdm" aria-controls="manage_position_sdm" data-toggle="tab" role="tab">Kelola Posisi SDM</a>
					</li>
				</ul>
				<div class="tab-content">
					@include('sdm.prosesrekruitmen.rekrutmen.tab_rekruitmen')
					@include('sdm.prosesrekruitmen.pelamarditerima.tab_pelamarditerima')
                    @include('sdm.prosesrekruitmen.penggajuansdm.index')
					@include('sdm.prosesrekruitmen.kelolarekrutmen.kelola_rekruitment')
					@include('sdm.prosesrekruitmen.kelolaposisisdm.index')
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
		publishRekrutmen();
        PengajuanSdm();
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

    // Penggajuan SDM -----------------------------------------------------------------------------
    function PengajuanSdm() {
        if ($.fn.DataTable.isDataTable("#penggajuan_sdm")) {
            $('#penggajuan_sdm').dataTable().fnDestroy();
        }
        penggajuan_sdm = $('#penggajuan_sdm').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/sdm/prosesrekruitmen/listPengajuan') }}",
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'ss_reff'},
                {data: 'tanggal'},
                {data: 'm_name'},
                {data: 'j_name'},
                {data: 'ss_qtyneed'},
                {data: 'status'},
                {data: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function simpanPengajuan() {
        $.ajax({
            url: "{{url('/sdm/prosesrekruitmen/simpanPengajuan')}}",
            type: "get",
            data: $('#simpanPengajuan').serialize(),
            beforeSend: function () {
                loadingShow();
            },
            success: function (response) {
                if (response.status == 'sukses') {
                    loadingHide();
                    messageSuccess('Success', 'Data berhasil ditambahkan!');
                    penggajuan_sdm.ajax.reload();
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

    function activatePengajuan(id) {
        var active_pengajuan = "{{url('/sdm/prosesrekruitment/activatePengajuan/')}}"+"/"+id;
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
                            url: active_pengajuan,
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
                                    penggajuan_sdm.ajax.reload();
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

    function nonPengajuan(id) {
        var non_pengajuan = "{{url('/sdm/prosesrekruitment/nonPengajuan/')}}"+"/"+id;
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
                            url: non_pengajuan,
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
                                    penggajuan_sdm.ajax.reload();
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

    function deletePengajuan(id) {
        var non_pengajuan = "{{url('/sdm/prosesrekruitment/deletePengajuan/')}}"+"/"+id;
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
                            url: non_pengajuan,
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
                                    penggajuan_sdm.ajax.reload();
                                } else if (response.status == 'warning') {
                                    loadingHide();
                                    messageWarning('Peringatan', 'Data ini masih aktif!');
                                    penggajuan_sdm.ajax.reload();
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

    function editPengajuan(id) {
        var modal_edit_pengajuan = "{{url('/sdm/prosesrekruitment/editPengajuan/')}}"+"/"+id;
        $.ajax({
            url: modal_edit_pengajuan,
            type: "get",
            success:function(res) {
                $('#editPengajuan').modal('show');
                $('#id_pengajuan').val(res.data1.ss_id);
                $('#qtyneed').val(res.data1.ss_qtyneed);
                $('#positions_edit').find('option').remove();
                $('#positions_edit').append('<option value="" disabled>=== Pilih Posisi/Jabatan ===</option>'+
                    '<option value="'+res.data1.ss_position+'" selected>'+res.data1.j_name+'</option>');
                $.each(res.data2, function(key, val){
                    $('#positions_edit').append('<option value="'+val.j_id+'">'+val.j_name+'</option>');
                });
                $('#divisis_edit').find('option').remove();
                $('#divisis_edit').append('<option value="" disabled>=== Pilih Posisi/Jabatan ===</option>'+
                    '<option value="'+res.data1.ss_department+'" selected>'+res.data1.m_name+'</option>');
                $.each(res.data3, function(key, val){
                    $('#divisis_edit').append('<option value="'+val.m_id+'">'+val.m_name+'</option>');
                });
            }
        })
    }

    function updatePengajuan() {
        $.ajax({
            url: "{{url('/sdm/prosesrekruitment/updatePengajuan')}}",
            type: "get",
            data: $('#formEditPengajuan').serialize(),
            beforeSend: function () {
                loadingShow();
            },
            success: function (response) {
                if (response.status == 'sukses') {
                    $('#editPengajuan').modal('hide');
                    loadingHide();
                    messageSuccess('Success', 'Data berhasil diperbarui!');
                    penggajuan_sdm.ajax.reload();
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
    // End Code -----------------------------------------------------------------------------------

	// Publish Recruitment --------------------------------------------------------------------
	function publishRekrutmen() {
		if ($.fn.DataTable.isDataTable("#kelola_rekrutmen")) {
    	$('#kelola_rekrutmen').dataTable().fnDestroy();
    }
		kelola_rekrutmen = $('#kelola_rekrutmen').DataTable({
			responsive: true,
			serverSide: true,
			ajax: {
				url: "{{ url('/sdm/prosesrekruitmen/listPublish') }}",
				type: "get"
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'ss_reff'},
				{data: 'm_name'},
				{data: 'j_name'},
                {data: 'ss_qtyneed'},
				{data: 'start'},
                {data: 'end'},
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

	function approvePublish(id) {
		var approve_publish = "{{url('/sdm/prosesrekruitment/approvePublish/')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin menyetujui data ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: approve_publish,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Data publikasi berhasil diterima!');
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

	function rejectPublish(id) {
		var reject_publish = "{{url('/sdm/prosesrekruitment/rejectPublish/')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin membatalkan publikasi data ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: reject_publish,
							          data: {
							              "_token": "{{ csrf_token() }}"
							          },
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Data publikasi berhasil dibatalkan!');
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
				$('#id_loker').val(res.data1.ss_id);
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

<!-- script for kelola-posisi-sdm -->
<script type="text/javascript">
	$(document).ready(function() {
		var tb_kps;

		$('#modal_createposition').on('hidden.bs.modal', function() {
			$('#newPosition').trigger('reset');
			$('#btnStorePos').addClass('simpanKPS');
			$('#btnStorePos').removeClass('editKPS');
		});

		getTableKPS();
		// button store new-kps
		$('#btnStorePos').on('click', function() {
			if ($(this).hasClass('simpanKPS')) {
				storeKPS();
			}
			else if ($(this).hasClass('editKPS')) {
				let id = $('#idPosition').val();
				updateKPS(id);
			}
			$('#modal_createposition').modal('hide');
		});
	});
	// function to retrieve datatable 'table_kps'
	function getTableKPS() {
		$('#table_kps').dataTable().fnDestroy();
		tb_kps = $('#table_kps').DataTable({
			responsive: true,
			serverSide: true,
			processing: true,
			ajax: {
				url: "{{ route('kps.getTableKPS') }}",
				type: 'get',
				data: {
					date_from: $('#date_from_kps').val(),
					date_to: $('#date_to_kps').val()
				}
			},
			columns: [
				{data: 'DT_RowIndex'},
				{data: 'position'},
				{data: 'action'}
			],
			pageLength: 10,
			lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
		});
	}
	// function to store data to database
	function storeKPS() {
		loadingShow();
		$.ajax({
			url: "{{ route('kps.store') }}",
			type: 'post',
			data: $('#newPosition').serialize(),
			success: function(resp) {
				loadingHide();
				if (resp.status == 'berhasil') {
					messageSuccess('Berhasil', 'Data posisi baru berhasil ditambahkan !');
					$('#newPosition').trigger('reset');
					tb_kps.ajax.reload();
				}
				else {
					// messageWarning('Perhatian', 'Terjadi kesalahan saat menyimpan data posisi. Hubungi pengembang !');
					messageWarning('Perhatian', 'Terjadi kesalahan : '+ resp.message);
				}
			},
			error: function(e) {
				loadingHide();
				messageWarning('Error', 'Error saat menyimpan data posisi. Hubungi pengembang !');
			}
		});
	}
	// function to delete data from database
	function deleteKPS(id) {
		$.confirm({
			animation: 'RotateY',
			closeAnimation: 'scale',
			animationBounce: 1.5,
			icon: 'fa fa-exclamation-triangle',
			title: 'Peringatan!',
			content: 'Apa anda akan mengapus data posisi SDM ini ?',
			theme: 'disable',
		    buttons: {
		        info: {
					btnClass: 'btn-blue',
		        	text:'Ya',
		        	action : function(){
						// ajax request to delete data from db
						loadingShow();
						$.ajax({
							url: baseUrl +"/sdm/prosesrekruitment/delete/"+ id,
							type: 'post',
							success: function(resp) {
								loadingHide();
								if (resp.status == 'berhasil') {
									messageSuccess('Berhasil', 'Data posisi berhasil dihapus !');
									tb_kps.ajax.reload();
								}
								else {
									// messageWarning('Perhatian', 'Terjadi kesalahan saat menyimpan data posisi. Hubungi pengembang !');
									messageWarning('Perhatian', 'Terjadi kesalahan : '+ resp.message);
								}
							},
							error: function(e) {
								loadingHide();
								messageWarning('Error', 'Error saat menghapus data posisi. Hubungi pengembang !');
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
	// function display modal to edit data
	function editKPS(id) {
		loadingShow();
		$.ajax({
			url: baseUrl +"/sdm/prosesrekruitment/edit/"+ id,
			type: 'get',
			success: function(resp) {
				loadingHide();
				if (resp.status == 'berhasil') {
					$('#idPosition').val(id);
					$('#positionName').val(resp.positionName);
					$('#btnStorePos').addClass('editKPS');
					$('#btnStorePos').removeClass('simpanKPS');
					$('#modal_createposition').modal('show');
				}
				else {
					messageWarning('Error', 'Error saat menyimpan data ');
				}
			},
			error: function(e) {
				loadingHide();
				messageWarning('Error', 'Error saat menyimpan data posisi. Hubungi pengembang !');
			}
		});
	}
	// function update data
	function updateKPS(id) {
		loadingShow();
		$.ajax({
			url: baseUrl +"/sdm/prosesrekruitment/update/"+ id,
			type: 'post',
			data: $('#newPosition').serialize(),
			success: function(resp) {
				loadingHide();
				if (resp.status == 'berhasil') {
					messageSuccess('Berhasil', 'Data posisi berhasil diperbarui !');
					$('#newPosition').trigger('reset');
					tb_kps.ajax.reload();
				}
				else {
					// messageWarning('Perhatian', 'Terjadi kesalahan saat menyimpan data posisi. Hubungi pengembang !');
					messageWarning('Perhatian', 'Terjadi kesalahan : '+ resp.message);
				}
			},
			error: function(e) {
				loadingHide();
				messageWarning('Error', 'Error saat menyimpan data posisi. Hubungi pengembang !');
			}
		});
	}
</script>
@endsection
