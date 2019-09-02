
<div class="tab-pane fade in show" id="presensi">
	@include('sdm.absensisdm.presensi.modal_create')
	@include('sdm.absensisdm.presensi.modal_detail')

	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Daftar Presensi SDM</h3>
			</div>
			<div class="header-block pull-right">
				<button class="btn btn-primary" id="btnCreate" data-toggle="modal" data-target="#modalCreate"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<div class="col-md-6 col-sm-12">
						<div class="input-group input-group-sm input-daterange">
							<input type="text" class="form-control" id="filterDateFromPr" name="filterDateFromPr">
							<span class="input-group-addon">-</span>
							<input type="text" class="form-control" id="filterDateToPr" name="filterDateToPr">
							<div class="input-group-append">
								<button class="btn btn-primary" type="button" id="btnRefreshTable"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>
					<div class="col-md-1 col-sm-12">
						<!-- empty -->
					</div>
					<div class="col-md-5 col-sm-6">
						<div class="row col-md-12 col-sm-12">
							<div class="col-md-3 col-sm-12">
								<label for="">Cabang</label>
							</div>
							<div class="col-md-9 col-sm-12">
								<div class="form-group">
									<select name="filterByBranch" id="filterByBranch" class="form-control form-control-sm select2">
										<option value="" selected>Semua Cabang</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover data-table table-striped table-bordered display nowrap" cellspacing="0" style="width: 100%" id="table_presensi_sdm">
						<thead class="bg-primary">
							<tr>
								<th class="text-center" rowspan="2">No</th>
								<th class="text-center" rowspan="2">Tanggal</th>
								<th class="text-center" colspan="4">Status</th>
								<th class="text-center" rowspan="2">Aksi</th>
							</tr>
							<tr>
								<th class="text-center">Hadir</th>
								<th class="text-center">Ijin</th>
								<th class="text-center">Tidak masuk</th>
								<th class="text-center">Cuti</th>
							</tr>
						</thead>
						<!-- <thead class="bg-primary">
						</thead> -->
						<tbody>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</div>
