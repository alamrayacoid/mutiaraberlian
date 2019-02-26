@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Order Produksi </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Produksi</span>
         / <a href="{{route('order.index')}}"><span>Order Produksi</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Order Produksi</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

                    <div class="card-header bordered p-2">
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
                            <div class="input-group mb-3">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                              </div>
                              <input type="text" class="form-control form-control-sm datepicker" value="07-02-2019">
                            </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Suplier</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">CV Apaya</option>
                                  <option value="">CV Itulo</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Total Harga</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm input-rupiah" name="" value="Rp. 1.000.000,00">
                              </div>
                            </div>
                            <div class="container">
                            <div class="table-responsive">
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_order">
	                                <thead class="bg-primary">
	                                  <tr>
	                                    <th>Kode Barang/Nama Barang</th>
	                                		<th width="10%">Satuan</th>
                                      <th width="10%">Jumlah</th>
                                      <th>Harga</th>
                                      <th>Sub Total</th>
	                                		<th>Aksi</th>
	                                	</tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <select name="" id="" class="form-control form-control-sm select2">
                                        <option value=""></option>
                                      </select>
                                      </td>
	                                		<td>
                                      <input type="number" class="form-control form-control-sm" value="3">
                                      </td>
                                      <td>
                                      <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 1.000.000,00">
                                      </td>
                                      <td>
                                      <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 1.000.000,00">
                                      </td>
                                      <td>
                                      <button class="btn btn-success btn-tambah btn-sm" type="button"><i class="fa fa-plus" aria-hidden="true"></i>
</button>
	                                		</td>
	                                	</tr>
	                                </tbody>
	                            </table>
	                        </div> 
                            </div>
                            <div class="container">
                                <hr style="border:0.7px solid grey; margin-bottom:30px;">
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
                                      <input type="text" class="form-control form-control-sm" value="1">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm datepicker" value="19-02-2019">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 1.000.000,00">
                                      </td>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm datepicker" value="11-02-2019">
                                      </td>
	                                		<td>
                                      <button class="btn btn-success btn-tambah-termin btn-sm" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
	                                		</td>
	                                	</tr>
	                                </tbody>
	                            </table>
	                        </div>                                
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
          '<td><input type="number" class="form-control form-control-sm"></td>'+
          '<td><input type="text" class="form-control form-control-sm input-rupiah"></td>'+
          '<td><input type="text" class="form-control form-control-sm input-rupiah"></td>'+
          '<td><button class="btn btn-danger btn-sm btn-hapus" type="button"><i class="fa fa-trash-o"></i></button></td>'+
        '</tr>'
        );
    });

    $(document).on('click', '.btn-hapus-termin', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambah-termin').on('click',function(){
      var tbody = $(this).parents('tbody');
      var last_row = tbody.find('tr:last-child');
      var input = last_row.find('td:eq(0) input');
      var termin = input.val();
      termin = parseInt( termin );
      var next_termin = termin + 1;
      $('#table_order_termin')
      .append(
        '<tr>'+
          '<td><input type="text" class="form-control form-control-sm" value="' + next_termin + '"></td>'+
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
				text: 'Data Berhasil di Simpan',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		})
  });
</script>
@endsection
