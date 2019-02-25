<div class="tab-pane fade in" id="list_tunjangan">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Tunjangan</h3>
			</div>
			<div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('tunjangan.setting') }}"><i class="fa fa-cog"></i>&nbsp;Set Tunjungan Pegawai</a>
				<a class="btn btn-primary" href="{{ route('tunjangan.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_tunjangan">
                        <thead class="bg-primary">
                            <tr>
                                <th>Nama</th>
                                <th>Periode</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kehadiran (Leader)</td>
                                <td>Hari</td>
                                <td>Rp. 1.040.000.00</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-warning btn-edit-tunjangan" onclick="window.location.href='{{ route('tunjangan.edit') }}'" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-disable-tunjangan" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
