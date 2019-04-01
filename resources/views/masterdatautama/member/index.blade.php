@extends('main')

@section('content')

<article class="content animated fadeInLeft">
	<div class="title-block text-primary">
		<h1 class="title">Master Member</h1>
		<p class="title-description">
			<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
			/ <span>Master Data Utama</span>
			/ <span class="text-primary" style="font-weight: bold;">Master Member</span>
		</p>
	</div>
	<section class="section">
		<div class="row">
			<div class="col-12">
				
				<div class="card">
					<div class="card-header bordered p-2">
						<div class="header-block">
							<h3 class="title"> Master Member </h3>
						</div>
						<div class="header-block pull-right">
							<a class="btn btn-primary" href="{{route('member.create')}}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
						</div>
					</div>
					<div class="card-block">
						<section>
							
							<div class="table-responsive">
								<table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_member">
									<thead class="bg-primary">
										<tr>
											<th width="1%">No</th>
											<th>Nama</th>
											<th>NIK</th>
											<th>Telp</th>
											<th>Alamat</th>
											<th>Kota</th>
											<th>Provinsi</th>
											<th>Agen</th>
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

@endsection
@section('extra_script')
<script type="text/javascript">
	var table_member;
	// Document Ready --------------------------------------------------
	$(document).ready(function(){
		// Code Dummy ----------------------------------------------------
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
    // End Code Dummy ------------------------------------------------
    listDataMember();
	});
	// End Document Ready ----------------------------------------------
	function listDataMember() {
		table_member = $('#table_member').DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
          url: "{{url('/masterdatautama/member/list-member')}}",
          type: "get",
          data: {
              "_token": "{{ csrf_token() }}"
          }
      },
      columns: [
          {data: 'DT_RowIndex'},
          {data: 'm_name'},
          {data: 'm_nik'},
          {data: 'm_tlp'},
          {data: 'm_address'},
          {data: 'wc_name'},
          {data: 'wp_name'},
          {data: 'a_name'},
          {data: 'action'}
      ],
      pageLength: 10,
      lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
	}

	function editMember(id) {
		window.location.href = "{{url('/masterdatautama/member/edit')}}"+"/"+id;
	}

	function nonActivateMember(id) {
		var nonactive_member = "{{url('/masterdatautama/member/nonactivate')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin non aktifkan member ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: nonactive_member,
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
                                messageSuccess('Berhasil', 'Member berhasil dinon aktifkan!');
                                table_member.ajax.reload();
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

	function activateMember(id) {
		var active_member = "{{url('/masterdatautama/member/activate')}}"+"/"+id;
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin ingin aktifkan member ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        type: "post",
                        url: active_member,
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
                                messageSuccess('Berhasil', 'Member berhasil diaktifkan!');
                                table_member.ajax.reload();
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
</script>
@endsection
