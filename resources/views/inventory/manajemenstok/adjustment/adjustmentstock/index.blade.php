<div class="tab-pane animated fadeIn active show" id="adjustmentstock">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Adjustment Stock</h3>
			</div>
        <div class="header-block pull-right">
          <a class="btn btn-primary" href="{{ route('adjustment.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
        </div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_adjustment">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Tanggal</th>
								<th>Code</th>
								<th>Nama Barang</th>
                <th>Status</th>
                <th>Aksi</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</div>
