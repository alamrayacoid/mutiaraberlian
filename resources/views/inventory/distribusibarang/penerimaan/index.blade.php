<div class="tab-pane fade in show" id="penerimaan">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Penerimaan Distribusi Barang</h3>
                <span> (Fitur untuk mewakili cabang dalam menerima barang hasil distribusi)</span>
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
								<input type="text" class="form-control" id="date_from_ac" autocomplete="off">
								<span class="input-group-addon">-</span>
								<input type="text" class="form-control" id="date_to_ac" autocomplete="off">
								<div class="input-group-append">
									<button class="btn btn-secondary" type="button" id="btn_search_date_ac"><i class="fa fa-search"></i></button>
									<button class="btn btn-primary" type="button" id="btn_refresh_date_ac"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_acceptance" width="100%">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Tujuan</th>
                                <th>Nota</th>
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
