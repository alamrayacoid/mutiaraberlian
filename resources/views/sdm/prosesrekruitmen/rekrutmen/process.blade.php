@extends('main')
@section('content')
<?php 
  if ($data->p_stateapprove == 1 && $data->p_state == "Y") {
    $approve1    = "disabled";
    $app_name    = "Approve 1";
    $status_name = "(Test Interview)";
    $class_bg1   = "";
    $checkedN1   = "";
    $checkedP1   = "";
    $checkedY1   = "checked";
    $app1        = "p_stateapprove1";
    $appr3_1     = "approve3_none";
    $name1       = "p_state1";
    $date1       = "p_date1";
  } else if ($data->p_stateapprove == 1 && $data->p_state == "N") {
    $approve1    = "disabled";
    $app_name    = "Approve 1";
    $status_name = "(Ditolak Administrasi(tidak bisa diproses lagi))";
    $class_bg1   = "";
    $checkedN1   = "checked";
    $checkedP1   = "";
    $checkedY1   = "";
    $app1        = "p_stateapprove";
    $appr3_1     = "approve3_none";
    $name1       = "p_state";
    $date1       = "p_date1";
  } else if ($data->p_stateapprove == 1 && $data->p_state == "P") {
    $approve1    = "";
    $app_name    = "Approve 1";
    $status_name = "(Pending Tahap 1)";
    $class_bg1   = "";
    $checkedN1   = "";
    $checkedP1   = "checked";
    $checkedY1   = "";
    $app1        = "p_stateapprove";
    $appr3_1     = "approve3";
    $name1       = "p_state";
    $date1       = "p_date";
  } else if ($data->p_stateapprove > 1) {
    $approve1  = "disabled";
    $class_bg1 = "";
    $checkedN1 = "";
    $checkedP1 = "";
    $checkedY1 = "checked";
    $app1      = "p_stateapprove1";
    $appr3_1   = "approve3_none";
    $name1     = "p_state1";
    $date1     = "p_date1";
  } else {
    $approve1    = "";
    $app_name    = "(Belum Terapprove!)";
    $class_bg1   = "";
    $status_name = "";
    $checkedN1   = "";
    $checkedP1   = "";
    $checkedY1   = "";
    $app1        = "p_stateapprove";
    $appr3_1     = "approve3";
    $name1       = "p_state";
    $date1       = "p_date";
  }
  if ($data->p_stateapprove == 1 && $data->p_state == "Y") {
    $approve2    = "";
    $app_name    = "Approve 1";
    $status_name = "(Test Interview)";
    $class_bg2   = "";
    $checkedN2   = "";
    $checkedP2   = "";
    $checkedY2   = "";
    $app2        = "p_stateapprove";
    $appr3_2     = "approve3";
    $name2       = "p_state";
    $date2       = "p_date";
  } else if ($data->p_stateapprove == 2 && $data->p_state == "N") {
    $approve2    = "disabled";
    $app_name    = "Approve 2";
    $status_name = "(Ditolak Administrasi(tidak bisa diproses lagi))";
    $class_bg2   = "";
    $checkedN2   = "checked";
    $checkedP2   = "";
    $checkedY2   = "";
    $app2        = "p_stateapprove";
    $appr3_2     = "approve3_none";
    $name2       = "p_state";
    $date2       = "p_date";
  } else if ($data->p_stateapprove == 2 && $data->p_state == "P") {
    $approve2    = "";
    $app_name    = "Approve 2";
    $status_name = "(Pending Tahap 2)";
    $class_bg2   = "";
    $checkedN2   = "";
    $checkedP2   = "checked";
    $checkedY2   = "";
    $app2        = "p_stateapprove";
    $appr3_2     = "approve3";
    $name2       = "p_state";
    $date2       = "p_date";
  } else if ($data->p_stateapprove == 2 && $data->p_state == "Y") {
    $approve2    = "disabled";
    $app_name    = "Approve 2";
    $status_name = "(Test Presentasi)";
    $class_bg2   = "";
    $checkedN2   = "";
    $checkedP2   = "";
    $checkedY2   = "checked";
    $app2        = "p_stateapprove2";
    $appr3_2     = "approve3_none";
    $name2       = "p_state2";
    $date2       = "p_date2";
  } else if ($data->p_stateapprove > 2) {
    $approve2  = "disabled";
    $class_bg2 = "";
    $checkedN2 = "";
    $checkedP2 = "";
    $checkedY2 = "checked";
    $app2      = "p_stateapprove2";
    $appr3_2   = "approve3_none";
    $name2     = "p_state2";
    $date2     = "p_date2";
  } else {
    $approve2  = "disabled";
    $class_bg2 = "bg-secondary-smooth";
    $checkedN2 = "";
    $checkedP2 = "";
    $checkedY2 = "";
    $app2      = "p_stateapprove2";
    $appr3_2   = "approve3_none";
    $name2     = "p_state2";
    $date2     = "p_date2";
  }
  if ($data->p_stateapprove == 2 && $data->p_state == "Y") {
    $approve3    = "";
    $app_name    = "Approve 2";
    $status_name = "(Test Presentasi)";
    $class_bg3   = "";
    $checkedN3   = "";
    $checkedY3   = "";
    $app3        = "p_stateapprove";
    $name3       = "p_state";
  } else if ($data->p_stateapprove == 3 && $data->p_state == "N") {
    $approve3    = "disabled";
    $app_name    = "Approve 3";
    $status_name = "(Ditolak Final)";
    $class_bg3    = "";
    $checkedN3   = "checked";
    $checkedY3   = "";
    $app3        = "p_stateapprove";
    $name3       = "p_state";
  } else if ($data->p_stateapprove == 3 && $data->p_state == "Y") {
    $approve3    = "disabled";
    $app_name    = "Approve 3";
    $status_name = "(Diterima Sebagai Karyawan)";
    $class_bg3   = "";
    $checkedN3   = "";
    $checkedY3   = "checked";
    $app3        = "p_stateapprove";
    $name3       = "p_state";
  } else {
    $approve3  = "disabled";
    $class_bg3 = "bg-secondary-smooth";
    $checkedN3 = "";
    $checkedY3 = "";
    $app3      = "p_stateapprove3";
    $name3     = "p_state3";
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
              <form id="formProses">
                {{csrf_field()}}
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
                      <label>Posisi yang dilamar</label>
                    </div>
                    <div class="col-md-7 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" readonly="" name="" value="{{$data->j_name}}">
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="col-5">
                  <h6 style="font-weight:bold;">Status Pelamar: </h6>
                  <div class="status-pelamar">
                    <span>{{$app_name}}</span>
                    <p>{{$status_name}}</p>
                  </div>
                </fieldset>
                <fieldset class="approval-1 {{$class_bg1}}" id="approval1">
                  <div class="container">
                    <input type="hidden" name="{{$app1}}" value="1">
                    <h6 style="font-weight:bold;">Approval 1: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="checkbox" name="{{$appr3_1}}" id="hide1" value="3" {{$approve1}}>
                      <p>Terima Sebagai Karyawan</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name1}}" id="hides1" value="N" {{$checkedN1}} {{$approve1}}>
                      <p>Ditolak Administrasi(tidak bisa diproses lagi)</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name1}}" id="hide1" value="P" {{$checkedP1}} {{$approve1}}>
                      <p>Pending Tahap 1</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name1}}" id="show1" value="Y" {{$checkedY1}} {{$approve1}}>
                      <p>Test Interview</p>
                    </div>
                    @if($dateApp1)
                    <div class="row date1 d-block">
                      <input type="text" name="{{$date1}}" class="form-control form-control-sm datepicker bg-light" placeholder="Tanggal Interview" value="{{$dateApp1->pl_date}}" disabled="">
                    </div>
                    @else
                    <div class="row date1 d-none">
                      <input type="text" name="{{$date1}}" class="form-control form-control-sm datepicker" placeholder="Tanggal Interview" value="">
                    </div>
                    @endif
                  </div>
                </fieldset>
                <fieldset class="approval-2 {{$class_bg2}}" id="approval2">
                  <div class="container">
                    <input type="hidden" name="{{$app2}}" value="2">
                    <h6 style="font-weight:bold;">Approval 2: </h6>
                    <br>
                    <div class="row radio-box">
                      <input type="checkbox" name="{{$appr3_2}}" id="hide1" value="3" {{$approve2}}>
                      <p>Terima Sebagai Karyawan</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name2}}" id="hides2" value="N" {{$checkedN2}} {{$approve2}}>
                      <p>Ditolak Administrasi(tidak bisa diproses lagi)</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name2}}" id="hide2" value="P" {{$checkedP2}} {{$approve2}}>
                      <p>Pending Tahap 2</p>
                    </div>
                    <div class="row radio-box">
                      <input type="radio" name="{{$name2}}" id="show2" value="Y" {{$checkedY2}} {{$approve2}}>
                      <p>Test Presentasi</p>
                    </div>
                    @if($dateApp2)
                    <div class="row date2 d-block">
                      <input type="text" name="{{$date2}}" class="form-control form-control-sm datepicker bg-light" placeholder="Tanggal Interview" value="{{$dateApp2->pl_date}}" disabled="">
                    </div>
                    @else
                    <div class="row date2 d-none">
                      <input type="text" name="{{$date2}}" class="form-control form-control-sm datepicker" placeholder="Tanggal Interview" value="">
                    </div>
                    @endif
                  </div>
                </fieldset>
                <fieldset class="approval-3 {{$class_bg3}}">
                  <div class="container">
                    <input type="hidden" name="{{$app3}}" value="3">
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
              </form>
            </section>
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary" type="button" onclick="addProses('{{Crypt::encrypt($data->p_id)}}')">Simpan</button>
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
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function addProses(id) {
    $.confirm({
        animation: 'RotateY',
        closeAnimation: 'scale',
        animationBounce: 1.5,
        icon: 'fa fa-exclamation-triangle',
        title: 'Pesan!',
        content: 'Apakah anda yakin dengan keputusan ini?',
        theme: 'disable',
        buttons: {
            info: {
                btnClass: 'btn-blue',
                text: 'Ya',
                action: function() {
                    return $.ajax({
                        url: "{{url('/sdm/prosesrekruitmen/addProses')}}"+"/"+id,
                        type: "get",
                        data: $("#formProses").serialize(),
                        beforeSend: function() {
                            loadingShow();
                        },
                        success: function(response) {
                            if (response.status == 'sukses') {
                                loadingHide();
                                messageSuccess('Berhasil', 'Data berhasil disimpan');
                                window.location.href="{{route('rekruitmen.index')}}";
                            } else {
                                loadingHide();
                                messageFailed('Gagal', response.message);
                            }
                        },
                        error: function(e) {
                            loadingHide();
                            messageWarning('Peringatan', e.message);
                        }
                    });
                }
            },
            cancel: {
                text: 'Tidak',
                action: function(response) {
                    loadingHide();
                    messageWarning('Peringatan', 'Anda telah membatalkan!');
                }
            }
        }
    });
  }
</script>
<script>
  $('#show1').on('click', function(){
    $('#approval1 .date1').removeClass('d-none');
  });
  $('#hides1').on('click', function(){
    $('#approval1 .date1').addClass('d-none');
  });
  $('#hide1').on('click', function(){
    $('#approval1 .date1').addClass('d-none');
  });
  $('#show2').on('click', function(){
    $('#approval2 .date2').removeClass('d-none');
  });
  $('#hides2').on('click', function(){
    $('#approval2 .date2').addClass('d-none');
  });
  $('#hide2').on('click', function(){
    $('#approval2 .date2').addClass('d-none');
  });
</script>
@endsection