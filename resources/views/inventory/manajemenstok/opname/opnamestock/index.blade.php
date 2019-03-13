<div class="tab-pane animated fadeIn active show" id="opnamestock">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Opname Stock</h3>
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('opname.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>	                    	
	        </div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_opnamestock">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Tanggal</th>
								<th>Reff</th>
								<th>Nama Barang</th>
                                <th>Status</th>
                                <th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>07/09/2019</td>
								<td>-</td>
                                <td>Obat</td>
								<td><button class="btn btn-primary status-reject">Pending</button></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary btn-modal" data-toggle="modal" data-target="#detail" type="button" title="Detail"><i class="fa fa-folder"></i></button>
                                        <button class="btn btn-info btn-print" type="button" title="Nota Opname"><i class="fa fa-print"></i></button>
                                        <button class="btn btn-danger btn-disable-tunjangan" type="button" title="Delete"><i class="fa fa-times-circle"></i></button>
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
