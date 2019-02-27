@extends('main')

@section('content')


<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Edit Harga Produk </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data</span>
         / <a href="{{route('dataharga.index')}}"><span>Data Harga</span></a>
         / <span class="text-primary" style="font-weight: bold;">Edit Harga Produk</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Edit Harga Produk </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('dataharga.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>
                    <div class="card-block">
                        <section>

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Tipe</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">Pilih Tipe</option>
                                  <option value="">Agen</option>
                                  <option value="">Sub Agen</option>
                                  <option value="">Konsine</option>
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Kode/Nama Barang</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">Pilih Kode/Nama Barang</option>
                                  <option value="">DUS001/Kerdus</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="" id="" class="form-control form-control-sm select2">
                                  <option value="">DUS</option>
                                  <option value="">Botol</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Set Satuan</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                              <label>Set Rentang</label>
                            </div>

                            <div class="col-md-1 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>
                            <span>-</span>
                            <div class="col-md-1 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Harga</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                               <input type="text" class="form-control-sm form-control input-rupiah" name="">
                              </div>
                            </div>

                          </div>

                        </section>
                    </div>

                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('dataharga.index')}}" class="btn btn-secondary">Kembali</a>
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
				text: 'Data Berhasil di Edit',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		})
  });
</script>
@endsection