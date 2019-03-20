@extends('main')

@section('content')

    {{--@include('produksi.pembayaran.modal')--}}
    @include('produksi.pembayaran.bayar')

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

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Pembayaran </h3>
                            </div>
                            <!-- <div class="header-block pull-right">

                                <a class="btn btn-primary" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
                            </div> -->
                        </div>
                        <div class="card-block">
                            <section>
                                <div class="row">
                                    <div class="col-2 ml-3">
                                        <label for="">Pilih Order</label>
                                    </div>
                                    <div class="col-6">
                                        <select name="po_nota" id="po_nota"
                                                class="form-control form-control-sm select2">
                                            @foreach($data as $po)
                                                @if($po->terbayar != $po->value)
                                                    <option value="{{ $po->id }}">{{ $po->nota }}
                                                        - {{ $po->supplier }}</option>
                                                @endif
                                            @endforeach
                                        <!-- <option value="">-</option>
							<option value="">001533903</option>
							<option value="">001433953</option> -->
                                        </select>
                                    </div>
                                    <div class="">
                                        <!-- <button class="btn btn-primary btn-go">Go</button> -->
                                    </div>
                                </div>
                                <hr style="border:0.5px solid grey">
                                <div class="table-responsive termin-table">
                                    <table class="table table-striped table-hover w-100" cellspacing="0"
                                           id="table_pembayaran">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th>Termin</th>
                                            <th>Estimasi</th>
                                            <th>Nominal</th>
                                            <th>Terbayar</th>
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
        });

        $('#po_nota').on('select2:select', function () {
            TablePembayaran();
        })

        var tb_pembayaran;

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

        function bayar(id,termin){
            loadingShow();
            axios.get(baseUrl+'/produksi/pembayaran/bayar', {
                params: {
                    id: id,
                    termin: termin
                }
            })
                .then(function (response) {
                    if (response.data.status == "Failed") {
                        loadingHide();
                        messageFailed("Gagal", "Terjadi kesalahan sistem");
                    } else if (response.data.status == "Success") {
                        loadingHide();
                        $("#poi").val(response.data.data.poid);
                        $("#terminid").val(response.data.data.termin);
                        $("#nota").val(response.data.data.nota);
                        $("#supplier").val(response.data.data.supplier);
                        $("#tgl_beli").val(response.data.data.tanggal_pembelian);
                        $("#termin").val(response.data.data.termin);
                        $("#tagihan").val(convertToRupiah(response.data.data.tagihan));
                        $("#terbayar").val(convertToRupiah(response.data.data.terbayar));
                        $("#kekurangan").val(convertToRupiah(response.data.data.kekurangan));
                        $("#modalBayar").modal("show");
                    }
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error);
                })
                .then(function () {
                    // always executed
                });

        }

        function Detail(idx, termin) {
            // loadingShow();
            // axios.get(baseUrl + "/produksi/pembayaran/show/" + idx + "/" + termin)
            //     .then(function (response) {
            //         // handle success
            //         console.log(response);
            //         if (response.data.status == "Failed") {
            //             loadingHide();
            //             messageFailed("Gagal", "Terjadi kesalahan sistem");
            //         } else if (response.data.status == "Success") {
            //             loadingHide();
            //         }
            //     })
            //     .catch(function (error) {
            //         // handle error
            //         loadingHide();
            //         messageWarning("Error", error);
            //     })
            //     .then(function () {
            //         // always executed
            //     });
            
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
                    $('#nilai_bayar').val('Rp. 0');
                    updateSisaPembayaran();
                    $('#detail').modal('show');
                }
            })
        }

        $("#nilai_bayar").on('keyup', function (evt) {
            evt.preventDefault();
            updateSisaPembayaran();
        })

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
