@extends('main')

@section('content')

    @include('produksi.pembayaran.history.modal-detail')
    @include('produksi.pembayaran.bayar')
    @include('produksi.pembayaran.history.modal-search')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Pembayaran </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktifitas Produksi</span>
                / <span class="text-primary" style="font-weight: bold;">Pembayaran</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <ul class="nav nav-pills mb-3" id="Tabzs">
                        <li class="nav-item">
                            <a href="#pembayaran" class="nav-link active" data-target="#pembayaran"
                               aria-controls="pembayaran" data-toggle="tab" role="tab">Pembayaran</a>
                        </li>
                        <li class="nav-item">
                            <a href="#history" class="nav-link" data-target="#history" aria-controls="history"
                               data-toggle="tab" role="tab">History Pembayaran</a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        @include('produksi.pembayaran.pembayaran.index')
                        @include('produksi.pembayaran.history.index')

                    </div>

                </div>

            </div>

        </section>

    </article>

    <!-- Modal -->
    <div id="searchLanjutan" class="modal fade animated fadeIn" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-gradient-info">
                    <h4 class="modal-title">Pencarian Lanjutan</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-inline">
                        <div class="form-group">
                            <label for="timepay">Waktu Pembayaran </label>
                            <input type="text" class="form-control input-hari" id="timepay" style="margin-left: 10px">
                            <button class="btn-primary btn" onclick="cariTermin()" type="button" style="margin-left: 10px">Cari</button>
                        </div>
                    </form>
                    <div class="table-responsive" style="margin-top: 40px">
                        <table class="table table-striped table-hover table-bordered display nowrap" cellspacing="0" id="table_pencarian_lanjutan">
                            <thead class="bg-primary">
                            <tr>
                                <th width="1%">No</th>
                                <th>Nota Order</th>
                                <th>Supplier</th>
                                <th>Jatuh Tempo</th>
                                <th>Tagihan</th>
                                <th>Terbayar</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extra_script')

    <!-- script for history-pembayaran -->
    <script type="text/javascript">
        var table_termin;
        $(document).ready(function () {
            table_termin = $('#table_pencarian_lanjutan').DataTable();
            $('#findNota').on('click', function () {
                $('#findNota').val('');
            });
            $('#findNota').autocomplete({
                source: baseUrl + '/produksi/pembayaran/find-nota-history',
                minLength: 1,
                select: function (event, data) {

                },
                close: function () {
                    tableListHistory();
                }
            });

            $('.input-hari').maskMoney({
                thousands: ".",
                precision: 0,
                decimal: ",",
                suffix: " Hari"
            });

            $('#findSupplier').on('click', function () {
                $('#findSupplier').val('');
                $('#supplierId').val('');
            });
            $('#findSupplier').autocomplete({
                source: baseUrl + '/produksi/pembayaran/find-supplier',
                minLength: 1,
                select: function (event, data) {
                    if (data.item.id !== null) {
                        $('#supplierId').val(data.item.data.s_id);
                    }
                },
                // append autocomplete to modal
                appendTo: '#searchNotaModal'
            });

            $('#search-nota').on('click', function () {
                tableListNota();
            });
            // init dataTable
            tableListNota();
            //tableListHistory();
        });

        function cariTermin() {
            $('#table_pencarian_lanjutan').dataTable().fnDestroy();
            table_termin = $('#table_pencarian_lanjutan').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pembayaran.getTerminByDate') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "hari": $('#timepay').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'po_nota'},
                    {data: 's_company'},
                    {data: 'pop_datetop'},
                    {data: 'pop_value'},
                    {data: 'pop_pay'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        function gunakanNota(id) {
            $('#po_nota').val(id).trigger('change');
            $('#searchLanjutan').modal('hide');
            TablePembayaran();
        }

        // show detail pembayaran
        function showDetailHistory(idPO) {
            $.ajax({
                url: baseUrl + "/produksi/pembayaran/show-detail-history/" + idPO,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    $('#detail_history_nota').val(response[0].get_p_o.po_nota);
                    $('#detail_history_supplier_name').val(response[0].get_p_o.get_supplier.s_name);
                    $('#table_detail_history tbody').empty();
                    $.each(response, function (i, val) {
                        termin = val.pop_termin;
                        estimasi = GetFormattedDate(val.pop_datetop);
                        nominal = '<span class="float-right rupiah">' + parseInt(val.pop_value) + '</span>';
                        terbayar = '<span class="float-right rupiah">' + parseInt(val.pop_pay) + '</span>';
                        if (val.pop_status === 'N') {
                            status = 'Belum Lunas';
                        } else {
                            status = 'Lunas';
                        }
                        $('#table_detail_history > tbody:last-child').append('<tr><td>' + termin + '</td><td>' + estimasi + '</td><td>' + nominal + '</td><td>' + terbayar + '</td><td>' + status + '</td>');
                    });
                    //mask money
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
                    $('#detailModal').modal('show');
                }
            })
        }

        // refresh list history pembayaran
        function refreshHistory() {
            tableListHistory();
        }

        // change date format using js (params: yyyy-mm-dd, output: d M Y)
        function GetFormattedDate(date) {
            const monthNames = ["January", "February", "March", "April", "May",
                "June", "July", "August", "September", "October", "November", "December"
            ];
            var dateX = new Date(date);
            var month = dateX.getMonth();
            var day = ('0' + dateX.getDate()).slice(-2);
            var year = dateX.getFullYear();
            return day + ' ' + monthNames[month] + ' ' + year;
        }

        var tb_listhistory;

        function tableListHistory() {
            $('#table_history').dataTable().fnDestroy();
            tb_listhistory = $('#table_history').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pembayaran.getListHistory') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "nota": $('#findNota').val()
                    }
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'po_nota'},
                    {data: 'supplier'},
                    {data: 'date'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        var tb_listnota;

        function tableListNota() {
            $('#table_search_nota').dataTable().fnDestroy();
            tb_listnota = $('#table_search_nota').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pembayaran.getListNota') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "date_from": $('#date_from').val(),
                        "date_to": $('#date_to').val(),
                        "supplier": $('#supplierId').val()
                    }
                },
                columns: [
                    {data: 'date'},
                    {data: 'po_nota'},
                    {data: 'supplier'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // add-filter from modal-search-nota
        function addFilter(nota) {
            $('#searchNotaModal').modal('hide');
            $('#findNota').val(nota);
            tableListHistory();
        }

    </script>

    <!-- script for pembayaran -->
    <script type="text/javascript">
        $(document).ready(function () {
            table = $('#table_pembayaran').DataTable();
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
            });

            TablePembayaran();
            $(".termin-table").show();

            // Tambah Table
            $(document).on('click', '.btn-hapus-termin-gan', function () {
                $(this).parents('tr').remove();
            });

            $(document).on('click', '.btn-tambah-termin-gan', function () {
                var ini = $(this);
                $.confirm({
                    animation: 'RotateY',
                    closeAnimation: 'scale',
                    animationBounce: 1.5,
                    icon: 'fa fa-exclamation-triangle',
                    title: 'Peringatan!',
                    content: 'Apa anda yakin mau menambah data?',
                    theme: 'disable',
                    buttons: {
                        info: {
                            btnClass: 'btn-blue',
                            text: 'Ya',
                            action: function () {
                                $('#table_pembayaran')
                                    .append(
                                        '<tr>' +
                                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>' +
                                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>' +
                                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>' +
                                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>' +
                                        '<td><input type="text" class="form-control form-control-sm" readonly=""></td>' +
                                        '<div class="status-danger"><p>Belum</p></div>' +
                                        '<td width="15%"><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>' +
                                        '</tr>'
                                    );
                                // ini.parents('.btn-group').html('<button class="btn btn-success btn-enable" type="button" title="Enable"><i class="fa fa-check-circle"></i></button>');
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
            // $('.btn-tambah-termin-gan').on('click',function(){

            // End


            // $(document).on('click', '.btn-enable', function(){
            // 	$.toast({
            // 		heading: 'Information',
            // 		text: 'Data Berhasil di Aktifkan.',
            // 		bgColor: '#0984e3',
            // 		textColor: 'white',
            // 		loaderBg: '#fdcb6e',
            // 		icon: 'info'
            // 	})
            // 	$(this).parents('.btn-group').html('<button class="btn btn-warning btn-edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>'+
            //                             		'<button class="btn btn-danger btn-disable" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>')
            // })

            // function table_hapus(a){
            // 	table.row($(a).parents('tr')).remove().draw();
            // }

            $("#fromBayarTermin").on("submit", function (evt) {
                var id = $('#poid').val();
                var termin = $('#termin').val();
                evt.preventDefault();
                if ($("#nilai_bayar").val() == "" || $("#nilai_bayar").val() == "Rp. 0") {
                    $("#nilai_bayar").focus();
                    messageWarning("Peringatan", "Masukkan nominal pembayaran")
                } else {
                    loadingShow();
                    axios.post(baseUrl + '/produksi/pembayaran/bayar', $("#fromBayarTermin").serialize())
                        .then(function (response) {
                            if (response.data.status == "Failed") {
                                messageFailed("Peringatan", response.data.message);
                            } else if (response.data.status == "Success") {
                                tb_pembayaran.ajax.reload();
                                $("#modalBayar").modal("hide");
                                messageSuccess("Berhasil", response.data.message);
                                // printNota(id, termin);
                            }
                        })
                        .catch(function (error) {
                            messageWarning("Error", error);
                        })
                        .then(function () {
                            loadingHide();
                        });

                }
            });

        });



        $('#po_nota').on('select2:select', function () {
            TablePembayaran();
        });

        var tb_pembayaran;

        function printNota(id, termin) {
            // alert(id); return false;
            $.ajax({
                url: '{{ url("/produksi/pembayaran/nota") }}',
                type: 'get',
                data: {
                    id: id, termin: termin
                }
            })
        }

        function TablePembayaran() {
            $('#table_pembayaran').dataTable().fnDestroy();
            tb_pembayaran = $('#table_pembayaran').DataTable({
                responsive: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pembayaran.list') }}",
                    type: "get",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "po_id": $('#po_nota').val()
                    }
                },
                columns: [
                    {data: 'pop_termin'},
                    {data: 'estimasi'},
                    {data: 'nominal'},
                    {data: 'terbayar'},
                    {data: 'status'},
                    {data: 'action'}
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
            });
        }

        // view-data -> append data to modal with dataTable
        function openNota(id, termin){
          window.open("{{ url("/produksi/pembayaran/nota?") }}" + "id=" + id + "&termin=" + termin, '_blank')
        }

        function bayar(id, termin) {
            loadingShow();
            axios.get(baseUrl + '/produksi/pembayaran/bayar-list', {
                    params: {
                        id: id,
                        termin: termin
                    }
                })
                .then(function (response) {
                    console.log(response);
                    if (response.data.status == "Failed") {
                        loadingHide();
                        messageFailed("Gagal", "Terjadi kesalahan sistem");
                    } else if (response.data.status == "Success") {
                        loadingHide();
                        $("#nilai_bayar").val('0');
                        $("#poid").val(response.data.data.poid);
                        $("#nota").val(response.data.data.nota);
                        $("#supplier").val(response.data.data.supplier);
                        $("#tgl_beli").val(response.data.data.tanggal_pembelian);
                        $("#termin").val(response.data.data.termin);
                        $("#tagihan").val(convertToRupiah(response.data.data.tagihan));
                        $("#terbayar").val(convertToRupiah(response.data.data.terbayar));
                        $("#kekurangan").val(convertToRupiah(response.data.data.kekurangan));

                        $('#cashAccountPP').empty();
                        $('#cashAccountPP').select2({
                            data: response.data.data.payment_method,
                            dropdownAutoWidth : true
                        });
                        $('#cashAccountPP').prop('selectedIndex', 0).trigger('select2:select');
                        console.log($('#cashAccountPP').val());
                        $("#modalBayar").modal("show");
                    }
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error);
                })
                .then(function () {
                    loadingHide();
                });

        }

        function Detail(idx, termin) {
            $.ajax({
                url: baseUrl + "/produksi/pembayaran/show/" + idx + "/" + termin,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    $('#table_detail tbody').empty();
                    $.each(response.data.get_p_o_dt, function (i, val) {
                        harga_satuan = formatRupiah(val.pod_value);
                        sub_total = formatRupiah(val.pod_totalnet);
                        harga_satuan = '<span class="float-left">Rp </span><span class="float-right">' + harga_satuan + '</span>';
                        sub_total = '<span class="float-left">Rp </span><span class="float-right">' + sub_total + '</span>';
                        $('#table_detail > tbody:last-child').append('<tr><td>' + val.get_item.i_code + '</td><td>' + val.get_item.i_name + '</td><td>' + val.pod_qty + '</td><td>' + val.get_unit.u_name + '</td><td>' + harga_satuan + '</td><td>' + sub_total + '</td>');
                    })
                    $('#total_nominal').val('Rp ' + formatRupiah(response.data.po_totalnet));
                    $('#nominal_termin_lbl').html('Nominal termin ' + response.data.get_p_o_payment[0].pop_termin);
                    $('#nominal_termin').val('Rp ' + formatRupiah(response.data.get_p_o_payment[0].pop_value));
                    $('#nilai_bayar').val('0');
                    updateSisaPembayaran();
                    $('#detail').modal('show');
                }
            })
        }

        function lunasiTermin() {
            $('#nilai_bayar').val($('#nominal_termin').val());
            $('#nilai_bayar').focus();
            $('#btn_simpan').focus();
            updateSisaPembayaran();
        }

        /* Fungsi formatRupiah */
        function formatRupiah(angka) {
            var number_string = angka.replace(/[^.\d]/g, '').toString();
            split = number_string.split(',');
            sisa = split[0].length % 3;
            rupiah = split[0].substr(0, sisa);
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        /* Fungsi updateSisaPembayaran */
        function updateSisaPembayaran() {
            var inpNominal = $('#nilai_bayar'),
                nominal = [].map.call(inpNominal, function (input) {
                    return input.value;
                });
            var tot_harga = $("#nominal_termin").val();
            var nomTot = 0;
            for (var i = 0; i < nominal.length; i++) {
                var nomTermin = nominal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", "");
                nomTot += parseInt(nomTermin);
            }
            var sisa = parseInt(tot_harga.replace("Rp ", "").replace(".", "").replace(".", "").replace(".", "")) - parseInt(nomTot);
            console.log(parseInt(nomTot));
            console.log(tot_harga);
            $("#sisapembayaran").val(convertToCurrency(sisa));
        }

        // fungsi convertToCurrency
        function convertToCurrency(angka) {
            var currency = '';
            var angkarev = angka.toString().split('').reverse().join('');
            for (var i = 0; i < angkarev.length; i++) if (i % 3 == 0) currency += angkarev.substr(i, 3) + '.';
            var hasil = currency.split('', currency.length - 1).reverse().join('');
            return hasil;
        }

    </script>
@endsection
