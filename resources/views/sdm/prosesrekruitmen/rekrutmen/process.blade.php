@extends('main')

@section('content')
<?php 
  if ($data->p_stateapprove == 1 && $data->p_state == "Y") {
    $approve1  = "disabled";
    $app_name  = "Approve 1";
    $status_name = "Test Interview";
    $checkedN1 = "";
    $checkedP1 = "";
    $checkedY1 = "checked";
    $name1     = "status_app1";
  } else if ($data->p_stateapprove == 1 && $data->p_state == "N") {
    $approve1  = "disabled";
    $app_name  = "Approve 1";
    $status_name = "Ditolak Administrasi(tidak bisa diproses lagi)";
    $checkedN1 = "checked";
    $checkedP1 = "";
    $checkedY1 = "";
    $name1     = "status_app";
  } else if ($data->p_stateapprove == 1 && $data->p_state == "P") {
    $approve1  = "";
    $app_name  = "Approve 1";
    $status_name = "Pending Tahap 1";
    $checkedN1 = "";
    $checkedP1 = "checked";
    $checkedY1 = "";
    $name1     = "status_app";
  } else if ($data->p_stateapprove > 1) {
    $approve1  = "disabled";
    $checkedN1 = "";
    $checkedP1 = "";
    $checkedY1 = "checked";
    $name1     = "status_app1";
  } else {
    $approve1  = "";
    $app_name  = "Belum Terapprove!";
    $status_name = "";
    $checkedN1 = "";
    $checkedP1 = "";
    $checkedY1 = "";
    $name1     = "status_app";
  }
  if ($data->p_stateapprove == 1 && $data->p_state == "Y") {
    $approve2  = "";
    $app_name  = "Approve 1";
    $status_name = "Test Interview";
    $checkedN2 = "";
    $checkedP2 = "";
    $checkedY2 = "";
    $name2     = "status_app";
  } else if ($data->p_stateapprove == 2 && $data->p_state == "N") {
    $approve2  = "disabled";
    $app_name  = "Approve 2";
    $status_name = "Ditolak Administrasi(tidak bisa diproses lagi)";
    $checkedN2 = "checked";
    $checkedP2 = "";
    $checkedY2 = "";
    $name2     = "status_app";
  } else if ($data->p_stateapprove == 2 && $data->p_state == "P") {
    $approve2  = "";
    $app_name  = "Approve 2";
    $status_name = "Pending Tahap 2";
    $checkedN2 = "";
    $checkedP2 = "checked";
    $checkedY2 = "";
    $name2     = "status_app";
  } else if ($data->p_stateapprove == 2 && $data->p_state == "Y") {
    $approve2  = "disabled";
    $app_name  = "Approve 2";
    $status_name = "Test Presentasi";
    $checkedN2 = "";
    $checkedP2 = "";
    $checkedY2 = "checked";
    $name2     = "status_app2";
  } else if ($data->p_stateapprove > 2) {
    $approve2  = "disabled";
    $checkedN2 = "";
    $checkedP2 = "";
    $checkedY2 = "checked";
    $name2     = "status_app2";
  } else {
    $approve2  = "disabled";
    $checkedN2 = "";
    $checkedP2 = "";
    $checkedY2 = "";
    $name2     = "status_app2";
  }
  if ($data->p_stateapprove == 2 && $data->p_state == "Y") {
    $approve3  = "";
    $app_name  = "Approve 2";
    $status_name = "Test Presentasi";
    $checkedN3 = "";
    $checkedY3 = "";
    $name3     = "status_app";
  } else if ($data->p_stateapprove == 3 && $data->p_state == "N") {
    $approve3  = "disabled";
    $app_name  = "Approve 3";
    $status_name = "Ditolak Final";
    $checkedN3 = "checked";
    $checkedY3 = "";
    $name3     = "status_app";
  } else if ($data->p_stateapprove == 3 && $data->p_state == "Y") {
    $approve3  = "disabled";
    $app_name  = "Approve 3";
    $status_name = "Diterima Sebagai Karyawan";
    $checkedN3 = "";
    $checkedY3 = "checked";
    $name3     = "status_app";
  } else {
    $approve3  = "disabled";
    $checkedN3 = "";
    $checkedY3 = "";
    $name3     = "status_app3";
  }
?>
{{-- Content --}}
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
                    <span>{{$app_name}}</span>
                    <p>( {{$status_name}} )</p>
                  </div>
                </fieldset>
                <fieldset class="approval-1">
                  <div class="container">
                    <h6 style="font-weight:bold;">Approval 1: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name1}}" value="N" {{$checkedN1}} {{$approve1}}>
                      <p>Ditolak Administrasi(tidak bisa diproses lagi)</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name1}}" value="P" {{$checkedP1}} {{$approve1}}>
                      <p>Pending Tahap 1</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name1}}" value="Y" {{$checkedY1}} {{$approve1}}>
                      <p>Test Interview</p>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="approval-2">
                  <div class="container">
                    <h6 style="font-weight:bold;">Approval 2: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name2}}" value="N" {{$checkedN2}} {{$approve2}}>
                      <p>Ditolak Administrasi(tidak bisa diproses lagi)</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name2}}" value="P" {{$checkedP2}} {{$approve2}}>
                      <p>Pending Tahap 2</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name2}}" value="Y" {{$checkedY2}} {{$approve2}}>
                      <p>Test Presentasi</p>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="approval-3">
                  <div class="container">
                    <h6 style="font-weight:bold;">Approval 3: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name3}}" value="N" {{$checkedN3}} {{$approve3}}>
                      <p>Ditolak Final</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name3}}" value="Y" {{$checkedY3}} {{$approve3}}>
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