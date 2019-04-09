@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Kelola Penjualan Langsung </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Marketing</span>
         / <a href="{{route('manajemenagen.index')}}"><span>Manajemen Agen</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Data Kelola Penjualan Langsung</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Data Kelola Penjualan Langsung </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                          
                          <div id="sectionsuplier" class="row">
                            
                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Member</label>
                            </div> 

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                    <option value=""></option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Total</label>
                            </div> 

                            <div class="col-md-10 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="" value="Rp. 0">
                              </div>
                            </div>

                            <div class="container">
                            <div class="table-responsive mt-3">
                            <table class="table table-hover table-striped diplay nowrap" id="table_create">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>Kode/Nama Barang</th>
                                        <th width="10%">Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Sub Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control form-control-sm"></td>
                                        <td><select name="" id="" class="form-control form-control-sm select2"></select></td>
                                        <td><input type="number" min="0" value="0" class="form-control form-control-sm"></td>
                                        <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                                        <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                                        <td><button class="btn btn-sm btn-success btn-tambahp rounded-circle"><i class="fa fa-plus"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>

                          </div>
                          </div>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('manajemenagen.index')}}" class="btn btn-secondary">Kembali</a>
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

    $(document).on('click', '.btn-hapus', function(){
      $(this).parents('tr').remove();
    });

    $('.btn-tambahp').on('click',function(){
      $('#table_create tbody')
      .append(
        '<tr>'+
          '<td><input type="text" class="form-control form-control-sm"></td>'+
          '<td><select name="" id="" class="form-control form-control-sm select2"></select></td>'+
          '<td><input type="number" min="0" value="0" class="form-control form-control-sm"></td>'+
          '<td><input type="text" class="form-control form-control-sm input-rupiah"></td>'+
          '<td><input type="text" class="form-control form-control-sm input-rupiah"></td>'+
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
