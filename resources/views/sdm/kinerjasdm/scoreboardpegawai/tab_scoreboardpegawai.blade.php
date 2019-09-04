<div class="tab-pane fade in active show animated fadeIn" id="list_scoreboardpegawai">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Dashboard KPI</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
          <div class="col-md-5 col-sm-6">
            <div class="row col-md-12 col-sm-12">
              <div class="col-md-3 col-sm-12">
                <label for="">Periode</label>
              </div>
              <div class="col-md-9 col-sm-12">
                <div class="input-group input-group-sm input-daterange periode_dashboard">
                  <input type="text" class="form-control" id="periode_dashboard" name="periode_dashboard" autocomplete="off">
                  {{-- onchange="getDashboardKpi()" --}}
                </div>
              </div>
            </div>
          </div>
	      </div>
				<hr>
				<div class="row col-md-12 col-sm-12">
					<div class="col-md-6 col-sm-12">
						<div class="table-responsive">
							<table class="table table-hover data-table table-striped table-bordered display nowrap w-100" cellspacing="0" id="table_dashboard_kpi_pegawai">
								<thead class="bg-primary">
									<tr>
										<th>No</th>
										<th>Nama Pegawai</th>
										<th>Rata - rata Point</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-6 col-sm-12">
						<div class="table-responsive">
							<table class="table table-hover data-table table-striped table-bordered display nowrap w-100" cellspacing="0" id="table_dashboard_kpi_divisi">
								<thead class="bg-primary">
									<tr>
										<th>No</th>
										<th>Nama Divisi</th>
										<th>Rata - rata Point</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>		
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
