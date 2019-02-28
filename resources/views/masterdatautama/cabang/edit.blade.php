@extends('main')

@section('content')
<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Edit Data Cabang </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Master Data Utama</span>
      / <a href="{{route('cabang.index')}}"><span>Data Cabang</span></a>
      / <span class="text-primary" style="font-weight: bold;"> Edit Data Cabang</span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title"> Edit Data Cabang </h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('cabang.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <form action="{{ route('cabang.edit', [Crypt::encrypt($data->c_id)]) }}" method="post" id="myForm" autocomplete="off">
            <div class="card-block">
              <section>
                  <?php
                    if($data->c_type == 'AGEN'){
                      $temp = "disabled";
                    }else{
                      $temp = "";
                    }
                  ?>
                <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <label>Nama Cabang</label>
                  </div>
                  <div class="col-md-9 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-sm" name="cabang_name" value="{{ $data->c_name }}" style="text-transform: uppercase;">
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <label>Alamat Cabang</label>
                  </div>
                  <div class="col-md-9 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <textarea type="text" class="form-control form-control-sm" name="cabang_address">{{ $data->c_address }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <label>No Telp</label>
                  </div>
                  <div class="col-md-9 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <input type="number" class="form-control form-control-sm" name="cabang_telp" value="{{ $data->c_tlp }}">
                    </div>
                  </div>
                  <input type="hidden" name="cabang_type" value="{{$data->c_type}}">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <label>Pemilik Cabang</label>
                  </div>
                  <div class="col-md-9 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <select name="cabang_user" id="" class="form-control form-control-sm" {{$temp}}>
                        @if($data->c_user == null)
                          <option value="" selected>!--- Pilih Pemilik Cabang ---!</option>
                          @foreach($employe as $emp)
                            <option value="{{$emp->e_id}}">{{$emp->e_name}}</option>
                          @endforeach
                        @else
                          <option value="{{$data->c_user}}" selected>{{$data->e_name}}</option>
                          @foreach($employe as $emp)
                            <option value="{{$emp->e_id}}">{{$emp->e_name}}</option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
              </section>
            </div>
            <div class="card-footer text-right">
              <button class="btn btn-primary btn-submit" type="button" id="btn_simpan">Simpan</button>
              <a href="{{route('cabang.index')}}" class="btn btn-secondary">Kembali</a>
            </div>
          </form>
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

  $('#btn_simpan').on('click', function() {
    $.confirm({
      animation: 'RotateY',
      closeAnimation: 'scale',
      animationBounce: 1.5,
      icon: 'fa fa-exclamation-triangle',
      title: 'Pesan!',
      content: 'Apakah anda yakin ingin memperbarui data ini?',
      theme: 'disable',
      buttons: {
        info: {
          btnClass: 'btn-blue',
          text: 'Ya',
          action: function () {
            SubmitForm(event);
          }
        },
        cancel: {
          text: 'Tidak',
          action: function () {

          }
        }
      }
    });
  });
  
  function SubmitForm(event)
  {
    event.preventDefault();

    $.ajax({
      data : $('#myForm').serialize(),
      type : "post",
      url  : $("#myForm").attr('action'),
      dataType : 'json',
      beforeSend: function() {
        loadingShow();
      },
      success : function (response){
        if(response.status == 'sukses'){
          loadingHide();
          messageSuccess('Success', 'Data berhasil diperbarui!');
          window.location.href = "{{route('cabang.index')}}";
        } else if (response.status == 'invalid') {
          loadingHide();
          messageWarning('Perhatian', response.message);
        }
      },
      error : function(e){
        loadingHide();
        messageWarning('Warning', e.message);
      }
    });
  }
</script>
@endsection
