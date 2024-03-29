@extends('main')

@section('content')
<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Tambah Data Pegawai </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Master Data Utama</span>
      / <a href="{{route('pegawai.index')}}"><span>Data Pegawai</span></a>
      / <span class="text-primary font-weight-bold">Tambah Data Pegawai</span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title">Tambah Data Pegawai</h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('pegawai.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <section>
              <fieldset>
                <form id="formAdd" action="{{ route('pegawai.store') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="row">
                        <div class="col-12" align="center">
                          <div class="form-group">
                            <img src="{{asset('assets/img/default.jpg')}}" id="img-preview" style="cursor: pointer; max-height: 254px;max-width: 100%;" class="img-thumbnail">
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <input type="file" class="form-control form-control-sm" name="e_foto" id="foto" accept="image/*">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>NIP <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm nip" name="e_nip" required>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Nama Pegawai <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="e_name" required>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>NIK <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm nik" name="e_nik" required>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Pemilik Cabang <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <select name="e_company" id="" class="form-control form-control-sm select2">
                              <option value="" disabled selected>== Pilih Cabang ==</option>
                              @foreach($company as $comp)
                              <option value="{{$comp->c_id}}">{{$comp->c_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Nomor HP <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm hp" name="e_telp" required>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Agama <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="e_religion" required>
                          </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                          <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <select class="form-control form-control-sm select2" name="e_gender" id="e_gender" required>
                              <option value="" disabled selected>== Pilih Jenis ==</option>
                              <option value="L">Laki-laki</option>
                              <option value="P">Perempuan</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Status <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <select class="form-control form-control-sm select2" name="e_maritalstatus" id="e_maritalstatus" required>
                              <option value="" disabled selected>== Pilih Status ==</option>
                              <option value="N">Belum Menikah</option>
                              <option value="Y">Sudah Menikah</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    {{-- <div class="menikah col-12"> --}}
                      <div class="col-md-2 col-sm-6 col-xs-12 menikah">
                        <label>Nama Pasangan</label>
                      </div>
                      <div class="col-md-4 col-sm-6 col-xs-12 menikah">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm menikahForm" name="e_matename">
                        </div>
                      </div>
                      <div class="col-md-2 col-sm-6 col-xs-12 menikah">
                        <label>Jumlah Anak</label>
                      </div>
                      <div class="col-md-4 col-sm-6 col-xs-12 menikah">
                        <div class="form-group">
                          <input type="text" class="form-control form-control-sm digits menikahForm" name="e_child">
                        </div>
                      </div>
                    {{-- </div> --}}
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Tanggal Lahir <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar mt-2"></i></span>
                          <input type="text" class="form-control form-control-sm datepicker" name="e_birth" required>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Tanggal Masuk</label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar mt-2"></i></span>
                          <input type="text" class="form-control form-control-sm datepicker" name="e_workingyear">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Pendidikan <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="e_education" required>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>E-mail <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm email" name="e_email" required>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Jabatan <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <select name="e_position" id="" class="form-control form-control-sm select2">
                          <option value="" disabled selected="">== Pilih Jabatan ==</option>
                          @foreach($jabatan as $jab)
                          <option value="{{$jab->j_id}}">{{$jab->j_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Divisi <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <select name="e_department" id="" class="form-control form-control-sm select2">
                          <option value="" disabled selected="">== Pilih Divisi ==</option>
                          @foreach($divisi as $div)
                          <option value="{{$div->m_id}}">{{$div->m_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-12 col-xs-12">
                      <label>Alamat <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <textarea type="text" class="form-control form-control-sm mb-3" name="e_address" required></textarea>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Bank <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="e_bank" required>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>No. Rekening <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm rek" name="e_rekening" required>
                      </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                      <label>Atas Nama <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="e_an" required>
                      </div>
                    </div>
                  </div>
                </form>
              </fieldset>
            </section>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-6">
                <p class="text-secondary">(<span class="text-danger">*</span>) = Wajib Diisi.</p>
              </div>
              <div class="col-sm-6">
                <div class="text-right">
                  <button type="button" class="btn btn-primary" id="btn-submit">Simpan</button>
                  <a href="{{route('pegawai.index')}}" class="btn btn-secondary">Kembali</a>
                </div>
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
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).ready(function(){
    $('#showpassword').click(function(){
      var val = $(this).parents('.input-group').find('input')
      .attr('type', function(index, attr){
        return attr == 'text' ? 'password' : 'text';
      });
      $('#showpassword-icon').toggleClass('fa-eye fa-eye-slash');
    });

    function readURL(input, target) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $(target).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#foto").change(function() {
      readURL(this, '#img-preview');
    });
    $('#img-preview').click(function(){
      $('#foto').click();
    });
  });

  $('#e_gender').on('change', function(){
    var gender = $('#e_gender').val();
    if (gender == 'L') {
      $('#img-preview').attr("src", "{{asset('assets/img/default.jpg')}}");
    } else if (gender == 'P') {
      $('#img-preview').attr("src", "{{asset('assets/img/default2.jpg')}}");
    }
  });

  $('#e_maritalstatus').on('change', function(){
    var status = $('#e_maritalstatus').val();
    if (status == 'N') {
      console.log(status);
      $('.menikah').css("display", "none");
      $('.menikahForm').attr("disabled", "");
    }else if (status == 'Y') {
      console.log(status);
      $('.menikah').css('display', '');
      $('.menikahForm').removeAttr('disabled');
    }
  })

  $('#btn-submit').on('click', function(){
    loadingShow();
    submitForm(event);
  });

  function submitForm(event){
    event.preventDefault();
    // data_link = new FormData($('#formAdd')[0]);
    $.ajax({
      data   : new FormData($('#formAdd')[0]),
      type   : "post",
      processData: false,
      contentType: false,
      enctype: "multipart/form-data",
      url    : $("#formAdd").attr('action'),
      dataType  : "json",
      beforeSend: function() {
        loadingShow();
      },
      success : function (response){
        if(response.status == 'sukses'){
          loadingHide();
          messageSuccess('Success', 'Data berhasil ditambahkan!');
          window.location.href = "{{route('pegawai.index')}}";
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
