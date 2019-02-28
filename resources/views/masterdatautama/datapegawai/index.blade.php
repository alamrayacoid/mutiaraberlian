@extends('main')

@section('content')
<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Data Pegawai </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
	    	/ <span>Master Data Utama</span> 
	    	/ <span class="text-primary font-weight-bold">Data Pegawai</span>
	     </p>
	</div>

	<section class="section">
		<div class="row">
			<div class="col-12">
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
	                        <h3 class="title"> Data Pegawai </h3>
	                    </div>
	                    <div class="header-block pull-right">
                			<button type="button" class="btn btn-primary" id="btn-tambah"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>
                        	<div class="table-responsive">
	                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_pegawai">
	                                <thead class="bg-primary">
	                                    <tr>
							                <th>ID Pegawai</th>
                                            <th>Nama Pegawai</th>
							                <th>NIK</th>
                                            <th>Ktp</th>
											<th>Jabatan</th>
							                <th>Alamat</th>
							                <th>Status Karyawan</th>
											<th>Tanggal Masuk</th>
                                            <th>Status Keaktifan</th>
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
	$('#calendar_date').click(function(){
		$('.datepicker').datepicker('show');
	});
</script>
<script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var tb_pegawai;
        setTimeout(function () {
            tablePegawai();
        }, 500);

        function tablePegawai() {
            tb_pegawai = $('#table_pegawai').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pegawai.list') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'e_id', name: 'e_id'},
                    {data: 'e_name', name: 'e_name'},
                    {data: 'e_nik', name: 'e_nik'},
                    {data: 'e_nip', name: 'e_nip'},
                    {data: 'jabatan', name: 'jabatan'},
                    {data: 'e_address', name: 'e_address'},
                    {data: 'maried', name: 'maried'},
                    {data: 'e_workingdays', name: 'e_workingdays'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function addPegawai()
        {
        	$('#btn-tambah').on('click', function(){
        		window.location.href = "{{route('pegawai.create')}}";
        	})
        }

        function editPegawai(idx) {
            window.location = baseUrl + "/masterdatautama/datapegawai/edit/" + idx;
        }

        function Deletepegawai(idx) {
            var url_hapus = baseUrl + "/masterdatautama/datapegawai/delete/" + idx;

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
                        action: function () {
                            return $.ajax({
                                type: "get",
                                url: url_hapus,
                                beforeSend: function() {
                                    loadingShow();
                                },
                                success: function (response) {
                                    if (response.status == 'berhasil') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                        tb_pegawai.ajax.reload();
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
                    },
                    cancel: {
                        text: 'Tidak',
                        action: function () {

                        }
                    }
                }
            });
        }

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
					}
				}
			}
		});
	});

    function nonActive(idx) {
        var nonActive = baseUrl + "/masterdatautama/datapegawai/nonactive/" + idx;

        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Pesan!',
            content: 'Apakah anda yakin ingin nonaktifkan pegawai ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        return $.ajax({
                            type: "get",
                            url: nonActive,
                            beforeSend: function() {
                                loadingShow();
                            },
                            success: function (response) {
                                if (response.status == 'sukses') {
                                    loadingHide();
                                    messageSuccess('Berhasil', 'pegawai Berhasil Dinonaktifkan!');
                                    tb_pegawai.ajax.reload();
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
                },
                cancel: {
                    text: 'Tidak',
                    action: function () {

                    }
                }
            }
        });
    }

    function active(idx) {
        var actived = baseUrl + "/masterdatautama/datapegawai/actived/" + idx;

        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Pesan!',
            content: 'Apakah anda yakin ingin aktifkan pegawai ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        return $.ajax({
                            type: "get",
                            url: actived,
                            beforeSend: function() {
                                loadingShow();
                            },
                            success: function (response) {
                                if (response.status == 'sukses') {
                                    loadingHide();
                                    messageSuccess('Berhasil', 'pegawai Berhasil Diaktifkan!');
                                    tb_pegawai.ajax.reload();
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
                },
                cancel: {
                    text: 'Tidak',
                    action: function () {

                    }
                }
            }
        });
    }
</script>
@endsection
