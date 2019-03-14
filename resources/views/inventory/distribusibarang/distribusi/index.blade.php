<div class="tab-pane fade in active show" id="distribusibarang">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Pengelolaan Distribusi Barang</h3>
			</div>
			<div class="header-block pull-right">
				<a class="btn btn-primary" href="{{ route('distribusibarang.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
			</div>
			<div class=""></div>
		</div>
		<div class="card-block">
			<section>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_distribusi">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Tujuan</th>
                                <th>Nota</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>07/09/2019</td>
                                <td>Cabang</td>
                                <td>1231213</td>
                                <td>Penjualan</td>
                                <td>Pending</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary btn-modal-detail" data-toggle="modal" data-target="#detail"><i class="fa fa-folder"></i></button>
                                        <button class="btn btn-warning btn-edit-distribusi" onclick="window.location.href='{{ route('distribusibarang.edit') }}'" type="button" title="Edit"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-disable-distribusi" type="button" title="Disable"><i class="fa fa-times-circle"></i></button>
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
