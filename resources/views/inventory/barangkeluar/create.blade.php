@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Barang Keluar </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas Inventory</span>
         / <a href="#"><span>Barang Keluar</span></a>
         / <span class="text-primary font-weight-bold">Tambah Barang Keluar</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Tambah Barang Keluar</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                        

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Barang</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="hidden" name="idItem" id="idItem">
                                <input type="text" class="form-control form-control-sm" name="namaItem" id="namaItem" style="text-transform:uppercase">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Satuan</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="satuan" id="satuan" class="form-control form-control-sm select2">
                                  <option value="" disabled selected>== Pilih Satuan ==</option>
                                  @foreach($unit as $unit)
                                    <option value="{{$unit->u_id}}">{{$unit->u_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Lokasi Barang</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                  <select name="lokasi" id="lokasi" class="form-control form-control-sm select2">
                                    <option value="" disabled selected>== Pilih Lokasi Barang ==</option>
                                    @foreach($company as $lokasi)
                                      <option value="{{$lokasi->c_id}}">{{$lokasi->c_name}}</option>
                                    @endforeach
                                  </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Jumlah Barang</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="number" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Pemilik Barang</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select name="pemilik" id="pemilik" class="form-control form-control-sm select2">
                                  <option value="" disabled selected>== Pilih Pemilik Barang ==</option>
                                  @foreach($company as $pemilik)
                                    <option value="{{$pemilik->c_id}}">{{$pemilik->c_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>HPP</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control input-hpp text-right" name="">
                              </div>
                            </div>


                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Keterangan Barang Keluar</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select class="form-control form-control-sm select2" name="">
                                  <option value="" disabled="" selected="">== Pilih Keterangan ==</option>
                                  @foreach($mutcat as $ket)
                                    <option value="{{$ket->m_id}}">{{$ket->m_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                          </div>

                          
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary">Kembali</a>
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

    $('.input-hpp').maskMoney({
      thousands: ".",
      precision: 0,
      decimal: ","
    });


  });
</script>
@endsection