<div class="tab-pane animated fadeIn active show" id="penempatanproduk">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Penempatan Produk ke Consignee </h3>
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('penempatanproduk.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
	        </div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="row filter">
					<div class="col-md-2 col-sm-6">
						<label>Consignor</label>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="form-group">
							<select class="form-control select2" name="consignor" id="consignor">
								<option value="all">Semua</option>
								<option value="{{ $pusat->c_id }}" selected>{{ $pusat->c_name }}</option>
								@foreach ($consignor as $consign)
								<option value="{{ $consign->getComp->c_id }}">{{ $consign->getComp->c_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<br>
				<div class="table-responsive">
					<table class="table table-hover table-striped w-100" cellspacing="0" id="table_penempatan">
						<thead class="bg-primary">
							<tr>
								<th class="text-center">Tanggal</th>
								<th class="text-center">Nota</th>
								<th class="text-center">Consignee</th>
								<th class="text-center">Total</th>
								<th class="text-center">Aksi</th>
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
