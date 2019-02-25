@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Penggunaan Bahan Baku </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> 
        / <span>Stok</span> 
        / <a href="{{route('penggunaanbahanbaku')}}"><span>Penggunaan Bahan Baku</span> </a>
        / <span class="text-primary font-weight-bold">Tambah Penggunaan Bahan Baku</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Penggunaan Bahan Baku </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('penggunaanbahanbaku')}}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>

                          <fieldset>
                            <div class="row">

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Gudang</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select class="select2 form-control form-control-sm">
                                    <option value="">--Pilih--</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Tanggal Pemakaian</label>
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
                                <label>Peminta</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Keperluan</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                            </div>
                          </fieldset>

                          <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover table-striped" cellspacing="0" id="table_penggunaan">
                              <thead class="bg-primary">
                                <tr>
                                  <th width="1%">No</th>
                                  <th>Kode | Barang</th>
                                  <th width="15%">Qty</th>
                                  <th width="15%">Satuan</th>
                                  <th width="15%">Stok</th>
                                  <th>Keterangan</th>
                                  <th width="1%"></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>#</td>
                                  <td>
                                    <select class="form-control form-control-sm select2">
                                      <option value="">--Pilih--</option>
                                    </select>
                                  </td>
                                  <td>
                                    <input type="number" min="0" class="form-control-sm form-control" name="">
                                  </td>
                                  <td>
                                    <select class="form-control-sm form-control">
                                      <option value="">--Pilih--</option>
                                    </select>
                                  </td>
                                  <td>
                                    <input type="number" min="0" class="form-control-sm form-control" name="">
                                    
                                  </td>
                                  <td>
                                    <input type="number" min="0" class="form-control-sm form-control" name="">
                                    
                                  </td>
                                  <td>
                                    <button class="btn btn-primary btn-sm btn-tambah" type="button" title="Tambah"><i class="fa fa-plus"></i></button>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>

                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary" type="button">Simpan</button>
                      <a href="{{route('penggunaanbahanbaku')}}" class="btn btn-secondary">Kembali</a>
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

    $('.btn-tambah').on('click', function(){
      $('#table_penggunaan tbody').append(
        '<tr>'+
          '<td>#</td>'+
          '<td>'+
            '<select class="form-control form-control-sm select2">'+
              '<option value="">--Pilih--</option>'+
            '</select>'+
          '</td>'+
          '<td>'+
            '<input type="number" min="0" class="form-control-sm form-control" name="">'+
          '</td>'+
          '<td>'+
            '<select class="form-control-sm form-control">'+
              '<option value="">--Pilih--</option>'+
            '</select>'+
          '</td>'+
          '<td>'+
            '<input type="number" min="0" class="form-control-sm form-control" name="">'+            
          '</td>'+
          '<td>'+
            '<input type="number" min="0" class="form-control-sm form-control" name="">'+           
          '</td>'+
          '<td>'+
            '<button class="btn btn-danger btn-hapus btn-sm" type="button" title="Hapus"><i class="fa fa-trash-o"></i></button>'+
          '</td>'+
        '</tr>'
        );
        $('.select2').select2();
    });

    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

  });
</script>
@endsection