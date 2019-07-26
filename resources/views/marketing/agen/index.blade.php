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
    @include('marketing.agen.penjualanviaweb.modal_create')
    @include('marketing.agen.penjualanviaweb.modal-detail')
    @include('marketing.agen.orderproduk.detailDO')
    @include('marketing.agen.orderproduk.modal-acceptance')
    @include('marketing.agen.kelolapenjualan.modal-search')
    @include('marketing.agen.kelolapenjualan.modal')
    @include('marketing.agen.inventoryagen.modal_detail_agen')

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
                               aria-controls="orderprodukagenpusat" data-toggle="tab" role="tab">Order ke Agen /
                                Cabang</a>
                        </li>
                        <li class="nav-item">
                            <a href="#kelolapenjualan" class="nav-link" data-target="#kelolapenjualan"
                               aria-controls="kelolapenjualan" data-toggle="tab" role="tab">Kelola Penjualan
                                Langsung </a>
                        </li>
                        <li class="nav-item" onclick="tableHistoryColumn()">
                            <a href="#penjualanviaweb" class="nav-link" data-target="#penjualanviaweb"
                               aria-controls="penjualanviaweb" data-toggle="tab" role="tab">Kelola Penjualan Website</a>
                        </li>
                        <li class="nav-item">
                            <a href="#dataLaporan" class="nav-link" data-target="#dataLaporan"
                               aria-controls="dataLaporan" data-toggle="tab" role="tab">Kelola Laporan
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
                        @include('marketing.agen.laporan.index')
                    </div>
                </div>
            </div>
        </section>
    </article>

@endsection
@section('extra_script')
<!-- script for time/date in each-tabs -->
<script type="text/javascript">
    $(document).ready(function() {
        var cur_date = new Date();
        var first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
        var last_day =   new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
        // order produk ke agen
        $('#date_from_od').datepicker('setDate', first_day);
        $('#date_to_od').datepicker('setDate', last_day);
    })
</script>

<!-- time setup -->
<script type="text/javascript">
    $(document).ready(function() {
        cur_date = new Date();
        first_day = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1);
        last_day = new Date(cur_date.getFullYear(), cur_date.getMonth() + 1, 0);
        $('#date_from_kpl').datepicker('setDate', first_day);
        $('#date_to_kpl').datepicker('setDate', last_day);
        $('#date_from_kpw').datepicker('setDate', first_day);
        $('#date_to_kpw').datepicker('setDate', last_day);
    });
</script>

<!-- order product and other -->
<script type="text/javascript">
    var table_do;
    var table_kpw;
    var table_listKPW;
    var table_detailKPW, table_editKPW;
    var tableKodeProduksi;

    $(document).ready(function () {
        // start: order produk ke agen/cabang
        getStatusDO();
        $('#date_from_od').on('change', function() {
            getStatusDO();
        });
        $('#date_to_od').on('change', function() {
            getStatusDO();
        });
        $('#btn_refresh_date_od').on('click', function() {
            $('#date_from_od').datepicker('setDate', first_day);
            $('#date_to_od').datepicker('setDate', last_day);
        });
        // end: ---

        setTimeout(function () {
            var table_pus = $('#table_kelolapenjualan').DataTable({
                bAutoWidth: true
            });
        },500);

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
        let date_from = $('#date_from_od').val();
        let date_to = $('#date_to_od').val();

        $('#table_orderprodukagenpusat').DataTable().clear().destroy();
        table_do = $('#table_orderprodukagenpusat').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('orderagenpusat.getDO') }}",
                type: "get",
                data: {
                    status: st,
                    date_from: date_from,
                    date_to: date_to
                }
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

    // show detail order before acceptance
    function showDetailAc(idx)
    {
        loadingShow();
        $.ajax({
            url: baseUrl + "/marketing/agen/orderproduk/get-detail-do-accept/" + idx,
            type: "get",
            success: function(response) {
                $('#id_ac').val(response.poId);
                $('#nota_ac').val(response.po_nota);
                $('#date_ac').val(response.dateFormated);
                $('#origin_ac').val(response.get_origin.c_name);
                $('#dest_ac').val(response.get_destination.c_name);
                $('#table_detail_ac tbody').empty();
                $.each(response.get_p_o_dt, function (index, val) {
                    no = '<td>'+ (index + 1) +'</td>';
                    kodeXnamaBrg = '<td>'+ val.get_item.i_code +' - '+ val.get_item.i_name +'</td>';
                    qty = '<td class="digits">'+ val.pod_qty +'</td>';
                    unit = '<td>'+ val.get_unit.u_name +'</td>';
                    aksi = '<td><button type="button" class="btn btn-info btn-sm" onclick="getDetailDOCode('+ val.pod_productorder +', '+ val.pod_item +')">Lihat Kode</button></td>';
                    appendItem = no + kodeXnamaBrg + qty + unit + aksi;
                    $('#table_detail_ac > tbody:last-child').append('<tr>'+ appendItem +'</tr>');

                    if ( $.fn.DataTable.isDataTable('#table_detail_ackode') ) {
                        $('#table_detail_ackode').DataTable().destroy();
                    }
                    $('#tblRemittanceList tbody').empty();

                    tableKodeProduksi = $('#table_detail_ackode').DataTable({
                        "searching": false,
                        "paging": false,
                    });
                    tableKodeProduksi.clear();
                    $('#product_name').html('');
                });
                //mask digits
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

                $('#modalAcceptanceDO').modal('show');
                $('#modalAcceptanceDO').on('shown.bs.modal', function() {
                    $('#dateReceive_ac').datepicker('setDate', new Date());
                });
                loadingHide();
            },
            error: function(xhr, status, error) {
                let err = JSON.parse(xhr.responseText);
                messageWarning('Error', err.message);
                loadingHide();
            }
        });
    }
    // show list of production-code that will be received (acceptance)
    function getDetailDOCode(id, itemId)
    {
        loadingShow();
        axios.get('{{ route("orderagenpusat.getDetailDOCode") }}', {
            params:{
                "id": id,
                "itemId": itemId
            }
        })
        .then(function (response) {
            loadingHide();
            let data = response.data;
            tableKodeProduksi.clear();
            $.each(data.get_p_o_dt[0].get_prod_code, function(idx, val) {
                tableKodeProduksi.row.add([
                idx + 1,
                val.poc_code,
                val.poc_qty
                ]).draw(false);
            })
            $('#product_name').html(data.get_p_o_dt[0].get_item.i_name);
        })
        .catch(function (error) {
            loadingHide();
            messageWarning('Error', 'Terjadi kesalahan : '+ error);
        })
    }
    // acc receive item
    function terimaDO() {
        let id = $('#id_ac').val();
        let dateReceive = $('#dateReceive_ac').val();
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
                        $('#modalAcceptanceDO').modal('hide');

                        return $.ajax({
                            type: "post",
                            url: surl,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                date: dateReceive
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
                {data: 'status'},
                {data: 'kondisi'},
                {data: 'qty'},
                {data: 'aksi', className: 'text-center'}
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

    function detail_agen(id) {
        loadingShow();

        $.ajax({
            url: "{{url('/marketing/agen/get-detail-inventory')}}"+"/"+id,
            type: "get",
            success:function(resp){
                $('#owner_s').val(resp.data[0].pemilik);
                $('#owner_r').val(resp.data[0].pemilik);
                $('#position_s').val(resp.data[0].position);
                $('#position_r').val(resp.data[0].position);
                $('#item_s').val(resp.data[0].i_name);
                $('#item_r').val(resp.data[0].i_name);

                $('#table_inventory_agen').DataTable().clear().destroy()
                var tb_dtInventory = $('#table_inventory_agen').DataTable({
                    responsive: true,
                    info: false,
                    searching: false,
                    paging: false
                });
                $.each(resp.data, function(key, val){
                    let angka = val.sd_qty;
                    if (val.sd_qty == null) {
                        qty = '';
                    } else {
                        qty = convertToRibuan(angka);
                    }
                    tb_dtInventory.row.add([
                        val.sd_code,
                        '<div class="text-right">'+qty+'</div>'
                    ]).draw(false);
                });
                $('#modalDetail_agen').modal('show');
                loadingHide();
            }
        })
    }
</script>

<!-- kelola penjualan langsung -->
<script type="text/javascript">
        $(document).ready(function () {
            // remove filter agent for 'cabang' and 'agent'
            if ($('.current_user_type').val() != 'PUSAT') {
                $('.filter_agent').addClass('d-none');
                $('#filter_agent_code_kpl').attr('disabled', true);
            } else {
                $('.filter_agent').removeClass('d-none');
                $('#filter_agent_code_kpl').attr('disabled', false);
            }

            $('#date_from_kpl').on('change', function () {
                TableListKPL();
            });
            $('#date_to_kpl').on('change', function () {
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

            setTimeout(function () {
                TableListKPL();
            },1000);

        });

        function tableHistoryColumn() {
            table_listKPW.columns.adjust();
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
                        diskon = '<td class="rupiah">' + parseInt(val.sd_discvalue) + '</td>';
                        totalPrice = '<td class="rupiah">' + parseInt(val.sd_totalnet) + '</td>';
                        itemToAppend = nama + unit + qty + price + diskon + totalPrice;
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
                        allowMinus: false
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
                        allowMinus: false
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
                        if (resp.data.status == "Y" && resp.data.pengiriman == 'P') {
                            status = "Disetujui, barang dalam perjalanan";
                        }
                        else if (resp.data.status == "Y" && resp.data.pengiriman == 'Y') {
                            status = "Disetujui, barang telah diterima";
                        }
                        else if (resp.data.status == "N") {
                            status = "Ditolak";
                        }
                        else {
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
                            searching: false,
                            info: false,
                            lengthChange: false,
                            paging: false,
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
                            }
                        });
                        loadingHide();
                        $("#detailDO").modal('show');
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

        $('.input-harga').inputmask("currency", {
            radixPoint: ",",
            groupSeparator: ".",
            digits: 0,
            autoGroup: true,
            prefix: ' Rp. ', //Space after $, this will not truncate the first character.
            rightAlign: true,
            autoUnmask: true,
            nullable: false,
            allowMinus: false
            // unmaskAsNumber: true,
        });

        $(document).on('click', '.btn-trash', function () {
            table_editKPW
                .row( $(this).parents('tr') )
                .remove()
                .draw();
        });

    </script>

<!-- kelola penjualan web -->
<script type="text/javascript">
    var counter = 0;
    $(document).ready(function() {
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

        $('#btn_filter_kpw').on('click', function () {
            TableListKPW();
        });

        // tambahan dirga
            // alert($('#option-cabang').val());

            var lineChartData = {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                    label: 'Sisa Hutang',
                    borderColor: '#33b5e5',
                    backgroundColor: 'rgba(51, 181, 229, 0.3)',
                    fill: true,
                    data: [0, 0, 0, 0, 0],
                    yAxisID: 'y-axis-1',
                    pointRadius: 4,
                }, {
                    label: 'Total Pendapatan',
                    borderColor: '#00C851',
                    backgroundColor: 'rgba(0, 200, 81, 0.3)',
                    fill: true,
                    data: [0, 0, 0, 0, 0],
                    yAxisID: 'y-axis-2',
                    pointRadius: 4,
                }]
            };

            window.onload = function() {
                var ctx = document.getElementById('canvasku').getContext('2d');
                window.myLine = Chart.Line(ctx, {
                    data: lineChartData,
                    options: {
                        responsive: true,
                        stacked: false,
                        legend: {
                            display: false
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        title: {
                            display: false,
                            text: 'Chart.js Line Chart - Multi Axis'
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            yAxes: [{
                                type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                                display: true,
                                position: 'left',
                                id: 'y-axis-1',
                            }, {
                                type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                                display: true,
                                position: 'right',
                                id: 'y-axis-2',

                                // grid line settings
                                gridLines: {
                                    drawOnChartArea: false, // only want the grid lines for one axis to show up
                                },
                            }],
                        }
                    }
                });
            };

            axios.get('{{ Route("agen.laporan") }}?search='+$('#option-cabang').val())
                    .then((response) => {
                        // console.log(response.data);

                        $('#totPenjualan').html(humanizePrice(response.data.penjualan))
                        $('#totHutang').html(humanizePrice(response.data.sisahutang))

                        lineChartData.datasets[0].data = JSON.parse(response.data.sr_penjualan);
                        lineChartData.datasets[1].data = JSON.parse(response.data.sr_hutang);
                        window.myLine.update();

                        $('#cover-spin').hide();
                    })

            $('#option-cabang').change(function(){
                var ctx = $(this);
                $('#cover-spin').show();

                axios.get('{{ Route("agen.laporan") }}?search='+$('#option-cabang').val())
                        .then((response) => {
                            // console.log(response.data);

                            $('#totPenjualan').html(humanizePrice(response.data.penjualan))
                            $('#totHutang').html(humanizePrice(response.data.sisahutang))

                            lineChartData.datasets[0].data = JSON.parse(response.data.sr_penjualan);
                            lineChartData.datasets[1].data = JSON.parse(response.data.sr_hutang);
                            window.myLine.update();

                            $('#cover-spin').hide();
                        })
            })

         $('.set-total').on('click keyup', function(){
            let qty = $('#edit_kuantitas').val();
            let harga = $('#edit_harga').val();

            let total = parseInt(qty) * parseInt(harga);
            $('#edit_total').val(total);
        });

    });

    // tambahan dirga
    function humanizePrice(alpha){
      var kl = alpha.toString().replace('-', '');
      bilangan = kl;
      var commas = '00';


      if(bilangan.split('.').length > 1){
        commas = bilangan.split('.')[1];
        bilangan = bilangan.split('.')[0];
      }

      var number_string = bilangan.toString(),
        sisa  = number_string.length % 3,
        rupiah  = number_string.substr(0, sisa),
        ribuan  = number_string.substr(sisa).match(/\d{3}/g);

      if (ribuan) {
        separator = sisa ? ',' : '';
        rupiah += separator + ribuan.join(',');
      }

      // Cetak hasil
      return rupiah+'.'+commas; // Hasil: 23.456.789
    }

    function TableListKPW() {
        $('#table_penjualanviaweb').dataTable().fnDestroy();
        table_listKPW = $('#table_penjualanviaweb').DataTable({
            responsive: true,
            processing: true,
            bAutoWidth: true,
            serverSide: true,
            ajax: {
                url: "{{ route('kelolapenjualanviawebsite.getListKPW') }}",
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

    function detailKPW(id) {
        loadingShow();
        $.ajax({
            url: "{{ route('kelolapenjualanviawebsite.getDetailKPW') }}",
            type: 'get',
            data: {
                'id': id
            },
            success: function (response) {
                loadingHide();
                $('#nota_kpwdt').val(response.s_nota);
                $('#memName_kpwdt').val(response.get_member.m_name);
                $('#transCode_kpwdt').val(response.get_sales_web.sw_transactioncode);
                $('#webUrl_kpwdt').val(response.get_sales_web.sw_website);
                $('#total_kpwdt').val(parseInt(response.s_total));
                $('#table_kpwdt tbody').empty();
                $.each(response.get_sales_dt, function (key, val) {
                    nama = '<td>' + val.get_item.i_name + '</td>';
                    unit = '<td>' + val.get_unit.u_name + '</td>';
                    qty = '<td class="digits">' + parseInt(val.sd_qty) + '</td>';
                    price = '<td class="rupiah">' + parseInt(val.sd_value) + '</td>';
                    diskon = '<td class="rupiah">' + parseInt(val.sd_discvalue) + '</td>';
                    totalPrice = '<td class="rupiah">' + parseInt(val.sd_totalnet) + '</td>';
                    itemToAppend = nama + unit + qty + price + diskon + totalPrice;
                    $('#table_kpwdt > tbody:last-child').append('<tr>' + itemToAppend + '</tr>');
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
                    allowMinus: false
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
                    allowMinus: false
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
                $('#detailkpw').modal('show');
            },
            error: function (e) {
                loadingHide();
                messageWarning('Error', 'Terjadi kesalahan : '+ e);
            }
        });

    }


    // edit detail penjualan
    function editKPW(id) {
        window.location.href = baseUrl +'/marketing/agen/kelolapenjualanviawebsite/edit-kpw/'+ id;
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
                        axios.get('{{ route("kelolapenjualanviawebsite.deleteKPW") }}', {
                            params:{
                                '_token': '{{ @csrf_token() }}',
                                'id': id
                            }
                        }).then(function (response) {
                            loadingHide();
                            if (response.data.status == 'sukses'){
                                messageSuccess("Berhasil", "Transaksi berhasil dihapus");
                                table_listKPW.ajax.reload();
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
