@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Edit Order Produksi </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Produksi</span>
         / <a href="{{route('order.index')}}"><span>Order Produksi</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Edit Order Produksi</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Edit Order Produksi </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('order.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                          
                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Tanggal</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Produsen</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" readonly="" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nilai</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Suplier</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nilai</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Total Bayar</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_order">
	                                <thead class="bg-primary">
	                                  <tr>
	                                    <th>Kode Barang</th>
	                                		<th>Satuan</th>
	                                		<th>Nama Barang</th>
	                                		<th>Aksi</th>
	                                	</tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
                                      <td>
                                      <button class="btn btn-success btn-tambah btn-sm" type="button"><i class="fa fa-plus" aria-hidden="true"></i>
</button>
	                                		</td>
	                                	</tr>
	                                </tbody>
	                            </table>
	                        </div>

                          <div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_order_termin">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th>Termin</th>
	                                		  <th>Estimasi</th>
	                                		  <th>Nominal</th>
	                                	  	<th>Tanggal</th>
	                                		  <th>Aksi</th>
	                                	</tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <button class="btn btn-success btn-tambah-termin btn-sm" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
	                                		</td>
	                                	</tr>
	                                </tbody>
	                            </table>
	                        </div>

                          </div>
                        
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('order.index')}}" class="btn btn-secondary">Kembali</a>
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

    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambah').on('click',function(){
      $('#table_order')
      .append(
        '<tr>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
        '</tr>'
        );
    });

    $(document).on('click', '.btn-hapus-termin', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambah-termin').on('click',function(){
      $('#table_order_termin')
      .append(
        '<tr>'+
          '<td><input type="text" class="form-control form-control-sm""></td>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
        '</tr>'
        );
    });

    $(document).on('click', '.btn-submit', function(){
			$.toast({
				heading: 'Success',
				text: 'Data Berhasil di Edit',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		})
  });
</script>
@endsection
