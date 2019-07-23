<div class="tab-pane fade in" id="list_pelamarditerima">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Data Pelamar Diterima</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<h6 class="col-11">Pencarian Berdasarkan :</h6>
				</div>
				<fieldset class="mb-3">
					<div class="row">
						<div class="col-md-3 col-sm-6 col-xs-12">
							<label>Tanggal Awal</label>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control form-control-sm datepicker" id="diterima_from" name="">
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<label>Tanggal Akhir</label>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control form-control-sm datepicker" id="diterima_to" name="">
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<label>Pendidikan Terakhir</label>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="form-group">
								<select class="form-control form-control-sm select2" id="terima_edu">
									<option value="" selected>Tampilkan Semua</option>
									<option value="SD">SD</option>
									<option value="SMP">SMP</option>
									<option value="SMA">SMA</option>
									<option value="SMK">SMK</option>
									<option value="D1">D1</option>
									<option value="D3">D3</option>
									<option value="S1">S1</option>
									<option value="S2">S2</option>
									<option value="S3">S3</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<label>Posisi Yang Dilamar</label>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<div class="form-group">
								<select class="form-control form-control-sm select2" id="terima_position">
									<option value="" selected>Tampilkan Semua</option>
									@foreach($applicant as $key => $app)
										<option value="{{$app->ss_id}}">{{$app->j_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12 text-center">
							<button class="btn btn-primary rounded" type="button" onclick="TableDiterima()"><i class="fa fa-fw fa-filter"></i> Terapkan Filter</button>
						</div>
					</div>
				</fieldset>
				<br>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" style="width: 100%" cellspacing="0" id="table_diterima">
						<thead class="bg-primary">
							<tr>
								<th class="text-center" width="1%">No</th>
								<th>Tanggal Apply</th>
								<th>Nama Pelamar</th>
								<th>No. HP</th>
								<th>Email</th>
								<th>Status</th>
								<th class="text-center">Tanggal Proses</th>
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
