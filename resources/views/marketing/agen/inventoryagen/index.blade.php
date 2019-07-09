<div class="tab-pane animated fadeIn show" id="inventoryagen">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Data Inventory Agen</h3>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="row mb-3">
					<div class="col-md-3 col-sm-12">
            <select name="m_prov" id="prov" class="form-control form-control-sm select2" onchange="getProvId()">
              <option value="" selected="" disabled="">=== Pilih Provinsi ===</option>
              @foreach($provinsi as $prov)
              <option value="{{$prov->wp_id}}">{{$prov->wp_name}}</option>
              @endforeach
            </select>
					</div>
					<div class="col-md-3 col-sm-12">
            <select name="m_city" id="city" class="form-control form-control-sm select2 city">
              <option value="" selected disabled>=== Pilih Kota ===</option>
            </select>
					</div>
					<div class="col-md-4 col-sm-12">
            <select name="m_agen" id="agen" class="form-control form-control-sm select2 agen">
              <option value="" selected disabled>=== Pilih Agen ===</option>
            </select>
					</div>
					<div class="col-md-2">
						<button class="btn btn-primary btn-md" title="Cari Berdasarkan Filter" onclick="filterData()"><i class="fa fa-filter"></i> Filter</button>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_inventoryagen">
						<thead class="bg-primary">
							<tr>
								<td width="30%">Pemilik</td>
								<td width="30%">Posisi</td>
								<td width="20%">Nama Barang</td>
								<td width="10%">Kondisi</td>
								<td class="text-center" width="10%">Qty</td>
							</tr>
							<tr>
								<th width="30%">Pemilik</th>
								<th width="30%">Posisi</th>
								<th width="20%">Nama Barang</th>
								<th width="10%">Kondisi</th>
								<th class="text-center" width="10%">Qty</th>
							</tr>
						</thead>
						<tbody>
							{{-- <tr>
								<td>Zidan</td>
								<td>Indonesia</td>
								<td>Beras</td>
								<td>Normal</td>
								<td>20</td>
							</tr>
							<tr>
								<td>Zainab</td>
								<td>Malaysia</td>
								<td>Kencur</td>
								<td>Normal</td>
								<td>30</td>
							</tr>
							<tr>
								<td>Boruto</td>
								<td>Jepang</td>
								<td>Ketan</td>
								<td>Rusak</td>
								<td>100</td>
							</tr>
							<tr>
								<td>Lusi</td>
								<td>Amerika</td>
								<td>Uduk</td>
								<td>Normal</td>
								<td>220</td>
							</tr>
							<tr>
								<td>Rudi</td>
								<td>Australia</td>
								<td>Jinten Hitam</td>
								<td>Rusak</td>
								<td>210</td>
							</tr> --}}
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</div>