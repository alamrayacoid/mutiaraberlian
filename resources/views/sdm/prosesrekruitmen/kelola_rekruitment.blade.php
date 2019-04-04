<div class="tab-pane fade in show" id="kelola_rekruitment">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Kelola Rekruitment</h3>
			</div>
            <div class="header-block pull-right">
                <button class="btn btn-primary" id="btn-tambah" data-toggle="modal" data-target="#create"><i class="fa fa-plus"></i>&nbsp;Tambah Data</button>
            </div>
		</div>
		<div class="card-block">
			<section>
				<div class="table-responsive">
					<table class="table table-hover table-striped display nowrap" cellspacing="0" style="width: 100%" id="table_rekrutmen">
						<thead class="bg-primary">
							<tr>
								<th width="1%">No</th>
								<th width="50%" style="text-align:center;">Posisi</th>
								<th width="15%">Start</th>
								<th width="15%">End</th>
								<th width="10%">Aksi</th>
							</tr>
						</thead>
						<tbody>
                            <tr>
                                <td>1</td>
                                <td style="text-align:center;">Staff</td>
                                <td>01-01-2019</td>
                                <td>03-03-2019</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-success btn-accepted" title="Terima" type="button"><i class="fa fa-check"></i></button>
                                        <button class="btn btn-danger btn-rejected" type="button" title="Tolak"><i class="fa fa-times"></i></button>
                                        <button class="btn btn-warning btn-edit" title="Edit" type="button" data-toggle="modal" data-target="#edit"><i class="fa fa-pencil"></i></button>
                                    </div>
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</div>
