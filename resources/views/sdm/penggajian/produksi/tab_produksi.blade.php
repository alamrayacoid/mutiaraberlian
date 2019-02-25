<div class="tab-pane fade in" id="list_produksi">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Produksi</h3>
			</div>
			<div class="header-block pull-right">
				<a class="btn btn-primary" href="{{ route('produksi.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_produksi">
                        <thead class="bg-primary">
                            <tr>
                                <th>Nama</th>
                                <th>Gaji</th>
                                <th>Lembut</th>
                                <th>Jabatan</th>
                                <th>Jenis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Gourmet</td>
                                <td>Rp. 1.040.000.00</td>
                                <td>Rp. 1.040.000.00</td>
                                <td>
                                    <li>Mandor</li>
                                    <li>Ast. Mandor</li>
                                    <li>Penggiles</li>
                                </td>
                                <td>Garapan</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-warning btn-edit-produksi" onclick="window.location.href='{{ route('produksi.edit') }}'" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-disable-produksi" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
