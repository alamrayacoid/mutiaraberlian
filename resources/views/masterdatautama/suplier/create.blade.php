@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Suplier </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('suplier.index')}}"><span>Data Suplier</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Data Suplier</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Data Suplier </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('suplier.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                          
                          <div id="sectionsuplier" class="row">
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Perusahaan</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm"name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Suplier</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Alamat</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>NPWP</label>
                            </div> 

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>No Telp</label>
                            </div> 

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>No Telp 1</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>No Telp 2</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>No Rekening</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Bank</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>


                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Fax</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Note</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <textarea type="text" class="form-control form-control-sm"></textarea>
                              </div>
                            </div>


                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>TOP(Termin Of Payment)</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Deposit</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Limit</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm input-rupiah">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Hutang</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm input-rupiah">
                              </div>
                            </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('suplier.index')}}" class="btn btn-secondary">Kembali</a>
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
          '<td><input type="text" class="form-control" value="Obat Masuk Angin"></td>'+
          '<td><input type="number" class="form-control" value="2500"></td>'+
          '<td><input type="number" class="form-control" value="20"></td>'+
          '<td><input type="text" class="form-control" value="Hampir Expired"></td>'+
          '<td align="center"><button class="btn btn-danger btn-hapus btn-sm" type="button"><i class="fa fa-trash-o"></i></button></td>'+
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
