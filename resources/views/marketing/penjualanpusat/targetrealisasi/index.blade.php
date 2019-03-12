<div class="tab-pane animated fadeIn show" id="targetrealisasi">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Target dan Realisasi</h3>
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('targetrealisasi.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>	                    	
	        </div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<fieldset class="mb-3">
					<div class="row">
						<div class="col-2">
							<label for="">Nama Item</label>
						</div>
						<div class="col-3">
							<input type="text" class="form-control form-control-sm">
						</div>
						<div class="col-2">
							<label for="">Bulan/Tahun</label>
						</div>
						<div class="col-3">
							<input type="text" class="form-control form-control-sm" id="datepicker">
						</div>
						<div class="col-1">
							<button class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</fieldset>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_targetrealisasi">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Nama Barang</th>
								<th>Bulan/Tahun</th>
								<th>Jumlah Terjual</th>
								<th>Target</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Agarilus</td>
								<td>February/2019</td>
								<td>500</td>
                                <td>1000</td>
								<td><button class="btn btn-primary status-reject" type="button">Gagal</button></td>
							</tr>
						</tbody>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
