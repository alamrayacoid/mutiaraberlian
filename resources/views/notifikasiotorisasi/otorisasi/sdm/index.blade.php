@extends('main')

@section('content')
    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Otorisasi SDM </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Notifikasi & Otorisasi</span>
                / <a href="{{route('otorisasi')}}">Otorisasi</a>
                / <span class="text-primary font-weight-bold">Otorisasi SDM</span>
            </p>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header bordered p-2">
                    <div class="header-block">
                        <h3 class="title">Data Pengajuan SDM</h3>
                    </div>
                    <div class=""></div>
                </div>
                <div class="card-block">
                    <section>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_sdm">
                                <thead class="bg-primary">
                                <tr>
                                    <th class="w-5 text-center">No</th>
                                    <th class="w-35">Reff</th>
                                    <th class="w-15">Tanggal</th>
                                    <th class="w-15">Divisi</th>
                                    <th class="w-15">Jabatan</th>
                                    <th class="w-15">Kebutuhan</th>
                                    <th class="w-15 text-center">Status</th>
                                    <th class="w-15 text-center">Aksi</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </section>

    </article>
@endsection
@section('extra_script')
    <script type="text/javascript">
        $(document).ready(function(){
            PengajuanSdm();
            function PengajuanSdm() {
                if ($.fn.DataTable.isDataTable("#table_sdm")) {
                    $('#table_sdm').dataTable().fnDestroy();
                }
                penggajuan_sdm = $('#table_sdm').DataTable({
                    responsive: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('/notifikasiotorisasi/otorisasi/sdm/getListPengajuanInOtorisasi') }}",
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

        });

        function ApprovePengajuan(id) {
            var approve_pengajuan = "{{url('/notifikasiotorisasi/otorisasi/sdm/ApprovePengajuan/')}}"+"/"+id;
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Terima',
                content: 'Apakah anda yakin ingin ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function() {
                            return $.ajax({
                                type: "post",
                                url: approve_pengajuan,
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                beforeSend: function() {
                                    loadingShow();
                                },
                                success: function(response) {
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Berhasil Diterima');
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


        function DeclinePengajuan(id) {
            var decline_pengajuan = "{{url('/notifikasiotorisasi/otorisasi/sdm/DeclinePengajuan/')}}"+"/"+id;
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Tolak!',
                content: 'Apakah anda yakin ingin ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function() {
                            return $.ajax({
                                type: "post",
                                url: decline_pengajuan,
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                beforeSend: function() {
                                    loadingShow();
                                },
                                success: function(response) {
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Data berhasil ditolak!');
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

    </script>
@endsection
