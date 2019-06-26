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
                  </form>
                </div>
                <div class="col-md-6">
                  <div class="title-block">
                    <h3 class="title"> Stock Real </h3>
                  </div>
                  <form role="form">
                    <div class="form-group">
                      <label class="control-label" for="formGroupExampleInput">Satuan</label>
                      <select type="text" class="form-control form-control-sm select2" id="satuanreal">
                        <option value="" disabled selected> - Pilih Satuan - </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="control-label" for="formGroupExampleInput2">Qty</label>
                      <input type="number" class="form-control form-control-sm" id="qtyreal">
                    </div>
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
  $(document).ready(function(){
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
            $.ajax({
              type: 'get',
              data: {data, satuanreal, qtyreal},
              dataType: 'json',
              url: baseUrl + '/inventory/manajemenstok/adjustmentstock/simpan',
              success : function(response){
                if (response.status == 'berhasil') {
                  loadingHide();
                  messageSuccess('Berhasil', 'Opname berhasil!');
                  setTimeout(function () {
                    window.location.href = '{{route('adjustment.index')}}';
                  }, 1000);
                } else {
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
  });

  function getopname(){
    var nota = $('#nota').val();

    axios.get(baseUrl + '/inventory/manajemenstok/adjustmentstock/getopname?nota='+nota)
  .then(function (response) {
    data = response.data.data;
    // handle success
    if (parseInt(response.data.data.o_qtysystem) != parseInt(response.data.stock.s_qty)) {
      $('#btnsimpan').css('display', 'none');
      messageFailed('Failed', 'Data Stock System Sudah Berubah');
    } else {
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
    }
  })
  .catch(function (error) {
    // handle error
    messageFailed('Failed', 'Data Stock System Sudah Berubah');
  });
  }
</script>
@endsection
