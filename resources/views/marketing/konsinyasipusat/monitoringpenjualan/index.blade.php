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
				<div class="col-md-3 col-sm-12">
					<input type="text" class="form-control form-control-sm datepicker" placeholder="Tanggal Awal">
				</div>
				<span>-</span>
				<div class="col-md-3 col-sm-12">
					<input type="text" class="form-control form-control-sm datepicker" placeholder="Tanggal Akhir">
				</div>
				<div class="col-md-4 col-sm-12" style="padding-right:0px;">
				<div class="input-group">
					<input type="text" class="form-control form-control-sm" placeholder="Cari Agen"> 
					<button class="btn btn-secondary btn-md" title="Cari Agen" style="border-left:none;" data-toggle="modal" data-target="#searchAgen"><i class="fa fa-search"></i></button>
				</div>
				</div>
				<div class="col-1">
					<button class="btn btn-primary btn-md" title="Cari Berdasarkan Filter">Filter</button>
				</div>
			</div>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_monitoringpenjualan">
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
							<tr>
								<td>1</td>
								<td>Toko Itu</td>
								<td><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detailm" type="button">Detail</button></td>
								<td>500</td>
								<td>Rp. 500.000,00</td>
								<td>30%</td>
								<td>Rp. 80.000,00</td>
							</tr>
						</tbody>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
