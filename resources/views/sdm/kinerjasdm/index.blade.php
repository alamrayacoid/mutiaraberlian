@extends('main')
@section('extra_style')
    <style>
        table {
            width: 100%;
        }
        .th-number {
            width: 10% !important;
        }
        .read-only {
            pointer-events: none;
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
                        <li class="nav-item">
                            <a href="#sop" class="nav-link" data-target="#sop" aria-controls="Kelola SOP"
                               data-toggle="tab" role="tab">Kelola SOP</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        @include('sdm.kinerjasdm.scoreboardpegawai.tab_scoreboardpegawai')
                        @include('sdm.kinerjasdm.inputkpi.tab_inputkpi')
                        @include('sdm.kinerjasdm.kpipegawai.index')
                        @include('sdm.kinerjasdm.kpidivisi.index')
                        @include('sdm.kinerjasdm.masterkpi.tab_masterkpi')
                        @include('sdm.kinerjasdm.sop.tab_sop')

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
    <!-- modal SOP -->
    @include('sdm.kinerjasdm.sop.modal')
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

    $(document).ready(function () {
        getDashboardKpi();
        // get_kpiAgen();
        getDataIndikatorPegawaiIndex();
        getDataIndikatorDivisiIndex();

        setTimeout(function () {
            getDataMasterKPI();
        }, 1500);
    });
</script>

<!-- Master KPI -->
<script type="text/javascript">

    $(document).ready(function() {
        setTimeout(function () {
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
                    $('#div_pki_realisasi').addClass('d-none');
                } else {
                    $('#div_pki_realisasi').removeClass('d-none');
                }
            });

            getDataMasterKPI();
        }, 100);
    })

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
                loadingHide();
                messageWarning("Error", "Terjadi kesalahan : " + error);
            });
        }
    }

    function getDataMasterKPI() {
        var status = $('#statuskpi').val();
        $('#table_masterkpi').DataTable().destroy();
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
</script>

<!-- KPI Pegawai -->
<script type="text/javascript">
    // Kpi Agen -->
    $(document).ready(function(){
        setTimeout(function () {
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
        }, 200);
    })

    function deatilKpiPegawai(emp) {
        loadingShow()
        axios.get('{{ url("/sdm/kinerjasdm/kpi-pegawai/get-detail-kpi-pegawai?") }}' + 'employee=' + emp)
        .then(function(resp) {
            $('#m_employee').text(resp.data.data[0].e_name);
            $('#m_divisi').text(resp.data.data[0].m_name);
            $('#m_posisi').text(resp.data.data[0].j_name);

            $('#tb_detail_kpi_pegawai').DataTable().clear().destroy();
            tb_detail_kpi_pegawai = $('#tb_detail_kpi_pegawai').DataTable({
                serverSide: false,
                bAutoWidth: true,
                processing:true,
                paging: false,
                info: false,
                searching: false
            });

            $.each(resp.data.data, function(key, val) {
                var no = ++key;

                if (val.k_isactive == 'Y')
                var status = '<span class="badge badge-pill badge-success p-2">Aktif</span>';
                else
                var status = '<span class="badge badge-pill badge-danger p-2">Tidak Aktif</span>';

                tb_detail_kpi_pegawai.row.add([
                '<div class="text-center">'+no+'</div>',
                val.k_indicator,
                '<div class="text-right">'+val.ke_weight+'</div>',
                '<div class="text-right">'+val.ke_target+'</div>'
                ]).draw(false);
            });
            $('#detailKpiPegawai').modal('show');
            loadingHide();
        })
        .catch(function(error) {
            loadingHide();
            messageWarning("Error", "Terjadi kesalahan : " + error);
        });
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
</script>

<!-- KPI Divisi -->
<script type="text/javascript">
    // Kpi Divisi
    $(document).ready(function(){
        setTimeout(function () {
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
        }, 300);
    })

    function deatilKpiDivisi(divs) {
        loadingShow();
        axios.get('{{ url("/sdm/kinerjasdm/kpi-divisi/get-detail-kpi-divisi?") }}'+'divisi='+divs)
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
                loadingHide();
                messageWarning("Error", "Terjadi kesalahan : " + error);

            });
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
        window.location.href = "{{ url('/sdm/kinerjasdm/kpi-divisi/edit-kpi-divisi?') }}" + "divisi=" + divs;
    }
</script>

<script type="text/javascript"> // form periode di input kpi
    var month_years = new Date();
    const month_year = new Date(month_years.getFullYear(), month_years.getMonth());

    $("#periode_kpi").datepicker({
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

<!-- Kelola SOP -->
<script type="text/javascript">
    var tb_sopMaster, table_sop;
    $(document).ready(function() {
        let todayDate;
        setTimeout(function () {
            // timeout
            todayDate = new Date();
            $('#fil_sopr_date').datepicker("setDate", new Date(todayDate.getFullYear(), todayDate.getMonth(), todayDate.getDate()));
            let first_day = new Date(todayDate.getFullYear(), todayDate.getMonth(), 1);
            let last_day = new Date(todayDate.getFullYear(), todayDate.getMonth() + 1, 0);
            $('#fil_sopi_date_from').datepicker('setDate', first_day);
            $('#fil_sopi_date_to').datepicker('setDate', last_day);
            getListSOP();

    		$('#fil_sopi_date_from').on('change', function() {
    			getListSOP();
    		});
    		$('#fil_sopi_date_to').on('change', function() {
    			getListSOP();
    		});
            $('#btn_sopi_refresh').on('click', function() {
                $('#fil_sopi_date_from').datepicker('setDate', first_day);
                $('#fil_sopi_date_to').datepicker('setDate', last_day);
            })
        }, 400);

        // Master SOP
        $('#btn_sop_master').on('click', function() {
            $('#modal_master_sop').modal('show');
        });
        $('#modal_master_sop').on('hidden.bs.modal', function() {
            $('#fil_sop_name').val('');
            $('#fil_sop_level').val('1').trigger('change');
        });
        $('#modal_master_sop').on('show.bs.modal', function() {
            getListMasterSOP();
        })
        $('#btn_sop_add').on('click', function() {
            storeMasterSOP();
        });

        // record SOP
        $('#btn_sop_record').on('click', function() {
            $('#modal_record_sop').modal('show');
            $('#fil_sopr_type').val('create');
            getListEmployee();
            getListMasterForRecord();
        });
        $('#modal_record_sop').on('hidden.bs.modal', function() {
            $('#form_sopr').trigger('reset');
            $('#fil_sopr_date').datepicker("setDate", new Date(todayDate.getFullYear(), todayDate.getMonth(), todayDate.getDate()));
            $('#fil_sopr_date').removeClass('read-only');
            $('#fil_sopr_emp').attr('disabled', false);
            $('#fil_sopr_trespass').attr('disabled', false);
            $('#fil_sopr_react').removeClass('read-only');
            $('#fil_sopr_note').removeClass('read-only');
            $('#btn_sopr_add').removeClass('d-none');
            $('#fil_sopr_type').val('create');
        });
        $('#btn_sopr_add').on('click', function() {
            if ($('#fil_sopr_type').val() == 'create') {
                storeRecordSOP();
            }
            else if ($('#fil_sopr_type').val() == 'update') {
                updateSOP();
            }
        });
    })

    // =========================== Master ===========================
    // get list master-sop
    function getListMasterSOP() {
        $.ajax({
            url: "{{ route('sop.getListMaster') }}",
            type: 'get',
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                $('#table_sop_master').dataTable().fnDestroy();
                tb_sopMaster = $('#table_sop_master').DataTable({
                    ordering: false,
                });
                tb_sopMaster.clear().draw();
                $.each(resp, function(idx, val) {
                    let level, actions;
                    switch (val.r_level) {
                        case '1':
                            level = 'Ringan';
                            break;
                        case '2':
                            level = 'Sedang';
                            break;
                        case '3':
                            level = 'Berat';
                            break;
                    }
                    let actionReactivate = '<button type="button" class="btn btn-warning btn-sm" onclick="reactivateMasterSOP(\''+ val.r_id +'\')"><i class="fa fa-check" aria-hidden="true"></i></button>';
                    let actionDelete = '<button type="button" class="btn btn-danger btn-sm" onclick="deleteMasterSOP(\''+ val.r_id +'\')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                    if (val.r_isactive == 'Y') {
                        actions = '<div class="text-center"><div class="btn-group btn-group-sm">'+ actionDelete +'</div></div>';
                    }
                    else {
                        actions = '<div class="text-center"><div class="btn-group btn-group-sm">'+ actionReactivate +'</div></div>';
                    }
                    tb_sopMaster.row.add([
                        val.r_name,
                        level,
                        actions
                    ]).draw().node();
                });
                tb_sopMaster.columns.adjust();
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // store new master-sop
    function storeMasterSOP() {
        let name = $('#fil_sop_name').val();
        let level = $('#fil_sop_level').val();

        $.ajax({
            url: "{{ route('sop.storeMaster') }}",
            type: 'post',
            data: {
                name: name,
                level: level
            },
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                if (resp.status == 'berhasil') {
                    messageSuccess('Berhasil', 'Master SOP berhasil ditambahkan !');
                    $('#modal_master_sop').trigger('hidden.bs.modal');
                    $('#modal_master_sop').trigger('show.bs.modal');
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // delete master-sop
    function deleteMasterSOP(id) {
        $.ajax({
            url: "{{ route('sop.deleteMaster') }}",
            type: 'post',
            data: {
                id: id
            },
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                if (resp.status == 'berhasil') {
                    messageSuccess('Berhasil', 'Master SOP berhasil dihapus !');
                    $('#modal_master_sop').trigger('hidden.bs.modal');
                    $('#modal_master_sop').trigger('show.bs.modal');
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // re-activate master-sop
    function reactivateMasterSOP(id) {
        $.ajax({
            url: "{{ route('sop.reActivateMaster') }}",
            type: 'post',
            data: {
                id: id
            },
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                if (resp.status == 'berhasil') {
                    messageSuccess('Berhasil', 'Master SOP berhasil diaktifkan kembali !');
                    $('#modal_master_sop').trigger('hidden.bs.modal');
                    $('#modal_master_sop').trigger('show.bs.modal');
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }

    // =========================== Record ===========================
    // get list employee
    function getListEmployee() {
        $.ajax({
            url: "{{ route('sop.getListEmployee') }}",
            type: 'get',
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                console.log(resp);
                data = resp;
                $('#fil_sopr_emp > option').remove();
                // append list employee
                $('#fil_sopr_emp').select2({
                    data: data
                });
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // get list master-sop for select-option
    function getListMasterForRecord() {
        $.ajax({
            url: "{{ route('sop.getListMasterForRecord') }}",
            type: 'get',
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                let data = resp;
                $("#fil_sopr_trespass").empty();
                $("#fil_sopr_trespass").select2({
                    data: data
                });
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // store new sop-record
    function storeRecordSOP() {
        let formData = $('#form_sopr').serialize();

        $.ajax({
            url: "{{ route('sop.storeRecord') }}",
            type: 'post',
            data: formData,
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                console.log(resp);
                if (resp.status == 'berhasil') {
                    messageSuccess('Berhasil', 'Pencatatan Pelanggaran berhasil disimpan !');
                    $('#modal_record_sop').modal('hide');
                    table_sop.ajax.reload();
                }
                else if (resp.status == 'exist') {
                    messageWarning('Perhatian', 'Data sudah ada !');
                    $('#fil_sopr_react').val(resp.action);
                    $('#fil_sopr_note').val(resp.note);
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }

    // =========================== Index ===========================
    function getListSOP() {
        let dateFrom = $('#fil_sopi_date_from').val();
        let dateTo = $('#fil_sopi_date_to').val();

        $('#table_sop').dataTable().fnDestroy();
        table_sop = $('#table_sop').DataTable({
            serverSide: true,
            bAutoWidth: true,
            processing: true,
            ajax: {
                url: '{{ route("sop.getListSOP") }}',
                type: "get",
                data: {
                    dateFrom: dateFrom,
                    dateTo: dateTo
                }
            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'date',},
                {data: 'employee'},
                {data: 'regulation'},
                {data: 'action'}
            ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
        });
    }
    // detail record sop
    function detailRecord(id, detailid) {
        $.ajax({
            url: "{{ route('sop.getDetailSOP') }}",
            type: 'post',
            data: {
                id: id,
                detailid: detailid
            },
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                console.log(resp);
                if (resp.status == 'berhasil') {
                    $('#fil_sopr_date').addClass('read-only');
                    $('#fil_sopr_emp').attr('disabled', true);
                    $('#fil_sopr_trespass').attr('disabled', true);
                    $('#fil_sopr_react').addClass('read-only');
                    $('#fil_sopr_note').addClass('read-only');
                    $('#fil_sopr_date').datepicker('setDate', new Date(resp.data.dateY, parseInt(resp.data.dateM) - 1, resp.data.dateD));
                    $('#fil_sopr_emp').empty();
                    $('#fil_sopr_emp').append(new Option(resp.data.get_reg_act.get_employee.e_name, resp.data.get_reg_act.get_employee.e_id));
                    $('#fil_sopr_trespass').empty();
                    $('#fil_sopr_trespass').append(new Option(resp.data.get_regulation.r_name, resp.data.get_regulation.r_id));
                    $('#fil_sopr_react').val(resp.data.rad_action);
                    $('#fil_sopr_note').val(resp.data.rad_note);
                    $('#btn_sopr_add').addClass('d-none');
                    $('#modal_record_sop').modal('show');
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // delete record sop
    function deleteRecord(id, detailid) {
        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Pesan!',
            content: 'Apakah anda yakin ingin menghapus catatan pelanggaran ini ?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function() {
                        callDeleteSOP(id, detailid);
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
    function callDeleteSOP(id, detailid) {
        $.ajax({
            url: "{{ route('sop.deleteSOP') }}",
            type: 'post',
            data: {
                id: id,
                detailid: detailid
            },
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                console.log(resp);
                if (resp.status == 'berhasil') {
                    messageSuccess('Berhasil', 'Catatan Pelanggaran berhasil dihapus !');
                    table_sop.ajax.reload();
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // edit record sop
    function editRecord(id, detailid) {
        $.ajax({
            url: "{{ route('sop.getDetailSOP') }}",
            type: 'post',
            data: {
                id: id,
                detailid: detailid
            },
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                console.log(resp);
                if (resp.status == 'berhasil') {
                    $('#fil_sopr_type').val('update');
                    $('#fil_sopr_id').val(id);
                    $('#fil_sopr_detailid').val(detailid);

                    $('#fil_sopr_date').addClass('read-only');
                    $('#fil_sopr_emp').attr('disabled', true);
                    $('#fil_sopr_trespass').attr('disabled', true);
                    $('#fil_sopr_date').datepicker('setDate', new Date(resp.data.dateY, parseInt(resp.data.dateM) - 1, resp.data.dateD));
                    $('#fil_sopr_emp').empty();
                    $('#fil_sopr_emp').append(new Option(resp.data.get_reg_act.get_employee.e_name, resp.data.get_reg_act.get_employee.e_id));
                    $('#fil_sopr_trespass').empty();
                    $('#fil_sopr_trespass').append(new Option(resp.data.get_regulation.r_name, resp.data.get_regulation.r_id));
                    $('#fil_sopr_react').val(resp.data.rad_action);
                    $('#fil_sopr_note').val(resp.data.rad_note);
                    $('#modal_record_sop').modal('show');
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
    // update record sop
    function updateSOP() {
        let formData = $('#form_sopr').serialize();

        $.ajax({
            url: "{{ route('sop.updateSOP') }}",
            type: 'post',
            data: formData,
            beforeSend: function() {
                loadingShow();
            },
            success: function(resp) {
                console.log(resp);
                if (resp.status == 'berhasil') {
                    messageSuccess('Berhasil', 'Catatan Pelanggaran berhasil diperbarui !');
                    $('#modal_record_sop').modal('hide');
                    table_sop.ajax.reload();
                }
                else {
                    messageWarning('Error', 'Terjadi kesalahan : ' + resp.message);
                }
            },
            error: function(err) {
                messageWarning('Error', 'Terjadi kesalahan : ' + err);
            },
            complete: function() {
                loadingHide();
            }
        });
    }
</script>
@endsection
