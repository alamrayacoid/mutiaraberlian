@extends('main')

@section('content')

<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Tambah Adjustment Stock </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Inventory</span>
      / <a href="{{route('adjustment.index')}}"><span>Pengelolaan Manajemen Stock</span></a>
      / <span class="text-primary" style="font-weight: bold;"> Tambah Adjustment Stock </span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title"> Tambah Adjustment Stock </h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('adjustment.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <section>
              <div class="row">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Nota Opname</label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select class="form-control select2" name="nota" id="nota" onchange="getopname()">
                      <option value="" disabled selected> - Pilih Nota Opname - </option>
                      @foreach ($nota as $key => $value)
                      <option value="{{$value->o_nota}}">{{$value->o_nota}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-12"><hr></div>
                <div class="col-12">
                  <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Nama Barang</label>
                    </div>
                    <div class="col-md-10 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="nama" id="item" readonly="">
                        <input type="hidden" name="iditem" id="iditem">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="title-block">
                    <h3 class="title"> Stock System </h3>
                  </div>
                  <form role="form">
                    <div class="form-group">
                      <label class="control-label" for="formGroupExampleInput">Satuan</label>
                      <select type="text" disabled class="form-control form-control-sm select2" id="satuansystem">
                        <option value="" disabled selected> - Pilih Satuan - </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="formGroupExampleInput2">Qty</label>
                      <input type="number" readonly class="form-control form-control-sm" id="qtysystem">
                    </div>
                    <table class="table table-hover table-bordered table-striped w-100" id="tb_code_produksi">
                      <thead>
                        <th class="text-center">Kode Produksi</th>
                        <th class="text-center">Qty</th>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </form>
                </div>
                <div class="col-md-6">
                  <div class="title-block">
                    <h3 class="title"> Stock Real </h3>
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="formGroupExampleInput">Satuan</label>
                    <select type="text" class="form-control form-control-sm select2" id="satuanreal">
                      <option value="" disabled selected> - Pilih Satuan - </option>
                    </select>
                    <span class="text-danger errorSatuanR d-none">Harap pilih satuan!</span>
                  </div>
                  <div class="form-group" style="margin-bottom: 1.4rem !important;">
                    <label class="control-label" for="formGroupExampleInput2">Qty</label>
                    <input type="number" class="form-control form-control-sm" id="qtyreal">
                    <span class="text-danger errorQtyR1 d-none">Harap masukkan qty!</span>
                  </div>

                  <div class="form-group" style="margin-bottom: 1.1rem !important;">
                    <div class="row">
                      <div class="col-6">
                        <input type="text" id="codeReal" class="form-control" placeholder="Tuliskan Kode">
                        <span class="text-danger errorCodeR d-none">Kode masih kosong!</span>
                      </div>
                      <div class="col">
                        <input type="number" id="qtyReal" class="form-control text-right" min="1" placeholder="Qty" disabled="">
                        <span class="text-danger errorQtyR2 d-none">Qty masih kosong!</span>
                      </div>
                      <div class="col">
                        <button type="button" class="btn btn-primary tambah-code" disabled=""><i class="fa fa-plus"></i> Tambah</button>
                      </div>
                    </div>
                  </div>

                  <form id="formStockReal">
                    <table class="table" id="tb_addCodeReal">
                      <thead>
                        <th class="text-center" style="width: 60%;">Kode Produksi</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Action</th>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </form>
                </div>
              </div>
            </section>
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary btn-submit" id="btnsimpan" style="display:none" type="button">Simpan</button>
            <a href="{{route('adjustment.index')}}" class="btn btn-secondary">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>

@endsection

@section('extra_script')
<script type="text/javascript">
  var data = [];
  var nota, code, qty, idX, tb_code_produksi, tb_addCodeReal,
    codeExist = false,
    idxCode = null,
    codeReal = [];
  $(document).ready(function(){

    $('#tb_code_produksi').dataTable({
      "searching": false,
      "lengthChange": false,
      "paging": false,
      "info": false
    });

    $('.select2').select2();

    $('#type_cus').change(function(){
      if($(this).val() === 'kontrak'){
        $('#label_type_cus').text('Jumlah Bulan');
        $('#jumlah_hari_bulan').val('');
        $('#pagu').val('');
        $('#armada').prop('selectedIndex', 0).trigger('change');
        $('.120mm').removeClass('d-none');
        $('.125mm').addClass('d-none');
        $('.122mm').removeClass('d-none');
      } else if($(this).val() === 'harian'){
        $('#label_type_cus').text('Jumlah Hari');
        $('#armada').prop('selectedIndex', 0).trigger('change');
        $('#pagu').val('');
        $('#jumlah_hari_bulan').val('');
        $('.122mm').addClass('d-none');
        $('.120mm').removeClass('d-none');
        $('.125mm').removeClass('d-none');
      } else {
        $('#jumlah_hari_bulan').val('');
        $('#armada').prop('selectedIndex', 0).trigger('change');
        $('#pagu').val('');
        $('.122mm').addClass('d-none');
        $('.120mm').addClass('d-none');
        $('.125mm').addClass('d-none');
      }
    });
  });

  //  Get Data Opname from nota ------
  function getopname(){
    loadingShow();
    nota = $('#nota').val();
    axios.get(baseUrl + '/inventory/manajemenstok/adjustmentstock/getopname?nota='+nota)
    .then(function (response) {
      data = response.data.data;
      // handle success
      if (parseInt(response.data.data.o_qtysystem) != parseInt(response.data.stock.s_qty)) {
        $('#btnsimpan').css('display', 'none');
        messageFailed('Failed', 'Data Stock System Sudah Berubah');
        loadingHide();
      } else {
        loadingHide();
        $('#btnsimpan').css('display', '');
        $('#item').val(response.data.item.i_name);
        $('#iditem').val(response.data.item.i_id);
        for (var i = 0; i < response.data.unit.length; i++) {
          if (response.data.unit[i].u_id == response.data.unitsystem.u_id) {
            var selected = 'selected';
          } else {
            var selected = '';
          }

          $('#satuansystem').append('<option value="'+response.data.unit[i].u_id+'" '+selected+'>'+response.data.unit[i].u_name+'</option>');
        }

        for (var i = 0; i < response.data.unit.length; i++) {
          if (response.data.unit[i].u_id == response.data.unitreal.u_id) {
            var selected = 'selected';
          } else {
            var selected = '';
          }

          $('#satuanreal').append('<option value="'+response.data.unit[i].u_id+'" '+selected+'>'+response.data.unit[i].u_name+'</option>');
        }
        $('#qtysystem').val(response.data.data.o_qtysystem);
        $('#qtyreal').val(response.data.data.o_qtyreal);

        getCodeProduksi(nota);
      }
    })
    .catch(function (error) {
      // handle error
      messageFailed('Failed', 'Data Stock System Sudah Berubah');
    });
  }

  // Get Code Produksi ---------------
  function getCodeProduksi(nota) {
    $('#tb_code_produksi').dataTable().fnDestroy();
    tb_code_produksi = $('#tb_code_produksi').DataTable({
        responsive: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        paging: false,
        info: false,
        ajax: {
          url: '{{url('/inventory/manajemenstok/adjustmentstock/list-code-produksi')}}',
          type: 'get',
          data: {nota},
        },
        columns: [
            {data: 'od_code', className: 'text-left'},
            {data: 'od_qty', className: 'text-right'}
        ],
        pageLength: 10,
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
  }

  // Add Code Real -------------------
  $('#codeReal').keyup(function(){
    this.value = this.value.toUpperCase();
    if (this.value == '' || this.value == null) {
      $('#qtyReal').attr("disabled", "");
      $('.tambah-code').attr("disabled", "");
      $('#qtyReal').val('');
    } else {
      $('#qtyReal').removeAttr('disabled');
      $('.tambah-code').removeAttr('disabled');
      $('#qtyReal').val(1);
    }
  });

  $('#codeReal').keypress(function(e){
    if(e.which == 13) {
      e.preventDefault();
      $('.tambah-code').trigger('click');
    }
  });

  $('#qtyReal').keypress(function(e){
    if(e.which == 13) {
      e.preventDefault();
      $('.tambah-code').trigger('click');
    }
  });

  $('.tambah-code').on('click', function(){
    var unit_real = $('#satuanreal').val();
    var qty_real  = $('#qtyreal').val();
    if (unit_real == '' || unit_real == null) {
      $('.errorSatuanR').removeClass('d-none');
      $('.errorSatuanR').css('display', 'block');
    } else if (qty_real == '') {
      $('.errorSatuanR').css('display', 'none');
      $('.errorQtyR1').removeClass('d-none');
      $('.errorQtyR1').css('display', 'block');
    } else {
      $('.errorSatuanR').css('display', 'none');
      $('.errorQtyR1').css('display', 'none');
      checkInputCodeReal();
    }
  });

  function checkInputCodeReal() {
    console.log('checkInput');
    code = $('#codeReal').val();
    qty = $('#qtyReal').val();
    idX = null;
    if (code == '') {
      $('.errorCodeR').removeClass('d-none');
      $('.errorCodeR').css('display', 'block');
    } else if (qty == 0 || qty == '') {
      $('.errorCodeR').css('display', 'none');
      $('.errorQtyR2').removeClass('d-none');
      $('.errorQtyR2').css('display', 'block');
    } else {
      $('.errorCodeR').css('display', 'none');
      $('.errorQtyR2').css('display', 'none');
      loadingShow();
      sendCode(code, qty);
    }
  }

  function sendCode(code, qty) {
    loadingHide();
    codeExist = false;
    idxCode = null;

    $.each($('.code_r'), function (index, val) {
      if (code == $('.code_r').eq(index).val()) {
        idxCode   =  index;
        codeExist = true
        return false;
       } else {
          codeExist = false;
       }
    });

    if (codeExist == true) {
      var qty_r = $('.qty_r').eq(idxCode).val();
      qty_r = parseInt(qty_r) + parseInt(qty);
      $('.qty_r').eq(idxCode).val(qty_r);
      $('.qty_s').eq(idxCode).val(qty_r);
    } else {
      $('#tb_addCodeReal tbody').append(`<tr>
                                          <td style="padding: 8px;">
                                            <input type="text" disabled="" class="form-control bg-light code_s" value="`+code+`">
                                            <input type="hidden" name="code_r[]" class="form-control code_r" readonly value="`+code+`" >
                                          </td>
                                          <td style="padding: 8px;">
                                            <input type="text" disabled="" class="form-control bg-light qty_s text-right" value="`+qty+`">
                                            <input type="hidden" name="qty_r[]" class="form-control qty_r" readonly value="`+qty+`">
                                          </td>
                                          <td class="text-center">
                                            <button class="btn rounded-circle btn-danger btn-sm delete-list" onclick><i class="fa fa-trash"></i></button>
                                        <tr>`);
    }
  }

  $(document).on('click', '.delete-list', function () {
    loadingShow();
    $(this).parents('tr').remove();
    loadingHide();
  });

  // Save Adjustment -------------------------------
  $(document).on('click', '.btn-submit', function(){
    var ini = $(this);
    $.confirm({
      animation: 'RotateY',
      closeAnimation: 'scale',
      animationBounce: 1.5,
      icon: 'fa fa-exclamation-triangle',
      title: 'Peringatan!',
      content: 'Apa anda sudah yakin?',
      theme: 'disable',
      buttons: {
        info: {
          btnClass: 'btn-blue',
          text:'Ya',
          action : function(){
            loadingShow();
            var satuanreal = $('#satuanreal').val();
            var qtyreal = $('#qtyreal').val();
            var code_real = [];
            var qty_code = [];
            $.each($('.code_r'), function(index, value){
              code_real.push($('.code_r').eq(index).val());
              qty_code.push($('.qty_r').eq(index).val());
            });

            $.ajax({
              type: 'get',
              data: {data, satuanreal, qtyreal, code_real, qty_code},
              dataType: 'json',
              url: baseUrl + '/inventory/manajemenstok/adjustmentstock/simpan',
              success : function(response){
                if (response.status == 'berhasil') {
                  loadingHide();
                  messageSuccess('Berhasil', 'Opname berhasil!');
                  setTimeout(function () {
                    window.location.href = '{{route('adjustment.index')}}';
                  }, 1000);
                } else if (response.status == 'warning') {
                  loadingHide();
                  messageWarning('Peringatan!', 'Opname Expired!');
                } else {
                    loadingHide();
                  messageFailed('Gagal!', 'Opname gagal!');
                }
              }
            })
          }
        },
        cancel:{
          text: 'Tidak',
          action: function () {
            // tutup confirm
          }
        }
      }
    });
  });
</script>
@endsection
