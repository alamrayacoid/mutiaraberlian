@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Opname Stock </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Inventory</span>
         / <a href="{{route('manajemenstok.index'')}}"><span>Pengelolaan Manajemen Stock</span></a>
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
                        <a href="{{route('opname.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

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
                                  <select name="" id="" class="form-control form-control-sm">
                                    <option value=""></option>
                                  </select>
                                </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label>Lokasi</label>
                                </div>

                                <div class="col-md-10 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select name="" id="" class="form-control form-control-sm">
                                    <option value=""></option>
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
                                            <option value="" disabled>Pilih Satuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="qty_sys">Qty</label>
                                        <input type="number" class="form-control form-control-sm" id="qty_sys" name="qty_sys" readonly>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="title-block">
                                    <h3 class="title"> Stock Real </h3>
                                </div>
                                <form role="form">
                                    <div class="form-group">
                                        <label class="control-label" for="unit_real">Satuan</label>
                                        <select type="text" class="form-control form-control-sm select2" id="unit_real" name="unit_real">
                                            <option value="" disabled>Pilih Satuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="qty_real">Qty</label>
                                        <input type="number" class="form-control form-control-sm" id="qty_real" name="qty_real">
                                    </div>
                                </form>
                            </div>
                            </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('mngagen.index')}}" class="btn btn-secondary">Kembali</a>
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

    $('#name').autocomplete({
      source: baseUrl+'/inventory/manajemenstok/opnamestock/getItems',
      minLength: 2,
      select: function(event, data){
        $('#itemId').val(data.item.id);
        $('#unit_sys').find('option').not(':first').remove();
        if (data.item.unit1_id != null) {
          $('#unit_sys').append('<option value="'+ data.item.unit1_id +'" data-qty="">'+ data.item.unit1_name +'</option>');
          $('#unit_real').append('<option value="'+ data.item.unit1_id +'" data-qty="">'+ data.item.unit1_name +'</option>');
        }
        if (data.item.unit2_id != null) {
          $('#unit_sys').append('<option value="'+ data.item.unit2_id +'" data-qty="">'+ data.item.unit2_name +'</option>');
          $('#unit_real').append('<option value="'+ data.item.unit2_id +'" data-qty="">'+ data.item.unit2_name +'</option>');
        }
        if (data.item.unit3_id != null) {
          $('#unit_sys').append('<option value="'+ data.item.unit3_id +'" data-qty="">'+ data.item.unit3_name +'</option>');
          $('#unit_real').append('<option value="'+ data.item.unit3_id +'" data-qty="">'+ data.item.unit3_name +'</option>');
        }
      }
    });

    $('#unit_sys').on('select2:select', function() {

    })

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
                        $.toast({
                        heading: 'Success',
                        text: 'Data Berhasil di Simpan',
                        bgColor: '#00b894',
                        textColor: 'white',
                        loaderBg: '#55efc4',
                        icon: 'success'
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
</script>
@endsection
