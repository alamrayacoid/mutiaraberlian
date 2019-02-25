@extends('main')

@section('content')



<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Return Pembelian </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
        / <span>Purchasing</span> 
        / <a href="{{route('returnpembelian')}}"><span>Return Pembelian</span> </a>
        / <span class="text-primary" style="font-weight: bold;">Tambah Return Pembelian</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Return Pembelian </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('returnpembelian')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Metode Return</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm" id="metode_return">
                                  <option value="">--Pilih--</option>
                                  <option value="1">Tukar Barang</option>
                                  <option value="2">Potong Nota</option>
                                </select>
                              </div>
                            </div>

                          </div>

                          <div id="div_return" class="d-none">
                            <fieldset>
                              <div class="row">

                                
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nota Pembelian</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <select class="form-control form-control-sm select2">
                                      <option value="">--Pilih--</option>
                                    </select>
                                  </div>
                                </div>

                                  <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Kode Return</label>
                                  </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Tanggal Return</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm datepicker" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Staff</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Metode Bayar</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nilai Total Pembelian</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nilai Total Diskon</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nilai Pajak</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nilai Total Pembelian</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nilai Total Diskon</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nilai Pajak</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nilai Total Pembelian (Nett)</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12 120mm d-none">
                                  <label>Nilai Total Return</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12 120mm d-none">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                              </div>

                            </fieldset>

                            <div class="table-responsive mt-3">
                              <table class="table table-hover table-striped table-bordered">
                                <thead class="bg-primary">
                                  <tr>
                                    <th>No</th>
                                    <th>Kode | Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                            </div>

                          </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary" type="button">Simpan</button>
                      <a href="{{route('returnpembelian')}}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>

      </div>

    </div>

  </section>

</article>

@endsection

@section('extra_script')
<script type="text/javascript">
  $('#metode_return').on('change', function(){
    if($(this).val() === '1'){
      $('#div_return').removeClass('d-none');
      $('.120mm').addClass('d-none');
    } else if($(this).val() === '2'){
      $('#div_return').removeClass('d-none');
      $('.120mm').removeClass('d-none');
    } else {
      $('#div_return').addClass('d-none');
      $('.120mm').addClass('d-none');
    }
  });
</script>
@endsection