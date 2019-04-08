@extends('main')

@section('content')
@section('extra_style')
<style>
	#img_priview {
    transform-origin: top left; /* IE 10+, Firefox, etc. */
    -webkit-transform-origin: top left; /* Chrome */
    -ms-transform-origin: top left; /* IE 9 */
	}
	#img_priview.rotate90 {
		transform: rotate(90deg) translateY(-100%);
		-webkit-transform: rotate(90deg) translateY(-100%);
		-ms-transform: rotate(90deg) translateY(-100%);
	}
	#img_priview.rotate180 {
		transform: rotate(180deg) translate(-100%,-100%);
		-webkit-transform: rotate(180deg) translate(-100%,-100%);
		-ms-transform: rotate(180deg) translateX(-100%,-100%);
	}
	#img_priview.rotate270 {
		transform: rotate(270deg) translateX(-100%);
		-webkit-transform: rotate(270deg) translateX(-100%);
		-ms-transform: rotate(270deg) translateX(-100%);
	}
    #overlay {
        border: 1px solid black;
        width: 200px;
        height: 200px;
        display: inline-block;
        background-repeat: no-repeat;
    }
    .zoom-title {
        background-color:rgba(45, 52, 54,0.3);
        width: 200px;
        height: 217px;
        z-index:99999999;
    }

    .zoom-title span {
        color:white;
    }

    .zoom-title i {
        color:white;
    }
</style>
@endsection

@include('sdm.prosesrekruitmen.rekrutmen.modal_view_img')

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
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_name}}" name="p_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Nomor Identitas</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_nik}}" name="p_nik">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Alamat</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_address}}" name="p_address">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Alamat Sekarang</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_address_now}}" name="p_address_now">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tempat Lahir</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_birth_place}}" name="p_birth_place">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tanggal Lahir</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_birthday}}" name="p_birthday">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Pendidikan</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_education}}" name="p_education">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_email}}" name="p_email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>No. HP/WA</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_tlp}}" name="p_tlp">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Agama</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_religion}}" name="p_religion">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Status</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <?php if($data->p_status == "S") {
                                                $status = "Belum Menikah";
                                            } else {
                                                $status = "Sudah Menikah";
                                            } ?>
                                            
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$status}}" name="p_status">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Nama Suami/Istri</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_wife_name}}" name="p_wife_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Anak</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_child}}" name="p_child">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Promo dari pelamar</label>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <textarea type="text" class="form-control form-control-sm" readonly="" name="p_promo">{{$data->p_promo}}</textarea>
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
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_schoolname}}" name="p_schoolname">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tahun Masuk</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_yearin}}" name="p_yearin">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tahun Lulus</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_yearout}}" name="p_yearout">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Jurusan</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_jurusan}}" name="p_jurusan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Nilai</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_nilai}}" name="p_nilai">
                                            </div>
                                        </div>
                                    </div>
                                    <h6 style="font-weight:bold; text-decoration:underline;">Daftar Riwayat Hidup</h6>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Nama Perusahaan</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_jobcompany1}}" name="p_jobcompany1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tahun Awal</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="{{$data->p_jobyear1}}" name="p_jobyear1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tahun Akhir</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Job Desc</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <textarea type="text" class="form-control form-control-sm" readonly="" name="p_jobdesc1">{{$data->p_jobdesc1}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="preview-hr">
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Nama Perusahaan</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tahun Awal</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Tahun Akhir</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-sm" readonly="" value="" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label>Job Desc</label>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <textarea type="text" class="form-control form-control-sm" readonly="" name=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </section>
                                <section class="col-12">
                                    <h6 style="font-weight:bold; text-decoration:underline;">Kelengkapan Berkas</h6>
                                    <div class="row">
                                        
                                        <div class="section-1 col-md-3 col-sm-12">
                                            <div class="col-12" style="text-align:center;">
                                                <label for="">Foto</label>
                                            </div>
                                            <div class="outline-img col-12 d-flex align-items-end justify-content-center justify-content-center">   
                                                <img src="{{url('storage/uploads/recruitment', $data->p_imgfoto)}}" alt="Foto" class="img-fluid img-thumbnail" style="max-width:200px; max-height:300px;" id="foto">
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-secondary btn-block rounded" data-toggle="modal" onclick="priview('{{url('storage/uploads/recruitment', $data->p_imgfoto)}}')">Lihat Berkas</button>
                                            </div>
                                        </div>

                                        <div class="section-2 col-md-3 col-sm-12">
                                            <div class="col-12" style="text-align:center;">
                                                <label for="">KTP</label>
                                            </div>
                                            <div class="outline-img col-12 d-flex align-items-end justify-content-center">
                                                <img src="{{url('storage/uploads/recruitment', $data->p_imgktp)}}" alt="" class="img-fluid img-thumbnail" style="max-width:200px; max-height:300px;" id="ktp">
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-secondary btn-block rounded" data-toggle="modal" onclick="priview('{{url('storage/uploads/recruitment', $data->p_imgktp)}}')">Lihat Berkas</button>
                                            </div>
                                        </div>

                                        <div class="section-3 col-md-3 col-sm-12">
                                            <div class="col-12" style="text-align:center;">
                                                <label for="">Ijazah</label>
                                            </div>
                                            <div class="outline-img col-12 d-flex align-items-end justify-content-center">
                                                <img src="{{url('storage/uploads/recruitment', $data->img_ijazah)}}" alt="" class="img-fluid img-thumbnail" style="max-width:200px; max-height:300px;">
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-secondary btn-block rounded" data-toggle="modal" onclick="priview('{{url('storage/uploads/recruitment', $data->img_ijazah)}}')">Lihat Berkas</button>
                                            </div>
                                        </div>

                                        <div class="section-4 col-md-3 col-sm-12">
                                            <div class="col-12" style="text-align:center;">
                                                <label for="">Lain - Lain</label>
                                            </div>
                                            <div class="outline-img col-12 d-flex align-items-end justify-content-center">
                                                <img src="{{url('storage/uploads/recruitment', $data->img_other)}}" alt="" class="img-fluid img-thumbnail" style="max-width:200px; max-height:300px;">
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-secondary btn-block rounded" data-toggle="modal" onclick="priview('{{url('storage/uploads/recruitment', $data->img_other)}}')">Lihat Berkas</button>
                                            </div>
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

  function priview(img) {
    $('#view_img').modal('show');
    //$('#img_priview').remove();
    $("#img_priview").attr("src", img);
    $("#overlay").attr("style", "background-image: url('"+img+"')");
  }
</script>
<script>
var angle = 0, img = document.getElementById('img_priview');
document.getElementById('button').onclick = function() {
    angle = (angle+90)%360;
    img.className = "rotate"+angle;
}
function zoomIn(event) {
  var element = document.getElementById("overlay");
  element.style.display = "inline-block";
  var img = document.getElementById("img_priview");
  var posX = event.offsetX ? (event.offsetX) : event.pageX - img.offsetLeft;
  var posY = event.offsetY ? (event.offsetY) : event.pageY - img.offsetTop;
  element.style.backgroundPosition = (-posX * 4) + "px " + (-posY * 4) + "px";

}

function zoomOut() {
  var element = document.getElementById("overlay");
  element.style.display = "none";
}
</script>
@endsection