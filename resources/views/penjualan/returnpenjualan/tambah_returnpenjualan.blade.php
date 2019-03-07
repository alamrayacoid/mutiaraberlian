@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Return Penjualan </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
        / <span>Penjualan</span> 
        / <a href="{{route('returnpenjualan')}}"><span>Return Penjualan</span> </a>
        / <span class="text-primary font-weight-bold">Tambah Return Penjualan</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Return Penjualan </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('returnpenjualan')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
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
                                <select class="form-control form-control-sm" id="metode_rusak">
                                  <option value=""> - Pilih Metode Return</option>
                                  <option value="BR"> Barang Rusak </option>
                                  <option value="JS"> Jumlah Salah </option>
                                  <option value="KU"> Kembali Uang </option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div id="jumlah_salah" class="d-none">
                            <fieldset>
                              <div class="row">

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nota Penjualan</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <select class="form-control form-control-sm select2">
                                      <option>--Pilih--</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>No Resi dari Cus</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Tanggal Return</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm datepicker" value="{{date('d-m-Y')}}" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Metode Pembayaran</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                              </div>
                            </fieldset>

                            <div class="row mt-3">

                              <div class="col-md-3 col-sm-12">
                                <label>Detail Pelanggan</label>
                              </div>

                              <div class="col-md-9 col-sm-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Kirim</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>S Gross</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Total Diskon</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Total Penjualan (NETT)</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                            </div>

                            <div class="table-responsive mt-3">
                              <table class="table table-hover table-striped" cellspacing="0">
                                <thead class="bg-primary">
                                  <tr>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Kirim</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Disc Percent</th>
                                    <th>Disc Value</th>
                                    <th>Jumlah Kirim</th>
                                    <th>Total Barang Sesuai</th>
                                  </tr>
                                </thead>
                              </table>
                            </div>

                          </div>

                          <div id="barang_rusak" class="d-none">
                            <fieldset>
                              <div class="row">

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nota Penjualan</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <select class="form-control form-control-sm select2">
                                      <option>--Pilih--</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>No Resi dari Cus</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Tanggal Return</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm datepicker" value="{{date('d-m-Y')}}" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Metode Pembayaran</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                              </div>
                            </fieldset>

                            <div class="row mt-3">

                              <div class="col-md-3 col-sm-12">
                                <label>Detail Pelanggan</label>
                              </div>

                              <div class="col-md-9 col-sm-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Kirim</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>S Gross</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Total Diskon</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Total Penjualan (NETT)</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                            </div>

                            <div class="table-responsive mt-3">
                              <table class="table table-hover table-striped" cellspacing="0">
                                <thead class="bg-primary">
                                  <tr>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Rusak</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Disc Percent</th>
                                    <th>Disc Value</th>
                                    <th>Jumlah Kirim</th>
                                    <th>Total Barang Sesuai</th>
                                  </tr>
                                </thead>
                              </table>
                            </div>

                          </div>


                          <div id="kembali_uang" class="d-none">
                            <fieldset>
                              <div class="row">

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Nota Penjualan</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <select class="form-control form-control-sm select2">
                                      <option>--Pilih--</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>No Resi dari Cus</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Tanggal Return</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm datepicker" value="{{date('d-m-Y')}}" name="">
                                  </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <label>Metode Pembayaran</label>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-12">
                                  <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" name="">
                                  </div>
                                </div>

                              </div>
                            </fieldset>

                            <div class="row mt-3">

                              <div class="col-md-3 col-sm-12">
                                <label>Detail Pelanggan</label>
                              </div>

                              <div class="col-md-9 col-sm-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Kirim</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>S Gross</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Total Diskon</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Total Penjualan (NETT)</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control-sm form-control" readonly="" name="">
                                </div>
                              </div>

                            </div>

                            <div class="table-responsive mt-3">
                              <table class="table table-hover table-striped" cellspacing="0">
                                <thead class="bg-primary">
                                  <tr>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                    <th>Rusak / Salah Kirim</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Disc Percent</th>
                                    <th>Disc Value</th>
                                    <th>Jumlah Kirim</th>
                                    <th>Total Barang Sesuai</th>
                                  </tr>
                                </thead>
                              </table>
                            </div>

                          </div>

                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary" type="button">Simpan</button>
                      <a href="{{route('returnpenjualan')}}" class="btn btn-secondary">Kembali</a>
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
    $('#metode_rusak').on('change', function(){
      if ($(this).val() === 'BR') {
        $('#barang_rusak').removeClass('d-none');
        $('#jumlah_salah').addClass('d-none');
        $('#kembali_uang').addClass('d-none');
      } else if($(this).val() === 'JS'){
        $('#barang_rusak').addClass('d-none');
        $('#jumlah_salah').removeClass('d-none');
        $('#kembali_uang').addClass('d-none');
      } else if($(this).val() === 'KU'){
        $('#barang_rusak').addClass('d-none');
        $('#jumlah_salah').addClass('d-none');
        $('#kembali_uang').removeClass('d-none');
      } else {
        $('#barang_rusak').addClass('d-none');
        $('#jumlah_salah').addClass('d-none');
        $('#kembali_uang').addClass('d-none');
      }
    });
  });
</script>
@endsection