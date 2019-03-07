<div class="tab-pane animated fadeIn active show" id="targetrealisasi">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Target dan Realisasi</h3>
			</div>
	        <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('targetrealisasi.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>	                    	
	        </div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
				<p style="color:red;">*Click Status to See Details</p>
					<table class="table table-hover table-striped" cellspacing="0" id="table_approval">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th>Bulan/Tahun</th>
								<th>Jumlah Terjual</th>
								<th>Target</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>February/2019</td>
								<td>500</td>
                                <td>1000</td>
								<td><button class="btn btn-primary status-reject" type="button" onclick="window.location.href='{{ route('targetrealisasi.status') }}'">Gagal</button></td>
							</tr>
						</tbody>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
