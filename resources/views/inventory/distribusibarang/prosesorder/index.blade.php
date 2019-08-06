<div class="tab-pane fade in active show" id="prosesorder">
    <div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Pengelolaan Distribusi Barang</h3>
			</div>
			<div class="header-block pull-right">
				{{--<a class="btn btn-primary" href="{{ route('distribusibarang.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>--}}
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<fieldset class="mb-3">
					<div class="row">
						<div class="col-md-3 col-sm-12"></div>
						<div class="col-md-6 col-sm-12">
							<div class="input-group input-group-sm input-daterange">
								<input type="text" class="form-control" id="date_from_pr" autocomplete="off">
								<span class="input-group-addon">-</span>
								<input type="text" class="form-control" id="date_to_pr" autocomplete="off">
								<div class="input-group-append">
									<!-- <button class="btn btn-secondary" type="button" id="btn_search_date"><i class="fa fa-search"></i></button> -->
									<button class="btn btn-primary" type="button" id="btn_refresh_date_pr"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display" cellspacing="0" id="table_proses_order" width="100%">
                        <thead class="bg-primary">
                            <tr>
                                <th width="10%">No</th>
                                <th width="20%">Tanggal</th>
                                <th>Tujuan</th>
                                <th>Nota</th>
                                <th width="10%">Aksi</th>
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
