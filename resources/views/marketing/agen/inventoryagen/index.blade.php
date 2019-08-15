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
	            @if($company->c_type == 'AGEN' || $company->c_type == 'SUB AGEN' || $company->c_type == 'APOTEK/RADIO')
	            	<select name="m_prov" id="prov" class="form-control form-control-sm select2" onchange="getProvId()">
		              @foreach($provinsi->where('wp_id', '=', $company->a_provinsi) as $prov)
		              	<option value="{{$prov->wp_id}}" selected="">{{$prov->wp_name}}</option>
		              @endforeach
		            </select>
	            @else
	            	<select name="m_prov" id="prov" class="form-control form-control-sm select2" onchange="getProvId()">
		              <option value="" selected="" disabled="">=== Pilih Provinsi ===</option>
		              @foreach($provinsi as $prov)
		              	<option value="{{$prov->wp_id}}">{{$prov->wp_name}}</option>
		              @endforeach
		            </select>
	            @endif
					</div>
					<div class="col-md-3 col-sm-12">
	            @if($company->c_type == 'AGEN' || $company->c_type == 'SUB AGEN' || $company->c_type == 'APOTEK/RADIO')
		            <select name="m_city" id="city" class="form-control form-control-sm select2 city">
		              @foreach($kota->where('wc_id', '=', $company->a_kabupaten) as $kota)
		              	<option value="{{$kota->wc_id}}" selected="">{{$kota->wc_name}}</option>
		              @endforeach
		            </select>
	          	@else
		            <select name="m_city" id="city" class="form-control form-control-sm select2 city">
		              <option value="" selected disabled>=== Pilih Kota ===</option>
		            </select>
	          	@endif
						</div>
						<div class="col-md-4 col-sm-12">
	            @if($company->c_type == 'AGEN' || $company->c_type == 'SUB AGEN' || $company->c_type == 'APOTEK/RADIO')
		            <input type="text" class="form-control form-control-sm" value="{{$company->a_name}}" readonly="">
					<input type="hidden" id="agen" value="{{ $company->c_id }}">
	            @else
		            <select name="m_agen" id="agen" class="form-control form-control-sm select2 agen">
		              <option value="" selected disabled>=== Pilih Agen ===</option>
		            </select>
	            @endif
					</div>
					<div class="col-md-2">
						<button class="btn btn-primary btn-md" title="Cari Berdasarkan Filter" onclick="filterData()"><i class="fa fa-filter"></i> Filter</button>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap w-100" cellspacing="0" id="table_inventoryagen">
						<thead class="bg-primary">
							<tr>
								<td width="25%">Pemilik</td>
								<td width="25%">Posisi</td>
								<td width="20%">Nama Barang</td>
								<td width="10%">Status</td>
								<td width="10%">Kondisi</td>
								<td class="text-center" width="10%">Qty</td>
								<td class="text-center">Aksi</td>
							</tr>
							<tr>
								<th width="30%">Pemilik</th>
								<th width="30%">Posisi</th>
								<th width="20%">Nama Barang</th>
								<td width="10%">Status</td>
								<th width="10%">Kondisi</th>
								<th class="text-center" width="10%">Qty</th>
								<td class="text-center">Aksi</td>
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
