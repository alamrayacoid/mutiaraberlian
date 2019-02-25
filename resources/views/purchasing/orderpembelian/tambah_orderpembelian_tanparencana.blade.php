@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Order Pembelian Tanpa Rencana</h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
        / <span>Purchasing</span> 
        / <a href="{{route('orderpembelian')}}"><span>Order Pembelian</span></a>
        / <span class="text-primary font-weight-bold">Tambah Order Pembelian Tanpa Rencana</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Order Pembelian Tanpa Rencana </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('orderpembelian')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>
                          <fieldset>
                            <div class="row">

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>No Order Pembelian</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" readonly="" name="">
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
                                <label>Tanggal Order Pembelian</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm datepicker" value="{{date('d-m-Y')}}" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Cara Bayar</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select class="form-control form-control-sm">
                                    <option value="CASH">Tunai</option>
                                    <option value="DEPOSIT">Deposit</option>
                                    <option value="CREDIT">Tempo</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Suplier</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select class="form-control form-control-sm select2">
                                    <option value="">--Pilih--</option>
                                  </select>
                                </div>
                              </div>

                            </div>
                          </fieldset>

                          <fieldset class="mt-3">
                            <div class="row">
                              
                              <div class="col-md-4 col-sm-4 col-12">
                                <label>Barang</label>
                                <div class="form-group">
                                  <select class="select2 form-control form-control-sm" id="barang" name="">
                                    <option selected="" disabled="" value="">--Pilih--</option>
                                    <option>Semen</option>
                                    <option>Pasir</option>
                                    <option>Cat</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4 col-sm-4 col-12">
                                <label for="">Satuan</label>
                                <div class="form-group">
                                  <select class="form-control form-control-sm">
                                    <option value="" selected="" disabled="">-Pilih-</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-4 col-sm-4 col-xs-12">
                                <label>Qty</label>
                                <div class="form-group form-group-sm">
                                  <div class="input-group input-group-sm">
                                    <input type="number" class="form-control form-control-sm" id="qty" min="0" name="">
                                    <div class="input-group-append">
                                      <button class="btn btn-primary btn-tambah" type="button" title="Tambah"><i class="fa fa-plus"></i></button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </fieldset>

                          <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="table_order">
                              <thead class="bg-primary">
                                <tr align="center">
                                  <th width="20%">Kode | Barang</th>
                                  <th width="10%">Satuan</th>
                                  <th width="10%">Qty</th>
                                  <th width="10%">Stok Gudang</th>
                                  <th width="1%">Aksi</th>
                                </tr>
                              </thead>
                            </table>
                          </div>

                          <div class="row">
                            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6 col-xs-12">
                              <div class="row">

                                <div class="col-lg-12">
                                  <label>Total Harga</label>
                                </div>

                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                                  </div>
                                </div>
                                
                                <div class="col-lg-12">
                                  <label>Potongan Harga</label>
                                </div>
                                
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                                  </div>
                                </div>

                                <div class="col-lg-12">
                                  <label>Diskon</label>
                                </div>
                                
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                                  </div>
                                </div>

                                <div class="col-lg-12">
                                  <label>PPn</label>
                                </div>
                                
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                                  </div>
                                </div>

                                <div class="col-lg-12">
                                  <label>Total</label>
                                </div>
                                
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                                  </div>
                                </div>

                              </div>
                            </div>
                          </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary" type="button">Simpan</button>
                      <a href="{{route('orderpembelian')}}" class="btn btn-secondary">Kembali</a>
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
    var table = $('#table_order').DataTable();

    var counter = 1;

    $('.btn-tambah').click(function(){
      datatable_append();
    });

    $('#table_order tbody').on('click', '.btn-hapus', function(){
      table.row($(this).parents('tr')).remove().draw();
    });

    function datatable_append(){
      table.row.add([
        '<input type="text" class="form-control form-control-sm" readonly="" value="'+ $('#barang').val() +'">',
        '<input type="text" class="form-control form-control-sm" readonly="">',
        '<input type="number" min="0" readonly="" class="form-control form-control-sm" value="'+ $('#qty').val() +'">',
        '<input type="text" class="form-control form-control-sm" readonly="">',
        '<button type="button" class="btn btn-danger btn-hapus"><i class="fa fa-trash-o"></i></button>'
        ]).draw(false);

      counter++;

      $('#barang').prop('selectedIndex', 0).trigger('change');
      $('#qty').val('');

      $('#barang').focus();
    }
  });
</script>
@endsection