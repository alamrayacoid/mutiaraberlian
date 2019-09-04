<div class="tab-pane fade in active show" id="datasuplier">
	<div class="card">
			<div class="card-header bordered p-2">
				<div class="header-block">
						<h3 class="title"> Data Suplier </h3>
				</div>
				<div class="header-block pull-right">
				<button class="btn btn-primary" data-toggle="modal" data-target="#tambah" onclick="window.location.href='{{route('suplier.create')}}'"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
				</div>
			</div>
			<div class="card-block">
					<section>
						<div class="row mb-5">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<label>Status Supplier</label>
							</div>
							<div class="col-md-4 col-sm-6 col-xs-12">
								<select name="status" id="supplier_status" class="form-control form-control-sm select2">
									<option value="">Semua</option>
									<option value="Y" selected>Aktif</option>
									<option value="N">Non Aktif</option>
								</select>
							</div>
						</div>

						<div class="table-responsive">
							<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_supplier">
								<thead class="bg-primary">
									<tr align="center">
										<th width="1%">No</th>
										<th>Nama Perusahaan</th>
										<th width="15%">Telepon</th>
										<th width="15%">Limit</th>
										<th width="15%">Hutang</th>
										<th width="5%">Aksi</th>
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
