@extends('main')

@section('content')

@include('masterdatautama.member.modal-edit')
<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Edit Data Member </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Master Data Utama</span>
      / <a href="{{route('member.index')}}"><span>Master Member</span></a>
      / <span class="text-primary" style="font-weight: bold;">Edit Data Member</span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title">Edit Data Member </h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('member.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <form id="updateMember">
            <section>
              <div class="row">
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Nama <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input name="m_name" type="text" class="form-control form-control-sm" value="{{$member->m_name}}">
                  </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>NIK <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input name="m_nik" type="text" class="form-control form-control-sm" value="{{$member->m_nik}}">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Nomer Telp <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input name="m_tlp" type="text" class="form-control form-control-sm" value="{{$member->m_tlp}}">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Provinsi <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select name="m_prov" id="prov" class="form-control form-control-sm select2" onchange="getProvId()">
                      <option value="{{$member->m_province}}" selected>{{$member->wp_name}}</option>
                      @foreach($provinsi->where('wp_id', '!=', $member->m_province) as $prov)
                      <option value="{{$prov->wp_id}}">{{$prov->wp_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Kota/Kabupaten <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <select name="m_city" id="city" class="form-control form-control-sm select2 city">
                      <option value="{{$member->m_city}}" selected>{{$member->wc_name}}</option>
                      @foreach($city as $c)
                        <option value="{{$c->wc_id}}">{{$c->wc_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Alamat <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="form-group">
                    <input name="m_address" type="text" class="form-control form-control-sm" value="{{$member->m_address}}">
                  </div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                  <label>Agen <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10 col-sm-6 col-xs-12">
                  <div class="input-group">
                    <input type="text" name="nameAgen" class="form-control form-control-sm agen" autocomplete="off" style="text-transform: uppercase;" value="{{$member->a_name}}">
                    <input type="hidden" name="idAgen" class="agenId" value="{{$member->c_id}}">
                    <input type="hidden" name="codeAgen" class="codeAgen" value="{{$member->a_code}}">
                    <a class="btn btn-primary rounded-right" data-toggle="modal" data-target="#modal-edit"><i class="fa fa-search"></i></a>
                  </div>
                </div>
              </div>
            </section>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-6"><p>(<span class="text-danger">*</span>) Wajib diisi.</p></div>
              <div class="col-md-6 text-right" style="align-self: center;">
                <button class="btn btn-primary btn-submit" onclick="updateMember('{{Crypt::encrypt($member->m_id)}}')" type="button">Simpan</button>
                <a href="{{route('member.index')}}" class="btn btn-secondary">Kembali</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>

@endsection
@section('extra_script')
<script type="text/javascript">
  var idAgen    = [];
  var namaAgen  = null;
  var kode      = null;
  var icode     = [];
  {{-- Document Ready --}}
  $(document).ready(function(){
      
    $('.agen').on('click change', function () {
        setArrayAgen();
    });

    $(".agen").on("keyup", function () {
        $(".agenId").val('');
        $(".codeAgen").val('');
    });

  });
  // End Document Ready ------------------------------------

  function getProvId() {
    var id = document.getElementById("prov").value;
    $.ajax({
        url: "{{route('orderProduk.getCity')}}",
        type: "get",
        data:{
            provId: id
        },
        success: function (response) {
            $('#city').empty();
            $("#city").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
            $.each(response.data, function( key, val ) {
                $("#city").append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
            });
            $('#city').focus();
            $('#city').select2('open');
        }
    });
  }

  function getProvIdToCity() {
    var id = document.getElementById("prov_agen").value;
    $.ajax({
        url: "{{route('orderProduk.getCity')}}",
        type: "get",
        data:{
            provId: id
        },
        success: function (response) {
            $('#city_agen').empty();
            $("#city_agen").append('<option value="" selected disabled>=== Pilih Kota ===</option>');
            $.each(response.data, function( key, val ) {
                $("#city_agen").append('<option value="'+val.wc_id+'">'+val.wc_name+'</option>');
            });
            $('#city_agen').focus();
            $('#city_agen').select2('open');
        }
    });
  }

  function getDataAgen() {    
    $(".table-modal").removeClass('d-none');
    $('#table_search_agen').DataTable().clear().destroy();
    table_agen = $('#table_search_agen').DataTable({
      responsive: true,
      serverSide: true,
      ajax: {
          url: "{{ url('/masterdatautama/member/get-agen') }}",
          type: "get",
          data: {
              "_token": "{{ csrf_token() }}",
              id : $('#city_agen').val()
          }
      },
      columns: [
          {data: 'wp_name'},
          {data: 'wc_name'},
          {data: 'a_name'},
          {data: 'a_type'},
          {data: 'action_agen'}
      ],
      pageLength: 10,
      lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'All']]
    });
  }

  function chooseAgen(id, name, code) {
    $('#modal-edit').modal('hide');
    $('.agenId').val(id);
    $('.agen').val(name);
    $('.codeAgen').val(code);
  }
    
  // Autocomplete Data Agen ---------------------------------------------
  function setArrayAgen() {
    var inputs = document.getElementsByClassName('codeAgen'),
        code   = [].map.call(inputs, function (input) {
            return input.value.toString();
        });

    for (var i = 0; i < code.length; i++) {
        if (code[i] != "") {
            icode.push(code[i]);
        }
    }

    var agen = [];
    var inpAgenId = document.getElementsByClassName('agenId'),
        agen      = [].map.call(inpAgenId, function (input) {
            return input.value;
        });

    $(".agen").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ url('/masterdatautama/member/cari-agen') }}",
                data: {
                    idAgen: agen,
                    term: $(".agen").val()
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: function (event, data) {
            setAgen(data.item);
        }
    });
  }

  function setAgen(info) {
    idAgen   = info.data.c_id;
    namaAgen = info.data.a_name;
    kode     = info.data.a_code;
    $(".codeAgen").val(kode);
    $(".agenId").val(idAgen);
    setArrayAgen();
  }
  // End Autocomplete -----------------------------------------------------

  // Update Data Member ---------------------------------------------------
  function updateMember(id) {
    $.ajax({
      url: "{{url('/masterdatautama/member/update')}}"+"/"+id,
      type: "get",
      data: $('#updateMember').serialize(),
      beforeSend: function () {
          loadingShow();
      },
      success: function (response) {
          if (response.status == 'sukses') {
              loadingHide();
              messageSuccess('Success', 'Data berhasil diperbarui!');
              window.location.href = "{{route('member.index')}}";
          } else {
              loadingHide();
              messageFailed('Gagal', response.message);
          }
      },
      error: function (e) {
          loadingHide();
          messageWarning('Peringatan', e.message);
      }
    });
  }
</script>
@endsection