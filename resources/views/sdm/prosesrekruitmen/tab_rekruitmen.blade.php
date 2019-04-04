<div class="tab-pane fade in active show" id="list_rekruitmen">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Data Rekruitmen</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
		<div class="row mb-3">
		<h6 class="col-11">Pencarian Berdasarkan :</h6>
		<div class="d-flex justify-content-end col-1">
			<button class="btn btn-sm btn-primary">Filter</button>
		</div>
		</div>
			<fieldset class="mb-3 col-12">
				<div class="row">

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Tanggal Awal</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" id="rekrut_from" name="">
					</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Tanggal Akhir</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" id="rekrut_to" name="">
					</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Pendidikan Terakhir</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<select class="form-control form-control-sm select2" id="education">
							<option value="" selected disabled>Tampilkan Semua</option>
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
					<label>Status Rekruitmen</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<select class="form-control form-control-sm" id="statusRec">
							<option value="">Tampilkan Semua</option>
							<option value="">Released</option>
							<option value="">Approve 1</option>
							<option value="">Approve 2</option>
							<option value="">Approve 3</option>
						</select>
					</div>
					</div>
				</div>
			</fieldset>

				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" style="width: 100%" id="table_rekrutmen">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Tanggal Apply</th>
								<th>Nama Pelamar</th>
								<th>No. HP</th>
								<th>Email</th>
								<th>Pendidikan</th>
								<th>Status</th>
								<th>Approval</th>
								<th>Aksi</th>
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
