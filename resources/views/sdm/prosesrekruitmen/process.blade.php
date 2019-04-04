@extends('main')

@section('content')

<article class="content">
  <div class="title-block text-primary">
    <h1 class="title"> Proses Data Rekruitmen </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Aktivitas SDM</span>
      / <a href="{{route('rekruitmen.index')}}"><span>Proses Rekruitmen</span></a>
      / <span class="text-primary font-weight-bold">Proses Data Rekruitmen</span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title">Proses Data Rekruitmen</h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('rekruitmen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <section>
              <div class="row">
                
                <fieldset class="col-7">
                  <div class="row">
                    <div class="col-md-5 col-sm-6 col-xs-12">
                      <label>Nama Pelamar</label>
                    </div>
                    <div class="col-md-7 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" readonly="" name="" value="{{$data->p_name}}">
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-5 col-sm-6 col-xs-12">
                      <label>No. HP</label>
                    </div>
                    <div class="col-md-7 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" readonly="" name="" value="{{$data->p_tlp}}">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-5 col-sm-6 col-xs-12">
                      <label>Jabatan yang dilamar</label>
                    </div>
                    <div class="col-md-7 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" readonly="" name="" value="">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-5 col-sm-6 col-xs-12">
                      <label>Posisi</label>
                    </div>
                    <div class="col-md-7 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" readonly="" name="" value="">
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="col-5">
                  <h6 style="font-weight:bold;">Status Pelamar: </h6>
                  <div class="status-pelamar">
                    <span>Approve 1</span>
                    <p>( Pending Tahap 1 )</p>
                  </div>
                </fieldset>
                <fieldset class="approval-1">
                  <div class="container">
                    <h6 style="font-weight:bold;">Approval 1: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="radio" name="check">
                      <p>Ditolak Administrasi(tidak bisa diproses lagi)</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="check">
                      <p>Pending Tahap 1</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="check">
                      <p>Test Interview</p>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="approval-2">
                  <div class="container">
                    <h6 style="font-weight:bold;">Approval 2: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="radio" name="check" disabled>
                      <p>Ditolak Administrasi(tidak bisa diproses lagi)</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="check" disabled>
                      <p>Pending Tahap 2</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="check" disabled>
                      <p>Test Presentasi</p>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="approval-3">
                  <div class="container">
                    <h6 style="font-weight:bold;">Approval 3: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="radio" name="check" disabled>
                      <p>Ditolak Final</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="check" disabled>
                      <p>Diterima Sebagai Karyawan</p>
                    </div>
                  </div>
                </fieldset>
                
              </div>
            </section>
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary btn-submit" type="button">Simpan</button>
            <a href="{{route('rekruitmen.index')}}" class="btn btn-secondary">Kembali</a>
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