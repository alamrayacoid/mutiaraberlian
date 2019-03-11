@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Opname Stock </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Inventory</span>
         / <a href="{{route('mngagen.index')}}"><span>Pengelolaan Manajemen Stock</span></a>
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
                                    <input type="text" class="form-control form-control-sm" name="">
                                </div>
                                </div>
                            <div class="col-12"><hr></div>
                            <div class="col-md-6">
                                <div class="title-block">
                                    <h3 class="title"> Stock System </h3>
                                </div>
                                <form role="form">
                                    <div class="form-group">
                                        <label class="control-label" for="formGroupExampleInput">Satuan</label>
                                        <select type="text" class="form-control form-control-sm select2" id="formGroupExampleInput">
                                            <option value="">Pilih Satuan</option>
                                        </select> 
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="formGroupExampleInput2">Qty</label>
                                        <input type="number" class="form-control form-control-sm" id="formGroupExampleInput2">
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
                                        <input type="text" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="formGroupExampleInput2">Qty</label>
                                        <input type="number" class="form-control form-control-sm" id="formGroupExampleInput2">
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
  $(document).ready(function(){
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
