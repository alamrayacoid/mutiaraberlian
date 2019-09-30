<div class="tab-pane fade in animated fadeIn" id="sop">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola SOP</h3>
			</div>
            <div class="header-block pull-right">
				<button class="btn btn-primary" style="background-color: #cd5d1b;" id="btn_sop_record"><i class="fa fa-plus"></i>&nbsp;Catat Pelanggaran</button>
                <button class="btn btn-primary" id="btn_sop_master"><i class="fa fa-plus"></i>&nbsp;Master SOP</button>
            </div>
		</div>
		<div class="card-block">
			<section>
				<div class="row">
					<div class="col-2">
                        <label>Tanggal</label>
                    </div>
					<div class="input-group input-group-sm input-daterange col-md-6">
						<input type="text" class="form-control" id="fil_sopi_date_from" name="fil_sopi_date_from">
						<span class="input-group-addon">-</span>
						<input type="text" class="form-control" id="fil_sopi_date_to" name="fil_sopi_date_to">
						<div class="input-group-append">
							<button class="btn btn-primary" type="button" id="btn_sopi_refresh"><i class="fa fa-refresh"></i></button>
						</div>
					</div>
				</div>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover table-striped display w-100" cellspacing="0" id="table_sop">
						<thead class="bg-primary">
							<tr>
								<th width="10%">No</th>
								<th width="10%">Tanggal</th>
								<th width="40%">Pegawai</th>
								<th width="30%">SOP yang dilanggar</th>
								<th width="10%">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
