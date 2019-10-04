@extends('main')

@section('content')

<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Edit Opname Stock </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Inventory</span>
      / <a href="{{route('manajemenstok.index')}}"><span>Pengelolaan Manajemen Stock</span></a>
      / <span class="text-primary" style="font-weight: bold;"> Edit Opname Stock </span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title"> Edit Opname Stock </h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{ route('opname.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <form id="myForm" action="{{ route('opname.update', [$data['opname']->oa_id]) }}" method="post" autocomplete="off">
            <div class="card-block">
              <section>
                <div class="row">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Nama Barang</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="hidden" name="itemId" id="itemId" value="{{ $data['opname']->oa_item }}">
                      <input type="text" class="form-control form-control-sm" id="name" name="name" style="text-transform:uppercase" value="{{ $data['opname']->i_code }} - {{ $data['opname']->i_name }}" disabled>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Pemilik</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="hidden" id="owner_hidden" value="{{ $data['opname']->oa_comp }}">
                      <input type="text" class="form-control form-control-sm" value="{{ $data['opname']->oa_comp }}" disabled>
                      {{--
                      <!-- <select name="owner" id="owner" class="form-control form-control-sm select2 read-only">
                        @foreach($data['company'] as $position)
                            <option value="{{$position->c_id}}" @if($position->c_id == $data['opname']->oa_comp) selected @endif>{{$position->c_name}}</option>
                        @endforeach
                      </select> -->
                      --}}
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Lokasi</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="hidden" id="position_hidden" value="{{ $data['opname']->oa_position }}">
                      <input type="text" class="form-control form-control-sm" value="{{ $data['opname']->oa_position }}" disabled>
                      {{--
                      <!-- <select name="position" id="position" class="form-control form-control-sm select2 read-only">
                        @foreach($data['company'] as $position)
                            <option value="{{$position->c_id}}" @if($position->c_id == $data['opname']->oa_position) selected @endif>{{$position->c_name}}</option>
                        @endforeach
                    </select> -->
                    --}}
                    </div>
                  </div>
                  {{--
                  <!-- <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Kondisi</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="text" name="" value="">
                      <select name="condition" id="condition" class="form-control form-control-sm select2">
                        <option value="FINE" selected>Baik</option>
                        <option value="BROKEN">Rusak</option>
                      </select>
                    </div>
                  </div> -->
                  --}}
                  <div class="col-12"><hr></div>
                  <div class="col-md-6">
                    <div class="title-block">
                      <h3 class="title"> Stock System </h3>
                    </div>
                    <form role="form">
                      <div class="form-group">
                        <label class="control-label" for="unit_sys">Satuan</label>
                        <input type="hidden" id="unit_sys_hidden" value="{{ $data['opname']->oa_unitsystem }}">
                        <select type="text" class="form-control form-control-sm select2" id="unit_sys" name="unit_sys">
                          <option value="" selected disabled>Pilih Satuan</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label class="control-label" for="qty_sys">Qty</label>
                        <input type="hidden" id="qty_sys_hidden" name="qty_sys_hidden">
                        <input type="text" class="form-control form-control-sm" id="qty_sys" name="qty_sys" readonly>
                      </div>
                    </form>
                    <table class="table table-hover table-bordered table-striped w-100" id="tb_code_produksi">
                      <thead>
                        <th class="text-center">Kode Produksi</th>
                        <th class="text-center">Qty</th>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-6">
                    <div class="title-block">
                      <h3 class="title"> Stock Real </h3>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="unit_real">Satuan</label>
                      <input type="hidden" id="unit_real_hidden" value="{{ $data['opname']->oa_unitreal }}">
                      <select type="text" class="form-control form-control-sm select2" id="unit_real" name="unit_real">
                        <option value="" selected disabled>Pilih Satuan</option>
                      </select>
                      <span class="text-danger errorSatuanR d-none">Harap pilih satuan!</span>
                    </div>
                    <div class="form-group" style="margin-bottom: 1.4rem !important;">
                      <label class="control-label" for="qty_real">Qty</label>
                      <input type="number" class="form-control form-control-sm" id="qty_real" name="qty_real">
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

                    <table class="table" id="tb_addCodeReal">
                      <thead>
                        <th class="text-center" style="width: 60%;">Kode Produksi</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Action</th>
                      </thead>
                      <tbody>
                        @foreach($code_real as $key => $code)
                        <tr>
                          <td style="padding: 8px;">
                            <input type="text" disabled="" class="form-control bg-light code_s" value="{{$code->oad_code}}">
                            <input type="hidden" name="code_r[]" class="form-control code_r" readonly value="{{$code->oad_code}}" >
                          </td>
                          <td style="padding: 8px;">
                            <input type="text" disabled="" class="form-control bg-light qty_s text-right" value="{{$code->oad_qty}}">
                            <input type="hidden" name="qty_r[]" class="form-control qty_r" readonly value="{{$code->oad_qty}}">
                          </td>
                          <td class="text-center">
                            <button class="btn rounded-circle btn-danger btn-sm delete-list"><i class="fa fa-trash"></i></button>
                          </td>
                        <tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </section>
            </div>
          </form>
          <div class="card-footer text-right">
            <button class="btn btn-primary btn-submit" type="button" id="btn_simpan">Simpan</button>
            <a href="{{route('opname.index')}}" class="btn btn-secondary">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>

@endsection

@section('extra_script')
<script type="text/javascript">
  var tb_code_produksi;
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function(){
    $('#name').autocomplete({
      source: baseUrl+'/inventory/manajemenstok/opnamestock/getItemAutocomplete',
      minLength: 2,
      select: function(event, data){
        $('#itemId').val(data.item.id);
        $('#unit_sys_hidden').val('x');
        updateUnitSys();
      }
    });

    updateUnitSys();
    $('#owner').on('select2:select', function() {
      updateUnitSys();
    });

    $('#position').on('select2:select', function() {
      updateUnitSys();
    });

    $('#unit_sys').on('select2:select', function() {
      $.ajax({
        type: 'get',
        data: $('#myForm').serialize(),
        url: baseUrl + '/inventory/manajemenstok/opnamestock/getQty',
        success: function(data) {
          $('#qty_sys').val('');
          $('#qty_sys_hidden').val('');
          $('#qty_sys').val(data.qty);
          $('#qty_sys_hidden').val(data.qtySystem);
        }
      });
    })

    $('#btn_simpan').on('click', function() {
      SubmitForm(event);
    });
  });

  owner_hidden = $('#owner_hidden').val();
  $("#owner option[value="+owner_hidden+"]").prop("selected", true);
  position_hidden = $('#position_hidden').val();
  $("#position option[value="+position_hidden+"]").prop("selected", true);

  function updateUnitSys()
  {
    $.ajax({
      type: 'get',
      data: $('#myForm').serialize(),
      url: baseUrl + '/inventory/manajemenstok/opnamestock/getItem',
      success: function(data) {
        $('#unit_sys').find('option').not(':first').remove();
        $('#unit_real').find('option').not(':first').remove();
        $('#qty_sys').val('');
        if (data != 'empty') {
          if (data.unit1_id != null) {
            $('#unit_sys').append('<option value="'+ data.unit1_id +'" data-qty="">'+ data.unit1_name +'</option>');
            $('#unit_real').append('<option value="'+ data.unit1_id +'" data-qty="">'+ data.unit1_name +'</option>');
          }
          if (data.unit2_id != null && data.unit2_id != data.unit1_id) {
            $('#unit_sys').append('<option value="'+ data.unit2_id +'" data-qty="">'+ data.unit2_name +'</option>');
            $('#unit_real').append('<option value="'+ data.unit2_id +'" data-qty="">'+ data.unit2_name +'</option>');
          }
          if (data.unit3_id != null && data.unit3_id != data.unit2_id) {
            $('#unit_sys').append('<option value="'+ data.unit3_id +'" data-qty="">'+ data.unit3_name +'</option>');
            $('#unit_real').append('<option value="'+ data.unit3_id +'" data-qty="">'+ data.unit3_name +'</option>');
          }
          // set selected item in 'unit_sys' and 'unit_real'
          unit_sys_hidden = $('#unit_sys_hidden').val();
          if (unit_sys_hidden != 'x') {
            $("#unit_sys option[value="+unit_sys_hidden+"]").prop("selected", true);
          }
          // set value to 'qty_sys'
          $('#unit_sys').trigger('select2:select');
          getCodeProduksi();
        } else {
          messageWarning('Perhatian', 'Data tidak ditemukan !');
          getCodeProduksi();
        }
      }
    });
  }

  function getCodeProduksi() {
    var item      = $('#itemId').val();
    var owner     = $('#owner').val();
    var position  = $('#position').val();
    // var condition = $('#condition').val();

    $('#tb_code_produksi').dataTable().fnDestroy();
    tb_code_produksi = $('#tb_code_produksi').DataTable({
        responsive: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        paging: false,
        info: false,
        ajax: {
          url: '{{url('/inventory/manajemenstok/opnamestock/list-code-opname')}}',
          type: 'get',
          data: {item: item, owner: owner, position: position},
        },
        columns: [
            {data: 'sd_code', className: 'text-left'},
            {data: 'sd_qty', className: 'text-right'}
        ],
        pageLength: 10,
        lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
  }

  // Add Code Real
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
    var unit_real = $('#unit_real').val();
    var qty_real  = $('#qty_real').val();
    if (unit_real == '' || unit_real == null) {
      $('.errorSatuanR').removeClass('d-none');
      $('.errorSatuanR').css('display', 'block');
    } else if (qty_real == '') {
      $('.errorSatuanR').css('display', 'none');
      $('.errorQtyR1').removeClass('d-none');
      $('.errorQtyR1').css('display', 'block');
    } else {
      $('.errorQtyR1').css('display', 'none');
      checkInputCodeReal();
    }
  });

  function checkInputCodeReal() {
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
                                            <button class="btn rounded-circle btn-danger btn-sm delete-list"><i class="fa fa-trash"></i></button>
                                          </td>
                                        <tr>`);
    }
  }

  $(document).on('click', '.delete-list', function () {
    $(this).parents('tr').remove();
  });

  // submit form to store data in db
  function SubmitForm(event)
  {
    loadingShow();
    event.preventDefault();
    form_data = $('#myForm').serialize();

    $.ajax({
      data : form_data,
      type : "post",
      url : $("#myForm").attr('action'),
      dataType : 'json',
      success : function (response){
        if(response.status == 'berhasil'){
          loadingHide();
          messageSuccess('Berhasil', 'Data berhasil ditambahkan !');
          location.reload();
        } else if (response.status == 'invalid') {
          loadingHide();
          messageFailed('Perhatian', response.message);
        } else if (response.status == 'gagal') {
          loadingHide();
          messageWarning('Error', response.message);
        }
      },
      error : function(e){
        loadingHide();
        messageWarning('Gagal', 'Data gagal ditambahkan, hubungi pengembang !');
      }
    })
  }
</script>
@endsection
