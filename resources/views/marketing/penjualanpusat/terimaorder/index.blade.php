<div class="tab-pane animated fadeIn active show" id="terimaorder">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Terima Order Penjualan</h3>
			</div>
	        <!-- <div class="header-block pull-right">
                <a class="btn btn-primary" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>	                    	
	        </div> -->
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_approval">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Tanggal</th>
								<th>Nama Agen</th>
								<th>Nomer Nota</th>
								<th>Total Transaksi</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>07-09-2019</td>
								<td>Dr. Bambang</td>
								<td>KUY001</td>
								<td>~</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
										<button class="btn btn-success btn-proses" type="button" title="Proses" onclick="window.location.href='{{route('orderpenjualan.proses')}}'"><i class="fa fa-arrow-right"></i></button>
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
