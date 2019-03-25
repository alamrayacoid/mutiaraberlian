<div class="tab-pane animated fadeIn show" id="keloladataagen">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Data Order Agen</h3>
			</div>
	        <!-- <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('keloladataorder.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>	                    	
	        </div> -->
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_keloladataagen">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th width="10%">Tanggal</th>
								<th width="40%" style="text-align:center;">Nota</th>
								<th width="15%">Agen</th>
								<th width="20%">Total Harga</th>
								<th width="10%" style="text-align:center;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>07/09/2019</td>
								<td style="text-align:center;">KUY001</td>
								<td>Bambang</td>
								<td>Rp. 500.000,00</td>
								<td style="text-align:center;">
									<div class="btn-group btn-group-sm">
										<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
										<button class="btn btn-danger btn-reject" type="button" title="Tolak"><i class="fa fa-times"></i></button>
										<button class="btn btn-success btn-accept" type="button" title="Setuju"><i class="fa fa-check"></i></button>
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
