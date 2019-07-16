@extends('main')

@section('content')

    @include('produksi.penerimaanbarang.penerimaan')

    <article class="content animated fadeInLeft">

        <div class="title-block text-primary">
            <h1 class="title"> Proses Penerimaan Barang</h1>
            <p class="title-description">
                <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
                / <span>Aktifitas Produksi</span>
                / <a href="{{route('penerimaan.index')}}">Penerimaan Barang</a>
                / <span class="text-primary font-weight-bold">Proses Penerimaan Barang</span>
            </p>
        </div>

        <section class="section">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header bordered p-2">
                            <div class="header-block">
                                <h3 class="title"> Proses Penerimaan Barang </h3>
                            </div>
                            <div class="header-block pull-right">
                                <a href="{{route('penerimaan.index')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                        <div class="card-block">
                            <section>
                                <input type="hidden" name="order" id="order" value="{{$order}}">
                                <div class="table-responsive">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-hover" cellspacing="0" id="tbl_receiptitem">
                                            <thead class="bg-primary">
                                            <tr>
                                                <th>Nama Barang</th>
                                                <th width="20%">Satuan</th>
                                                <th width="10%">Jumlah</th>
                                                <th width="10%">QTY Terima</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="card-footer text-right">
                            {{--<button class="btn btn-primary" type="button">Simpan</button>--}}
                            <a href="{{route('penerimaan.index')}}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>

                </div>

            </div>

        </section>

    </article>

@endsection

@section('extra_script')
    <script type="text/javascript">
        var tbl_receiptitem;
        $(document).ready(function(){
            tbl_receiptitem = $('#tbl_receiptitem').DataTable({
                responsive  : true,
                "paging"    : true,
                "ordering"  : false,
                "info"      : true,
                "searching" : true,
                processing  : true,
                serverSide  : true,
                ajax        : {
                                    url: "{{ url('/produksi/penerimaanbarang/getlistitem') }}"+'/'+$("#order").val(),
                                    type: "get"
                                },
                columns     : [
                                    {data: 'barang'},
                                    {data: 'satuan'},
                                    {data: 'jumlah'},
                                    {data: 'terima'},
                                    {data: 'action'}
                                ]
            });
            // submit form penerimaanOrderProduksi
            $("#btn_simpanPenerimaan").on('click', function (evt) {
                evt.preventDefault();
                submitFormPenerimaan();
            })
            // add prodCode in modal
            $('.btnAddProdCode').on('click', function() {
                addRowProdCode();
            });
            // on modal peneriamaan close
            $('#penerimaanOrderProduksi').on('hidden.bs.modal', function() {
                console.log('modal closed !');
                $('#table_listProductionCode tbody').empty();
                $('#table_listProductionCode').append(
                    `<tr>
                        <td>
                            <input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input>
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input>
                        </td>
                        <td>
                            <button class="btn btn-success btnAddProdCode btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </td>
                    </tr>`
                );
            });
        });

        function receipt(id, item)
        {
            var sisa = 0;
            var terima = 0;
            axios.get(baseUrl+'/produksi/penerimaanbarang/terimalistitem'+'/'+id+'/'+item)
                .then(function (response) {
                    // handle success
                    if (response.data.status == "Success") {
                        if (response.data.data.terima == null) {
                            terima = 0;
                        } else {
                            terima = response.data.data.terima
                        }

                        sisa += parseInt(response.data.data.jumlah) - parseInt(terima);

                        $("#idOrder").val(response.data.data.id);
                        $("#idItem").val(response.data.data.item);
                        $("#txtBarang").text(response.data.data.barang);
                        $("#txtSatuan").text(response.data.data.satuan);
                        $("#txtJumlah").text(response.data.data.jumlah);
                        $("#txtTerima").text(terima);
                        $("#txtSisa").text(sisa);
                        $('#qtyName').text(response.data.satuan.unit1);
                        $('#prodUnit').val(response.data.satuan.id1);
                        $("#nota").val('');
                        $("#satuan").val(response.data.data.unit);
                        $("#qty").val(0);
                        document.getElementById("nota").addEventListener("keypress", forceKeyPressUppercase, false);

                        // append list of production-code
                        listProdCode = '';
                        $('#table_listProductionCode tbody').empty();
                        $.each(response.data.prodCode, function (key, val) {
                            prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" value="'+ val.poc_productioncode +'" readonly></input></td>';
                            qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits" value="'+ val.poc_qty +'" readonly></input></td>';
                            action = '<td><button class="btn btn-danger btn-sm rounded-circle" type="button" disabled><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
                            listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
                            $('#table_listProductionCode').append(listProdCode);
                        });
                        // append default-row to add more production-code
                        prodCode = '<td><input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input></td>';
                        qtyProdCode = '<td><input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input></td>';
                        action = '<td><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td>';
                        listProdCode = '<tr>'+ prodCode + qtyProdCode + action +'</tr>';
                        $('#table_listProductionCode').append(listProdCode);
                        // get some events ready to be used in modal
                        getModalReady();

                        $("#penerimaanOrderProduksi").modal('show');
                    } else {
                        messageFailed("Gagal", "Terjadi kesalahan sistem")
                    }
                })
                .catch(function (error) {
                    // handle error
                    messageWarning('Error', error);
                })
                .then(function () {
                    // always executed
                });
        }

        function forceKeyPressUppercase(e)
        {
            var charInput = e.keyCode;
            if((charInput >= 97) && (charInput <= 122)) { // lowercase
                if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
                    var newChar = charInput - 32;
                    var start = e.target.selectionStart;
                    var end = e.target.selectionEnd;
                    e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value.substring(end);
                    e.target.setSelectionRange(start+1, start+1);
                    e.preventDefault();
                }
            }
        }

        function addRowProdCode()
        {
            $('#table_listProductionCode').append(
                `<tr>
                    <td>
                        <input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input>
                    </td>
                    <td>
                        <button class="btn btn-danger btnRemoveProdCode btn-sm rounded-circle" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                    </td>
                </tr>`
            );
            getModalReady();
        }

        function getModalReady()
        {
            $('.qtyProdCode').off();
            $('.btnAddProdCode').off();
            $('.btnRemoveProdCode').off();
            $('.digits').off();
            // event to add an prodCode to table_listProductionCode
            $('.qtyProdCode').on('keypress', function(e) {
                if (e.keyCode == 13) {
                    addRowProdCode();
                }
            });
            // event to add an prodCode to table_listProductionCode
            $('.btnAddProdCode').on('click', function() {
                addRowProdCode();
            });
            // event to remove an prodCode from table_listProductionCode
            $('.btnRemoveProdCode').on('click', function() {
                $(this).parents('tr').remove();
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
        }

        // calculate qty in production-code table
        function getTotalQtyProdCode()
        {
            totalQty = 0;
            $.each($('.qtyProdCode'), function (key) {
                totalQty += parseInt($(this).val());
            });
            return totalQty;
        }

        function submitFormPenerimaan()
        {
            // get total qty in production-code
            totalQtyProdCode = getTotalQtyProdCode();

            var check = false;
            if ($("#nota").val() == "") {
                $("#nota").focus();
                messageWarning("Peringatan", "Masukkan nota order!");
            }
            else if ($("#satuan").val() == "") {
                $("#satuan").focus();
                messageWarning("Peringatan", "Pilih satuan barang!");
            }
            else if ($("#qty").val() == "" || $("#qty").val() == "0" || $("#qty").val() == 0) {
                $("#qty").focus();
                messageWarning("Peringatan", "Masukkan qty yang diterima!");
            }
            else {
                loadingShow();
                axios.post(baseUrl+'/produksi/penerimaanbarang/checkqty', $("#formTerimaBarang").serialize())
                .then(function (response) {
                    if (response.data.status == "Success") {
                        if (response.data.result == "Over qty") {
                            loadingHide();
                            messageWarning("Pesan", response.data.message);
                        } else if (totalQtyProdCode > parseInt($("#txtSisa").text())) {
                            loadingHide();
                            messageWarning("Pesan", "Jumlah qty pada \'Kode Produksi\' melebihi kebutuhan !");
                        } else {
                            check = true;
                        }
                    } else {
                        loadingHide();
                        messageFailed("Gagal", response.data.message);
                    }
                })
                .catch(function (error) {
                    loadingHide();
                    messageWarning("Error", error);
                })
                .then(function(){
                    if (check == true) {
                        axios.post('{{ route('penerimaan.terimaitem') }}', $("#formTerimaBarang").serialize())
                        .then(function(resp){
                            loadingHide();
                            if (resp.data.status == "Success") {
                                $("#penerimaanOrderProduksi").modal('hide');
                                tbl_receiptitem.ajax.reload();
                                messageSuccess("Berhasil", resp.data.message);
                            } else {
                                messageFailed("Gagal", resp.data.message);
                            }
                        })
                        .catch(function (error) {
                            loadingHide();
                            messageWarning("Error", error);
                        });
                    }
                });


            }
        }
    </script>
@endsection
