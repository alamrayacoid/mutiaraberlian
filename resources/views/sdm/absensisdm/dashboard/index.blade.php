@section('extra_style')
<style type="text/css">
	.arriveTimePr:read-only {
		background-color: #dddddd;
		pointer-events:none;
	}
	.returnTimePr:read-only {
		background-color: #dddddd;
		pointer-events:none;
	}
	.onlyread {
		pointer-events:none;
		word-wrap: break-word;
		word-break: break-all;
	}
</style>
@endsection

<div class="tab-pane fade in show active" id="dashboard">
	@include('sdm.absensisdm.dashboard.modal_detail')

	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Dashboard Presensi</h3>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<div class="col-md-5 col-sm-6">
						<div class="row col-md-12 col-sm-12">
							<div class="col-md-3 col-sm-12">
								<label for="">Bulan</label>
							</div>
							<div class="col-md-9 col-sm-12">
								<div class="input-group input-group-sm input-daterange">
									<input type="text" class="form-control" id="filterByMonthYearDashbord" name="filterByMonthYearDashbord" autocomplete="off">
								</div>
							</div>
						</div>
					</div>
				
					<div class="col-md-5 col-sm-6">
						<div class="row col-md-12 col-sm-12">
							<div class="col-md-3 col-sm-12">
								<label for="">Cabang</label>
							</div>
							<div class="col-md-9 col-sm-12">
								<div class="form-group">
									<select name="filterByBranchDashbord" id="filterByBranchDashbord" class="form-control form-control-sm select2">
										<option value="" selected>Semua Cabang</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover data-table table-striped table-bordered display nowrap" cellspacing="0" style="width: 100%" id="table_presensi_sdm_dashboard">
						<thead class="bg-primary">
							<tr>
								<th class="text-center">No</th>
								<th class="text-center">Pegawai</th>
								<th class="text-center">Hadir</th>
								<th class="text-center">Ijin</th>
								<th class="text-center">Tidak masuk</th>
								<th class="text-center">Cuti</th>
								<th class="text-center">Aksi</th>
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
