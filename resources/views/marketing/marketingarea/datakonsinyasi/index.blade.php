<div class="tab-pane animated fadeIn show" id="datakonsinyasi">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Data Konsinyasi </h3>
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('datakonsinyasi.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
	        </div>
		</div>
		<div class="card-block">
			<section>
				<div class="row filterBranch">
					<div class="col-md-2 col-sm-6 col-xs-12">
						<label>Area</label>
					</div>
					<div class="col-md-5 col-sm-6 col-xs-12">
						<div class="form-group">
							<select name="provinsi" id="provinsi" class="form-control form-control-sm select2 provIdxDK">
							</select>
						</div>
					</div>
					<div class="col-md-5 col-sm-6 col-xs-12">
						<div class="form-group">
							<select name="kota" id="kota" class="form-control form-control-sm select2 cityIdxDK" disabled>
							</select>
						</div>
					</div>

					<div class="col-md-2 col-sm-6 col-xs-12">
						<label>Cabang</label>
					</div>
					<div class="col-md-10 col-sm-12">
						<div class="form-group">
							<input type="hidden" class="userType" value="{{ Auth::user()->getCompany->c_type }}">
							<input type="hidden" name="branchCode" id="branchCode">
							<select class="form-control select2" name="branch" id="branch" disabled>
							</select>
						</div>
					</div>
				</div>

				<!-- <div class="row mb-3 d-none">
					<div class="col-md-3"></div>
					<div class="col-md-6 col-sm-12">
						<div class="input-group input-group-sm input-daterange">
							<input type="text" class="form-control" id="date_from_dk">
							<span class="input-group-addon">-</span>
							<input type="text" class="form-control" id="date_to_dk">
							<div class="input-group-append">
								<button class="btn btn-secondary" type="button" id="btn_search_date_dk"><i class="fa fa-search"></i></button>
								<button class="btn btn-primary" type="button" id="btn_refresh_date_dk"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>
				</div> -->
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_konsinyasi">
						<thead class="bg-primary">
							<tr>
								<th class="text-center">Tanggal</th>
								<th class="text-center">Nota</th>
								<th class="text-center">Agen</th>
								<th class="text-center">Total</th>
								<th class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
