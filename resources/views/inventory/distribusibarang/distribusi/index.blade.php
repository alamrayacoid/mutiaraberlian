<div class="tab-pane fade in active show" id="distribusibarang">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Pengelolaan Distribusi Barang</h3>
			</div>
			<div class="header-block pull-right">
				<a class="btn btn-primary" href="{{ route('distribusibarang.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<fieldset class="mb-3">
						<div class="row">

								<div class="col-md-2 col-sm-6 col-xs-12">
										<label>Tanggal Awal</label>
								</div>

								<div class="col-md-3 col-sm-6 col-xs-12">
										<div class="form-group">
												<input type="text" class="form-control form-control-sm datepicker" id="rekrut_from" name="">
										</div>
								</div>

								<div class="col-md-2 col-sm-6 col-xs-12">
										<label>Tanggal Akhir</label>
								</div>

								<div class="col-md-3 col-sm-6 col-xs-12">
										<div class="form-group">
												<input type="text" class="form-control form-control-sm datepicker" id="rekrut_to" name="">
										</div>
								</div>

								<div class="col-2">
										<button class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
								</div>

						</div>
				</fieldset>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_distribusi">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Tujuan</th>
                                <th>Nota</th>
                                <th>Jenis</th>
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
