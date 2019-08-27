<div class="tab-pane fade in animated fadeIn" id="list_inputkpi">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Data KPI</h3>
			</div>
			<div class="header-block pull-right">
				<!-- <a class="btn btn-primary" id="btn-tambah-kpi" href="#"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a> -->
				<a class="btn btn-primary" href="{{ route('inputkpi.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
          <div class="col-md-5 col-sm-6">
            <div class="row col-md-12 col-sm-12">
              <div class="col-md-3 col-sm-12">
                <label for="">Periode</label>
              </div>
              <div class="col-md-9 col-sm-12">
                <div class="input-group input-group-sm input-daterange periode_kpi">
                  <input type="text" class="form-control" id="periode_kpi" name="periode_kpi" autocomplete="off">
                </div>
              </div>
            </div>
          </div>
	      </div>
	      <div class="row mb-3">
          <div class="col-md-5 col-sm-6">
            <div class="row col-md-12 col-sm-12">
              <div class="col-md-3 col-sm-12">
                <label for="">Tipe</label>
              </div>
              <div class="col-md-9 col-sm-12">
                <div class="form-group">
                  <select name="tipe" id="tipe" class="form-control form-control-sm select2 tipe" onchange="getFormDivisiOrEmployee()">
                    <option value="" selected="" disabled="">Pilih Tipe</option>
                    <option value="D">Divisi</option>
                    <option value="P">Pegawai</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div id="kolom"></div>
	      </div>
				<hr>
				<div class="table-responsive">
					<table class="table table-hover data-table table-striped table-bordered display nowrap w-100" cellspacing="0" id="table_indikator_divisi_pegawai_index">
						<thead class="bg-primary">
							<tr>
								<th class="text-center">No.</th>
								<th class="text-center">Indikator</th>
                <th class="text-center">Unit</th>
                <th class="text-center">Bobot</th>
                <th class="text-center">Target</th>
                <th class="text-center">Hasil</th>
                <th class="text-center">Point</th>
                <th class="text-center">Nilai</th>
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