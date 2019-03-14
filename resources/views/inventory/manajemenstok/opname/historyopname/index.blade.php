<div class="tab-pane animated fadeIn show" id="historyopname">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">History Opname Stock</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
            <h6>Pencarian Berdasarkan :</h6>
			<fieldset class="mb-3">
				<div class="row">

					<div class="col-md-2 col-sm-6 col-xs-12">
					<label>Tanggal Awal</label>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" id="date_from" name="date_from">
					</div>
					</div>

					<div class="col-md-2 col-sm-6 col-xs-12">
					<label>Tanggal Akhir</label>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="form-group">
						<input type="text" class="form-control form-control-sm datepicker" id="date_to" name="date_to">
					</div>
					</div>

                    <div class="col-2">
                        <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                    </div>

				</div>
			</fieldset>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_historyopname">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Tanggal</th>
								<th>Reff</th>
								<th>Nama Barang</th>
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
