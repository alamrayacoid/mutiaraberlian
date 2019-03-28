<div class="tab-pane animated fadeIn active show" id="orderproduk">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Order Produk ke Cabang</h3>
			</div>
			<div class="header-block pull-right">
				<a class="btn btn-primary" href="{{ route('orderProduk.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_orderproduk">
						<thead class="bg-primary">
							<tr>
								<th>Tanggal Periode</th>
								<th>Nomer Nota</th>
								<th>Nama Cabang</th>
								<th>Nama Agen</th>
								<th>Total Harga</th>
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