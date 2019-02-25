<div class="tab-pane fade in" id="list_payrollmanajemen">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Payroll Manajemen</h3>
			</div>
			<div class="header-block pull-right">
				<a class="btn btn-primary"  data-toggle="modal" data-target="#modal_tambah"  href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
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
					<label>Sampai Tanggal</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" name="">
					</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Divisi</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<select class="form-control form-control-sm">
							<option value="">Tampilkan Semua</option>
							<option value="">HRD & GA</option>
							<option value="">Keuangan dan Akutansi</option>
							<option value="">Sales dan Marketing</option>
						</select>
					</div>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<label>Status</label>
					</div>

					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<select class="form-control form-control-sm">
							<option value="">Tampilkan Semua</option>
							<option value="">Sudah Dicetak</option>
							<option value="">Belum Dicetak</option>
						</select>
					</div>
					</div>
				</div>
			</fieldset>

				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_payrollmanajemen">
						<thead class="bg-primary">
							<tr>
								<th width="1%">Kode</th>
								<th>Tanggal</th>
								<th>Periode</th>
								<th>NIP</th>
								<th>Nama</th>
								<th>Total Gaji</th>
								<th>Tanggal Cetak</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>13r4d</td>
								<td>07 Sep 2019</td>
								<td>-</td>
								<td>1234567</td>
								<td>Brad</td>
								<td>Rp. -10.000.000</td>
								<td>07 Sep 2019</td>
								<td>
									<div class="btn-group btn-group-sm">
										<!-- <button class="btn btn-primary btn-preview-payrollmanajemen" type="button" title="Preview"><i class="fa fa-search"></i></button> -->
										<button class="btn btn-warning btn-proses-payrollmanajemen" type="button" title="Process"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-danger btn-disable-payrollmanajemen" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
