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
		<h6>Pencarian Berdasarkan :</h6>
			<fieldset class="mb-3">
				<div class="row">

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Tanggal Awal</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" name="">
					</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Tanggal Akhir</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" name="">
					</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Pendidikan Terakhir</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<select class="form-control form-control-sm">
							<option value="">Tampilkan Semua</option>
							<option value="">SD</option>
							<option value="">SMP</option>
							<option value="">SMA</option>
							<option value="">SMK</option>
							<option value="">D1</option>
							<option value="">D3</option>
							<option value="">S1</option>
							<option value="">S2</option>
							<option value="">S3</option>
						</select>
					</div>
					</div>
				</div>
			</fieldset>

				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_pelamard">
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
							<tr>
								<td>1</td>
								<td>07 Sep 2019</td>
								<td>Brad</td>
								<td>01238312321376</td>
								<td>Brad@Pit.com</td>
								<td>S1</td>
								<td>Approve 1</td>
								<td>Test Interview</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-primary btn-preview-pelamar" type="button" title="Preview"><i class="fa fa-search"></i></button>
										<button class="btn btn-warning btn-proses-pelamar" data-toggle="modal" data-target="#calon-karyawan" type="button" title="Process"><i class="fa fa-file-powerpoint-o"></i></button>
										<button class="btn btn-danger btn-disable-pelamar" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
