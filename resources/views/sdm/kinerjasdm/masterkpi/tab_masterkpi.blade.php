<div class="tab-pane fade in animated fadeIn" id="masterkpi">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Master KPI</h3>
			</div>
            <div class="header-block pull-right">
                <button class="btn btn-primary" id="btn-tambah-masterkpi" data-toggle="modal" data-target="#modal_createmasterkpi"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
            </div>
		</div>
		<div class="card-block">
			<section>
				<div class="row">
                    <div class="col-2">
                        <label>Status</label>
                    </div>
                    <div class="col-4">
                        <select class="select2 form-control form-control-sm" name="statuskpi" id="statuskpi">
                            <option selected disabled>== Pilih Status ==</option>
                            <option value="Y">Aktif</option>
                            <option value="N">Tidak Aktif</option>
                        </select>
                    </div>
				</div>
					<hr>
					<div class="table-responsive">
						<table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_masterkpi">
							<thead class="bg-primary">
								<tr>
									<th width="1%">No</th>
									<th>Indikator</th>
									<th>Status</th>
								</tr>
							</thead>
						</table>
					</div>

			</section>

		</div>
	</div>
</div>
