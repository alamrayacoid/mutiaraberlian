<div class="tab-pane fade in show" id="history">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">History Distribusi Barang</h3>
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
								<input type="text" class="form-control" id="date_from_ht" autocomplete="off">
								<span class="input-group-addon">-</span>
								<input type="text" class="form-control" id="date_to_ht" autocomplete="off">
								<div class="input-group-append">
									<button class="btn btn-secondary" type="button" id="btn_search_date_ht"><i class="fa fa-search"></i></button>
									<button class="btn btn-primary" type="button" id="btn_refresh_date_ht"><i class="fa fa-refresh"></i></button>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_history">
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
                            <!-- <tr>
                                <td>1</td>
                                <td>07/09/2019</td>
                                <td>Cabang</td>
                                <td>1231213</td>
                                <td>Penjualan</td>
                                <td>Pending</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary btn-modal-detail" data-toggle="modal" data-target="#history-detail"><i class="fa fa-folder"></i></button>
                                    </div>
                                </td>
                            </tr>
                             -->
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
