@extends('main')

@section('content')


<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Pengelolaan Manajemen Stok </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Inventory</span>
         / <a href="{{route('dataharga.index')}}"><span>Penglolaan Data Max/Min Stok, Safety Stok</span></a>
         / <span class="text-primary" style="font-weight: bold;">Tambah Data Max/Min Stok, Safety Stok</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Tambah Data Max/Min Stok, Safety Stok</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('pengelolaanmms.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Pemilik</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">Pilih Pemilik</option>
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Posisi</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">Pilih Posisi</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Barang</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Min Stok</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Max Stok</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="number" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Safety Stok</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                               <input type="text" class="form-control-sm form-control" name="">
                              </div>
                            </div>

                            <div class="" style="font-size:15pt;">
                                <label for="">-</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                               <input type="text" class="form-control-sm form-control" name="">
                              </div>
                            </div>

                          </div>

                        </section>
                    </div>

                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('pengelolaanmms.index')}}" class="btn btn-secondary">Kembali</a>
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