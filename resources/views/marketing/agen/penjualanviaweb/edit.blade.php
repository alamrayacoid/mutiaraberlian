@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Edit Data Kelola Penjualan via Website </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Marketing</span>
         / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Edit Data Kelola Penjualan via Website</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">

            <div class="card-header bordered p-2">
                <div class="header-block">
                    <h3 class="title"> Edit Data Kelola Penjualan via Website </h3>
                </div>
                <div class="header-block pull-right">
                    <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                </div>
            </div>

            <form class="myForm" autocomplete="off">
                <div class="card-block">
                    <section>
                        <div id="sectionsuplier" class="row">
                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Tanggal Transaksi</label>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <input type="hidden" name="dateHidden" id="dateHidden" value="{{ $datas->sw_date }}">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="text" name="dateEditKPW" class="form-control form-control-sm datepicker" id="dateEditKPW">
                                </div>
                            </div>
                            <div class="col-md-6"></div>

                            <label for="nama_agen" class="col-2 form-group select-agent">Nama Agen :</label>
                            <div class="col-4 form-group select-agent">
                                <input id="editnama_agen" class="form-control form-control-sm" value="{{ $datas->c_name }}" readonly>
                                <input type="hidden" id="edit_agen" value="{{ $datas->sw_agen }}">
                                <input type="hidden" id="data_id" value="{{ $datas->dataId }}">
                            </div>

                            <label for="nama_customer" class="col-2 form-group">Nama Customer :</label>
                            <div class="col-4 form-group">
                                <input id="editnama_customerView" class="form-control form-control-sm" value="{{ $datas->customerName }}" readonly>
                                <input id="editnama_customer" class="form-control form-control-sm d-none" value="{{ $code[0]->s_member }}" readonly>
                            </div>

                            <label for="website" class="col-2 form-group">Website :</label>
                            <div class="col-4 form-group">
                                <input type="text" class="form-control-sm form-control" id="edit_website" value="{{ $datas->sw_website }}">
                            </div>

                            <label for="website" class="col-2 form-group">Kode Transaksi :</label>
                            <div class="col-4 form-group">
                                <input type="text" class="form-control-sm form-control" id="edit_transaksi" value="{{ $datas->sw_transactioncode }}" style="text-transform: uppercase">
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <label for="produk" class="col-2 form-group">Produk :</label>
                            <div class="col-10 form-group">
                                <input type="text" class="form-control-sm form-control" id="edit_produk" value="{{ $datas->i_name }}" style="text-transform: uppercase">
                                <input type="hidden" class="form-control-sm form-control" id="edit_produkid" value="{{ $datas->i_id }}">
                            </div>

                            <label for="kuantitas" class="col-2 form-group">Kuantitas :</label>
                            <div class="col-2 form-group">
                                <input type="number" class="form-control-sm form-control text-right" id="edit_kuantitas" value="{{ $datas->sw_qty }}">
                            </div>

                            <label for="edit_satuan" class="col-2 form-group">Satuan :</label>
                            <div class="col-2 form-group">
                                <input type="hidden" class="unitHiddenSW" value="{{ $datas->sw_unit }}">
                                <input type="hidden" class="unitHidden" value="{{ $units->id1 }}" data-name="{{ $units->name1 }}">
                                <input type="hidden" class="unitHidden" value="{{ $units->id2 }}" data-name="{{ $units->name2 }}">
                                <input type="hidden" class="unitHidden" value="{{ $units->id3 }}" data-name="{{ $units->name3 }}">
                                <select class="select2" id="edit_satuan">
                                </select>
                            </div>

                            <label for="harga" class="col-2 form-group">Harga/<span id="label-satuan">-</span> :</label>
                            <div class="col-2 form-group">
                                <input type="text" class="form-control-sm form-control rupiah" id="edit_harga" value="{{ (int)$datas->sw_price }}" onkeyup="setEditTotal()">
                            </div>

                            <label for="total" class="col-2 form-group">Total :</label>
                            <div class="col-4 form-group">
                                <input type="text" class="form-control-sm form-control rupiah" id="edit_total" value="{{ (int)$datas->sw_totalprice }}" readonly>
                            </div>
                            <div class="col-md-6"></div>

                            <label for="note" class="col-2 form-group">Catatan :</label>
                            <div class="col-10 form-group">
                                <textarea class="form-control form-control-sm" id="edit_note">{{ $datas->sw_note }}</textarea>
                            </div>

                            <div class="col-8 form-group">
                                <input type="text" class="form-control-sm form-control" id="add_editCode" onkeypress="cekCodeEdit(event)"
                                placeholder="Kode Produksi" style="text-transform: uppercase">
                            </div>
                            <div class="col-3 form-group">
                                <input type="number" class="form-control-sm form-control" id="add_codeQty"
                                onkeypress="cekCodeEdit(event)">
                            </div>
                            <div class="col-1 form-group">
                                <button type="button" class="btn btn-primary" onclick="addCodeEdit()"><i class="fa fa-plus"></i></button>
                            </div>

                            <div class="row col-md-12 form-group">
                                <div class="table-responsive" style="padding: 0px 15px 0px 15px;">
                                    <table class="table table-hover table-striped display w-100" cellspacing="0" id="table_EditKPW">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th style="width: 70%">Kode</th>
                                                <th style="width: 20%">Qty</th>
                                                <th style="width: 10%">Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </section>
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
    $(document).ready(function () {
        let dateKPW = $('#dateHidden').val();
        dateKPW = dateKPW.split('-');
        console.log(dateKPW);
        $('#dateEditKPW').datepicker('setDate', new Date(dateKPW[0], parseInt(dateKPW[1]) - 1, dateKPW[2]));

        // append option to 'satuan'
        let option = null;
        $("#edit_satuan").empty();
        $.each($('.unitHidden'), function (idx, val) {
            // just show first unit
            if (idx > 0) {
                return ;
            }
            if ($('.unitHidden').eq(idx).val() == $('.unitHiddenSW').val()) {
                option = '<option value="'+ $('.unitHidden').eq(idx).val() +'" selected>'+ $('.unitHidden').eq(idx).data('name') +'</option>';
            }
            else {
                option = '<option value="'+ $('.unitHidden').eq(idx).val() +'">'+ $('.unitHidden').eq(idx).data('name') +'</option>';
            }
            $("#edit_satuan").append(option);
            $('#label-satuan').html($('.unitHidden').eq(idx).data('name'));
        });
        // qty on changed
        $('#edit_kuantitas').on('click keyup', function () {
            setEditTotal();
        });
        // select2 satuan
        $('#satuan').change(function(){
            var selected = $(this).find('option:selected').data('nama');
            $('#label-satuan').html(selected);
        });
        // code-production list
        $('#table_EditKPW').DataTable().clear().destroy();
        table_editKPW = $('#table_EditKPW').DataTable({
            bAutoWidth: true,
            responsive: true,
            info: false,
            searching: false,
            paging: false
        });
        table_editKPW.columns.adjust();

        let code = {!! $code !!};
        console.log(code);
        $.each(code, function (key, val) {
            table_editKPW.row.add([
                '<input type="text" value="'+ val.sc_code +'" class="form-control bg-light code_sd" readonly disabled/><input type="hidden" name="code_s[]" class="code_s" value="'+ val.sc_code +'"/>',
                '<input type="number" min="1" name="qty_s[]" value="'+ val.sc_qty +'" class="qty_s form-control form-control-sm text-right"/>',
                '<div class="text-center"><button type="button" class="btn btn-sm rounded btn-danger btn-trash"><i class="fa fa-trash"></i></button></div>'
            ]).draw(false);
        });

        $('#btn_simpan').on('click', function() {
            updateKPW();
        });
    });

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

    function cekCodeEdit(e){
        if (e.keyCode == 13){
            addCodeEdit();
        }
    }

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

    function setEditTotal() {
        let qty = $('#edit_kuantitas').val();
        let harga = $('#edit_harga').val();

        let total = parseInt(qty) * parseInt(harga);
        $('#edit_total').val(total);
    }

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
                if (parseInt(qty) > parseInt($('#edit_kuantitas').val())) {
                    messageFailed("Peringatan!", "Qty terlalu besar");
                }else{
                    let values = $("input[name='code_s[]']")
                    .map(function(){return $(this).val();}).get();
                    let valuesQty = $("input[name='qty_s[]']")
                    .map(function(){return $(this).val();}).get();

                    let total = 0;
                    for (var i = 0; i < valuesQty.length; i++) {
                        total += parseInt(valuesQty[i])
                    }

                    let totalQty = parseInt(qty) + total;
                    if (totalQty > parseInt($('#edit_kuantitas').val())) {
                        messageFailed("Peringatan!", "Jumlah melebihi kuantitas");
                    }else{
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
                }
            }
        }).catch(function (error) {
            loadingHide();
            alert('error');
        });
    }

    function updateKPW() {
        let kuantitas = $('#edit_kuantitas').val();
        let qty = $("input[name='qty_s[]']")
        .map(function(){return $(this).val();}).get();
        let totalqty = 0;
        for (let i = 0; i < qty.length; i++) {
            totalqty = totalqty + parseInt(qty[i]);
        }

        if (parseInt(kuantitas) != parseInt(totalqty)){
            messageWarning('Perhatian', 'Kuantitas barang tidak sama dengan jumlah kode produksi !');
        } else {
            lanjutkanUpdate();
            // return post;
        }
    }

    function lanjutkanUpdate() {
        valid = 1;
        let date      = $('#dateEditKPW').val();
        let agen      = $('#edit_agen').val();
        let customer  = $('#editnama_customer').val();
        let website   = $('#edit_website').val();
        let transaksi = $('#edit_transaksi').val();
        let produk    = $('#edit_produkid').val();
        let kuantitas = $('#edit_kuantitas').val();
        let satuan    = $('#edit_satuan').val();
        let harga     = $('#edit_harga').val();
        let note      = $('#edit_note').val();
        let kode      = $("input[name='code_s[]']")
        .map(function(){return $(this).val();}).get();

        let kodeqty = $("input[name='qty_s[]']")
        .map(function(){return $(this).val();}).get();
        if (agen == '' || agen == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#edit_agen').focus();
            $('#edit_agen').select2('open');
            return false;
        }
        if (customer == '' || customer == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#editnama_customer').focus();
            $('#editnama_customer').select2('open');
            return false;
        }
        if (website == '' || website == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#edit_website').focus();
            return false;
        }
        if (transaksi == '' || transaksi == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#edit_transaksi').focus();
            return false;
        }
        if (produk == '' || produk == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#edit_produkid').focus();
            return false;
        }
        if (kuantitas == '' || kuantitas == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#edit_kuantitas').focus();
            return false;
        }
        if (satuan == '' || satuan == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#edit_satuan').focus();
            $('#edit_satuan').select2('open');
            return false;
        }
        if (harga == '' || harga == null){
            valid = 0;
            messageWarning("Perhatian", "Form harus lengkap");
            $('#edit_harga').focus();
            return false;
        }
        if (valid == 1){
            var post = [];
            post = {
                "id"        : $('#data_id').val(),
                "date"      : date,
                "agen"      : agen,
                "website"   : website,
                "customer"  : customer,
                "transaksi" : transaksi.toUpperCase(),
                "item"      : produk,
                "qty"       : kuantitas,
                "unit"      : satuan,
                "price"     : harga,
                "note"      : note,
                "code"      : kode,
                "qtycode"   : kodeqty,
                "_token"    : '{{ csrf_token() }}'
            };

            updateSalesWeb(post);
        }
    }

    function updateSalesWeb(post){
        // if (post != false) {
        $.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apa anda yakin akan mengupdate transaksi ini?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function () {
                        loadingShow();
                        $.ajax({
                            url: "{{url('/marketing/agen/kelolapenjualanviawebsite/update-kpw')}}",
                            type: "get",
                            data: post,
                            success:function(response){
                                loadingHide();
                                console.log(response);
                                if (response.status == 'sukses'){
                                    $('#editKPW').modal('hide');
                                    messageSuccess("Sukses", "Transaksi berhasil diperbarui!");
                                    setTimeout(function () {
                                        window.history.back();
                                    }, 1000);
                                } else if (response.status == 'gagal'){
                                    messageFailed("gagal", "Transaksi gagal diupdate !");
                                } else {
                                    messageWarning('Error', 'Terjadi kesalahan, hubungi pengembang !');
                                }
                            },
                            error: function(e) {
                                loadingHide();
                                messageWarning('Error', 'Terjadi kesalahan, hubungi pengembang !');
                            }
                        });
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
