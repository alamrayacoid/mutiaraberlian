@extends('main')

@section('content')
    <!-- modal scoreboard pegawai -->
    @include('sdm.kinerjasdm.scoreboardpegawai.modal_tambah_scoreboardp')
    @include('sdm.kinerjasdm.scoreboardpegawai.modal_edit_scoreboardp')
    @include('sdm.kinerjasdm.scoreboardpegawai.modal_detail_scoreboardp')
    <!-- end -->
    <!-- modal scoreboard manajemen scoreboard -->
    @include('sdm.kinerjasdm.manajemenscoreboard.modal_detail')
    @include('sdm.kinerjasdm.manajemenscoreboard.modal_edit')
    <!-- end -->
    <!-- modal scoreboard manajemen scoreboard & KPI-->
    @include('sdm.kinerjasdm.masterkpi.modal_detail')
    @include('sdm.kinerjasdm.masterkpi.modal_edit')
    <!-- end -->
    <!-- modal inputkpi -->
    @include('sdm.kinerjasdm.inputkpi.modal_tambah_datakpi')
    @include('sdm.kinerjasdm.inputkpi.modal_edit_datakpi')
    <!-- end -->

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Aktivitas SDM </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Master Data Utama</span> /
                <span class="text-primary" style="font-weight: bold;">Aktivitas SDM</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <ul class="nav nav-pills mb-3" id="Tabzs">
                        <li class="nav-item">
                            <a href="#list_scoreboardpegawai" class="nav-link active"
                               data-target="#list_scoreboardpegawai" aria-controls="list_scoreboardpegawai"
                               data-toggle="tab" role="tab">Dashboard KPI</a>
                        </li>
                        <li class="nav-item">
                            <a href="#list_inputkpi" class="nav-link" data-target="#list_inputkpi"
                               aria-controls="list_inputkpi" data-toggle="tab" role="tab">Kelola KPI</a>
                        </li>
                        <li class="nav-item">
                            <a href="#list_manajemenscoreboard" class="nav-link" data-target="#list_manajemenscoreboard"
                               aria-controls="list_manajemenscoreboard" data-toggle="tab" role="tab">Kelola SOP</a>
                        </li>
                        <li class="nav-item">
                            <a href="#masterkpi" class="nav-link" data-target="#masterkpi" aria-controls="masterkpi"
                               data-toggle="tab" role="tab">Master KPI</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        @include('sdm.kinerjasdm.scoreboardpegawai.tab_scoreboardpegawai')
                        @include('sdm.kinerjasdm.inputkpi.tab_inputkpi')
                        @include('sdm.kinerjasdm.manajemenscoreboard.tab_manajemenscoreboard')
                        @include('sdm.kinerjasdm.masterkpi.tab_masterkpi')

                    </div>

                </div>

            </div>

        </section>

    </article>
//===================== Modal ============================
    @include('sdm.kinerjasdm.masterkpi.modal_create')
@endsection
@section('extra_script')
    <script type="text/javascript">

        $(document).ready(function () {
            var table_sup = $('#table_scoreboard').DataTable();
            var table_bar = $('#table_inputkpi').DataTable();
            var table_kpi = $('#table_masterkpi').DataTable();
            var table_rab = $('#table_manajemenscoreboardkpi').DataTable();

// scoreboard pegawai
            $(document).on('click', '.btn-disable-sbpegawai', function () {
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
                            action: function () {
                                $.toast({
                                    heading: 'Information',
                                    text: 'Data Berhasil di Nonaktifkan.',
                                    bgColor: '#0984e3',
                                    textColor: 'white',
                                    loaderBg: '#fdcb6e',
                                    icon: 'info'
                                })
                                ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-sbpegawai" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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
            });

            $(document).on('click', '.btn-enable-sbpegawai', function () {
                $.toast({
                    heading: 'Information',
                    text: 'Data Berhasil di Aktifkan.',
                    bgColor: '#0984e3',
                    textColor: 'white',
                    loaderBg: '#fdcb6e',
                    icon: 'info'
                })
                $(this).parents('.btn-group').html('<button class="btn btn-primary btn-datail-sbpegawai" data-toggle="modal" data-target="#tambah_scoreboardp" type="button" title="Detail"><i class="fa fa-info-circle"></i></button>' +
                    '<button class="btn btn-warning btn-edit-sbpegawai" data-toggle="modal" data-target="#edit_scoreboardp" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                    '<button class="btn btn-danger btn-disable-sbpegawai" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
            })

// end scoreboard pegawai

            $(document).on('click', '.btn-disable-inputkpi', function () {
                var ini = $(this);
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Disable',
                    content: 'Apa anda yakin mau disable data ini?',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Ya',
                            action: function () {
                                $.toast({
                                    heading: 'Information',
                                    text: 'Data Berhasil di Disable.',
                                    bgColor: '#0984e3',
                                    textColor: 'white',
                                    loaderBg: '#fdcb6e',
                                    icon: 'info'
                                })
                                ini.parents('.btn-group').html('<button class="btn btn-success btn-enable-pelamar" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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
            });

            $(document).on('click', '.btn-enable-pelamar', function () {
                $.toast({
                    heading: 'Information',
                    text: 'Data Berhasil di Enable.',
                    bgColor: '#0984e3',
                    textColor: 'white',
                    loaderBg: '#fdcb6e',
                    icon: 'info'
                })
                $(this).parents('.btn-group').html('<button class="btn btn-primary" data-toggle="modal" data-target="#list_barang_dibawa" type="button" title="Preview"><i class="fa fa-search"></i></button>' +
                    '<button class="btn btn-warning btn-edit-pelamar" type="button" title="Process"><i class="fa fa-file-powerpoint-o"></i></button>' +
                    '<button class="btn btn-danger btn-disable-pelamar" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>')
            })

            $(document).on('click', '.btn-simpan-modal', function () {
                $.toast({
                    heading: 'Success',
                    text: 'Data Berhasil di Simpan',
                    bgColor: '#00b894',
                    textColor: 'white',
                    loaderBg: '#55efc4',
                    icon: 'success'
                })
            })
        });
    </script>

    <script type="text/javascript">
        $('#btn-tambah-kpi').click(function () {

            $('#div_pki_pegawai').addClass('d-none');
            $('#div_pki_realisasi').addClass('d-none');
            $('#form_masuk_pki :input').val('').trigger('change');

            $('#tambah_datakpi').modal('show');

        });

        $('#table_inputkpi').on('click', '.btn-edit-inputkpi', function () {
            $('#edit_datakpi').modal('show');
        });

        $('#pki_jabatan').on('change', function () {
            if ($(this).val() === '') {
                // console.log('if jab');
                $('#div_pki_pegawai').addClass('d-none');
                $('#div_pki_realisasi').addClass('d-none');

            } else {
                $('#div_pki_pegawai').removeClass('d-none');
                // console.log('else jab');

            }
        });

        $('#pki_pegawai').on('change', function () {
            if ($(this).val() === '') {

                // console.log('if pegawai');

                $('#div_pki_realisasi').addClass('d-none');

            } else {
                $('#div_pki_realisasi').removeClass('d-none');
                // console.log('else pegawai');

            }
        });
    </script>
@endsection
