@extends('main')

@section('extra_style')
    <style type="text/css">
        .txt-readonly {
            background-color: transparent;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <form class="formCodeProd">
        <!-- modal-code-production -->
        @include('marketing.agen.penjualanviaweb.modal-code-prod')
        @include('marketing.agen.penjualanviaweb.modal-code-prod-base')
    </form>


    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Tambah Data Kelola Penjualan via Website </h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktivitas Marketing</span>
                / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen</span></a>
                / <span class="text-primary" style="font-weight: bold;"> Tambah Data Kelola Penjualan via Website</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Tambah Data Kelola Penjualan via Website</h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i
                                        class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>

                        <form class="myForm" autocomplete="off">
                            <div class="card-block">
                                <section>
                                    <div id="sectionsuplier" class="row">
                                        <input type="hidden" id="userType" value="{{ $type }}">
                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Tanggal</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                </div>
                                                <input type="text" name="dateKPL" class="form-control form-control-sm datepicker" autocomplete="off" id="dateKPL" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>

                                        @if($type == 'PUSAT')
                                            <div class="col-2">
                                                <label>Provinsi Agen</label>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <select name="provinsi" id="provinsi"
                                                            class="form-control form-control-sm select2">
                                                        <option value="" selected disabled>== Pilih Provinsi ==</option>
                                                        @foreach($provinsi as $prov)
                                                            <option
                                                                value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                <label>Kota Agen</label>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <select name="kota" id="kota"
                                                            class="form-control form-control-sm select2">
                                                        <option value="" selected disabled>== Pilih Kota ==</option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-2 col-sm-6 col-xs-12 select-agent">
                                            <label>Agen</label>
                                        </div>
                                        <div class="col-md-10 col-sm-6 col-xs-12 select-agent">
                                            <div class="form-group">
                                                <input type="hidden" value="{{ $data['user'] }}" id="user">
                                                <select name="agent" id="agent"
                                                        class="form-control form-control-sm select2">
                                                    <option value="" selected disabled>Pilih Agen</option>
                                                    @foreach($data['agents'] as $agent)
                                                        <option value="{{ $agent->c_id }}">{{ $agent->c_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Member</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <select name="member" id="member"
                                                        class="form-control form-control-sm select2">
                                                    <option value="" selected disabled>Pilih Member</option>
                                                    @foreach($data['member'] as $member)
                                                        <option
                                                            value="{{ $member->m_code }}">{{ $member->m_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Kode Transaksi</label>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control-sm form-control" name="transactionCode" id="transactionCode" style="text-transform: uppercase">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Website</label>
                                        </div>
                                        <div class="col-md-10 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control-sm form-control" name="webUrl" id="webUrl">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Catatan</label>
                                        </div>
                                        <div class="col-md-10 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <textarea class="form-control form-control-sm" name="note" id="note"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-6 col-xs-12">
                                            <label>Total</label>
                                        </div>
                                        <div class="col-md-10 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="total_harga" id="total_harga" value="Rp. 0" readonly>
                                                <input type="hidden" name="tot_hrg" id="tot_hrg">
                                            </div>
                                        </div>

                                        <div class="container">
                                            <div class="table-responsive mt-3">
                                                <table class="table table-hover table-striped diplay w-100" id="table_create">
                                                    <thead class="bg-primary">
                                                    <tr>
                                                        <th width="19%">Kode/Nama Barang</th>
                                                        <th width="10%">Satuan</th>
                                                        <th width="10%">Jumlah</th>
                                                        <th width="15%">Kode Produksi</th>
                                                        <th width="13%">Harga Satuan</th>
                                                        <th width="13%">Diskon @</th>
                                                        <th width="15%">Sub Total</th>
                                                        <th width="5%">Aksi</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="idItem[]" class="itemid">
                                                            <input type="hidden" name="kode[]" class="kode">
                                                            <input type="hidden" name="idStock[]" class="idStock">
                                                            <input type="text"
                                                                   name="barang[]"
                                                                   class="form-control form-control-sm barang"
                                                                   autocomplete="off">
                                                        </td>
                                                        <td><select name="satuan[]"
                                                                    class="form-control form-control-sm select2 satuan">
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number"
                                                                   name="jumlah[]"
                                                                   min="0"
                                                                   class="form-control form-control-sm jumlah"
                                                                   value="0" readonly>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btnCodeProd btn-sm rounded"
                                                                    type="button"><i class="fa fa-plus"></i> kode
                                                                produksi
                                                            </button>
                                                        </td>
                                                        @if ($type == 'CABANG')
                                                            <td>
                                                                <input type="text"
                                                                name="harga[]"
                                                                class="form-control form-control-sm rupiah harga">
                                                                <p class="text-danger unknow mb-0"
                                                                style="display: none; margin-bottom:-12px !important;">
                                                                Harga tidak ditemukan!</p>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <input type="text"
                                                                name="harga[]"
                                                                class="form-control form-control-sm text-right harga"
                                                                value="Rp. 0" readonly>
                                                                <p class="text-danger unknow mb-0"
                                                                style="display: none; margin-bottom:-12px !important;">
                                                                Harga tidak ditemukan!</p>
                                                            </td>
                                                        @endif
                                                        <td>
                                                            <input class="form-control form-control-sm diskon rupiah text-right" id="diskon" name="diskon[]">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="subtotal[]"
                                                                   style="text-align: right;"
                                                                   class="form-control form-control-sm subtotal"
                                                                   value="Rp. 0" readonly>
                                                            <input type="hidden" name="sbtotal[]" class="sbtotal">
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-success rounded-circle btn-tambahp">
                                                                <i
                                                                    class="fa fa-plus"></i></button>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </form>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary" id="btn_simpan" type="button">Simpan</button>
                            <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        var idStock = [];
        var idItem = [];
        var namaItem = null;
        var kode = null;
        var idxBarang = null;
        var icode = [];
        var checkitem = null;

        $(document).ready(function () {
            if ($('#userType').val() == 'PUSAT') {
                $('.select-agent').removeClass('d-none');
                $('#table_create').addClass('d-none');
            } else {
                $('.select-agent').addClass('d-none');
                $('#table_create').removeClass('d-none');
            }
            $('#agent').on('change', function () {
                $('#table_create').removeClass('d-none');
                getMember();
            });

            // re-init events for some class or id
            getEventsReady();

            if ($(".itemid").eq(idxBarang).val() == "") {
                $(".jumlah").eq(idxBarang).attr("readonly", true);
                $(".satuan").eq(idxBarang).find('option').remove();
            } else {
                $(".jumlah").eq(idxBarang).attr("readonly", false);
            }

            // append a new row to insert more items
            $('.btn-tambahp').on('click', function () {
                tambah();
            });

            // check if there is any empty value in table
            function checkForm() {
                var inpItemid = document.getElementsByClassName('itemid'),
                    item = [].map.call(inpItemid, function (input) {
                        return input.value;
                    });
                var inpHarga = document.getElementsByClassName('harga'),
                    harga = [].map.call(inpHarga, function (input) {
                        return input.value;
                    });
                var inpJumlah = document.getElementsByClassName('jumlah'),
                    jumlah = [].map.call(inpJumlah, function (input) {
                        return parseInt(input.value);
                    });

                for (var i = 0; i < item.length; i++) {
                    if (item[i] == "" || harga[i] == "Rp. 0" || jumlah[i] == 0) {
                        return "cek form";
                        break;
                    } else {
                        checkitem = "true";
                        continue;
                    }
                }
                return checkitem;
            }

            // submit-form
            $('#btn_simpan').on('click', function (evt) {
                evt.preventDefault();
                if (checkForm() == "cek form") {
                    messageWarning('Peringatan', 'Lengkapi data penjualan langsung !');
                } else {
                    $.confirm({
                        animation: 'RotateY',
                        closeAnimation: 'scale',
                        animationBounce: 1.5,
                        icon: 'fa fa-exclamation-triangle',
                        title: 'Konfirmasi!',
                        content: 'Apakah anda yakin akan menyimpan data penjualan langsung ini ?',
                        theme: 'disable',
                        buttons: {
                            info: {
                                btnClass: 'btn-blue',
                                text: 'Ya',
                                action: function () {
                                    simpan();
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
            });
        }); // end: document ready

        $(".diskon").on('keyup', function (evt) {
            let idx = $('.diskon').index(this);
            let diskon = $('.diskon').eq(idx).val();
            let harga = $('.harga').eq(idx).val();
            let jumlah = $('.jumlah').eq(idx).val();
            let subharga = (parseInt(convertToAngka(harga)) - parseInt(diskon)) * parseInt(jumlah);
            $('.subtotal').eq(idx).val(convertToRupiah(subharga));
            updateTotalTampil();
        })

        function changeSatuan() {
            // set-off first to prevent duplicate request from the previous item
            // $(".satuan").off();
            $(".satuan").on("change", function (evt) {
                loadingShow();
                var idx = $('.satuan').index(this);
                var jumlah = $('.jumlah').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }
                // check stock using marketing controller
                axios.get(baseUrl + '/marketing/agen/orderproduk/cek-stok/' + $(".idStock").eq(idx).val() + '/' + $(".itemid").eq(idx).val() + '/' + $(".satuan").eq(idx).val() + '/' + jumlah)
                    .then(function (resp) {
                        loadingHide();
                        $(".jumlah").eq(idx).val(resp.data);
                        // trigger on-input 'jumlah'
                        $(".jumlah").eq(idx).trigger('input');
                    })
                    .catch(function (error) {
                        loadingHide();
                        messageWarning("Error", error);
                    })
            })
        }

        function changeJumlah() {
            // set-off first to prevent duplicate request from the previous item
            $(".jumlah").off();
            $(".jumlah").on('input', function (evt) {
                var idx = $('.jumlah').index(this);
                var jumlah = $('.jumlah').eq(idx).val();
                if (jumlah == "") {
                    jumlah = null;
                }
                // get item price
                getPrices(idx, jumlah);
            })
        }

        // get price from selected item and count all sub-total
        function getPrices(idx, qty) {
            // skip get-prices if user is 'cabang'
            if ($('#userType').val() == 'CABANG') {
                // trigger diskon to 'keyup'
                $(".diskon").trigger('keyup');
                return false;
            }

            $.ajax({
                url: "{{ route('kelolapenjualan.getPrice') }}",
                data: {
                    "itemId": $('.itemid').eq(idx).val(),
                    "unitId": $('.satuan').eq(idx).val(),
                    "agentCode": $('#agent').val(),
                    "qty": qty
                },
                type: "get",
                dataType: 'json',
                success: function (response) {
                    var price = parseInt(response);

                    if (isNaN(price)) {
                        price = 0;
                    }
                    if (price == 0) {
                        $('.unknow').eq(idx).css('display', 'block');
                    } else {
                        $('.unknow').eq(idx).css('display', 'none');
                    }
                    $('.harga').eq(idx).val(convertToRupiah(price));
                    // trigger diskon to 'keyup'
                    $(".diskon").trigger('keyup');
                },
                error: function (e) {
                    console.error(e);
                }
            });
        }

        function tambah() {
            var row = '';
            let harga = '';
            row = '<tr>' +
                '<td><input type="text" name="barang[]" class="form-control form-control-sm barang" autocomplete="off"><input type="hidden" name="idItem[]" class="itemid"><input type="hidden" name="kode[]" class="kode"><input type="hidden" name="idStock[]" class="idStock"></td>' +
                '<td>' +
                '<select name="satuan[]" class="form-control form-control-sm select2 satuan">' +
                '</select>' +
                '</td>' +
                '<td><input type="number" name="jumlah[]" min="0" class="form-control form-control-sm jumlah" value="0" readonly></td>' +
                '<td><button class="btn btn-primary btnCodeProd btn-sm rounded" type="button"><i class="fa fa-plus"></i> kode produksi</button></td>';

            if ($('#userType').val() == 'CABANG') {
                harga = '<td><input type="text" name="harga[]" class="form-control form-control-sm rupiah harga"><p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>';
            }
            else {
                harga = '<td><input type="text" name="harga[]" class="form-control form-control-sm text-right harga" value="Rp. 0" readonly><p class="text-danger unknow mb-0" style="display: none; margin-bottom:-12px !important;">Harga tidak ditemukan!</p></td>';
            }

            row = row + harga + '<td><input type="text" name="diskon[]" style="text-align: right;" class="form-control form-control-sm diskon rupiah" value="Rp. 0"></td>'+
                '<td><input type="text" name="subtotal[]" style="text-align: right;" class="form-control form-control-sm subtotal" value="Rp. 0" readonly><input type="hidden" name="sbtotal[]" class="sbtotal"></td>' +
                '<td>' +
                '<button class="btn btn-danger btn-hapus btn-sm" type="button">' +
                '<i class="fa fa-remove" aria-hidden="true"></i>' +
                '</button>' +
                '</td>' +
                '</tr>';
            $('#table_create tbody').append(row);
            // clone modal-code-production and insert new one
            $('#modalCodeProdBase').clone().prop('id', 'modalCodeProd').addClass('modalCodeProd').insertAfter($('.modalCodeProd').last());
            // re-init events for some class or id
            getEventsReady();

            setArrayCode();

            $('.rupiah').inputmask("currency", {
                radixPoint: ",",
                groupSeparator: ".",
                digits: 0,
                autoGroup: true,
                prefix: ' Rp ', //Space after $, this will not truncate the first character.
                rightAlign: true,
                autoUnmask: true,
                nullable: false,
                // unmaskAsNumber: true,
            });

            $(".diskon").on('keyup', function (evt) {
                let idx = $('.diskon').index(this);
                let diskon = $('.diskon').eq(idx).val();
                let harga = $('.harga').eq(idx).val();
                let jumlah = $('.jumlah').eq(idx).val();
                let subharga = (parseInt(convertToAngka(harga)) - parseInt(diskon)) * parseInt(jumlah);
                $('.subtotal').eq(idx).val(convertToRupiah(subharga));
                updateTotalTampil();
            })

            updateTotalTampil();
        }

        function getEventsReady() {
            if ($('#userType').val() == 'CABANG') {
                $('.harga').on('keyup', function() {
                    // trigger diskon to 'keyup'
                    $(".diskon").trigger('keyup');
                });
            }

            // $('.barang').off();
            $(".satuan").off();
            $('.btn-hapus').off();
            $('.btnCodeProd').off();
            $('.btnAddProdCode').off();
            $('.btnRemoveProdCode').off();
            $('.qtyProdCode').off();

            $('.barang').on('click', function (e) {
                idxBarang = $('.barang').index(this);
                setArrayCode();
            });
            $('.barang').on('keyup', function (evt) {
                idxBarang = $('.barang').index(this);
                if (evt.which == 8 || evt.which == 46) {
                    $(".itemid").eq(idxBarang).val('');
                    $(".kode").eq(idxBarang).val('');
                    $(".idStock").eq(idxBarang).val('');
                    setArrayCode();
                    if ($(".itemid").eq(idxBarang).val() == "") {
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".subtotal").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampil();
                    } else {
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".subtotal").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        updateTotalTampil();
                    }
                } else if (evt.which <= 90 && evt.which >= 48) {
                    $(".itemid").eq(idxBarang).val('');
                    $(".kode").eq(idxBarang).val('');
                    $(".idStock").eq(idxBarang).val('');
                    setArrayCode();
                    if ($(".itemid").eq(idxBarang).val() == "") {
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                        updateTotalTampil();
                    } else {
                        $(".jumlah").eq(idxBarang).val(0);
                        $(".harga").eq(idxBarang).val("Rp. 0");
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                        updateTotalTampil();
                    }
                }
            });
            changeSatuan();
            changeJumlah();

            // remove a row from table
            $('.btn-hapus').on('click', function () {
                // get index of clicked element and delete a production-code-modal
                idxBarang = $('.btnRemoveItem').index(this);
                $('.modalCodeProd').eq(idxBarang).remove();
                $(this).parents('tr').remove();
                updateTotalTampil();
                setArrayCode();
            });
            // event to show modal to display list of code-production
            $('.btnCodeProd').on('click', function () {
                idxBarang = $('.btnCodeProd').index(this);
                // get unit-cmp from selected unit
                let unitCmp = parseInt($('.satuan').eq(idxBarang).find('option:selected').data('unitcmp')) || 0;
                let qty = parseInt($('.jumlah').eq(idxBarang).val()) || 0;
                let qtyUnit = qty * unitCmp;
                // pass qtyUnit to modal
                $('.modalCodeProd').eq(idxBarang).find('.QtyH').val(qtyUnit);
                $('.modalCodeProd').eq(idxBarang).find('.usedUnit').val($('.satuan').eq(idxBarang).find('option:first-child').text());
                calculateProdCodeQty();
                $('.modalCodeProd').eq(idxBarang).modal('show');
            });
            // event to add more row to insert production-code
            $('.btnAddProdCode').on('click', function () {
                prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
                qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input></td>';
                action = '<td><button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                listProdCode = '<tr>' + prodCode + qtyProdCode + action + '</tr>';
                // idxBarang is referenced from btnCodeProd above
                // $(listProdCode).insertBefore($('.modalCodeProd:eq('+ idxBarang +')').find('.table_listcodeprod .rowBtnAdd'));
                $('.modalCodeProd:eq(' + idxBarang + ')').find('.table_listcodeprod').append(listProdCode);
                getEventsReady();
            });
            // event to remove an prod-code from table_listcodeprod
            $('.btnRemoveProdCode').on('click', function () {
                idxProdCode = $('.btnRemoveProdCode').index(this);
                $(this).parents('tr').remove();
                calculateProdCodeQty();
            });
            // update total qty without production-code
            $('.qtyProdCode').on('keyup', function () {
                idxProdCode = $('.qtyProdCode').index(this);
                calculateProdCodeQty();
            });
            $('.select2').select2({
                theme: "bootstrap",
                dropdownAutoWidth: true,
                width: '100%'
            });
            // inputmask-digits
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
        }

        function updateTotalTampil() {
            var total = 0;

            var inputs = document.getElementsByClassName('subtotal'),
                subtotal = [].map.call(inputs, function (input) {
                    return input.value;
                });

            for (var i = 0; i < subtotal.length; i++) {
                total += parseInt(subtotal[i].replace("Rp.", "").replace(".", "").replace(".", "").replace(".", ""));
            }
            $("#tot_hrg").val(total);
            if (isNaN(total)) {
                total = 0;
            }
            $("#total_harga").val(convertToRupiah(total));

        }

        function setItem(info) {
            idStock = info.stock
            idItem = info.data.get_item.i_id;
            namaItem = info.data.get_item.i_name;
            kode = info.data.get_item.i_code;
            $(".kode").eq(idxBarang).val(kode);
            $(".itemid").eq(idxBarang).val(idItem);
            $(".idStock").eq(idxBarang).val(idStock);
            setArrayCode();
            $.ajax({
                url: "{{ url('/marketing/agen/kelolapenjualanlangsung/get-unit') }}" + '/' + idItem,
                type: 'GET',
                success: function (resp) {
                    $(".satuan").eq(idxBarang).find('option').remove();
                    var option = '';
                    option += '<option value="' + resp.i_unit1 + '" data-unitcmp="' + resp.i_unitcompare1 + '">' + resp.get_unit1.u_name + '</option>';
                    if (resp.i_unit2 != null && resp.i_unit2 != resp.i_unit1) {
                        option += '<option value="' + resp.i_unit2 + '" data-unitcmp="' + resp.i_unitcompare2 + '">' + resp.get_unit2.u_name + '</option>';
                    }
                    if (resp.i_unit3 != null && resp.i_unit3 != resp.i_unit2 && resp.i_unit3 != resp.i_unit1) {
                        option += '<option value="' + resp.i_unit3 + '" data-unitcmp="' + resp.i_unitcompare3 + '">' + resp.get_unit3.u_name + '</option>';
                    }
                    $(".satuan").eq(idxBarang).append(option);
                    if ($(".itemid").eq(idxBarang).val() == "") {
                        $(".jumlah").eq(idxBarang).attr("readonly", true);
                        $(".satuan").eq(idxBarang).find('option').remove();
                    } else {
                        $(".jumlah").eq(idxBarang).attr("readonly", false);
                    }
                }
            });
        }

        function setArrayCode() {
            var inputs = document.getElementsByClassName('kode'),
                code = [].map.call(inputs, function (input) {
                    return input.value.toString();
                });

            for (var i = 0; i < code.length; i++) {
                if (code[i] != "") {
                    icode.push(code[i]);
                }
            }

            var inpItemid = document.getElementsByClassName('itemid'),
                item = [].map.call(inpItemid, function (input) {
                    return input.value;
                });

            let agentCode = $('#agent').val();

            $(".barang").eq(idxBarang).autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "{{ url('/marketing/agen/kelolapenjualanlangsung/find-item') }}",
                        data: {
                            idItem: item,
                            agent: agentCode,
                            term: $(".barang").eq(idxBarang).val()
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function (event, data) {
                    setItem(data.item);
                }
            });
        }

        // check production code qty each item
        function calculateProdCodeQty() {
            let QtyH = parseInt($('.modalCodeProd').eq(idxBarang).find('.QtyH').val());
            let qtyWithProdCode = getQtyWithProdCode();
            let restQty = QtyH - qtyWithProdCode;

            if (restQty < 0) {
                $(':focus').val(0);
                qtyWithProdCode = getQtyWithProdCode();
                restQty = QtyH - qtyWithProdCode;
                $('.modalCodeProd').eq(idxBarang).find('.restQty').val(restQty);
                messageWarning('Perhatian', 'Jumlah item untuk penetapan kode produksi tidak boleh melebihi jumlah item yang ada !');
            } else {
                $('.modalCodeProd').eq(idxBarang).find('.restQty').val(restQty);
            }
        }

        function getQtyWithProdCode() {
            qtyWithProdCode = 0;
            $.each($('.modalCodeProd:eq(' + idxBarang + ') .table_listcodeprod').find('.qtyProdCode'), function (key, val) {
                qtyWithProdCode += parseInt($(this).val());
            });
            return qtyWithProdCode;
        }

        // get member based on agent (used for Employee-Login)
        function getMember() {
            $.ajax({
                data: {
                    "agentCode": $('#agent').val()
                },
                type: "get",
                url: "{{ route('kelolapenjualan.getMemberKPL') }}",
                dataType: 'json',
                success: function (response) {
                    $('#member').empty();
                    if (!$.trim(response)) {
                        messageFailed('Perhatian', 'Tidak ada member terdaftar !');
                    } else {
                        let optMember = '';
                        $.each(response, function (index, val) {
                            optMember += '<option value="' + val.m_code + '">' + val.m_name + '</option>';
                        })
                        $('#member').find('option').remove();
                        $('#member').append(optMember);
                        $('#member').selectedIndex = 0;
                    }
                },
                error: function (e) {
                    console.error(e.message);
                }
            });
        }

        function simpan() {
            loadingShow();
            var data = $('.myForm').serialize();
            // get list of production-code
            $.each($('.table_listcodeprod'), function (key, val) {
                // get length of production-code each items
                let prodCodeLength = $('.table_listcodeprod:eq(' + key + ') :input.qtyProdCode').length;
                $('.modalCodeProd:eq(' + key + ')').find('.prodcode-length').val(prodCodeLength);
                inputs = $('.table_listcodeprod:eq(' + key + ') :input').serialize();
                data = data + '&' + inputs;
            });

            axios.post('{{ route('kelolapenjualanviawebsite.storeKPW') }}', data)
                .then(function (response) {
                    loadingHide();
                    if (response.data.status == 'berhasil') {
                        messageSuccess("Berhasil", "Penjualan telah berhasil disimpan !");
                        setInterval(function () {
                            location.reload();
                        }, 1500)
                    } else if (response.data.status === 'invalid') {
                        messageWarning('Perhatian', response.data.message);
                    } else {
                        messageFailed("Gagal", response.data.message);
                    }

                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error);
                })
        }

        $('#provinsi').on('change', function () {
            loadingShow();
            $.ajax({
                type: 'get',
                url: baseUrl + '/masterdatautama/agen/cities/' + $('#provinsi').val(),
                success: function (data) {
                    loadingHide();
                    $('#kota').empty();
                    $("#kota").append('<option value="" selected>Pilih Kota</option>');
                    $.each(data, function (key, val) {
                        $("#kota").append('<option value="' + val.wc_id + '">' + val.wc_name + '</option>');
                    });
                    $('#kota').focus();
                    $('#kota').select2('open');
                }
            });
        });

        $("#kota").on("change", function (evt) {
            evt.preventDefault();
            loadingShow();
            axios.post('{{ route("kelolapenjualan.getAgenKPL") }}', {
                "kota": $('#kota').val(),
                "provinsi": $('#provinsi').val(),
                "_token": "{{ csrf_token() }}"
            }).then(function (resp) {
                $("#agent").empty();
                var option = '<option value="" selected disabled>Pilih Agen</option>';
                var pembeli = resp.data;
                pembeli.forEach(function (data) {
                    option += '<option value="'+ data.a_code +'">' + data.a_name + '</option>';
                })
                $("#agent").append(option);
                $("#agent").focus();
                $("#agent").select2('open');
                loadingHide();
            }).catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error)
                })
        });
    </script>
@endsection
