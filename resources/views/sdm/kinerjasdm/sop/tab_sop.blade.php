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
                        <label>Status</label>
                    </div>
                    <div class="col-4">
                        <select class="select2 form-control form-control-sm" name="statusACtion" id="btn_sop_statusAction">
                            <option value="Y" selected>Aktif</option>
                            <option value="N">Tidak Aktif</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>
				</div>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_sop">
						<thead class="bg-primary">
							<tr>
								<th width="10%">No</th>
								<th width="60%">Tanggal</th>
								<th width="20%">Pegawai</th>
								<th width="20%">Status</th>
								<th width="20%">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
