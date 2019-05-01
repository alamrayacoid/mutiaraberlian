<div class="tab-pane animated fadeIn show" id="monitoringpenjualan">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Monitoring Penjualan </h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<div class="col-md-6 col-sm-12">
						<div class="input-group input-group-sm input-daterange">
							<input type="text" class="form-control" id="date_from_mp">
							<span class="input-group-addon">-</span>
							<input type="text" class="form-control" id="date_to_mp">
							<div class="input-group-append">
								<button class="btn btn-secondary" type="button" id="btn_search_date_mp"><i class="fa fa-search"></i></button>
								<button class="btn btn-primary" type="button" id="btn_refresh_date_mp"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 filter_agent">
						<div class="input-group">
							<input type="text" class="form-control form-control-sm" placeholder="Cari konsigner" id="filter_agent_name_mp" autocomplete="off">
							<input type="hidden" id="filter_agent_code_mp">
							<button class="btn btn-secondary btn-md" title="Cari konsigner" style="border-left:none;" data-toggle="modal" data-target="#modalSearchAgentMP"><i class="fa fa-search"></i></button>
						</div>
					</div>
					<div class="col-md-2 col-sm-6 filter_agent">
						<button class="btn btn-primary btn-md" title="Terapkan filter" id="btn_filter_mp">Terapkan</button>
					</div>
				</div>
				<div class="table-responsive table-monitoringpenjualan d-none">
					<table class="table table-hover table-striped display table-bordered" cellspacing="0" id="table_monitoringpenjualan">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Penempatan</th>
								<th>Barang</th>
								<th>Total Barang</th>
								<th>Total Harga</th>
								<th>Status Barang Laku</th>
								<th>Uang Diterima</th>
							</tr>
						</thead>
						<tbody>
							<!-- <tr>
								<td>1</td>
								<td>Toko Itu</td>
								<td><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detailm" type="button">Detail</button></td>
								<td>500</td>
								<td>Rp. 500.000,00</td>
								<td>30%</td>
								<td>Rp. 80.000,00</td>
							</tr> -->
						</tbody>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
