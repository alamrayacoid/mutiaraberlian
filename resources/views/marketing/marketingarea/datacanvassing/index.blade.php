<div class="tab-pane animated fadeIn show" id="datacanvassing">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Data Canvassing </h3>
				<input type="hidden" class="current_user_type" value="{{ Auth::user()->u_user }}">
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="#" data-toggle="modal" data-target="#modalAddCanvassing"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
	        </div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<div class="col-md-6 col-sm-12">
						<div class="input-group input-group-sm input-daterange">
							<input type="text" class="form-control" id="date_from_dc">
							<span class="input-group-addon">-</span>
							<input type="text" class="form-control" id="date_to_dc">
							<div class="input-group-append">
								<button class="btn btn-secondary" type="button" id="btn_search_date_dc"><i class="fa fa-search"></i></button>
								<button class="btn btn-primary" type="button" id="btn_refresh_date_dc"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 filter_agent">
						<div class="input-group">
							<input type="text" class="form-control form-control-sm" placeholder="Cari Agen" id="filter_agent_name_dc" autocomplete="off">
							<input type="hidden" id="filter_agent_code_dc">
							<button class="btn btn-secondary btn-md" title="Cari Agen" style="border-left:none;" data-toggle="modal" data-target="#modalSearchAgentDC"><i class="fa fa-search"></i></button>
						</div>
					</div>
					<div class="col-md-2 col-sm-6 filter_agent">
						<button class="btn btn-primary btn-md" title="Cari Berdasarkan Filter" id="btn_filter_dc">Filter</button>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_canvassing">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Nama</th>
								<th>Email</th>
								<th>No Telp</th>
								<th>Alamat</th>
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
