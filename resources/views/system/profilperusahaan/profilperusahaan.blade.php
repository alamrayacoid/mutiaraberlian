@extends('main')

@section('content')

<article class="content">

	<div class="title-block text-primary">
	    <h1 class="title"> Profil Perusahaan </h1>
	    <p class="title-description">
	    	<i class="fa fa-home"></i>&nbsp;<a href="{{url('/home')}}">Home</a> / <span>Admin System</span> / <span class="text-primary" style="font-weight: bold;">Profil Perusahaan</span>
	     </p>
	</div>

	<section class="section">

		<div class="row">

			<div class="col-md-4 col-sm-5 col-12">
				<div class="d-block text-center mb-3">
					<img src="{{asset('assets/img/games.ico')}}" height="50px" width="50px">
				</div>
				<div class="table-responsive">
					<table class="table table-striped" cellspacing="0">
						<tbody>
							<tr>
								<td>Nama Perusahaan</td>
								
								<td>Alexis</td>
							</tr>
							<tr>
								<td>Alamat</td>
								
								<td>Jl. alamat surabaya</td>
							</tr>
							<tr>
								<td>Nama Pemilik</td>
								
								<td>Alexis</td>
							</tr>
							<tr>
								<td>Berdiri</td>
								
								<td>dd MM yyyy</td>
							</tr>
							<tr>
								<td>No Telp</td>
								
								<td>-</td>
							</tr>
							<tr>
								<td>No Telp 2</td>
								
								<td>-</td>
							</tr>
							<tr>
								<td>E-mail</td>
								
								<td>-</td>
							</tr>
							<tr>
								<td>Fax</td>
								
								<td>-</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="col-md-8 col-sm-7 col-12">
				
				<div class="card">
                    <div class="card-header bordered p-2">
                    	<div class="header-block">
	                        <h3 class="title"> Profil Perusahaan </h3>
	                    </div>
	                    <div class="header-block pull-right">
                			<button class="btn btn-primary" id="btn-edit" type="button"><i class="fa fa-edit"></i>&nbsp;Edit Data</button>
	                    	
	                    </div>
                    </div>
                    <div class="card-block">
                        <section>

                        	<form id="form_perusahaan">
	                        	<div class="row">
	                        		
	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">Nama Perusahaan</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="text" class="form-control form-control-sm" readonly="" name="">
	                        			</div>
	                        		</div>


	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">Nama Pemilik</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="text" class="form-control form-control-sm" readonly="" name="">
	                        			</div>
	                        		</div>


	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">Berdiri</label>
	                        		</div>
	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="text" class="form-control form-control-sm" id="berdiri" readonly="" name="">
	                        			</div>
	                        		</div>

	                        		<div class="col-md-4 col-sm-0 col-0">
	                        			
	                        		</div>

	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">Alamat</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<textarea class="form-control" readonly=""></textarea>
	                        			</div>
	                        		</div>

	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">No Telp</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="text" class="form-control form-control-sm" readonly="" name="">
	                        			</div>
	                        		</div>

	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">No Telp 2</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="text" class="form-control form-control-sm" readonly="" name="">
	                        			</div>
	                        		</div>


	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">No Fax</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="text" class="form-control form-control-sm" readonly="" name="">
	                        			</div>
	                        		</div>


	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">E-mail</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="text" class="form-control form-control-sm" readonly="" name="">
	                        			</div>
	                        		</div>


	                        		<div class="col-md-4 col-sm-6 col-12">
	                        			<label class="control-label">Logo</label>
	                        		</div>
	                        		<div class="col-md-8 col-sm-6 col-12">
	                        			<div class="form-group">
	                        				<input type="file" class="" readonly="" name="">
	                        			</div>
	                        			<small class="text-danger">Rekomendasi ukuran 50px x 50px</small>
	                        		</div>
	                        	</div>
                        	</form>
                        </section>
                    </div>
                    <div class="card-footer text-right">
                    	<button class="btn btn-primary" type="button" disabled="" id="btn-update">Update</button>
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

		$('#btn-edit').click(function(){
			$('#form_perusahaan :input').attr('readonly', false);

			$('#berdiri').datepicker({
				format:'dd-mm-yyyy',
				enableOnReadonly:false
			});

			$('#btn-update').attr('disabled', false);

			$(this).attr('disabled', true);
		});

		$('#btn-update').click(function(){
			$('#form_perusahaan').find('input[type="file"]').val('');

			$('#form_perusahaan :input').attr('readonly', true);

			$('#btn-edit').attr('disabled', false);

			$(this).attr('disabled', true);
		});

	});


</script>
@endsection