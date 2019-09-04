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
                            <button type="button" class="btn btn-primary" id="e-create" onclick="window.location.href = '{{route('pegawai.create')}}'"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>
                            <div class="row mb-5">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Status Pegawai</label>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <select name="status" id="emp_status" class="form-control form-control-sm select2">
                                        <option value="">Semua</option>
                                        <option value="Y" selected>Aktif</option>
                                        <option value="N">Non Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_pegawai">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama Pegawai</th>
                                            <th>Jabatan</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
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
    var tb_pegawai;
    $(document).ready(function () {
        setTimeout(function () {
            tablePegawai();
            addPegawai();

            $('#emp_status').on('select2:select', function() {
                tablePegawai();
            });

        }, 100);
    })

    function tablePegawai() {
        $('#table_pegawai').dataTable().fnDestroy();
        tb_pegawai = $('#table_pegawai').DataTable({
            responsive: true,
            serverSide: true,
            ajax: {
                url: "{{ route('pegawai.list') }}",
                type: "get",
                data: {
                    status: $('#emp_status').val(),
                    "_token": "{{ csrf_token() }}"
                }
            },
            columns: [
                {data: 'nik', name: 'nik'},
                {data: 'name', name: 'name'},
                {data: 'jabatan', name: 'jabatan'},
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
