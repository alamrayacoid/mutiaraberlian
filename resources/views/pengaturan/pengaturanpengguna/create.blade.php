@extends('main')

@section('content')



<article class="content animated fadeInLeft">

	<div class="title-block text-primary">
	    <h1 class="title"> Tambah Data User </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Pengaturan</span>
	    	 / <a href="{{route('pengaturanpengguna.index')}}">Pengaturan Pengguna</a>
	    	 / <span class="text-primary font-weight-bold">Tambah Data User</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
                            <h3 class="title"> Tambah Data User </h3>
                        </div>
                        <div class="header-block pull-right">
                			<a class="btn btn-secondary btn-sm" href="{{route('pengaturanpengguna.index')}}"><i class="fa fa-arrow-left"></i></a>
                        </div>
                    </div>
                    <div class="card-block">
                        <section>

                        	<div class="row">
                        		<div class="col-md-3 col-sm-6 col-12">
                        			<label>Pilih Jenis</label>
                        		</div>

                        		<div class="col-md-3 col-sm-6 col-12">
                        			<div class="form-group">
                        				<select class="form-control form-control-sm select2" id="jenis">
                        					<option value="" disabled selected="">--Pilih Jenis--</option>
                        					<option value="1">Agen</option>
                        					<option value="2">Pegawai</option>
                        				</select>
                        			</div>
                        		</div>
                        	</div>

                            @include('pengaturan.pengaturanpengguna.create_agen')
                            @include('pengaturan.pengaturanpengguna.create_pegawai')

                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button" onclick="simpan()">Simpan</button>
                    	<a href="{{url('/pengaturan/pengaturanpengguna/index')}}" class="btn btn-secondary">Kembali</a>
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

        $('#jenis').change(function(){
            var jenis, agen, pegawai;
            jenis            = $(this).val();
            agen             = $('#agen');
            pegawai          = $('#pegawai');

            if (jenis === '1') {
                agen.removeClass('d-none');
                pegawai.addClass('d-none');
            } else if(jenis === '2'){
                agen.addClass('d-none');
                pegawai.removeClass('d-none');
            } else {
                agen.addClass('d-none');
                pegawai.addClass('d-none');
            }
        });
	});

	function showpassword(){
		var password = document.getElementById("password");

		if (password.type === "password") {
			$('#password').attr('type', 'text');
		} else {
			$('#password').attr('type', 'password');
		}
	}

	function showconfirm(){
		var password = document.getElementById("confirmpassword");

		if (password.type === "password") {
			$('#confirmpassword').attr('type', 'text');
		} else {
			$('#confirmpassword').attr('type', 'password');
		}
	}

	function matching(){
				password = $('#password').val();
        confirm = $('#confirmpassword').val();
				if (password === confirm) {
					$('#check').css('display', '');
				} else {
					$('#check').css('display', 'none');
				}

	}

	function pshowpassword(){
		var password = document.getElementById("ppassword");

		if (password.type === "ppassword") {
			$('#ppassword').attr('type', 'text');
		} else {
			$('#ppassword').attr('type', 'password');
		}
	}

	function pshowconfirm(){
		var password = document.getElementById("pconfirmpassword");

		if (password.type === "password") {
			$('#pconfirmpassword').attr('type', 'text');
		} else {
			$('#pconfirmpassword').attr('type', 'password');
		}
	}

	function pmatching(){
				password = $('#ppassword').val();
				confirm = $('#pconfirmpassword').val();
				if (password === confirm) {
					$('#pcheck').css('display', '');
				} else {
					$('#pcheck').css('display', 'none');
				}

	}

	function simpan(){
		loadingShow();
		var jenis = $('#jenis').val();

		if (jenis == 1) {
			var type = 'agen';
			var agen = document.getElementById("sagen");
			var cabang = document.getElementById("cabang");
			var level = document.getElementById("level");
			var username = document.getElementById("username");
			var password = document.getElementById("password");
			var confirmpassword = document.getElementById("confirmpassword");
			var data = $('#formagen').serialize();

			if (agen.value == "" || agen.value == undefined) {
				messageFailed('Failed', 'agen kosong, mohon lengkapi data!');
				return false;
			} else if (cabang.value == "" || cabang.value == undefined) {
				messageFailed('Failed', 'cabang kosong, mohon lengkapi data!');
				return false;
			} else if (level.value == "" || level.value == undefined) {
				messageFailed('Failed', 'level password kosong, mohon lengkapi data!');
				return false;
			} else if (username.value == "" || username.value == undefined) {
				messageFailed('Failed', 'username kosong, mohon lengkapi data!');
				return false;
			}	else if (password.value == "" || password.value == undefined) {
				messageFailed('Failed', 'password kosong, mohon lengkapi data!');
				return false;
			} else if (confirmpassword.value == "" || confirmpassword.value == undefined) {
				messageFailed('Failed', 'confirm password kosong, mohon lengkapi data!');
				return false;
			} else {
				$.ajax({
					type: 'get',
					dataType: 'JSON',
					url: "{{route('pengaturanpengguna.simpan')}}"+'?type='+type+'&'+data,
					success : function(response){
						if (response.status == 'berhasil') {
							messageSuccess('Berhasil', 'Data berhasil disimpan!');
							loadingHide();
							setTimeout(function () {
								window.location.href = "{{url('/pengaturan/pengaturanpengguna/index')}}";
							}, 800);
						} else {
							messageFailed('Gagal', 'Data gagal disimpan!');
						}
					}
				});
			}
		} else if (jenis == 2) {
			var type = 'pegawai';
			var pegawai = document.getElementById("spegawai");
			var cabang = document.getElementById("pcabang");			
			var level = document.getElementById("plevel");
			var username = document.getElementById("pusername");
			var password = document.getElementById("ppassword");
			var confirmpassword = document.getElementById("pconfirmpassword");
			var data = $('#formpegawai').serialize();

			if (pegawai.value == "" || pegawai.value == undefined) {
				messageFailed('Failed', 'agen kosong, mohon lengkapi data!');
				return false;
			} else if (cabang.value == "" || cabang.value == undefined) {
				messageFailed('Failed', 'cabang kosong, mohon lengkapi data!');
				return false;
			} else if (level.value == "" || level.value == undefined) {
				messageFailed('Failed', 'level password kosong, mohon lengkapi data!');
				return false;
			} else if (username.value == "" || username.value == undefined) {
				messageFailed('Failed', 'username kosong, mohon lengkapi data!');
				return false;
			}	else if (password.value == "" || password.value == undefined) {
				messageFailed('Failed', 'password kosong, mohon lengkapi data!');
				return false;
			} else if (confirmpassword.value == "" || confirmpassword.value == undefined) {
				messageFailed('Failed', 'confirm password kosong, mohon lengkapi data!');
				return false;
			} else {
				$.ajax({
					type: 'get',
					data: {data},
					dataType: 'JSON',
					url: "{{route('pengaturanpengguna.simpan')}}"+'?type='+type+'&'+data,
					success : function(response){
						if (response.status == 'berhasil') {
							messageSuccess('Berhasil', 'Data berhasil disimpan!');
							loadingHide();
							setTimeout(function () {
								window.location.href = "{{url('/pengaturan/pengaturanpengguna/index')}}";
							}, 800);
						} else {
							messageFailed('Gagal', 'Data gagal disimpan!');
						}
					}
				});
			}
		}
	}
</script>
@endsection
