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
		                                    <img class="rounded-circle circle-border " id="profile-img" src="{{ asset('assets/img/default.jpg') }}" alt="profil" style="width:100%; height:100%;">
		                                    <div class="change-image">
		                                    	
		                                    </div>
		                                    <input type="file" class="d-none" id="input-img" name="">

		                                    <button class="btn btn-primary d-none mt-2" type="button" id="update-gambar">Update Gambar</button>
		                                </div>
		                                <div class="profil-info col-md-8">
		                                    <div>
		                                        <h2 class="mb-4">(Nama User)</h2>
		                                        <h4>(Akses)</h4>
		                                        <p>(Kota)</p>
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
		                                        <td>MUTIARA A</td>
		                                    </tr>
		                                    <tr>
		                                        <td>
		                                            <strong>Last Login</strong>
		                                        </td>
		                                        <td>{{Carbon\Carbon::parse(Auth::user()->u_lastlogin)->format('d/m/Y G:i:s')}}</td>
		                                    </tr>
		                                    <tr>
		                                        <td>
		                                            <strong>Last Logout</strong>
		                                        </td>
		                                        <td>{{Carbon\Carbon::parse(Auth::user()->u_lastlogout)->format('d/m/Y G:i:s')}}</td>
		                                    </tr>
		                                </tbody>
		                            </table>
		                        </div>
		                        <div class="col-md-3">
		                            <label for="">Username</label>
		                            <h2 class="no margins">{{Auth::user()->u_username}}</h2>
		                            <button class="btn btn-info" type="button" data-target="#change" data-toggle="modal">Ganti Password</button>
		                        </div>
		                    </div>
                        </section>
                    </div>
                </div>

			</div>

			<div class="col-12">
				<div class="card">

					<div class="card-header bordered">
						<div class="header-block">
							<h3 class="title">Detail Employee</h3>
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
											<input type="text" readonly="" class="form-control form-control-sm" name="">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Tanggal Lahir</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Alamat</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<textarea readonly="" class="form-control" name=""></textarea>
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>No Telp</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Jenis Kelamin</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="">
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
											<input type="text" readonly="" class="form-control form-control-sm" name="">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Agama</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Status</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="">
											
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>No Rekening</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="">
										</div>
									</div>

									<div class="col-lg-5 col-sm-12">
										<label>Atas Nama</label>
									</div>

									<div class="col-lg-7 col-sm-12">
										<div class="form-group">
											<input type="text" readonly="" class="form-control form-control-sm" name="">
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					
				</div>
			</div>

			<div class="col-12">
				<div class="card">

					<div class="card-header bordered">
						<div class="header-block">
							<h3 class="title">Detail Pegawai</h3>
						</div>
					</div>
					<div class="card-block">
						
						<div class="row">


							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Nama</label>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="">
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Tanggal Lahir</label>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="">
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Alamat</label>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<textarea readonly="" class="form-control" name=""></textarea>
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>No Telp</label>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="">
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Jenis Kelamin</label>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="">
								</div>
							</div>

							
							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Agama</label>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="">
								</div>
							</div>	


							<div class="col-lg-3 col-md-4 col-sm-5 col-sm-12">
								<label>Area</label>
							</div>

							<div class="col-lg-9 col-md-8 col-sm-7 col-sm-12">
								<div class="form-group">
									<input type="text" readonly="" class="form-control form-control-sm" name="">
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

		$("#input-img").change(function() {
		  readURL(this, '#profile-img');
		  $('#update-gambar').removeClass('d-none');
		});

		$('.change-image').click(function(){
			$('#input-img').click();

		})

	});
</script>
@endsection
