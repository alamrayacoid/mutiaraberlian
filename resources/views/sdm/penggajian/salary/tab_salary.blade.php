<div class="tab-pane fade in" id="tab_salary">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Pembayaran Salary</h3>
			</div>
			<div class="header-block pull-right">
                <button class="btn btn-primary" onclick="masterGajiPokok()"><i class="fa fa-money"></i>&nbsp;Master Gaji Pokok</button>
			</div>
		</div>
		<div class="card-block">
			<section>
                <div class="row mb-3">
                    <div class="col-1">
                        <label>Bulan</label>
                    </div>
                    <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-center periode_salary" id="periode_salary">
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-primary btn-cari" onclick="getDataSalary()"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-1">
                        <label>Status</label>
                    </div>
                    <div class="col-6">
                        <span class="statusdiberikan"></span>
                    </div>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-hover table-striped table-bordered display nowrap w-100" cellspacing="0" id="table_salary">
                        <thead class="bg-primary">
                            <tr>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Gaji Pokok</th>
                                <th>Reward</th>
                                <th>Punishment</th>
                                <th>Tunjangan</th>
                                <th>Total Gaji</th>
                                <th>Diserahkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="pull-right">
                    <button class="btn btn-warning btn-simpan" id="draft" onclick="simpanGajiPegawai('draft')">Draft Salary</button>
                    <button class="btn btn-primary btn-simpan" id="simpan" onclick="simpanGajiPegawai('save')">Simpan dan Berikan</button>
                </div>
			</section>
		</div>
	</div>
</div>
