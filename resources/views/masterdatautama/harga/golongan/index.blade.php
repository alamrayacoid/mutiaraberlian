<div class="tab-pane fade in active show" id="golongan">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Data Harga Golongan</h3>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="container">
				<div class="row">
					<fieldset class="col-md-6 col-sm-12 mr-4">
					<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_hargasatuan">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Nama</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Agen</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-warning btn-edit-golonganharga" title="Edit" type="button"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-danger btn-disable-golonganharga" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
					</fieldset>
					<fieldset class="col-md-5 col-sm-12">
						<div>
							<label for="">Nama Barang</label>
						</div>
						<div>
							<input type="text" class="form-control form-control-sm mb-2">
						</div>

						<div>
							<label for="">Jenis Harga</label>
						</div>
						<div class="form-group">
							<select class="form-control form-control-sm select2" id="jenisharga">
								<option value="">Pilih Jenis Harga</option>
								<option value="1">Satuan</option>
								<option value="2">Range</option>
							</select>
						</div>
						<hr>
						@include('masterdatautama.harga.golongan.satuan')
						@include('masterdatautama.harga.golongan.range')
					</fieldset>
				</div>
				</div>
			</section>

		</div>
	</div>
</div>
