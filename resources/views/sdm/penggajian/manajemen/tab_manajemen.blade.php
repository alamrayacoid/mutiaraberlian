<div class="tab-pane fade in active show" id="list_manajemen">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Manajemen</h3>
			</div>
			<div class="header-block pull-right">
				<a class="btn btn-primary" href="{{ route('manajemen.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_manajemen">
                        <thead class="bg-primary">
                            <tr>
                                <th>Nama</th>
                                <th>SMA</th>
                                <th>D3</th>
                                <th>S1</th>
                                <th>Pangkat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Gaji Pokok</td>
                                <td>Rp. 1.040.000.00</td>
                                <td>Rp. 1.040.000.00</td>
                                <td>Rp. 1.040.000.00</td>
                                <td>Semua</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-warning btn-edit-manajemen" onclick="window.location.href='{{ route('manajemen.edit') }}'" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-disable-manajemen" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
