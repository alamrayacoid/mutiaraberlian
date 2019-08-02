@extends('main')

@section('content')
<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
    <h1 class="title"> Edit Data Pegawai </h1>
    <p class="title-description">
      <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
      / <span>Master Data Utama</span>
      / <a href="{{route('pegawai.index')}}"><span>Data Pegawai</span></a>
      / <span class="text-primary font-weight-bold">Edit Data Pegawai</span>
    </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title">Edit Data Pegawai</h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('pegawai.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <section>
              <fieldset>
                <form id="formEdit" action="{{ route('pegawai.edit', [Crypt::encrypt($employee->e_id)]) }}" method="post"  autocomplete="off" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-3">
                      <div class="row">
                        <div class="col-12" align="center">
                          <div class="form-group">
                            @if($employee->e_foto != null)
                            <img src="{{ asset('storage/app/'.$employee->e_foto) }}" class="img-thumbnail" id="img-preview" style="cursor: pointer;max-height: 254px;max-width: 100%;">
                            @else
                            <img src="{{asset('assets/img/add-image-icon2.png')}}" class="img-thumbnail" id="img-preview" style="cursor: pointer;max-height: 254px;max-width: 100%;">
                            @endif
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <input type="file" class="form-control form-control-sm" name="e_foto" id="foto" accept="image/*">
                            <input type="hidden" name="current_foto" value="{{$employee->e_foto}}">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-9">
                      <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>NIP <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm nip" name="e_nip" value="{{$employee->e_nip}}">
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Nama Pegawai <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="e_name" value="{{$employee->e_name}}">
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>NIK <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm nik" name="e_nik" value="{{$employee->e_nik}}">
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Pemilik Cabang <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <select name="e_company" id="" class="form-control form-control-sm select2">
                              <option value="{{$employee->e_company}}" selected>{{$employee->c_name}}</option>
                              @foreach($company->where('c_id', '!=', $employee->e_company) as $comp)
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
                            <input type="text" class="form-control form-control-sm hp" name="e_telp" value="{{$employee->e_telp}}">
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Agama <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="e_religion" value="{{$employee->e_religion}}">
                          </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                          <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <?php
                            if ($employee->e_gender == 'L') {
                            $kelamin = "Laki-laki";
                            } else {
                            $kelamin = "Perempuan";
                            }
                            ?>
                            <select class="form-control form-control-sm select2" name="e_gender">
                              <option value="{{$employee->e_gender}}" selected>{{$kelamin}}</option>
                              @if($employee->e_gender == "L")
                              <option value="P">Perempuan</option>
                              @else
                              <option value="L">Laki-laki</option>
                              @endif
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                          <label>Nama Pasangan</label>
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                          <div class="form-group">
                            <input type="text" class="form-control form-control-sm" name="e_matename" value="{{$employee->e_matename}}">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Status <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <?php
                        if ($employee->e_maritalstatus == 'Y') {
                        $status = "Sudah Menikah";
                        } else {
                        $status = "Belum Menikah";
                        }
                        ?>
                        <select class="form-control form-control-sm select2" name="e_maritalstatus">
                          <option value="{{$employee->e_maritalstatus}}" selected>{{$status}}</option>
                          @if($employee->e_maritalstatus == "Y")
                          <option value="N">Belum Menikah</option>
                          @else
                          <option value="Y">Sudah Menikah</option>
                          @endif
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Jumlah Anak</label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm digits" name="e_child" value="{{$employee->e_child}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Tanggal Lahir <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar mt-2"></i></span>
                          <input type="text" class="form-control form-control-sm datepicker" name="e_birth" value="{{$employee->e_birth}}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Tanggal Masuk</label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar mt-2"></i></span>
                          <input type="text" class="form-control form-control-sm datepicker" name="e_workingyear" value="{{$employee->e_workingyear}}">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Pendidikan <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="e_education" value="{{$employee->e_education}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>E-mail <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm email" name="e_email" value="{{$employee->e_email}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Jabatan</label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <select name="e_position" id="" class="form-control form-control-sm select2">
                          <option value="{{$employee->e_position}}" selected>{{$employee->j_name}}</option>
                          @foreach($jabatan->where('j_id', '!=', $employee->e_position) as $jab)
                          <option value="{{$jab->j_id}}">{{$jab->j_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Divisi</label>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <select name="e_department" id="" class="form-control form-control-sm select2">
                          <option value="{{$employee->e_department}}" selected>{{$employee->m_name}}</option>
                          @foreach($divisi->where('m_id', '!=', $employee->e_department) as $div)
                          <option value="{{$div->m_id}}">{{$div->m_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                      <label>Alamat <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                      <div class="form-group">
                        <textarea type="text" class="form-control form-control-sm mb-3" name="e_address">{{$employee->e_address}}</textarea>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Bank <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-9 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="e_bank" value="{{$employee->e_bank}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>No. Rekening <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-9 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm rek" name="e_rekening" value="{{$employee->e_rekening}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <label>Atas Nama <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-9 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <input type="text" class="form-control form-control-sm" name="e_an" value="{{$employee->e_an}}">
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
                  <button class="btn btn-primary" id="btn-update">Simpan</button>
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
  $(document).ready(function(){
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
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#btn-update').on('click', function() {
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
      // data : $('#formEdit').serialize(),
      data : new FormData($('#formEdit')[0]),
      type : "post",
      processData: false,
      contentType: false,
      url  : $("#formEdit").attr('action'),
      dataType : 'json',
      beforeSend: function() {
        loadingShow();
      },
      success : function (response){
        if(response.status == 'sukses'){
          loadingHide();
          messageSuccess('Success', 'Data berhasil diperbarui!');
          // window.location.href = "{{route('pegawai.index')}}";
          location.reload();
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
