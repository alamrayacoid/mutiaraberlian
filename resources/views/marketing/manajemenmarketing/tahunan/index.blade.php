<div class="tab-pane animated fadeIn show" id="promosi_tahunan">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Promosi Tahunan</h3>
			</div>
			<div class="header-block pull-right">		
				<a class="btn btn-primary" href="{{ route('yearpromotion.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped" cellspacing="0" id="table_tahunan">
						<thead class="bg-primary">
							<tr>
                                <th width="1%">No</th>
                                <th>Kode</th>
                                <th>Judul Promosi</th>
                                <th>Bulan</th>
                                <th>Biaya Promosi</th>
                                <th>Status</th>
                                <th>Aksi</th>
							</tr>
						</thead>
					</table>
				</div>

			</section>

		</div>
	</div>
</div>
