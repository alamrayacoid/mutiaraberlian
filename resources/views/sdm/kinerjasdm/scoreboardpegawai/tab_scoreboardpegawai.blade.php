<div class="tab-pane fade in active show animated fadeIn" id="list_scoreboardpegawai">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Scoreboard pegawai</h3>
			</div>
			<div class="header-block pull-right">
				<a class="btn btn-primary" data-toggle="modal" data-target="#tambah_scoreboardp" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="row">

					<div class="col-md-2 col-sm-6 col-xs-12">
						<label>Periode</label>
					</div> 

					<div class="col-md-2 col-sm-6 col-xs-12">
						<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" name="">
						</div>
					</div>

					<div class="col-md-1 col-sm-6 col-xs-12">
						<span>S/D</span>
					</div> 

					<div class="col-md-2 col-sm-6 col-xs-12">
						<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" name="">
						</div>
					</div>

					<div class="col-md-3">
						<button type="button" class="btn btn-primary">Cari</button>
					</div>
				</div>
					<hr>
					<div class="table-responsive">
						<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_scoreboard">
							<thead class="bg-primary">
								<tr>
									<th>No</th>
									<th>Tanggal</th>
									<th>Kode</th>
									<th>Pegawai</th>
									<th>Tanggal Confirm</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>07 Sep 2019</td>
									<td>13r4d</td>
									<td>Brad</td>
									<td>-</td>
									<td>
										<div class="btn-group btn-group-sm">
											<button class="btn btn-primary btn-datail-sbpegawai" data-toggle="modal" data-target="#tambah_scoreboardp" type="button" title="Detail"><i class="fa fa-info-circle"></i></button>
											<button class="btn btn-warning btn-edit-sbpegawai" data-toggle="modal" data-target="#edit_scoreboardp" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
											<button class="btn btn-danger btn-disable-sbpegawai" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
