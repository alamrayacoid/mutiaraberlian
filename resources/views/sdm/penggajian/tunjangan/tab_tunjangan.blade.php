<div class="tab-pane fade in" id="tabtunjangan">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Tunjangan</h3>
			</div>
			<div class="header-block pull-right">
                <button class="btn btn-primary" onclick="getMasterTunjangan()" data-toggle="modal" data-target="#modal_mastertunjangan"><i class="fa fa-plus"></i>&nbsp;Master Tunjangan</button>
			</div>
		</div>
		<div class="card-block">
			<section>
                <div class="row mb-3">
                    <div class="col-1">
                        <label>Bulang</label>
                    </div>
                    <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-center periode_tunjangan" id="periode_tunjangan">
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-primary btn-cari" onclick="getDataTunjangan()"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" style="width: 100%" cellspacing="0" id="table_tunjangan">
                        <thead class="bg-primary">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Tunjangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
			</section>
		</div>
	</div>
</div>
