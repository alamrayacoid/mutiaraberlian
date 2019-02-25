@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Diskon Penjualan </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
        / <span>Penjualan</span> 
        / <a href="{{route('diskonpenjualan')}}"><span>Diskon Penjualan</span> </a>
        / <span class="text-primary font-weight-bold">Tambah Diskon Penjualan</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Diskon Penjualan </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('diskonpenjualan')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>


                          <div class="row mb-3">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Type Diskon</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm" id="tipe_dis">
                                  <option value="">--Pilih--</option>
                                  <option value="BRG">Barang</option>
                                  <option value="NT">Nota</option>
                                </select>
                              </div>
                            </div>

                          </div>

                          <div id="div_barang" class="d-none">

                            <fieldset>
                              
                              <div class="row">

                                <div class="col-md-6 col-sm-12">

                                  <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                      <label>Barang</label>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                      <div class="form-group">
                                        <select class="form-control form-control-sm select2">
                                          <option value="">--Pilih--</option>
                                          <option value="1">Semen</option>
                                          <option value="2">Cat</option>
                                          <option value="3">Pasir</option>
                                        </select>
                                      </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                      <label>Harga</label>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                      <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" name="">
                                      </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                      <label>Harga Disc</label>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                      <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" name="">
                                      </div>
                                    </div>

                                  </div>
                                </div>


                                <div class="col-md-6 col-sm-12">

                                  <div class="row">

                                    <div class="col-md-6 col-sm-12">
                                      <label>Disc Percent</label>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <input type="number" min="0" max="100" class="form-control form-control-sm" onkeyup="this.value = minmax(this.value, 0, 100)" onblur="this.value = minmax(this.value, 0, 100)"  name="disc_percent" id="disc_percent">
                                          <span class="input-group-addon">
                                            %
                                          </span>
                                        </div>
                                      </div>
                                    </div>


                                    <div class="col-md-6 col-sm-12">
                                      <label>Disc Value</label>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                      <div class="form-group">
                                        <input type="text" class="form-control form-control-sm input-rupiah" id="disc_value" name="disc_value">
                                      </div>
                                    </div>

                                  </div>

                                </div>

                              </div>

                            </fieldset>
                            
                          </div>

                          <div id="div_nota" class="d-none">
                            <fieldset>
                              <div class="row mb-3">

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nota</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <select class="form-control form-control-sm select2">
                                      <option value="">--Pilih--</option>
                                    </select>
                                  </div>
                                </div>
                              </div>

                              <div class="table-responsive mb-3">
                                <table class="table table-hover table-striped table-bordered data-table" cellspacing="0">
                                  <thead class="bg-primary">
                                    <tr>
                                      <th>Nama</th>
                                      <th>Jumlah</th>
                                      <th>Satuan</th>
                                      <th>Harga</th>
                                      <th>Disc Percent</th>
                                      <th>Disc Value</th>
                                      <th>Total</th>
                                    </tr>
                                  </thead>
                                </table>
                              </div>
                            </fieldset>
                          </div>


                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary" type="button">Simpan</button>
                      <a href="{{route('diskonpenjualan')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

      </div>

    </div>

  </section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
  function minmax(value, min, max) 
  {
      if(parseInt(value) < min || isNaN(parseInt(value))) 
          return min; 
      else if(parseInt(value) > max) 
          return max; 
      else return value;
  }
  $(document).ready(function(){

      $('#disc_value').on('keypress',function(){
          $('#disc_percent').val('');
      });

      $('#disc_percent').on('keypress',function(){
          $('#disc_value').val('');
      });
      

    $('#tipe_dis').on('change', function(){
      if ($(this).val() === 'BRG') {
        $('#div_nota').addClass('d-none');
        $('#div_barang').removeClass('d-none');
      } else if($(this).val() === 'NT'){
        $('#div_barang').addClass('d-none');
        $('#div_nota').removeClass('d-none');
      } else {
        $('#div_barang').addClass('d-none');
        $('#div_nota').addClass('d-none');

      }
    });
  });
</script>
@endsection