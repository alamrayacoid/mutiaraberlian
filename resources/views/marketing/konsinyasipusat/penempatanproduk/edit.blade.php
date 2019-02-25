@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Edit Data Penempatan Produk </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Marketing</span>
         / <a href="{{route('konsinyasipusat.index')}}"><span>Manajemen Konsinyasi Pusat </span></a>
         / <span class="text-primary" style="font-weight: bold;"> Edit Data Penempatan Produk </span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Edit Data Penempatan Produk </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('konsinyasipusat.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                          
                          <div id="sectionsuplier" class="row">
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Penempatan</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3">

                            </div>

                            <div class="container">
                            <div class="table-responsive mt-3">
                            <div class="pull-right mb-3">
                              <button class="btn btn-primary btn-tambahp"><i class="fa fa-plus"></i>&nbsp;Tambah Produk</button>
                            </div>
                            <table class="table table-hover table-striped table-bordered" id="table_rencana">
                              <thead class="bg-primary">
                              <tr>
                                <th>Kode/Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              </tbody>
                            </table>

                          </div>
                          </div>
                        </section>
                        <section>
                        <fieldset>
                        <div class="row">
                        <div class="container">
                          <div class="col-5 pull-right">
                            <input type="text" class="form-control form-control-sm" readonly="">
                          </div>
                          <div class="col-2 pull-right">
                            <label for="">Total Barang</label>
                          </div>
                        </div>
                        </div>
                        <br>
                        <div class="row">
                        <div class="container">
                          <div class="col-5 pull-right">
                            <input type="text" class="form-control form-control-sm input-rupiah" readonly="">
                          </div>
                          <div class="col-2 pull-right">
                            <label for="">Total Harga</label>
                          </div>
                        </div>
                        </div>
                        </fieldset>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('konsinyasipusat.index')}}" class="btn btn-secondary">Kembali</a>
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

    $('.btn-tambahp').on('click',function(){
      $('#table_rencana tbody')
      .append(
        '<tr>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><input type="text" class="form-control form-control-sm input-rupiah" readonly=""></td>'+
          '<td><input type="number" class="form-control form-control-sm"></td>'+
          '<td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
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
