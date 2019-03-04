@extends('main')

@section('content')

<article class="content animated fadeInLeft">
  <div class="title-block text-primary">
      <h1 class="title"> Edit Data Pegawai </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
        / <span>Master Data Utama</span>
        / <a href="{{route('pegawai.index')}}"><span>Data Pegawai</span></a>
        / <span class="text-primary font-weight-bold">Detail Data Pegawai</span>
      </p>
  </div>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bordered p-2">
            <div class="header-block">
              <h3 class="title">Detail Data Pegawai</h3>
            </div>
            <div class="header-block pull-right">
              <a href="{{route('pegawai.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
          <div class="card-block">
            <section>
              <fieldset>
                <div class="row">
                  <div class="col-md-3">
                    <div class="row">
                      <div class="col-12" align="center">
                        <div class="form-group">
                          @if($employee->e_foto != null)
                            <img src="{{asset('assets/uploads/pegawai')}}/{{$employee->e_foto}}" class="img-thumbnail" width="100%">
                          @else
                            <img src="{{asset('assets/img/add-image-icon2.png')}}" class="img-thumbnail" width="100%">
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="row">
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>NIP</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_nip}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Nama Pegawai</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_name}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>NIK</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_nik}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Pemilik Cabang</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->c_name}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Nomor HP</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_telp}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Agama</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_religion}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Jenis Kelamin</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <?php
                            if ($employee->e_gender == 'L') {
                              $kelamin = "Laki-laki";
                            } else {
                              $kelamin = "Perempuan";
                            }
                          ?>
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$kelamin}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Nama Pasangan</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_matename}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Status</label>
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
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$status}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Jumlah Anak</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_child}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Tanggal Lahir</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_birth}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Tanggal Masuk</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_workingyear}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Pendidikan</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_education}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>E-mail</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_email}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Jabatan</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->j_name}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Divisi</label>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->m_name}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12 col-xs-12">
                        <label>Alamat</label>
                      </div>
                      <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_address}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Bank</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_bank}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>No. Rekening</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_rekening}}">
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-6 col-xs-12">
                        <label>Atas Nama</label>
                      </div>
                      <div class="col-md-9 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <input type="text" readonly disabled style="background-color: white;" class="form-control form-control-sm" value="{{$employee->e_an}}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </section>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>
@endsection
{{-- @section('extra_script')
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
</script>
@endsection --}}
