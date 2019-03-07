@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Variasi Satuan Produk </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('agen.index')}}"><span>Data Agen</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Data Variasi Satuan Produk </span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Data Variasi Satuan Produk </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('variasisatuan.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                          
                          <div class="row">
                            
                          <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                              <select name="" id="" class="form-control form-control-sm">
                                <option value="">Pilih Satuan</option>
                                <option value="">DUS</option>
                                <option value="">Botol</option>
                              </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Rentan</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Tipe</label>
                            </div> 

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm">
                                    <option value="">Pilih Tipe</option>
                                    <option value="">Agen</option>
                                    <option value="">Sub Agen</option>
                                    <option value="">Konsigne</option>
                                </select>
                              </div>
                            </div>

                          </div>
                        
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('variasisatuan.index')}}" class="btn btn-secondary">Kembali</a>
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