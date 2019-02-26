@extends('main')

@section('content')

<article class="content animated fadeInLeft">

  <div class="title-block text-primary">
      <h1 class="title"> Tambah Data Pegawai </h1>
      <p class="title-description">
        <i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
         / <span>Master Data Utama</span>
         / <a href="{{route('kelolapegawai.index')}}"><span>Data Pegawai</span></a>
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
                        <a href="{{route('kelolapegawai.index')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i></a>
                      </div>
                    </div>

                    <div class="card-block">
                        <section>
                        
                          <fieldset>
                            <div class="row">


                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>NIK</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Nama Pegawai</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Ktp</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Hari Kerja</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Nomor HP</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Status</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select class="form-control form-control-sm">
                                    <option value="">--Pilih Status--</option>
                                    <option value="1">Belum Menikah</option>
                                    <option value="2">Sudah Menikah</option>
                                  </select>
                                </div>
                              </div>
                              
                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Jenis Kelamin</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select class="form-control form-control-sm" name="" id="">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Tanggal Lahir</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control form-control-sm datepicker" name="">
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Tanggal Masuk</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control form-control-sm datepicker" name="">
                                  </div>
                                </div>
                              </div>


                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Pendidikan</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="">
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>E-mail</label>
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
                                  <select name="" id="" class="form-control form-control-sm select2">
                                    <option value="">Manager</option>
                                    <option value="">Supervisor</option>
                                    <option value="">Staf</option>
                                    <option value="">Admin</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Divisi</label>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <select name="" id="" class="form-control form-control-sm select2">
                                    <option value="">HRD & GA</option>
                                    <option value="">Keuangan dan Akuntansi</option>
                                    <option value="">Sales dan Marketing</option>
                                    <option value="">Produksi</option>
                                    <option value="">Gudang dan Pengiriman</option>
                                    <option value="">Direksi</option>
                                    <option value="">Operasional</option>
                                  </select>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-12 col-xs-12">
                                <label>Alamat</label>
                              </div>

                              <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="form-group">
                                </div>
                                  <textarea type="text" class="form-control form-control-sm mb-3"></textarea>
                              </div>


                              <div class="col-md-3 col-sm-12 col-xs-12">
                                <label>Alamat Ktp</label>
                              </div>

                              <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                <div class="form-group">
                                  <textarea type="text" class="form-control form-control-sm"></textarea>
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Bank</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>


                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>No. Rekening</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Atas Nama</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="text" class="form-control form-control-sm" name="">
                                </div>
                              </div>

                              <div class="col-md-3 col-sm-6 col-xs-12">
                                <label>Foto</label>
                              </div>

                              <div class="col-md-9 col-sm-6 col-xs-12">
                                <div class="form-group">
                                  <input type="file" class="form-control form-control-sm" name="foto" id="foto">
                                </div>
                              </div>

                              <div class="col-12" align="center">
                                <div class="form-group">
                                  <img src="{{asset('assets/img/add-image-icon.png')}}" height="120px" width="90px" id="img-preview" style="cursor: pointer;">
                                </div>
                              </div>

                            </div>
                          </fieldset>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary btn-submit" type="button">Simpan</button>
                      <a href="{{route('kelolapegawai.index')}}" class="btn btn-secondary">Kembali</a>
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
    $('.btn-submit').on('click', function(){
			$.toast({
				heading: 'Success',
				text: 'Data Berhasil di Simpan',
				bgColor: '#00b894',
				textColor: 'white',
				loaderBg: '#55efc4',
				icon: 'success'
			})
		});

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
</script>
@endsection