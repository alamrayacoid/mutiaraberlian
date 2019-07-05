
@include('sdm.prosesrekruitmen.kelolaposisisdm.modal_createposition')

<div class="tab-pane fade in show" id="manage_position_sdm">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Posisi SDM</h3>
			</div>
			<div class="header-block pull-right">
				<button class="btn btn-primary" data-toggle="modal" data-target="#modal_createposition"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
			</div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" style="width: 100%" id="table_kps">
						<thead class="bg-primary">
							<tr>
								<th class="w-5 text-center">No</th>
								<th>Posisi</th>
								<th class="w-15 text-center">Aksi</th>
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
