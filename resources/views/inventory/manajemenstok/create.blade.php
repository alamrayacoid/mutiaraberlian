@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Manajemen Stok </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Inventory</span>
         / <a href="#"><span>Manajemen Stok</span></a>
         / <span class="text-primary font-weight-bold">Tambah Manajemen Stok</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Tambah Manajemen Stok</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{ route('manajemenstok.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                        

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Barang</label>
                            </div>

                            <div class="col-md-7 col-sm-6 col-xs-12">
                              <div class="form-group">
                              <select name="" id="" class="form-control form-control-sm select2">
                              <option value="">Obat 1</option>
                              </select>
                              </div>
                            </div>

                            <div class="col-1">
                            <button class="btn btn-primary btn-go">Go</button>
                            </div>

                            <div class="section-barang col-12">
                            <div class="row">

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Kode Barang</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                <input type="text" class="form-control form-contol-sm" value="BRG/001" readonly="">
                                </div>
                              </div>

                              <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Satuan Barang</label>
                              </div>

                              <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                <input type="text" class="form-control form-contol-sm" value="pcs" readonly="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Harga Barang</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                <input type="text" class="form-control form-contol-sm input-rupiah" value="Rp. 10.000,00" readonly="">
                                </div>
                              </div>

                              <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Jumlah Stok</label>
                              </div>

                              <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                <input type="number" class="form-control form-contol-sm">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Min Stok</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                <input type="number" class="form-control form-contol-sm">
                                </div>
                              </div>

                              <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Max Stok</label>
                              </div>

                              <div class="col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                <input type="number" class="form-control form-contol-sm">
                                </div>
                              </div>
                            
                            </div>
                            </div>
                          </div>

                          
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{ route('manajemenstok.index') }}" class="btn btn-secondary">Kembali</a>
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

    $( ".btn-go" ).click(function() {
			$( ".section-barang" ).show();
		});

  });
</script>
@endsection