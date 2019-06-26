<div class="tab-pane animated fadeIn show" id="datakonsinyasi">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Data Konsinyasi </h3>
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('datakonsinyasi.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
	        </div>
		</div>
		<div class="card-block">
			<section>
				<!-- <div class="row mb-3">
					<div class="col-md-3"></div>

					<div class="col-md-6 col-sm-12">
						<div class="input-group input-group-sm input-daterange">
							<input type="text" class="form-control" id="date_from_dk" autocomplete="off">
							<span class="input-group-addon">-</span>
							<input type="text" class="form-control" id="date_to_dk" autocomplete="off">
							<div class="input-group-append">
								//comment-><button class="btn btn-secondary" type="button" id="btn_search_date_dk"><i class="fa fa-search"></i></button>
								<button class="btn btn-primary" type="button" id="btn_refresh_date_dk"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>

					<div class="col-md-3"></div>
				</div> -->
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_konsinyasi">
						<thead class="bg-primary">
							<tr>
								<th class="text-center">Tanggal</th>
								<th class="text-center">Nota</th>
								<th class="text-center">Agen</th>
								<th class="text-center">Total</th>
								<th class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<!-- <tr>
								<td>1</td>
								<td>Brad</td>
								<td>Brad@Pit.com</td>
								<td>0123456812</td>
								<td>HollyWood</td>
								<td>
									<div class="btn-group btn-group-sm">
										<button class="btn btn-warning btn-edit-kons" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
										<button class="btn btn-danger btn-disable-kons" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
									</div>
								</td>
							</tr> -->
						</tbody>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
