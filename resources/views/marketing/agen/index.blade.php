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
                        <li class="nav-item">
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
            $('#btn_search_date_kpw').on('click', function () {
                TableListKPW();
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
                TableListKPW();
            });
        });

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
                    console.log(response);
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

        // append data to table-list-agens
        function appendListAgentsKPL() {
            $.ajax({
                url: "{{ route('kelolapenjualan.getAgentsKPL') }}",
                type: 'get',
                data: {
                    cityId: $('#citiesKPL').val()
                },
                success: function (response) {
                    // console.log('zxc');
                    console.log(response);
                    console.log(response.length);
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
                    // console.log($('#table_search_agen_kpl'));
                }
            });
        }

        // add filter-agent
        function addFilterAgent(agentCode, agentName) {
            $('#filter_agent_name_kpl').val(agentName);
            $('#filter_agent_code_kpl').val(agentCode);
            $('#searchAgen').modal('hide');
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
                    // console.log(data);
                    $('#nama_agen').find('option').remove();
                    $('#nama_agen').append('<option value="" selected disabled> == Pilih Agen ==</option>')
                    $.each(data, function(index, val) {
                        // console.log(val, val.a_id);
                        $('#nama_agen').append('<option value="'+ val.c_id +'" data-code="'+ val.a_code +'">'+ val.a_name +'</option>');
                    });
                    $('#nama_agen').focus();
                    $('#nama_agen').select2('open');
                },
                error: function(e) {
                    // console.log('get konsigner error: ');
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
                $("#satuan").append('<option data-nama="'+response.data.get_unit2.u_name+'" value="' + response.data.get_unit2.u_id + '">' + response.data.get_unit2.u_name + '</option>');
                $("#satuan").append('<option data-nama="'+response.data.get_unit3.u_name+'" value="' + response.data.get_unit3.u_id + '">' + response.data.get_unit3.u_name + '</option>');
            }).catch(function (error) {
                alert("error");
            })
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

        $('#satuan').change(function(){
            var selected = $(this).find('option:selected').data('nama');
            $('#label-satuan').html(selected);
        });
        
        function saveSalesWeb() {
            let kuantitas = $('#kuantitas').val();
            let qty = $("input[name='qty_code[]']")
                .map(function(){return $(this).val();}).get();
            let totalqty = 0;
            for (let i = 0; i < qty.length; i++) {
                totalqty = totalqty + qty[i];
            }

            if (parseInt(kuantitas) != parseInt(qty)){
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
            }
        }

        function lanjutkan() {
            valid = 1;
            let provinsi = $('#area_provinsi').val();
            let kota = $('#area_kota').val();
            let agen = $('#nama_agen').val();
            let website = $('#website').val();
            let produk = $('#id_produk').val();
            let kuantitas = $('#kuantitas').val();
            let satuan = $('#satuan').val();
            let harga = $('#harga').val();
            let note = $('#note').val();
            let kode = $("input[name='code[]']")
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
            if (website == '' || website == null){
                valid = 0;
                messageWarning("Perhatian", "Form harus lengkap");
                $('#website').focus();
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
                    "item": produk,
                    "qty": kuantitas,
                    "unit": satuan,
                    "price": harga,
                    "note": note,
                    "code": kode,
                    "qtycode": kodeqty,
                    "_token": '{{ csrf_token() }}'
                }).then(function (response) {
                    if (response.data.status == 'success'){
                        loadingHide();
                        messageSuccess("Berhasil", "Data berhasil disimpan");
                    } else if (response.data.status == 'gagal'){
                        loadingHide();
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

            });
        }
    </script>
@endsection
