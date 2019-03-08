<div class="tab-pane animated fadeIn show" id="promosi_bulanan">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Promosi Bulanan</h3>
			</div>
			<div class="header-block pull-right">		
				<a class="btn btn-primary" href="{{ route('monthpromotion.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_tahunan">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Judul Promosi</th>
								<th>Bulan</th>
								<th>Detail Promosi</th>
								<th>Biaya Promosi</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Herbal Life Saver</td>
								<td>January</td>
								<td><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>
								<td style="font-weight:bold;">Rp. 10.000.000,00</td>
								<td>
								<div class="status-pending">
								<p>Pending</p>
								</div>
								</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-warning btn-edit" onclick="window.location.href='{{ route('monthpromotion.edit') }}'" type="button" title="edit"><i class="fa fa-pencil"></i></button>
									</div>
								</td>
							</tr>
							<tr>
								<td>1</td>
								<td>Herbal For Better Life</td>
								<td>February</td>
								<td><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>
								<td style="font-weight:bold;">Rp. 10.000.000,00</td>
								<td>
								<div class="status-approve">
								<p>Approve</p>
								</div>
								</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-warning btn-edit" onclick="window.location.href='{{ route('monthpromotion.edit') }}'"type="button" title="edit"><i class="fa fa-pencil"></i></button>
									</div>
								</td>
							</tr>
							<tr>
								<td>1</td>
								<td>Herbal Good For U</td>
								<td>Maret</td>
								<td><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>
								<td style="font-weight:bold;">Rp. 10.000.000.000,00</td>
								<td>
								<div class="status-reject">
								<p>Rejected</p>
								</div>
								</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-warning btn-edit" onclick="window.location.href='{{ route('monthpromotion.edit') }}'"type="button" title="edit"><i class="fa fa-pencil"></i></button>
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
