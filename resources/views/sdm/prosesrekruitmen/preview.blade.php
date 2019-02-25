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
                        <div class="container">
                        <div class="row">
                        <section class="col-lg-6">

                            <h6 style="font-weight:bold; text-decoration:underline;">Data Pelamar</h6>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Nama</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Brad" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Nomor Identitas</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="1234567890" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Alamat</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Rungkut" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Alamat Sekarang</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Rungkut" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Tempat Lahir</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Surabaya" name="">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Tanggal Lahir</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="07 sep 1999" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Pendidikan</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="S3" name="">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Email</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Brad@pit.com" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>No. HP/WA</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="1234567789" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Agama</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Islam" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Status</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Belum Menikah" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Nama Suami/Istri</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="-" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Anak</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="-" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Promo dari pelamar</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <textarea type="text" class="form-control form-control-sm" readonly="" name="">Saya Artis</textarea>
                                    </div>
                                </div>
                            </div>

                        </section>
                        <section class="col-lg-6">

                        <h6 style="font-weight:bold; text-decoration:underline;">Riwayat Pendidikan Terakhir</h6>

                        <div class="row">
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <label>Sekolah/Universitas</label>
                                </div> 

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" value="Rahasia" name="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <label>Tahun Masuk</label>
                                </div> 

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" value="2015" name="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <label>Tahun Lulus</label>
                                </div> 

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" value="2015" name="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <label>Jurusan</label>
                                </div> 

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" value="IPA" name="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <label>Nilai</label>
                                </div> 

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" readonly="" value="100" name="">
                                </div>
                            </div>
                        </div>

                        <h6 style="font-weight:bold; text-decoration:underline;">Daftar Riwayat Hidup</h6>


                        <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Nama Perusahaan</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Rahasia" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Tahun Awal</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="2016" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Tahun Akhir</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="2016" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Job Desc</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <textarea type="text" class="form-control form-control-sm" readonly="" name="">Duduk Manis</textarea>
                                    </div>
                                </div>
                            </div>

                            <hr class="preview-hr">

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Nama Perusahaan</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="Rahasia" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Tahun Awal</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="2016" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Tahun Akhir</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" value="2016" name="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label>Job Desc</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <textarea type="text" class="form-control form-control-sm" readonly="" name="">Duduk Manis</textarea>
                                    </div>
                                </div>
                            </div>
                            
                        </section>
                        <section class="col-12">

                            <h6 style="font-weight:bold; text-decoration:underline;">Kelengkapan Berkas</h6>

                             <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>CV</label>
                                    </div> 
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" name="">
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary">Lihat Berkas</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Ijazah</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" name="">
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary">Lihat Berkas</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>KTP</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" name="">
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary">Lihat Berkas</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label>Lain-lain</label>
                                    </div> 

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm" readonly="" name="">
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary">Lihat Berkas</button>
                                </div>
                            </div>

                        </section>
                        </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
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