@extends('main')

@section('extra_style')
<style type="text/css">

</style>
@endsection

@section('content')

@include('profile.ganti_password')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Profile</h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a>
	    	 / <span>Profile</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-12">

				<div class="card">
                    <div class="card-block">
                        <section>
                        	<div class="row">
		                        <div class="col-md-6">
		                            <div class="row">
										<div class="profil-image text-center col-md-4">
											@if (Auth::user()->u_user == 'E')
												<img class="rounded-circle circle-border " id="profile-img" src="{{ asset('storage/app/'.$detailUser->getEmployee->e_foto) }}" alt="profil" style="width:100%; height:100%;">
											@elseif (Auth::user()->u_user == 'A')
												<img class="rounded-circle circle-border " id="profile-img" src="{{ asset('storage/app/'.$detailUser->getAgent->a_img) }}" alt="profil" style="width:100%; height:100%;">
											@else
												<img class="rounded-circle circle-border " id="profile-img" src="{{ asset('assets/img/default.jpg') }}" alt="profil" style="width:100%; height:100%;">
											@endif
											<div class="change-image">

											</div>
											<form class="myForm">
												<input type="file" class="d-none" id="input-img" name="photo">
											</form>

											<button class="btn btn-primary d-none mt-2" type="button" id="update-gambar">Update Gambar</button>
										</div>
		                                <div class="profil-info col-md-8">
		                                    <div>
		                                        <h2 class="mb-4">{{ $detailUser->c_name }}</h2>
		                                        <h4>{{ $detailUser->c_type }}</h4>
												@if ($detailUser->getCity == null)
													<p>{{ $detailUser->getAgent->getArea->wc_name }}</p>
												@else
													<p>{{ $detailUser->getCity->wc_name }}</p>
												@endif
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="col-md-3">
		                            <table class="table small m-b-sm">
		                                <tbody>
		                                    <tr>
		                                        <td>
		                                            <strong>Perusahaan</strong>
		                                        </td>
		                                        <td>{{ $detailUser->c_name }}</td>
		                                    </tr>
		                                    <tr>
		                                        <td>
		                                            <strong>Last Login</strong>
		                                        </td>
		                                        <td>{{ Carbon\Carbon::parse(Auth::user()->u_lastlogin)->format('d/m/Y G:i:s') }}</td>
		                                    </tr>
		                                    <tr>
		                                        <td>
		                                            <strong>Last Logout</strong>
		                                        </td>
		                                        <td>{{ Carbon\Carbon::parse(Auth::user()->u_lastlogout)->format('d/m/Y G:i:s') }}</td>
		                                    </tr>
		                                </tbody>
		                            </table>
		                        </div>
		                        <div class="col-md-3">
		                            <label for="">Username</label>
		                            <h2 class="no margins">{{ Auth::user()->u_username }}</h2>
									<div class="row">
										<button class="btn btn-sm btn-info" type="button" data-target="#change" data-toggle="modal">Ganti Password</button>
										<!-- <button class="btn btn-sm btn-danger" type="button" id="btnResetPass">Reset Password</button> -->
									</div>
		                        </div>
		                    </div>
                        </section>
                    </div>
                </div>

			</div>

			@if(Auth::user()->u_user == 'E')
			<div class="col-12">
				<div class="card">

					<div class="card-header bordered">
						<div class="header-block">
							<h3 class="title">Detail Pegawai</h3>
						</div>
					</div>
					<div class="card-block">
						<div class="row">
							<div class="col-lg-6 col-sm-12">
								<div class="row">
									<div class="col-lg-5 col-sm-12">
										<label>Nama</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getEmployee->e_name }}">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Tanggal Lahir</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->birthday }}">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Alamat</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<textarea readonly="" class="form-control" name="">{{ $detailUser->getEmployee->e_address }}</textarea>
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>No Telp</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm hp" name="" value="{{ $detailUser->getEmployee->e_telp }}">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Jenis Kelamin</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											@if ($detailUser->getEmployee->e_gender == 'L')
												<input type="text" readonly="" class="form-control form-control-sm" name="" value="Laki laki">
											@else
												<input type="text" readonly="" class="form-control form-control-sm" name="" value="Perempuan">
											@endif
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-6 col-sm-12">
								<div class="row">
									<div class="col-lg-5 col-sm-12">
										<label>Pendidikan</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getEmployee->e_education }}">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Agama</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getEmployee->e_religion }}">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Status</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											@if ($detailUser->getEmployee->e_maritalstatus == 'Y')
												<input type="text" readonly="" class="form-control form-control-sm" name="" value="Sudah Menikah">
											@else
												<input type="text" readonly="" class="form-control form-control-sm" name="" value="Belum Menikah">
											@endif
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>No Rekening</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getEmployee->e_rekening }}">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Atas Nama</label>
									</div>
									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->e_an }}">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			@elseif (Auth::user()->u_user == 'A')
			<div class="col-12">
				<div class="card">

					<div class="card-header bordered">
						<div class="header-block">
							<h3 class="title">Detail Agen</h3>
						</div>
					</div>
					<div class="card-block">
						<div class="row">
							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Nama</label>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getAgent->a_name }}">
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Tanggal Lahir</label>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->birthday }}">
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Alamat</label>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<textarea readonly="" class="form-control" name="">{{ $detailUser->getAgent->a_address }}</textarea>
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>No Telp</label>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getAgent->a_telp }}">
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Jenis Kelamin</label>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									@if ($detailUser->getAgent->a_sex == 'L')
										<input type="text" readonly="" class="form-control form-control-sm" name="" value="Laki laki">
									@else
										<input type="text" readonly="" class="form-control form-control-sm" name="" value="Perempuan">
									@endif
								</div>
							</div>

							<!-- <div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Agama</label>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="" value="">
								</div>
							</div> -->


							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Area</label>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									@if ($detailUser->getCity == null)
										<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getAgent->getArea->wc_name }}">
									@else
										<input type="text" readonly="" class="form-control form-control-sm" name="" value="{{ $detailUser->getCity->wc_name }}">
									@endif
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			@endif
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

		$("#input-img").change(function() {
		  readURL(this, '#profile-img');
		  $('#update-gambar').removeClass('d-none');
		});

		$('.change-image').click(function(){
			$('#input-img').click();
		});

		$('#update-gambar').on('click', function() {
			updatePhoto();
		});

		$('#btn_simpanpassword').on('click', function() {
			updatePassword();
		});

		$('#btnResetPass').on('click', function() {
			resetPassword();
		});
	});

	function updatePhoto() {
		loadingShow();
        form_data = new FormData($('.myForm')[0]);

		$.ajax({
			data: form_data,
			type: "post",
			processData: false,
			contentType: false,
			enctype: "multipart/form-data",
			url: "{{ route('profile.updatePhoto') }}",
			dataType: 'json',
			success: function(response) {
				if (response.status == 'berhasil') {
					loadingHide();
					messageSuccess('Berhasil', 'Photo berhasil diubah !');
					location.reload();
				} else if (response.status == 'invalid') {
					loadingHide();
					messageFailed('Perhatian', response.message);
				} else if (response.status == 'gagal') {
					loadingHide();
					messageWarning('Error', response.message);
				}
			},
			error: function(e) {
				loadingHide();
				messageWarning('Gagal', 'Photo gagal diubah, hubungi pengembang !');
			}
		});
	}

	function updatePassword() {
		let formData = $('.formUpdatePass').serialize();
		loadingShow();
		$.ajax({
			url: "{{ route('profile.updatePassword') }}",
			data: formData,
			type: 'post',
			success: function(resp) {
				loadingHide();
				if (resp.status == 'success') {
					messageSuccess('Berhasil', 'Password berhasil di perbarui !');
					$('#change').modal('hide');
				}
				else if (resp.status == 'failed') {
					messageFailed('Gagal', resp.message);
				}
			},
			error: function(e) {
				loadingHide();
				messageWarning('Error', 'Terjadi kesalahan, hubungi pengembang !');
			}
		});
	}

	function resetPassword() {
		$.confirm({
            animation: 'RotateY',
            closeAnimation: 'scale',
            animationBounce: 1.5,
            icon: 'fa fa-exclamation-triangle',
            title: 'Peringatan!',
            content: 'Apakah anda yakin ingin menonaktifkan data ini ?',
            theme: 'disable',
            buttons: {
                info: {
                    btnClass: 'btn-blue',
                    text: 'Ya',
                    action: function() {
						loadingShow();
                        return $.ajax({
							url: "{{ route('profile.resetPassword') }}",
							type: 'post',
							success: function(resp) {
								loadingHide();
								if (resp.status == 'success') {
									messageSuccess('Berhasil', 'Password berhasil di reset !');
									$('#change').modal('hide');
								}
								else if (resp.status == 'failed') {
									messageFailed('Gagal', resp.message);
								}
							},
							error: function(e) {
								loadingHide();
								messageWarning('Error', 'Terjadi kesalahan, hubungi pengembang !');
							}
						});
                    }
                },
                cancel: {
                    text: 'Tidak',
                    action: function() {
                        // tutup confirm
                    }
                }
            }
        });
	}
</script>
@endsection
