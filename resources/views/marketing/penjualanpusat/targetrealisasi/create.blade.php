@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Target </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Marketing</span>
         / <a href="{{route('targetrealisasi.index')}}"><span>Manajemen Penjualan Pusat</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Target dan Realisasi</span>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Data Target</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block pull-right">
                        <a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                          
                          <div class="row">
                            
                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Bulan/Tahun</label>
                            </div> 

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" id="datepicker" name="">
                              </div>
                            </div>

                            <div class="container">
                                <hr style="border:0.7px solid grey; margin-bottom:30px;">
                          <div class="table-responsive">  
                            <div class="pull-right mb-2">
                               <button class="btn btn-primary btn-tambahb">Tamba Barang</button>
                            </div>
	                            <table class="table table-striped table-hover" cellspacing="0" id="table_target">
	                                <thead class="bg-primary">
	                                    <tr>
	                                    	<th width="8%">No</th>
	                                		  <th width="30%">Kode/Nama Barang</th>
	                                		  <th width="10%">Satuan</th>
	                                	  	<th width="25%">Jumlah Target</th>
                                        <th width="25%">Cabang</th>
	                                		<th>Aksi</th>
	                                	</tr>
	                                </thead>
	                                <tbody>
	                                	<tr>
	                                		<td>
                                      <input type="text" class="form-control form-control-sm" value="1">
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
                                      <input type="text" class="form-control form-control-sm">
                                      </td>
	                                		<td>
                                      <button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
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
                      <a href="{{route('penjualanpusat.index')}}" class="btn btn-secondary">Kembali</a>
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

    $("#datepicker").datepicker( {
        format: "MM/yyyy",
        viewMode: "months", 
        minViewMode: "months"
    });

    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambahb').on('click',function(){
      // var tbody = $(this).parents('tbody');
      // var last_row = tbody.find('tr:last-child');
      // var input = last_row.find('td:eq(0) input');
      // var termin = input.val();
      // termin = parseInt( termin );
      // var next_termin = termin + 1;
      $('#table_target')
      .append(
        '<tr>'+
          '<td><input type="text" class="form-control form-control-sm" value=""></td>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
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
