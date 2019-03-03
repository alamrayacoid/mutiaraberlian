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
	                            <table class="table table-hover display nowrap" cellspacing="0" id="table_pegawai">
	                                <thead class="bg-primary">
	                                    <tr>
							                <th>NIK</th>
                                            <th>Nama Pegawai</th>
											<th>Jabatan</th>
							                <th>No. Telepon</th>
                                            <th>Status</th>
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
        addPegawai();
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
                {data: 'e_nik', name: 'e_nik'},
                {data: 'e_name', name: 'e_name'},
                {data: 'jabatan', name: 'jabatan'},
                {data: 'e_telp', name: 'e_telp'},
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

    function detailPegawai(idx) {
        window.location = baseUrl + "/masterdatautama/datapegawai/detail/" + idx;
    }

    function editPegawai(idx) {
        window.location = baseUrl + "/masterdatautama/datapegawai/edit/" + idx;
    }

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

    function detail(id)
    {
        window.location = baseUrl + "/masterdatautama/datapegawai/detail/" + id;
    }
</script>
@endsection
