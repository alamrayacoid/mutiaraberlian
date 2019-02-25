@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title">Setting Gaji</h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas SDM</span>
         / <a href="{{route('penggajian.index')}}"><span>Penggajian</span></a>
         / <span class="text-primary font-weight-bold">Produksi</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Setting Gaji</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('penggajian.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                        

                          <div class="row">

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Pegawai</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Gaji</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Lembur</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Jenis Gaji</label>
                            </div>

                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select type="text" class="form-control form-control-sm" name="">
                                    <option value="">Harian</option>
                                    <option value="">Garapan</option>
                                </select>
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Untuk Jabatan : </label>
                            </div>
                            <div class="form-group">
                                <div class="for-job">
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Mandor</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox" checked="checked">
                                        <span>Ast. Mandor</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox" checked="checked">
                                        <span>Mixing</span>
                                    </label>
                                </div>
                                </div>
                            </div>

                          </div>

                          
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('penggajian.index')}}" class="btn btn-secondary">Kembali</a>
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