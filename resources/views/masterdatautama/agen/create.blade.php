@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Agen </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('agen.index')}}"><span>Data Agen</span></a>
         / <span class="text-primary" style="font-weight: bold;"> Tambah Data Agen</span>
       </p>
  </div>

  <section class="section">

    <div class="row">

      <div class="col-12">

        <div class="card">

                    <div class="card-header bordered p-2">
                      <div class="header-block">
                        <h3 class="title"> Tambah Data Agen </h3>
                      </div>
                      <div class="header-block pull-right">
                        <a href="{{route('agen.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <form action="{{ route('agen.store') }}" method="post" id="myForm" autocomplete="off">
                      <div class="card-block">
                        <section>

                          <div class="row">

                            <!-- <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Kode Agen</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="code" value="(Auto Generete)" readonly>
                              </div>
                            </div> -->

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Area (Provinsi)</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="area_prov" class="select2 form-control form-control-sm" name="area_prov">
                                  <option value="" selected>Pilih Provinsi</option>
                                  @foreach($data['provinces'] as $prov)
                                    <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Area (Kota)</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="area_city" class="select2 form-control form-control-sm" name="area_city">
                                  <option value="" selected>Pilih Kota</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Nama Agen</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input id="name" type="text" class="form-control form-control-sm" name="name">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Tipe Agen</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="type" class="select2 form-control form-control-sm" name="type">
                                  <option value="">Pilih Tipe Agen</option>
                                  <option value="AGEN">Agen</option>
                                  <option value="SUB AGEN">Sub Agen</option>
                                  <option value="KONSIGNE">Konsigne</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12 div_parent">
                              <label>Agen (parent)</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12 div_parent">
                              <div class="form-group">
                                <select id="parent" class="select2 form-control form-control-sm" name="parent">
                                  <option value="" selected="">Pilih Parent</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Tanggal Lahir</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker" name="birthday">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Alamat (Provinsi)</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="address_prov" class="select2 form-control form-control-sm" name="address_prov">
                                  <option value="" selected>Pilih Provinsi</option>
                                  @foreach($data['provinces'] as $prov)
                                    <option value="{{ $prov->wp_id }}">{{ $prov->wp_name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Alamat (Kota/Kabupaten)</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="address_city" class="select2 form-control form-control-sm" name="address_city">
                                  <option value="" selected>Pilih Kota/Kabupaten</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Alamat (Kecamatan)</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="address_district" class="select2 form-control form-control-sm" name="address_district">
                                  <option value="" selected>Pilih Kecamatan</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Alamat (Desa)</label>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <select id="address_village" class="select2 form-control form-control-sm" name="address_village">
                                  <option value="" selected>Pilih Desa</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Alamat Agen</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <textarea id="address" type="text" class="form-control form-control-sm" name="address"></textarea>
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>Email</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="email" placeholder="user@email.com">
                              </div>
                            </div>

                            <div class="col-md-3 col-sm-6 col-xs-12">
                              <label>No Telp</label>
                            </div>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="telp">
                              </div>
                            </div>

                          </div>

                        </section>
                      </div>
                      <div class="card-footer text-right">
                        <button class="btn btn-primary btn-submit" id="btn_simpan" type="button">Simpan</button>
                        <a href="{{route('agen.index')}}" class="btn btn-secondary">Kembali</a>
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

  $(document).ready(function() {
    $('.div_parent').hide();
  })

  $('#type').on('change', function() {
    if ($(this).val() == 'SUB AGEN') {
      RetrieveListAgents();
      $('.div_parent').show();
    } else {
      $('.div_parent').hide();
      $('#parent').empty();
    }
  })

  function RetrieveListAgents()
  {
    $.ajax({
      type: 'get',
      url: baseUrl + '/masterdatautama/agen/agents',
      success: function(data) {
        $('#parent').empty();
        $.each(data, function(key, val) {
          $("#parent").append('<option value="'+ val.a_code +'">'+ val.a_name +'</option>');
        });
        $('#parent').focus();
        $('#parent').select2('open');
      }
    });
  }

  // set request when area_prov changed
  // set value area_city
  $('#area_prov').on('change', function() {
    $.ajax({
      type: 'get',
      url: baseUrl + '/masterdatautama/agen/cities/' + $('#area_prov').val(),
      success: function(data) {
        $('#area_city').empty();
        $.each(data, function(key, val) {
          $("#area_city").append('<option value="'+ val.wc_id +'">'+ val.wc_name +'</option>');
        });
        $('#area_city').focus();
        $('#area_city').select2('open');
      }
    });
  })

  // set request when address_prov changed
  // set value address_city
  $('#address_prov').on('change', function() {
    $.ajax({
      type: 'get',
      url: baseUrl + '/masterdatautama/agen/cities/' + $('#address_prov').val(),
      success: function(data) {
        $('#address_city').empty();
        $('#address_district').empty();
        $('#address_village').empty();
        $.each(data, function(key, val) {
          $("#address_city").append('<option value="'+ val.wc_id +'">'+ val.wc_name +'</option>');
        });
        $('#address_city').focus();
        $('#address_city').select2('open');
      }
    });
  })

  // set request when address_city changed
  // set value address_district
  $('#address_city').on('change', function() {
    $.ajax({
      type: 'get',
      url: baseUrl + '/masterdatautama/agen/districts/' + $('#address_city').val(),
      success: function(data) {
        $('#address_district').empty();
        $('#address_village').empty();
        $.each(data, function(key, val) {
          $("#address_district").append('<option value="'+ val.wk_id +'">'+ val.wk_name +'</option>');
        });
        $('#address_district').focus();
        $('#address_district').select2('open');
      }
    });
  })

  // set request when address_district changed
  // set value address_village
  $('#address_district').on('change', function() {
    $.ajax({
      type: 'get',
      url: baseUrl + '/masterdatautama/agen/villages/' + $('#address_district').val(),
      success: function(data) {
        $('#address_village').empty();
        $.each(data, function(key, val) {
          $("#address_village").append('<option value="'+ val.wd_id +'">'+ val.wd_name +'</option>');
        });
        $('#address_village').focus();
        $('#address_village').select2('open');
      }
    });
  })

  $('#btn_simpan').on('click', function() {
    SubmitForm(event);
  });

  // submit form to store data in db
  function SubmitForm(event)
  {
    loadingShow();
    event.preventDefault();
    form_data = $('#myForm').serialize();

    $.ajax({
      data : form_data,
      type : "post",
      url : $("#myForm").attr('action'),
      dataType : 'json',
      success : function (response){
        if(response.status == 'berhasil'){
          loadingHide();
          messageSuccess('Berhasil', 'Data berhasil ditambahkan !');
          location.reload();
        } else if (response.status == 'invalid') {
          loadingHide();
          messageWarning('Perhatian', response.message);
        }
      },
      error : function(e){
        loadingHide();
        messageWarning('Gagal', 'Data gagal ditambahkan, hubungi pengembang !');
      }
    })
  }

</script>
@endsection
