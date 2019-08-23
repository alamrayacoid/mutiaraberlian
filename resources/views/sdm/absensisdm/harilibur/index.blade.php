
<div class="tab-pane fade in" id="harikerja">

	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Hari Libur</h3>
			</div>
			<div class="header-block pull-right">
				<button class="btn btn-primary" data-toggle="modal" data-target="#modal_createharilibur"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<div class="col-md-1 col-sm-12">
                        <label for="">Filter</label>
                    </div>
                    <div class="col-md-3">
                        <select id="bulan" class="form-control form-control-sm select2" name="bulan">
                            <option value="all">Semua Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control tahun" id="tahun" name="tahun">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary">Terapkan</button>
                    </div>
                    <div class="col-md-4">
                        <span>Keterangan: Kosongi tahun untuk melihat semua data tanpa filter tahun</span>
                    </div>
				</div>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover data-table table-striped table-bordered nowrap" cellspacing="0" style="width: 100%" id="table_aturankehadiran">
						<thead class="bg-primary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
						</thead>
						<!-- <thead class="bg-primary">
						</thead> -->
						<tbody>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</div>
