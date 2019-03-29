<div class="tab-pane animated fadeIn show" id="keloladataagen">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Data Order Agen</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
			<div class="row mb-4">
				<div class="col-md-2 col-sm-12">
					<input type="text" name="start_date" class="form-control form-control-sm datepicker text-center" placeholder="Tanggal Awal">
				</div>
				<span>-</span>
				<div class="col-md-2 col-sm-12">
					<input type="text" name="end_date" class="form-control form-control-sm datepicker text-center" placeholder="Tanggal Akhir">
				</div>
				<div class="col-md-2 col-sm-12">
					<select name="status" id="status" class="form-control form-control-sm select2">
						<option value="P" selected>Menunggu</option>
						<option value="N">Ditolak</option>
						<option value="Y">Disetujui</option>
					</select>
				</div>
				<div class="col-md-4 col-sm-12">
					<div class="input-group">
						<input type="text" name="nameAgen" id="nameAgen" class="form-control form-control-sm" placeholder="Cari Agen">
						<input type="hidden" id="idAgen" name="idAgen">
						<button class="btn btn-secondary btn-md" style="border-left:none;" data-toggle="modal" data-target="#searchAgen"><i class="fa fa-search"></i></button>
					</div>
				</div>
				<div class="col-md-1">
					<button class="btn btn-primary btn-md" title="Cari Berdasarkan Filter"><i class="fa fa-filter" aria-hidden="true"></i> &nbspFilter</button>
				</div>
			</div>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_dataAgen">
						<thead class="bg-primary">
							<tr>
								<th width="15%">Tanggal</th>
								<th width="25%" style="text-align:center;">Nota</th>
								<th width="20%">Nama Agen</th>
								<th width="20%">Total Transaksi</th>
								<th width="20%" style="text-align:center;">Aksi</th>
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
