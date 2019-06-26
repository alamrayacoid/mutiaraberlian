@extends('main')

@section('content')

<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Tambah Opname Stock </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Inventory</span>
      / <a href="{{route('manajemenstok.index')}}"><span>Pengelolaan Manajemen Stock</span></a>
      / <span class="text-primary" style="font-weight: bold;"> Tambah Opname Stock </span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title"> Tambah Opname Stock </h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{ route('opname.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <form id="myForm" action="{{ route('opname.store') }}" method="post" autocomplete="off">
            <div class="card-block">
              <section>
                <div class="row">
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Nama Barang</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="hidden" name="itemId" id="itemId">
                      <input type="text" class="form-control form-control-sm" id="name" name="name" style="text-transform:uppercase">
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Pemilik</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <select name="owner" id="owner" class="form-control form-control-sm select2">
                        @foreach($data['company'] as $position)
                        <option value="{{$position->c_id}}">{{$position->c_name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Lokasi</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <select name="position" id="position" class="form-control form-control-sm select2">
                        @foreach($data['company'] as $position)
                        <option value="{{$position->c_id}}">{{$position->c_name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-sm-6 col-xs-12">
                    <label>Kondisi</label>
                  </div>
                  <div class="col-md-10 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <select name="condition" id="condition" class="form-control form-control-sm select2">
                        <option value="FINE" selected="">Baik</option>
                        <option value="BROKEN">Rusak</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-12"><hr></div>
                  <div class="col-md-6">
                    <div class="title-block">
                      <h3 class="title"> Stock System </h3>
                    </div>
                    <form role="form">
                      <div class="form-group">
                        <label class="control-label" for="unit_sys">Satuan</label>
                        <select type="text" class="form-control form-control-sm select2" id="unit_sys" name="unit_sys">
                          <option value="" selected disabled>Pilih Satuan</option>
                        </select>
                      </div>
                      <div class="form-group" style="margin-bottom: 2rem !important;">
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
                      <select type="text" class="form-control form-control-sm select2" id="unit_real" name="unit_real">
                        <option value="" selected disabled>Pilih Satuan</option>
                      </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 2rem !important;">
                      <label class="control-label" for="qty_real">Qty</label>
                      <input type="number" class="form-control form-control-sm" id="qty_real" name="qty_real">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0.8rem !important;">
                      <div class="row">
                        <div class="col-6">
                          <input type="text" id="codeReal" class="form-control" placeholder="Tuliskan Kode">
                          <span class="text-danger errorCodeR d-none">Kode masih kosong!</span>
                        </div>
                        <div class="col">
                          <input type="number" id="qtyReal" class="form-control" min="1" placeholder="Qty" disabled="">
                          <span class="text-danger errorQtyR d-none">Qty masih kosong!</span>
                        </div>
                        <div class="col">
                          <button type="button" class="btn btn-primary tambah-code" disabled=""><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                      </div>
                    </div>

                    <table class="table" id="tb_addCodeReal">
                      <thead>
                        <th class="text-center">Kode Produksi</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Action</th>
                      </thead>
                      <tbody>
                        
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
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $(document).ready(function(){
    $('#tb_code_produksi').dataTable();

    $('#name').autocomplete({
      source: baseUrl+'/inventory/manajemenstok/opnamestock/getItemAutocomplete',
      minLength: 2,
      select: function(event, data){
        $('#itemId').val(data.item.id);
        updateUnitSys();
        getCodeProduksi();
      }
    });

    $('#owner').on('select2:select', function() {
      updateUnitSys();
      getCodeProduksi();
    });

    $('#position').on('select2:select', function() {
      updateUnitSys();
      getCodeProduksi();
    });

    $('#condition').on('select2:select', function() {
      updateUnitSys();
      getCodeProduksi();
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
    });
    // $(document).on('click', '.btn-submit', function(){
    //   var ini = $(this);
  	// 	$.confirm({
  	// 		animation: 'RotateY',
  	// 		closeAnimation: 'scale',
  	// 		animationBounce: 1.5,
  	// 		icon: 'fa fa-exclamation-triangle',
  	// 		title: 'Peringatan!',
  	// 		content: 'Apa anda sudah yakin?',
  	// 		theme: 'disable',
  	// 		buttons: {
  	// 			info: {
  	// 				btnClass: 'btn-blue',
  	// 				text:'Ya',
  	// 				action : function(){
    //                       $.toast({
    //                       heading: 'Success',
    //                       text: 'Data Berhasil di Simpan',
    //                       bgColor: '#00b894',
    //                       textColor: 'white',
    //                       loaderBg: '#55efc4',
    //                       icon: 'success'
    //                   })
  	// 				}
  	// 			},
  	// 			cancel:{
  	// 				text: 'Tidak',
  	// 				action: function () {
  	// 					// tutup confirm
  	// 				}
  	// 			}
  	// 		}
  	// 	});
  	// });
    //
  });

  $('#btn_simpan').on('click', function() {
    SubmitForm(event);
  });

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
        $('#qty_real').val('');
        if (data != 'empty') {
          if (data.unit1_id != null) {
            $('#unit_sys').append('<option value="'+ data.unit1_id +'" data-qty="">'+ data.unit1_name +'</option>');
            $('#unit_real').append('<option value="'+ data.unit1_id +'" data-qty="">'+ data.unit1_name +'</option>');
          }
          if (data.unit2_id != null) {
            $('#unit_sys').append('<option value="'+ data.unit2_id +'" data-qty="">'+ data.unit2_name +'</option>');
            $('#unit_real').append('<option value="'+ data.unit2_id +'" data-qty="">'+ data.unit2_name +'</option>');
          }
          if (data.unit3_id != null) {
            $('#unit_sys').append('<option value="'+ data.unit3_id +'" data-qty="">'+ data.unit3_name +'</option>');
            $('#unit_real').append('<option value="'+ data.unit3_id +'" data-qty="">'+ data.unit3_name +'</option>');
          }
        } else {
          messageWarning('Perhatian', 'Data tidak ditemukan !');
        }
      }
    });
  }

  function getCodeProduksi() {
    var item = $('#itemId').val();
    var owner = $('#owner').val();
    var position = $('#position').val();
    var condition = $('#condition').val();

    $('#tb_code_produksi').dataTable().fnDestroy();
    tb_code_produksi = $('#tb_code_produksi').DataTable({
        responsive: true,
        serverSide: true,
        ajax: {
          url: '{{url('/inventory/manajemenstok/opnamestock/list-code-produksi')}}',
          type: 'get',
          data: {item: item, owner: owner, position: position, condition: condition},
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
    var code = $('#codeReal').val();
    var qty = $('#qtyReal').val();
    var idX = null;
    if (code == 0 || code == '') {
      $('.errorCodeR').removeClass('d-none');
      $('.errorCodeR').css('display', 'block');
    } else if (qty == 0 || qty == '') {
      $('.errorQtyR').removeClass('d-none');
      $('.errorQtyR').css('display', 'block');
    } else {
      $('.errorCodeR').css('display', 'none');
      $('.errorQtyR').css('display', 'none');

      sendCode(code, qty);
    }
  });

  function sendCode(code, qty) {
    let codeExist = false;
    let idxQty = null;

    $.each($('.code_r'), function (index, val) {
      if (code == $('.code_r').eq(index).val()) {
        idxQty =  index;
        codeExist = true
        return false;
       } else {
          codeExist = false;
       }
    });

    if (codeExist == true) {
      var qty_r = $('.qty_r').eq(idxQty).val();
      qty_r = parseInt(qty_r) + parseInt(qty);
      $('.qty_r').eq(idxQty).val(qty_r);  
    } else {
      $('#tb_addCodeReal tbody').append(`<tr>
                                          <td style="padding: 8px;">
                                            <input type="text" name="code_r[]" class="form-control code_r bg-light" readonly value="`+code+`">
                                          </td>
                                          <td style="padding: 8px;">
                                            <input type="text" name="qty_r[]" class="form-control qty_r bg-light" readonly value="`+qty+`">
                                          </td>
                                          <td class="text-center">
                                            <button class="btn rounded-circle btn-danger btn-sm delete-list" onclick><i class="fa fa-trash"></i></button>
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
      url  : $("#myForm").attr('action'),
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
