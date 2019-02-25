@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Pencatatan Barang Masuk </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
        / <span>Stok</span> 
        / <a class="{{route('pencatatanbarangmasuk')}}"><span>Pencatatan Barang Masuk</span> </a>
        / <span class="text-primary font-weight-bold">Tambah Pencatatan Barang Masuk</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Pencatatan Barang Masuk </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('pencatatanbarangmasuk')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>

                          <fieldset>
                            <div class="row">

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Nota Order Pembelian</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select class="form-control form-control-sm select2">
                                    <option value="">--Pilih--</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Tanggal Order Pembelian</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="">
                                </div>
                              </div>
                              
                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Staff</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="">
                                </div>
                              </div>
                              
                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Suplier</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Cara Bayar</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="">
                                </div>
                              </div>
                              
                              
                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Plat No</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select class="form-control form-control-sm select2" id="plat_no">
                                    <option value="">--Pilih--</option>
                                    <option value="1">N 9626 UT</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-12">
                                <button class="btn btn-primary btn-block btn-proses" type="button">Proses</button>
                              </div>
                            </div>
                          </fieldset>

                          <div class="table-responsive mt-3">
                            
                            <table class="table table-bordered table-striped table-hover" id="table_barangmasuk" cellspacing="0">
                              <thead class="bg-primary">
                                <tr>
                                  <th>Kode | Barang</th>
                                  <th>Satuan</th>
                                  <th>Panjang Bak (cm<sup>3</sup>)</th>
                                  <th>Lebar Bak (cm<sup>3</sup>)</th>
                                  <th>Tinggi Bak (cm<sup>3</sup>)</th>
                                  <th>Kubikasi Bak (m<sup>3</sup>)</th>
                                  <th>Kubikasi Muatan Bak (m<sup>3</sup>)</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                            </table>

                          </div>

                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary" type="button">Simpan</button>
                      <a href="{{route('pencatatanbarangmasuk')}}" class="btn btn-secondary">Kembali</a>
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

    var table = $('#table_barangmasuk').DataTable();
    var counter = 1;

    $('.btn-proses').click(function(){
      if ($('#plat_no').val().length != 0 && counter === 1) {
        datatable_append();
      } else if($('#plat_no').val() === '' || $('#plat_no').val() === null){
        datatable_clear();
      }
    });

    function datatable_clear(){
      table.clear().draw();
      counter--;
    }

    function datatable_append(){

      table.row.add([
          'BRG/1 Semen',
          'SAK',
          '551',
          '241',
          '200',
          '26.56',
          '<input type="text" class="form-control form-control-sm" name="" id="">'

        ]).draw(false);

      counter++;
    }

  });

</script>
@endsection