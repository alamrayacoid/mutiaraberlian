@extends('main')

@section('content')

<article class="content">

  <div class="title-block text-primary">
      <h1 class="title">Setting Tunjangan Pegawai</h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Aktivitas SDM</span>
         / <a href="{{route('penggajian.index')}}"><span>Penggajian</span></a>
         / <span class="text-primary font-weight-bold">Tunjangan</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">
        
        <div class="card">
                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title">Setting Tunjangan Pegawai</h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('tunjangan.setting')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
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
                              <label>Divisi</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>
                            
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Jabatan</label>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="">
                              </div>
                            </div>
                            <div class="container">
                            <div class="form-group">
                                <label class="control-label">Daftar Tunjangan (Ceklist pada tunjangan terpilih)</label>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Kehadiran (Leader) | Periode : Harian</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox" checked="checked">
                                        <span>Uang Makan | Periode : Harian</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox" checked="checked">
                                        <span>Tunjangan Anak | Periode : Statis</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Kesehatan (Leader) | Periode : Statis</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Transportasi | Periode : Harian</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Lembur | Periode : Jam</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Lembur Mingguan (Leader) | Periode : Mingguan</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Masa Kerja | Periode : Statis</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Tunjangan Jabatan | Periode : Statis</span>
                                    </label>
                                </div>
                                <div>
                                    <label>
                                        <input class="checkbox rounded" type="checkbox">
                                        <span>Tunjangan hari raya | Periode : Tahunan</span>
                                    </label>
                                </div>
                            </div>
                            </div>

                          </div>

                          
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('tunjangan.setting')}}" class="btn btn-secondary">Kembali</a>
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