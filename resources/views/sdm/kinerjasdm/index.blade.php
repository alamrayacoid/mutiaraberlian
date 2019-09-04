@extends('main')
@section('extra_style')
    <style>
        table {
            width: 100%;
        }
        .th-number{
            width: 10% !important;
        }
    </style>
@endsection
@section('content')
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
                            <a href="#kpipegawai" class="nav-link" data-target="#kpipegawai"
                               aria-controls="kpipegawai" data-toggle="tab" role="tab">KPI Pegawai</a>
                        </li>
                        <li class="nav-item">
                            <a href="#kpidivisi" class="nav-link" data-target="#kpidivisi"
                               aria-controls="kpidivisi" data-toggle="tab" role="tab">KPI Divisi</a>
                        </li>
                        <li class="nav-item">
                            <a href="#masterkpi" class="nav-link" data-target="#masterkpi" aria-controls="masterkpi"
                               data-toggle="tab" role="tab">Master Indikator</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        @include('sdm.kinerjasdm.scoreboardpegawai.tab_scoreboardpegawai')
                        @include('sdm.kinerjasdm.inputkpi.tab_inputkpi')
                        @include('sdm.kinerjasdm.kpipegawai.index')
                        @include('sdm.kinerjasdm.kpidivisi.index')
                        @include('sdm.kinerjasdm.masterkpi.tab_masterkpi')

                    </div>

                </div>

            </div>

        </section>

    </article>
{{--===================== Modal ============================--}}
    {{--===================== Modal ============================--}}
    <!-- modal scoreboard pegawai -->
    @include('sdm.kinerjasdm.scoreboardpegawai.modal_tambah_scoreboardp')
    @include('sdm.kinerjasdm.scoreboardpegawai.modal_edit_scoreboardp')
    @include('sdm.kinerjasdm.scoreboardpegawai.modal_detail_scoreboardp')
    <!-- end -->
    <!-- modal scoreboard manajemen scoreboard -->
    @include('sdm.kinerjasdm.manajemenscoreboard.modal_detail')
    @include('sdm.kinerjasdm.manajemenscoreboard.modal_edit')
    <!-- end -->
    <!-- modal master KPI-->
    @include('sdm.kinerjasdm.masterkpi.modal_detail')
    @include('sdm.kinerjasdm.masterkpi.modal_edit')
    @include('sdm.kinerjasdm.masterkpi.modal_create')
    <!-- end -->
    <!-- modal master KPI-->
    @include('sdm.kinerjasdm.kpipegawai.modal_detail')
    @include('sdm.kinerjasdm.kpidivisi.modal_detail')
    <!-- end -->
    <!-- modal inputkpi -->
    @include('sdm.kinerjasdm.inputkpi.modal_tambah_datakpi')
    @include('sdm.kinerjasdm.inputkpi.modal_edit_datakpi')
    <!-- end -->
@endsection
@section('extra_script')
<script type="text/javascript">

    var table_sup                              = $('#table_scoreboard').DataTable();
    var table_bar                              = $('#table_inputkpi').DataTable();
    var table_kpi_pegawai                      = $('#table_kpi_pegawai').DataTable();
    var table_kpi_divisi_d                     = $('#table_kpi_divisi_d').DataTable();
    var tb_detail_kpi_pegawai;
    var tb_detail_kpi_divisi;
    var table_divisi                           = $('#table_divisi').DataTable();
    var table_kpi;
    var table_rab                              = $('#table_manajemenscoreboardkpi').DataTable();
    var table_indikator_divisi_pegawai         = $('#table_indikator_divisi_pegawai').DataTable();
    // var table_indikator_divisi_pegawai_index   = $('#table_indikator_divisi_pegawai_index').DataTable();

    $(document).ready(function () {
        getDashboardKpi();
        get_kpiAgen();
        getDataIndikatorPegawaiIndex();
        getDataIndikatorDivisiIndex();

        setTimeout(function () {
            getDataMasterKPI();
        }, 1500)


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

    function simpanMasterKPI() {
        let indikator = $('#indikator_masterkpi').val();
        let unit = $('#unit_masterkpi').val();
        if (indikator == '' || indikator == ' ' || unit == '' || unit == ' '){
            messageWarning("Perhatian", "Indikator dan Unit tidak boleh kosong");
            return false;
        } else {
            axios.post('{{ route("masterkpi.create") }}', {
                'indikator': indikator,
                'unit': unit,
                '_token': '{{ csrf_token() }}'
            }).then(function (response) {
                console.log(response);
                if (response.data.status == 'sukses'){
                    messageSuccess("Berhasil", "Data KPI berhasil dibuat");
                    $('#indikator_masterkpi').val('');
                    $('#unit_masterkpi').val('');
                    $('#modal_createmasterkpi').modal('hide');
                    table_kpi.ajax.reload();
                } else if (response.data.status == 'gagal') {
                    messageFailed("Gagal", response.data.message);
                    table_kpi.ajax.reload();
                }
            }).catch(function (error) {
                alert('error');
            });
        }
    }

    function getDataMasterKPI() {
        if ( $.fn.DataTable.isDataTable('#table_masterkpi') ) {
            $('#table_masterkpi').DataTable().destroy();
        }

        $('#table_masterkpi tbody').empty();

        var status = $('#statuskpi').val();
        table_kpi = $('#table_masterkpi').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing:true,
            ajax: {
                url: '{{route("masterkpi.getData")}}',
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "status": status
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'k_indicator', name: 'k_indicator'},
                {data: 'k_unit', name: 'k_unit'},
                {data: 'action', name: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function activeKpi(id) {
        var active_kpi = "{{url('/sdm/kinerjasdm/master-kpi/activeKpi')}}"+"/"+id;
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
                            url: active_kpi,
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
                                    table_kpi.ajax.reload();
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

    function nonKpi(id) {
        var nonactive_kpi = "{{url('/sdm/kinerjasdm/master-kpi/nonKpi')}}"+"/"+id;
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
                            url: nonactive_kpi,
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
                                    table_kpi.ajax.reload();
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

    function deleteKpi(id) {
        var delete_kpi = "{{url('/sdm/kinerjasdm/master-kpi/deleteKpi')}}"+"/"+id;
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
                            url: delete_kpi,
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
                                    table_kpi.ajax.reload();
                                } else if (response.status == 'warning') {
                                    loadingHide();
                                    messageWarning('Peringatan', 'Data ini masih aktif!');
                                    table_kpi.ajax.reload();
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

    // Kpi Agen -->
    $(document).ready(function(){
        $('#table_kpi_pegawai').DataTable().destroy();
        table_kpi_pegawai = $('#table_kpi_pegawai').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing:true,
            ajax: {
                url: '{{url("/sdm/kinerjasdm/kpi-pegawai/get-kpi-pegawai")}}',
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex', className: "text-center"},
                {data: 'e_name'},
                {data: 'm_name'},
                {data: 'j_name'},
                {data: 'action', className: "text-center"}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    })

    function deatilKpiPegawai(emp) {
        loadingShow()
        axios.get('{{url('/sdm/kinerjasdm/kpi-pegawai/get-detail-kpi-pegawai?')}}'+'employee='+emp)
        .then(function(resp){
            $('#m_employee').text(resp.data.data[0].e_name)
            $('#m_divisi').text(resp.data.data[0].m_name)
            $('#m_posisi').text(resp.data.data[0].j_name)

            $('#tb_detail_kpi_pegawai').DataTable().clear().destroy();
            tb_detail_kpi_pegawai = $('#tb_detail_kpi_pegawai').DataTable({
                serverSide: false,
                bAutoWidth: true,
                processing:true,
                paging: false,
                info: false,
                searching: false
            })

            $.each(resp.data.data, function(key, val){
                var no = ++key

                if (val.k_isactive == 'Y')
                    var status = '<span class="badge badge-pill badge-success p-2">Aktif</span>'
                else
                    var status = '<span class="badge badge-pill badge-danger p-2">Tidak Aktif</span>'

                tb_detail_kpi_pegawai.row.add([
                    '<div class="text-center">'+no+'</div>',
                    val.k_indicator,
                    '<div class="text-right">'+val.ke_weight+'</div>',
                    '<div class="text-right">'+val.ke_target+'</div>'
                ]).draw(false)
            })
            $('#detailKpiPegawai').modal('show');
            loadingHide()
        })
        .catch(function(error){

        })
    }

    function delKpiPegawai(emp) {
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
                            type: "post",
                            url: "{{url('/sdm/kinerjasdm/kpi-pegawai/delete-kpi-pegawai')}}"+"/"+emp,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            beforeSend: function() {
                                loadingShow();
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    loadingHide();
                                    messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                    table_kpi_pegawai.ajax.reload();
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

    function editKpiPegawai(emp) {
        window.location.href = "{{url('/sdm/kinerjasdm/kpi-pegawai/edit-kpi-pegawai?')}}"+"employee="+emp
    }

    //Kpi Divisi -->
    $(document).ready(function(){
        $('#table_kpi_divisi_d').DataTable().destroy();
        table_kpi_divisi_d = $('#table_kpi_divisi_d').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing:true,
            ajax: {
                url: '{{url("/sdm/kinerjasdm/kpi-divisi/get-kpi-divisi_d")}}',
                type: "get"
            },
            columns: [
                {data: 'DT_RowIndex', className: "text-center"},
                {data: 'm_name'},
                {data: 'action', className: "text-center"}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    })

    function deatilKpiDivisi(divs) {
        loadingShow()
        axios.get('{{url('/sdm/kinerjasdm/kpi-divisi/get-detail-kpi-divisi?')}}'+'divisi='+divs)
        .then(function(resp){
            // console.log(resp.data.data[0].m_name);
            $('#m_divisi_d').text(resp.data.data[0].m_name)

            $('#tb_detail_kpi_divisi').DataTable().clear().destroy();
            tb_detail_kpi_divisi = $('#tb_detail_kpi_divisi').DataTable({
                serverSide: false,
                bAutoWidth: true,
                processing:true,
                paging: false,
                info: false,
                searching: false
            })

            $.each(resp.data.data, function(key, val){
                var no = ++key

                if (val.k_isactive == 'Y')
                    var status = '<span class="badge badge-pill badge-success p-2">Aktif</span>'
                else
                    var status = '<span class="badge badge-pill badge-danger p-2">Tidak Aktif</span>'

                tb_detail_kpi_divisi.row.add([
                    '<div class="text-center">'+no+'</div>',
                    val.k_indicator,
                    '<div class="text-right">'+val.ke_weight+'</div>',
                    '<div class="text-right">'+val.ke_target+'</div>'
                ]).draw(false)
            })
            $('#detailKpiDivisi').modal('show');
            loadingHide()
        })
        .catch(function(error){

        })
    }

    function delKpiDivisi(divs) {
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
                            type: "post",
                            url: "{{url('/sdm/kinerjasdm/kpi-divisi/delete-kpi-divisi')}}"+"/"+divs,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            beforeSend: function() {
                                loadingShow();
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    loadingHide();
                                    messageSuccess('Berhasil', 'Data berhasil dihapus!');
                                    table_kpi_divisi_d.ajax.reload();
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

    function editKpiDivisi(divs) {
        window.location.href = "{{url('/sdm/kinerjasdm/kpi-divisi/edit-kpi-divisi?')}}"+"divisi="+divs
    }
</script>

<script type="text/javascript"> // form periode di input kpi
    var month_years = new Date();
    const month_year = new Date(month_years.getFullYear(), month_years.getMonth());

    $("#periode_kpi").datepicker( {
    format: "mm-yyyy",
    viewMode: "months", 
    minViewMode: "months"
    });

    $('#periode_kpi').datepicker('setDate', month_year);
    
    $('#periode_kpi').datepicker("setDate", new Date(month_years.getFullYear(), month_years.getMonth()));

    $('#periode_kpi').on('change', function(){
        var data = $('#tipe').val();
        if (data != null && data == 'D') {
            getDataIndikatorDivisiIndex();
        }
        else if (data != null && data == 'P'){
            getDataIndikatorPegawaiIndex();
        }
        // $('#table_indikator_divisi_pegawai_index').dataTable().fnDestroy();
        // $("#table_indikator_divisi_pegawai_index > tbody").find('tr').remove();
    });
</script>

<script type="text/javascript"> // form periode di dashboard kpi
    var month_years_dashboard = new Date();
    const month_year_dashboard = new Date(month_years_dashboard.getFullYear(), month_years_dashboard.getMonth());

    $("#periode_dashboard").datepicker( {
    format: "mm-yyyy",
    viewMode: "months", 
    minViewMode: "months"
    });

    $('#periode_dashboard').datepicker('setDate', month_year);
    
    $('#periode_dashboard').datepicker("setDate", new Date(month_years.getFullYear(), month_years.getMonth()));

    $('#periode_dashboard').on('change', function(){
        $("#table_dashboard_kpi_pegawai > tbody").find('tr').remove();
        $("#table_dashboard_kpi_divisi > tbody").find('tr').remove();
    });
</script>

<script type="text/javascript">
    // $(document).ready(function () {
    //     setTimeout(function () {
    //         getDashboardKpi();    
    //     }, 2000);
    // });

    $('#periode_dashboard').on('change', function () {
        getDashboardKpi();
    });

    function getDashboardKpi() {
        var periode_dashboard = $('#periode_dashboard').val();
        
        $('#table_dashboard_kpi_pegawai').dataTable().fnDestroy();
        table_dashboard_kpi_pegawai = $('#table_dashboard_kpi_pegawai').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing:true,
            ajax: {
                url: '{{ route("dashboardkpipegawai.getKpiDashboardPegawai") }}',
                type: "post",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "periode_dashboard": periode_dashboard
                }
            },
            columns: [
                {data: 'DT_RowIndex', className: "text-center"},
                {data: 'e_name', name: 'e_name'},
                {data: 'sum_point_pegawai', name: 'sum_point_pegawai'}
                // {data: 'action', className: "text-center"}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });

        $('#table_dashboard_kpi_divisi').dataTable().fnDestroy();
        table_dashboard_kpi_divisi = $('#table_dashboard_kpi_divisi').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing:true,
            ajax: {
                url: '{{ route("dashboardkpidivisi.getKpiDashboardDivisi") }}',
                type: "post",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "periode_dashboard": periode_dashboard
                }
            },
            columns: [
                {data: 'DT_RowIndex', className: "text-center"},
                {data: 'm_name', name: 'm_name'},
                {data: 'sum_point_divisi', name: 'sum_point_divisi'}
                // {data: 'action', className: "text-center"}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        setTimeout(function () {
            getDataIndikatorPegawaiIndex();
            getDataIndikatorDivisiIndex();    
        }, 2000);

        // $('#periode_kpi').datepicker().on('changeDate', function() {
        //     getDataIndikatorPegawaiIndex();
        // });

        // $('#periode_kpi').datepicker().on('changeDate', function() {
        //     getDataIndikatorDivisiIndex();
        // });
    });

    function getDivisi(index) {
      axios.get('{{url('/sdm/kinerjasdm/kpi-divisi/get-kpi-divisi')}}')
      .then(function(resp) {
        var data = resp.data.data
        $.each(data, function(key, val) {
          $('#divisi').append('<option value="'+val.m_id+'">'+val.m_name+'</option>')
        })
      })
    }

    function getPegawai(index) {
      axios.get('{{url('/sdm/kinerjasdm/kpi-pegawai/get-kpi-employee')}}')
      .then(function(resp) {
        var data = resp.data.data
        $.each(data, function(key, val) {
          $('#pegawai').append('<option value="'+val.e_id+'">'+val.e_name+' ('+val.d_name+'/'+val.j_name+')</option>')
        })
      })
    }

    function getFormDivisiOrEmployee() {
          var data = $('#tipe').val();
          // console.log(data);
          if ( data == 'D' ) {
              console.log('ini data divisi');
              $('.section2').remove();
              $('#kolom')
                  .before(
                      '<div class="col-md-5 col-sm-6 section2">'+
                          '<div class="row col-md-12 col-sm-12">'+
                              '<div class="col-md-3 col-sm-12">'+
                                  '<label for="">Divisi</label>'+
                              '</div>'+
                              '<div class="col-md-9 col-sm-12">'+
                                  '<div class="form-group">'+
                                      '<select name="divisi" id="divisi" class="form-control form-control-sm select2 divisi" onchange="getDataIndikatorDivisiIndex()">'+
                                          '<option value="" disabled selected="">Pilih Divisi</option>'+
                                      '</select>'+
                                  '</div>'+
                              '</div>'+
                          '</div>'+
                      '</div>'
                  );

              $('.divisi').on('select2:select', function () {
                  $("#table_indikator_divisi_pegawai_index > tbody").find('tr').remove();
              });
                  
              $('.select2').select2({            
                  theme: "bootstrap",
                  dropdownAutoWidth: true,
                  width: '100%'
              });

              getDivisi();

              $("#table_indikator_divisi_pegawai_index > tbody").find('tr').remove();

          } else if ( data == 'P' ){
              console.log('ini data pegawai');
              $('.section2').remove();
              $('#kolom')
                  .before(
                      '<div class="col-md-5 col-sm-6 section2">'+
                          '<div class="row col-md-12 col-sm-12">'+
                              '<div class="col-md-3 col-sm-12">'+
                                  '<label for="">Pegawai</label>'+
                              '</div>'+
                              '<div class="col-md-9 col-sm-12">'+
                                  '<div class="form-group">'+
                                      '<select name="pegawai" id="pegawai" class="form-control form-control-sm select2 pegawai" onchange="getDataIndikatorPegawaiIndex()">'+
                                          '<option value="" disabled selected="">Pilih Pegawai</option>'+
                                      '</select>'+
                                  '</div>'+
                              '</div>'+
                          '</div>'+
                      '</div>'
                  );

              $('.pegawai').on('select2:select', function () {
                  $("#table_indikator_divisi_pegawai_index > tbody").find('tr').remove();
              });

              $('.select2').select2({            
                  theme: "bootstrap",
                  dropdownAutoWidth: true,
                  width: '100%'
              });

              getPegawai();

              $("#table_indikator_divisi_pegawai_index > tbody").find('tr').remove();
          }
      }

    function getDataIndikatorPegawaiIndex() {
        var pegawai = $('#pegawai').val();
        var periode = $('#periode_kpi').val();

        $('#table_indikator_divisi_pegawai_index').dataTable().fnDestroy();
        table_indikator_divisi_pegawai_index = $('#table_indikator_divisi_pegawai_index').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing:true,
            ajax: {
                url: '{{ route("inputkpi.getKelolaKpiPegawai") }}',
                type: "post",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "pegawai": pegawai,
                        "periode": periode
                }
            },
            columns: [
                {data: 'DT_RowIndex', className: "text-center"},
                {data: 'k_indicator', name: 'k_indicator'},
                {data: 'k_unit', name: 'k_unit'},
                {data: 'ke_weight', name: 'ke_weight'},
                {data: 'ke_target', name: 'ke_target'},
                {data: 'kd_result', name: 'kd_result'},
                {data: 'kd_point', name: 'kd_point'},
                {data: 'kd_total', name: 'kd_total'}
                // {data: 'action', className: "text-center"}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

    function getDataIndikatorDivisiIndex() {
        var divisi = $('#divisi').val();
        var periode = $('#periode_kpi').val();

        $('#table_indikator_divisi_pegawai_index').dataTable().fnDestroy();
        table_indikator_divisi_pegawai_index = $('#table_indikator_divisi_pegawai_index').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing:true,
            ajax: {
                url: '{{ route("inputkpi.getKelolaKpiDivisi") }}',
                type: "post",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "divisi": divisi,
                        "periode": periode
                }
            },
            columns: [
                {data: 'DT_RowIndex', className: "text-center"},
                {data: 'k_indicator', name: 'k_indicator'},
                {data: 'k_unit', name: 'k_unit'},
                {data: 'ke_weight', name: 'ke_weight'},
                {data: 'ke_target', name: 'ke_target'},
                {data: 'kd_result', name: 'kd_result'},
                {data: 'kd_point', name: 'kd_point'},
                {data: 'kd_total', name: 'kd_total'}
                // {data: 'action', className: "text-center"}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }

</script>
@endsection
