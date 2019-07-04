@extends('main')
@section('extra_style')
    <style type="text/css">
        thead > tr > th > select {
            width: 100%;
        }

        thead > tr > td {
            font-size: 14px;
            font-weight: bold;
        }

        .modal {
            z-index: 9999 !important;
        }

        .ui-autocomplete-input {
            z-index: 10000 !important;
        }
        .ui-autocomplete {
            z-index: 10001 !important;
        }
        .select2-dropdown{
            z-index: 10001 !important;
        }
        #cover-spin{
            z-index: 10002 !important;
        }
    </style>
@endsection
@section('content')
    @include('marketing.agen.orderproduk.detailDO')
    @include('marketing.agen.kelolapenjualan.modal-search')
    @include('marketing.agen.kelolapenjualan.modal')
    @include('marketing.agen.penjualanviaweb.modal_create')
    <article class="content animated fadeInLeft">
        <div class="title-block text-primary">
            <h1 class="title"> Manajemen Agen </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Aktivitas Marketing</span> /
                <span class="text-primary" style="font-weight: bold;">Manajemen Agen</span>
            </p>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-pills mb-3" id="Tabzs">
                        <li class="nav-item">
                            <a href="#orderprodukagenpusat" class="nav-link active" data-target="#orderprodukagenpusat"
                               aria-controls="orderprodukagenpusat" data-toggle="tab" role="tab">Order Produk ke Agen /
                                Cabang</a>
                        </li>
                        <li class="nav-item">
                            <a href="#kelolapenjualan" class="nav-link" data-target="#kelolapenjualan"
                               aria-controls="kelolapenjualan" data-toggle="tab" role="tab">Kelola Penjualan
                                Langsung </a>
                        </li>
                        <li class="nav-item" onclick="tableHistoryColumn()">
                            <a href="#penjualanviaweb" class="nav-link" data-target="#penjualanviaweb"
                               aria-controls="penjualanviaweb" data-toggle="tab" role="tab">Kelola Penjualan Via
                                Website</a>
                        </li>
                        <li class="nav-item">
                            <a href="#datacanvassing" class="nav-link" data-target="#datacanvassing"
                               aria-controls="datacanvassing" data-toggle="tab" role="tab">Kelola Laporan Keuangan
                                Sederhana </a>
                        </li>
                        <li class="nav-item">
                            <a href="#inventoryagen" class="nav-link" data-target="#inventoryagen"
                               aria-controls="inventoryagen" data-toggle="tab" role="tab">Kelola Data Inventory Agen</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        @include('marketing.agen.orderproduk.index')
                        @include('marketing.agen.inventoryagen.index')
                        @include('marketing.agen.penjualanviaweb.index')
                        @include('marketing.agen.kelolapenjualan.index')
                    </div>
                </div>
            </div>
        </section>
    </article>

@endsection
@section('extra_script')
    <script type="text/javascript">
        var table_do;
        var table_kpw;
        var table_historykpw;
        var table_detailKPW, table_editKPW;
        $(document).ready(function () {
            getStatusDO();
            var table_pus = $('#table_kelolapenjualan').DataTable({
                bAutoWidth: true
            });
            table_kpw = $('#table_KPW').DataTable({
                bAutoWidth: true,
                responsive: true,
                info: false,
                searching: false,
                paging: false
            });
            setTimeout(function () {
                TableListKPW();
            }, 1000)

            setTimeout(function () {
                table_detailKPW = $('#table_DetailKPW').DataTable();
            }, 1250)
            var table_modal_detail = $('#detail-kelola').DataTable();
            //var table_pus = $('#table_inventoryagen').DataTable();

            $(document).on('click', '.btn-edit', function () {
                window.location.href = '{{ route('orderagenpusat.edit') }}'
            });

            $(document).on('click', '.btn-disable', function () {
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
                                ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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

                $(document).ready(function () {
                    $('#modal-order').DataTable({
                        "iDisplayLength": 5
                    });
                });

                $(document).ready(function () {
                    $('#detail-monitoring').DataTable({
                        "iDisplayLength": 5
                    });
                });
            });

            $(document).on('click', '.btn-enable', function () {
                $.toast({
                    heading: 'Information',
                    text: 'Data Berhasil di Aktifkan.',
                    bgColor: '#0984e3',
                    textColor: 'white',
                    loaderBg: '#fdcb6e',
                    icon: 'info'
                })
                $(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>' +
                    '<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
            })

            $("#search-list-agen").on("click", function () {
                $(".table-modal").removeClass('d-none');
            });

            $('#table_inventoryagen').DataTable({
                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="filter select2"><option value=""></option></select>')
                            .appendTo($(column.header()).empty()).on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                    $('.filter').select2();
                }
            });
        });

        function getStatusDO() {
            var st = $('#statusDO').val();
            $('#table_orderprodukagenpusat').DataTable().clear().destroy();
            table_do = $('#table_orderprodukagenpusat').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('orderagenpusat.getDO') }}",
                    type: "get",
                    data: {status: st}
                },
                columns: [
                    {data: 'tanggal'},
                    {data: 'nota'},
                    {data: 'penjual'},
                    {data: 'pembeli'},
                    {data: 'status'},
                    {data: 'action'}
                ]
            });
        }

        function terimaDO(id) {
            var surl = "{{url('/marketing/agen/orderproduk/terima-delivery-order')}}"+"/"+id;

            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Pesan!',
                content: 'Apakah anda yakin ingin menyetujui orderan ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            return $.ajax({
                                type: "post",
                                url: surl,
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                beforeSend: function () {
                                    loadingShow();
                                },
                                success: function (response) {
                                    //var table_agen = $('#table_dataAgen').DataTable();
                                    if (response.status == 'sukses') {
                                        loadingHide();
                                        messageSuccess('Berhasil', 'Data berhasil disetujui!');
                                        table_do.ajax.reload();
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
                        action: function (response) {
                            loadingHide();
                            messageWarning('Peringatan', 'Anda telah membatalkan!');
                        }
                    }
                }
            });
        }

        function getProvId() {
            var id = document.getElementById("prov").value;
            $.ajax({
                url: "{{route('orderProduk.getCity')}}",
                type: "get",
                data: {
                    provId: id
                },
                success: function (response) {
                    $('#city').empty();
                    $("#city").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
                    $.each(response.data, function (key, val) {
                        $("#city").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#city').focus();
                    $('#city').select2('open');
                }
            });
        }

        $('#city').on('change', function () {
            var city = $('#city').val();
            $.ajax({
                url: "{{url('/marketing/agen/get-agen')}}" + "/" + city,
                type: "get",
                success: function (response) {
                    $('#agen').empty();
                    $("#agen").append('<option value="" selected disabled>=== Pilih Agen ===</option>');
                    $.each(response.data, function (key, val) {
                        $("#agen").append('<option value="' + val.c_id + '">' + val.a_name + '</option>');
                    });
                    $('#agen').focus();
                    $('#agen').select2('open');
                }
            });
        });

        function filterData() {
            var id = $('#agen').val();

            $('#table_inventoryagen').DataTable().clear().destroy();
            table_agen = $('#table_inventoryagen').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/marketing/agen/filter-data') }}" + "/" + id,
                    type: "post",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    }
                },
                columns: [
                    {data: 'agen'},
                    {data: 'comp'},
                    {data: 'i_name'},
                    {data: 'kondisi'},
                    {data: 'qty'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']],

                initComplete: function () {
                    this.api().columns().every(function () {
                        var column = this;
                        var select = $('<select class="filter select2"><option value=""></option></select>')
                            .appendTo($(column.header()).empty()).on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                    $('.filter').select2();
                }
            });
        }
    </script>

    <!-- kelola penjualan langsung -->
    <script type="text/javascript">
        $(document).ready(function () {
            cur_date = new Date();
            first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
            last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
            $('#date_from_kpl').datepicker('setDate', first_day);
            $('#date_to_kpl').datepicker('setDate', last_day);
            $('#date_from_kpw').datepicker('setDate', first_day);
            $('#date_to_kpw').datepicker('setDate', last_day);

            if ($('.current_user_type').val() !== 'E') {
                $('.filter_agent').addClass('d-none');
            } else {
                $('.filter_agent').removeClass('d-none');
            }

            $('#date_from_kpl').on('change', function () {
                TableListKPL();
            });
            $('#date_to_kpl').on('change', function () {
                TableListKPL();
            });
            $('#date_from_kpw').on('change', function () {
                TableListKPL();
            });
            $('#date_to_kpw').on('change', function () {
                TableListKPL();
            });
            $('#btn_search_date_kpl').on('click', function () {
                TableListKPL();
            });
            $('#btn_refresh_date_kpl').on('click', function () {
                $('#filter_agent_code_kpl').val('');
                $('#date_from_kpl').datepicker('setDate', first_day);
                $('#date_to_kpl').datepicker('setDate', last_day);
            });
            TableListKPL();
            $('#filter_agent_name_kpl').on('click', function () {
                $('#searchAgen').modal('show');
            });
            $('#provKPL').on('change', function () {
                getCitiesKPL();
            });
            $('#citiesKPL').on('change', function () {
                $(".table-modal").removeClass('d-none');
                appendListAgentsKPL();
            });
            $('#btn_filter_kpl').on('click', function () {
                TableListKPL();
            });
            $('#btn_filter_kpl').on('click', function () {
                TableListKPL();
            });
        //===========================
            $('#date_from_kpw').on('change', function () {
                TableListKPW();
            });
            $('#date_to_kpw').on('change', function () {
                TableListKPW();
            });
            $('#btn_search_date_kpw').on('click', function () {
                TableListKPW();
            });
            $('#btn_refresh_date_kpw').on('click', function () {
                $('#filter_agent_code_kpw').val('');
                $('#date_from_kpw').datepicker('setDate', first_day);
                $('#date_to_kpw').datepicker('setDate', last_day);
            });

            $('#filter_agent_name_kpw').on('click', function () {
                $('#searchAgenKpw').modal('show');
            });
            $('#provKPW').on('change', function () {
                getCitiesKPW();
            });
            $('#citiesKPW').on('change', function () {
                $(".table-modal").removeClass('d-none');
                appendListAgentsKPW();
            });
            $('#btn_filter_kpw').on('click', function () {
                TableListKPW();
            });
        });

        function tableHistoryColumn() {
            table_historykpw.columns.adjust();
        }

        // data-table -> function to retrieve DataTable server side
        var tb_listkpl;

        function TableListKPL() {
            $('#table_kelolapenjualan').dataTable().fnDestroy();
            tb_listkpl = $('#table_kelolapenjualan').DataTable({
                responsive: true,
                processing: true,
                bAutoWidth: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kelolapenjualan.getListKPL') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "date_from": $('#date_from_kpl').val(),
                        "date_to": $('#date_to_kpl').val(),
                        "agent_code": $('#filter_agent_code_kpl').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'date'},
                    {data: 's_nota'},
                    {data: 'member', width: "40%"},
                    {data: 'total'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function TableListKPW() {
            $('#table_penjualanviaweb').dataTable().fnDestroy();
            table_historykpw = $('#table_penjualanviaweb').DataTable({
                responsive: true,
                processing: true,
                bAutoWidth: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kelolapenjualan.getListKPW') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "date_from": $('#date_from_kpw').val(),
                        "date_to": $('#date_to_kpw').val(),
                        "agent_code": $('#filter_agent_code_kpw').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'c_name'},
                    {data: 'date'},
                    {data: 'sw_reff'},
                    {data: 'sw_transactioncode'},
                    {data: 'sw_website'},
                    {data: 'total'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // edit detail penjualan
        function editDetailPenjualan(idPenjualan) {
            window.location.href = baseUrl + '/marketing/agen/kelolapenjualanlangsung/edit/' + idPenjualan;
        }

        // show detail penjualan
        function showDetailPenjualan(idPenjualan) {
            loadingShow();
            $.ajax({
                url: "{{ route('kelolapenjualan.getDetailPenjualan') }}",
                type: 'get',
                data: {
                    'id': idPenjualan
                },
                success: function (response) {
                    loadingHide();
                    $('#detail_kpl_nota').val(response.s_nota);
                    $('#detail_kpl_member_name').val(response.get_member.m_name);
                    $('#detail_kpl_total').val(parseInt(response.s_total));
                    $('#table_detail_kelola tbody').empty();
                    $.each(response.get_sales_dt, function (key, val) {
                        nama = '<td>' + val.get_item.i_name + '</td>';
                        unit = '<td>' + val.get_unit.u_name + '</td>';
                        qty = '<td class="digits">' + parseInt(val.sd_qty) + '</td>';
                        price = '<td class="rupiah">' + parseInt(val.sd_value) + '</td>';
                        totalPrice = '<td class="rupiah">' + parseInt(val.sd_totalnet) + '</td>';
                        itemToAppend = nama + unit + qty + price + totalPrice;
                        $('#table_detail_kelola > tbody:last-child').append('<tr>' + itemToAppend + '</tr>');
                    });
                    $('.rupiah').inputmask("currency", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        prefix: ' Rp ', //Space after $, this will not truncate the first character.
                        rightAlign: true,
                        autoUnmask: true,
                        nullable: false,
                        // unmaskAsNumber: true,
                    });
                    $('.rupiah-left').inputmask("currency", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 2,
                        autoGroup: true,
                        prefix: 'Rp ', //Space after $, this will not truncate the first character.
                        rightAlign: false,
                        autoUnmask: true,
                        nullable: false,
                        // unmaskAsNumber: true,
                    });
                    $('.digits').inputmask("currency", {
                        radixPoint: ",",
                        groupSeparator: ".",
                        digits: 0,
                        autoGroup: true,
                        prefix: '', //Space after $, this will not truncate the first character.
                        rightAlign: true,
                        autoUnmask: true,
                        nullable: false,
                        // unmaskAsNumber: true,
                    });
                    $('#detailkpl').modal('show');
                },
                error: function (e) {
                    loadingHide();
                    console.error(e);
                }

            });
        }

        // delete penjualan
        function deleteDetailPenjualan(idPenjualan) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Konfirmasi!',
                content: 'Apakah anda yakin akan menghapus data penjualan ini ?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            deleteKPL(idPenjualan);
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
        }

        function deleteKPL(idPenjualan) {
            $.ajax({
                url: "{{ route('kelolapenjualan.deleteDetailPenjualan') }}",
                type: 'post',
                data: {
                    'id': idPenjualan
                },
                success: function (response) {
                    if (response.status === 'berhasil') {
                        messageSuccess('Berhasil', 'Penjualan berhasil dihapus !');
                        tb_listkpl.ajax.reload();
                    } else if (response.status === 'gagal') {
                        messageFailed('Gagal', response.message)
                    }
                },
                error: function (e) {
                    messageWarning('Gagal', 'Delete penjualan gagal, hubungi pengembang !');
                }
            })
        }

        // get provinces for search-agen
        function getCitiesKPL() {
            var id = $('#provKPL').val();
            $.ajax({
                url: "{{route('kelolapenjualan.getCitiesKPL')}}",
                type: "get",
                data: {
                    provId: id
                },
                success: function (response) {
                    $('#citiesKPL').empty();
                    $("#citiesKPL").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
                    $.each(response.get_cities, function (key, val) {
                        $("#citiesKPL").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#citiesKPL').focus();
                    $('#citiesKPL').select2('open');
                }
            });
        }

        function getCitiesKPW() {
            var id = $('#provKPW').val();
            $.ajax({
                url: "{{route('kelolapenjualan.getCitiesKPL')}}",
                type: "get",
                data: {
                    provId: id
                },
                success: function (response) {
                    $('#citiesKPW').empty();
                    $("#citiesKPW").append('<option value="" selected="" disabled="">=== Pilih Kota ===</option>');
                    $.each(response.get_cities, function (key, val) {
                        $("#citiesKPW").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#citiesKPW').focus();
                    $('#citiesKPW').select2('open');
                }
            });
        }

        // append data to table-list-agens
        function appendListAgentsKPL() {
            $.ajax({
                url: "{{ route('kelolapenjualan.getAgentsKPL') }}",
                type: 'get',
                data: {
                    cityId: $('#citiesKPL').val()
                },
                success: function (response) {
                    $('#table_search_agen_kpl tbody').empty();
                    if (response.length <= 0) {
                        return 0;
                    }
                    $.each(response, function (index, val) {
                        listAgents = '<tr><td>' + val.get_province.wp_name + '</td>';
                        listAgents += '<td>' + val.get_city.wc_name + '</td>';
                        listAgents += '<td>' + val.a_name + '</td>';
                        listAgents += '<td>' + val.a_type + '</td>';
                        listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgent(\'' + val.a_code + '\',\'' + val.a_name + '\')"><i class="fa fa-download"></i></button></td></tr>';
                    });
                    $('#table_search_agen_kpl > tbody:last-child').append(listAgents);
                }
            });
        }

        function appendListAgentsKPW() {
            $.ajax({
                url: "{{ route('kelolapenjualan.getAgentsKPL') }}",
                type: 'get',
                data: {
                    cityId: $('#citiesKPW').val()
                },
                success: function (response) {
                    $('#table_search_agen_kpw tbody').empty();
                    if (response.length <= 0) {
                        return 0;
                    }
                    $.each(response, function (index, val) {
                        listAgents = '<tr><td>' + val.get_province.wp_name + '</td>';
                        listAgents += '<td>' + val.get_city.wc_name + '</td>';
                        listAgents += '<td>' + val.a_name + '</td>';
                        listAgents += '<td>' + val.a_type + '</td>';
                        listAgents += '<td><button type="button" class="btn btn-sm btn-primary" onclick="addFilterAgentKpw(\'' + val.a_code + '\',\'' + val.a_name + '\')"><i class="fa fa-download"></i></button></td></tr>';
                    });
                    $('#table_search_agen_kpw > tbody:last-child').append(listAgents);
                }
            });
        }

        // add filter-agent
        function addFilterAgent(agentCode, agentName) {
            $('#filter_agent_name_kpl').val(agentName);
            $('#filter_agent_code_kpl').val(agentCode);
            $('#searchAgen').modal('hide');
        }

        function addFilterAgentKpw(agentCode, agentName) {
            $('#filter_agent_name_kpw').val(agentName);
            $('#filter_agent_code_kpw').val(agentCode);
            $('#searchAgenKpw').modal('hide');
        }

        function reloadTable() {
            table_do.ajax.reload();
        }

        function hapusDO(id) {
            deleteConfirm(baseUrl + "/marketing/agen/orderproduk/hapus-delivery-order/" + id);
        }

        function editDO(id) {
            //
        }

        function detailDo(id) {
            loadingShow();
            axios.get(baseUrl + '/marketing/agen/orderproduk/detail-delivery-order/' + id + '/detail')
                .then(function (resp) {
                    loadingHide();
                    if (resp.data.status == "Failed") {
                        messageFailed("Gagal", resp.data.message)
                    } else {
                        var status = '';
                        if (resp.data.status == "Y") {
                            status = "Disetujui";
                        } else if (resp.data.status == "N") {
                            status = "Ditolak";
                        } else {
                            status = "Pending";
                        }
                        $("#txt_tanggal").val(resp.data.tanggal);
                        $("#txt_nota").val(resp.data.nota);
                        $("#txt_status").val(status);
                        $("#txt_penjual").val(resp.data.penjual);
                        $("#txt_pembeli").val(resp.data.pembeli);

                        if ($.fn.DataTable.isDataTable("#table_itemDO")) {
                            $('#table_itemDO').dataTable().fnDestroy();
                        }

                        $('#table_itemDO').DataTable({
                            responsive: true,
                            autoWidth: false,
                            serverSide: true,
                            ajax: {
                                url: baseUrl + '/marketing/agen/orderproduk/detail-delivery-order/' + id + '/table',
                                type: "get"
                            },
                            columns: [
                                {data: 'barang'},
                                {data: 'jumlah'},
                                {data: 'harga', className: "text-right"},
                                {data: 'total_harga', className: "text-right"}
                            ],
                            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, 100]],
                            "drawCallback": function (settings) {
                                loadingHide();
                                $("#detailDO").modal('show');
                            }
                        });
                    }
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error)
                })
        }
        
        function getCity() {
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/cities/' + $('#area_provinsi').val(),
                success: function(data) {
                    $('#area_kota').empty();
                    $("#area_kota").append('<option disabled selected>== Pilih Kota ==</option>');
                    $.each(data, function(key, val) {
                        $("#area_kota").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#area_kota').focus();
                    $('#area_kota').select2('open');
                }
            });
        }

        function getAgen() {
            $.ajax({
                url: baseUrl+'/marketing/konsinyasipusat/cari-konsigner-select2/'+$("#area_provinsi").val()+'/'+$("#area_kota").val(),
                type: 'get',
                success: function( data ) {
                    $('#nama_agen').find('option').remove();
                    $('#nama_agen').append('<option value="" selected disabled> == Pilih Agen ==</option>')
                    $.each(data, function(index, val) {
                        $('#nama_agen').append('<option value="'+ val.c_id +'" data-code="'+ val.a_code +'">'+ val.a_name +'</option>');
                    });
                    $('#nama_agen').focus();
                    $('#nama_agen').select2('open');
                },
                error: function(e) {
                }
            });

        }

        $( "#produk" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: '{{ route('kelolapenjualanviawebsite.cariProduk') }}',
                    data: {
                        term: $("#produk").val()
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 1,
            select: function(event, data) {
                $('#id_produk').val(data.item.id);
                getUnit();
            }
        });

        function getUnit() {
            let item = $('#id_produk').val();
            axios.get(baseUrl + '/marketing/agen/kelolapenjualanlangsung/get-unit/' + item,).then(function (response) {
                $('#satuan').empty();
                $("#satuan").append('<option value="" selected disabled>== Pilih Satuan ==</option>');
                $("#satuan").append('<option data-nama="'+response.data.get_unit1.u_name+'" value="' + response.data.get_unit1.u_id + '">' + response.data.get_unit1.u_name + '</option>');
            }).catch(function (error) {
                alert("error");
            })
        }

        $( "#edit_produk" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: '{{ route('kelolapenjualanviawebsite.cariProduk') }}',
                    data: {
                        term: $("#edit_produk").val()
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 1,
            select: function(event, data) {
                $('#edit_produkid').val(data.item.id);
                getEditUnit();
            }
        });

        function getEditUnit() {
            let item = $('#edit_produkid').val();
            axios.get(baseUrl + '/marketing/agen/kelolapenjualanlangsung/get-unit/' + item,).then(function (response) {
                let id1   = response.data.get_unit1.u_id;
                let name1 = response.data.get_unit1.u_name;
                let id2   = response.data.get_unit2.u_id;
                let name2 = response.data.get_unit2.u_name;
                let id3   = response.data.get_unit3.u_id;
                let name3 = response.data.get_unit3.u_name;

                $('#edit_satuan').empty();
                $("#edit_satuan").append('<option value="" selected disabled>== Pilih Satuan ==</option>');
                let opsi = '';
                opsi += '<option data-nama="'+name1+'" value="' + id1 + '">' + name1 + '</option>';
                if (id2 != null && id2 != id1) {
                    opsi += '<option data-nama="'+name2+'" value="' + id2 + '">' + name2 + '</option>';
                }
                if (id3 != null && id3 != id2) {
                    opsi += '<option data-nama="'+name3+'" value="' + id3 + '">' + name3 + '</option>';
                }
                $("#edit_satuan").append(opsi);
            }).catch(function (error) {
                alert("error");
            });
        }

        $('.input-harga').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: ' Rp. ', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            // unmaskAsNumber: true,
        });

        function setTotal() {
            let qty = $('#kuantitas').val();
            let harga = $('#harga').val();

            let total = parseInt(qty) * parseInt(harga);
            $('#total').val(total);
        }

        $('.set-total').on('click keyup', function(){
            let qty = $('#edit_kuantitas').val();
            let harga = $('#edit_harga').val();

            let total = parseInt(qty) * parseInt(harga);
            $('#edit_total').val(total);
        });

        $('#satuan').change(function(){
            var selected = $(this).find('option:selected').data('nama');
            $('#label-satuan').html(selected);
        });
        
        function saveSalesWeb() {
            let kuantitas = $('#kuantitas').val();
            let qty = $("input[name='qtycode[]']")
                .map(function(){return $(this).val();}).get();
            let totalqty = 0;
            for (let i = 0; i < qty.length; i++) {
                totalqty = totalqty + parseInt(qty[i]);
            }

            if (parseInt(kuantitas) != parseInt(totalqty)){
                return $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 2.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Peringatan!',
                    content: 'Kuantitas barang tidak sama dengan jumlah kode!!',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Lanjutkan',
                            action: function () {
                               return lanjutkan();
                            }
                        },
                        cancel: {
                            text: 'Batal',
                            action: function () {
                                // tutup confirm
                                valid = 0;
                            }
                        }
                    }
                });
            } else {
                lanjutkan();
            }
        }

        function lanjutkan() {
            valid = 1;
            let provinsi  = $('#area_provinsi').val();
            let kota      = $('#area_kota').val();
            let agen      = $('#nama_agen').val();
            let customer  = $('#nama_customer').val();
            let website   = $('#website').val();
            let transaksi = $('#transaksi').val();
            let produk    = $('#id_produk').val();
            let kuantitas = $('#kuantitas').val();
            let satuan    = $('#satuan').val();
            let harga     = $('#harga').val();
            let note      = $('#note').val();
            let kode      = $("input[name='code[]']")
                .map(function(){return $(this).val();}).get();

            let kodeqty = $("input[name='qtycode[]']")
                .map(function(){return $(this).val();}).get();

            if (provinsi == '' || provinsi == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#area_provinsi').focus();
                $('#area_provinsi').select2('open');
                return false;
            }
            if (kota == '' || kota == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#area_kota').focus();
                $('#area_kota').select2('open');
                return false;
            }
            if (agen == '' || agen == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#nama_agen').focus();
                $('#nama_agen').select2('open');
                return false;
            }
            if (customer == '' || customer == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#nama_customer').focus();
                $('#nama_customer').select2('open');
                return false;
            }
            if (website == '' || website == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#website').focus();
                return false;
            }
            if (transaksi == '' || transaksi == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#transaksi').focus();
                return false;
            }
            if (produk == '' || produk == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#produk').focus();
                return false;
            }
            if (kuantitas == '' || kuantitas == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#kuantitas').focus();
                return false;
            }
            if (satuan == '' || satuan == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#satuan').focus();
                $('#satuan').select2('open');
                return false;
            }
            if (harga == '' || harga == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#harga').focus();
                return false;
            }
            if (valid == 1){
                loadingShow();
                axios.post('{{ route("kelolapenjualanviawebsite.saveKPW") }}', {
                    "agen": agen,
                    "website": website,
                    "customer": customer,
                    "transaksi": transaksi.toUpperCase(),
                    "item": produk,
                    "qty": kuantitas,
                    "unit": satuan,
                    "price": harga,
                    "note": note,
                    "code": kode,
                    "qtycode": kodeqty,
                    "_token": '{{ csrf_token() }}'
                }).then(function (response) {
                    loadingHide();
                    if (response.data.status == 'success'){
                        messageSuccess("Berhasil", "Data berhasil disimpan");
                        $('#createKPW').modal('hide');
                        table_historykpw.ajax.reload();
                    } else if (response.data.status == 'gagal'){
                        messageFailed("Gagal", response.data.message);
                    }
                }).catch(function (error) {
                    loadingHide();
                    alert("error");
                })
            }
        }

        function cekCode(e){
            if (e.keyCode == 13){
                addCode();
            }
        }

        $('#createKPW').on('shown.bs.modal', function () {
            table_kpw.clear().destroy();
            table_kpw = $('#table_KPW').DataTable({
                bAutoWidth: true,
                responsive: true,
                info: false,
                searching: false,
                paging: false
            });
            table_kpw.columns.adjust();
        });

        var counter = 0;
        function addCode() {
            loadingShow();
            //cek stockdt
            let agen = $('#nama_agen').val();
            let code = $('#code').val();
            let item = $('#id_produk').val();
            axios.get('{{ route("kelolapenjualanviawebsite.cekProductionCode") }}', {
                params:{
                    "posisi": agen,
                    "kode": code,
                    "item": item
                }
            }).then(function (response) {
                loadingHide();
                code = code.toUpperCase();
                if (response.data.status == 'gagal'){
                    messageFailed('Peringatan', 'Kode tidak ditemukan');
                } else if (response.data.status == 'sukses'){
                    let qty = $('#code_qty').val();
                    if (qty == '' || qty == 0 || qty == null){
                        qty = 1;
                    }
                    let values = $("input[name='code[]']")
                        .map(function(){return $(this).val();}).get();
                    if (!values.includes(code)){
                        ++counter;
                        table_kpw.row.add([
                            "<input type='text' class='code form-control form-control-sm codeprod' name='code[]' value='"+code+"' readonly>",
                            "<input type='number' class='qtycode form-control form-control-sm' name='qtycode[]' value='"+qty+"'>",
                            "<button class='btn btn-danger btn-sm btn-delete-"+counter+"'><i class='fa fa-close'></i></button>"
                        ]).draw(false);
                        $('#table_KPW tbody').on( 'click', '.btn-delete-'+counter, function () {
                            table_kpw
                                .row( $(this).parents('tr') )
                                .remove()
                                .draw();
                        } );
                        $('#code').val('');
                        $('#code_qty').val('');
                        $('#code').focus();
                    } else {
                        messageWarning("Perhatian", "Kode sudah ada");
                        let idx = values.indexOf(code);
                        let qtylama = $('.qtycode').eq(idx).val();
                        let total = parseInt(qty) + parseInt(qtylama);
                        $('.qtycode').eq(idx).val(total);
                        $('.qtycode').eq(idx).focus();
                    }
                }
            }).catch(function (error) {
                loadingHide();
                alert('error');
            });
        }

        function getCustomer() {
            loadingShow();
            let agen = $('#nama_agen').val();
            axios.get('{{ route("kelolapenjualan.getMemberKPL") }}', {
                'agentCode': agen
            }).then(function (response) {
                loadingHide();
                $.each(response.data, function (key, val) {
                    $("#nama_customer").append('<option value="' + val.m_code + '">' + val.m_name + '</option>');
                });
                $('#nama_customer').focus();
                $('#nama_customer').select2('open');
            }).catch(function (error) {
                loadingHide();
                alert('error');
            })
        }
        
        function detailKPW(id) {
            loadingShow();
            axios.get('{{ route("kelolapenjualan.getDetailKPW") }}', {
                params:{
                    "sw_id": id
                }
            }).then(function (response) {
                loadingHide();
                let data = response.data.data;
                let kode = response.data.kode;
                $('#modalnama_agen').val(data.c_name);
                $('#modalnama_customer').val(data.m_name);
                $('#modal_website').val(data.sw_website);
                $('#modal_transaksi').val(data.sw_transactioncode);
                $('#modal_produk').val(data.i_name);
                $('#modal_kuantitas').val(data.sw_qty);
                $('#modal_satuan').val(data.u_name);
                $('#modal_label-satuan').html(data.u_name);
                $('#modal_harga').val(convertToRupiah(parseInt(data.sw_price)));
                 $('#modal_total').val(convertToRupiah(parseInt(data.sw_totalprice)));
                $('#modal_note').val(data.sw_note);

                table_detailKPW.clear().destroy();
                table_detailKPW = $('#table_DetailKPW').DataTable({
                    bAutoWidth: true,
                    responsive: true,
                    info: false,
                    searching: false,
                    paging: false
                });
                table_detailKPW.columns.adjust();

                $.each(response.data.kode, function (key, val) {
                    table_detailKPW.row.add([
                        val.sc_code,
                        val.sc_qty
                    ]).draw(false);
                })

                $('#modal_detailKPW').modal('show');
            }).catch(function (error) {
                loadingHide();
            })

        }

        function editKPW(id) {
            $.ajax({
                url: "{{url('marketing/agen/kelolapenjualanviawebsite/edit-kpw')}}"+"/"+id,
                type: "get",
                dataType: "json",
                success:function(resp) {
                    loadingShow();
                    $('#editKPW').modal('show');
                    $('#editnama_agen').val(resp.datas.c_name);
                    $('#edit_agen').val(resp.datas.sw_agen);
                    $('#editnama_customer').val('CUSTOMER');
                    $('#edit_website').val(resp.datas.sw_website);
                    $('#edit_transaksi').val(resp.datas.sw_transactioncode);
                    $('#edit_produk').val(resp.datas.i_name);
                    $('#edit_produkid').val(resp.datas.i_id);
                    $('#edit_kuantitas').val(resp.datas.sw_qty);
                    var price = parseInt(resp.datas.sw_price)
                    var total_price = parseInt(resp.datas.sw_totalprice)
                    $('#edit_harga').val(price);
                    $('#edit_total').val(total_price)
                    $('#edit_note').val(resp.datas.sw_note);
                    
                    $("#edit_satuan").find('option').remove();
                    var option = '';
                    var selected1, selected2, selected3;
                    if (resp.units.id1 == resp.datas.sw_unit) {
                        var selected1 = "selected";
                    } else {
                        var selected1 = "";
                    }
                    if (resp.units.id2 == resp.datas.sw_unit) {
                        var selected2 = "selected";
                    } else {
                        var selected2 = "";
                    }
                    if (resp.units.id3 == resp.datas.sw_unit) {
                        var selected3 = "selected";
                    } else {
                        var selected3 = "";
                    }

                    option += '<option value="'+resp.units.id1+'" '+selected1+'>'+resp.units.name1+'</option>';
                    if (resp.units.id2 != null && resp.units.id2 != resp.units.id1) {
                        option += '<option value="'+resp.units.id2+'" '+selected2+'>'+resp.units.name2+'</option>';
                    }
                    if (resp.units.id3 != null && resp.units.id3 != resp.units.id2) {
                        option += '<option value="'+resp.units.id3+'" '+selected3+'>'+resp.units.name3+'</option>';
                    }
                    $("#edit_satuan").append(option);

                    $('#table_EditKPW').DataTable().clear().destroy();
                    table_editKPW = $('#table_EditKPW').DataTable({
                        bAutoWidth: true,
                        responsive: true,
                        info: false,
                        searching: false,
                        paging: false
                    });
                    table_editKPW.columns.adjust();

                    $.each(resp.code, function (key, val) {
                        table_editKPW.row.add([
                            '<input type="text" value="'+val.sc_code+'" class="form-control bg-light code_sd" readonly disabled/><input type="hidden" name="code_s[]" class="code_s" value="'+val.sc_code+'"/>',
                            '<input type="number" min="1" name="qty_s[]" value="'+val.sc_qty+'" class="qty_s form-control"/>',
                            '<div class="text-center"><button class="btn btn-sm rounded btn-danger btn-trash"><i class="fa fa-trash"></i></button></div>'
                        ]).draw(false);
                    });
                    loadingHide();
                }
            });
        }

        $(document).on('click', '.btn-trash', function () {            
            table_editKPW
                .row( $(this).parents('tr') )
                .remove()
                .draw();
        });



        function cekCodeEdit(e){
            if (e.keyCode == 13){
                addCodeEdit();
            }
        }

        var counter = 0;
        function addCodeEdit() {
            loadingShow();
            //cek stockdt
            let agen = $('#edit_agen').val();
            let code = $('#add_editCode').val();
            let item = $('#edit_produkid').val();
            axios.get('{{ route("kelolapenjualanviawebsite.cekProductionCode") }}', {
                params:{
                    "posisi": agen,
                    "kode": code,
                    "item": item
                }
            }).then(function (response) {
                loadingHide();
                code = code.toUpperCase();
                if (response.data.status == 'gagal'){
                    messageFailed('Peringatan', 'Kode tidak ditemukan');
                } else if (response.data.status == 'sukses'){
                    let qty = $('#add_codeQty').val();
                    if (qty == '' || qty == 0 || qty == null){
                        qty = 1;
                    }
                    let values = $("input[name='code_s[]']")
                        .map(function(){return $(this).val();}).get();
                    if (!values.includes(code)){
                        ++counter;
                        table_editKPW.row.add([
                            "<input type='text' class='form-control form-control-sm bg-light code_sd' value='"+code+"' readonly disabled><input type='hidden' name='code_s[]' class='code_s' value='"+code+"'>",
                            "<input type='number' min='1' class='form-control form-control-sm qty_s' name='qty_s[]' value='"+qty+"'>",
                            "<div class='text-center'><button class='btn btn-sm rounded btn-danger btn-trash'><i class='fa fa-trash'></i></button></div>"
                        ]).draw(false);
                    } else {
                        messageWarning("Perhatian", "Kode sudah ada");
                        let idx = values.indexOf(code);
                        let qtylama = $('.qty_s').val();
                        let total = parseInt(qty) + parseInt(qtylama);
                        $('.qty_s').val(total);
                        $('.qty_s').focus();
                    }
                }
            }).catch(function (error) {
                loadingHide();
                alert('error');
            });
        }

        function deleteKPW(id) {
            $.confirm({
                animation: 'RotateY',
                closeAnimation: 'scale',
                animationBounce: 1.5,
                icon: 'fa fa-exclamation-triangle',
                title: 'Peringatan!',
                content: 'Apa anda yakin akan menghapus transaksi ini?',
                theme: 'disable',
                buttons: {
                    info: {
                        btnClass: 'btn-blue',
                        text: 'Ya',
                        action: function () {
                            loadingShow();
                            axios.get('{{ route("kelolapenjualan.deleteKPW") }}', {
                                params:{
                                    '_token': '{{ @csrf_token() }}',
                                    'id': id
                                }
                            }).then(function (response) {
                                loadingHide();
                                if (response.data.status == 'sukses'){
                                    messageSuccess("Berhasil", "Transaksi berhasil dihapus");
                                    table_historykpw.ajax.reload();
                                } else if (response.data.status == 'gagal'){
                                    messageFailed("Gagal", "Transaksi gagal dihapus");
                                }
                            }).catch(function (error) {
                                loadingHide();
                                alert('error');
                            })
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
        }
    </script>
@endsection
