<div class="tab-pane fade in animated fadeIn" id="list_manajemenscoreboard">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Manajemen Scoreboard</h3>
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
						<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_manajemenscoreboard">
							<thead class="bg-primary">
								<tr>
									<th width="1%">No</th>
									<th>Date</th>
									<th>Kode</th>
									<th>Pegawai</th>
									<th>Status</th>
									<th>Tanggal Confirm</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>08-09-2019</td>
									<td>13r4d</td>
									<td>Brad</td>
									<td class="statusmng">Belum Dikonfirmasi</td>
									<td>-</td>
									<td>
										<div class="btn-group btn-group-sm">
											<button class="btn btn-primary btn-detail-manajemensb" data-toggle="modal" data-target="#detail" type="button" title="Detail"><i class="fa fa-info-circle"></i></button>
											<button class="btn btn-warning btn-edit-manajemensb" data-toggle="modal" data-target="#modal_edit" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
											<button class="btn btn-danger btn-disable-manajemensb" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
