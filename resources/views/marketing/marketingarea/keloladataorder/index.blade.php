<div class="tab-pane animated fadeIn show" id="keloladataagen">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Data Order Agen</h3>
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('keloladataorder.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>	                    	
	        </div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_keloladataagen">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Nama Agen</th>
								<th>List Barang</th>
								<th>Total Barang</th>
								<th>Total Harga</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Brad</td>
								<td><button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button">Detail</button></td>
								<td>500</td>
								<td>Rp. 500.000,00</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-warning btn-edit-order" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-danger btn-disable-order" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
