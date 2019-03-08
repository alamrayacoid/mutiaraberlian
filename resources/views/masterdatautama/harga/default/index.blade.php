<div class="tab-pane fade in show" id="default">
	<div class="card">
		<div class="card-header bordered p-2">
			<div class="header-block">
				<h3 class="title">Data Default</h3>
			</div>
		</div>
		<div class="card-block">
            <div class="row">
            <div class="col-md-6 col-sm-12">
            <div class="row">
                <div class="col-3">
                    <label for="">Nama Barang</label>
                </div>
                <div class="col-md-7 col-sm-12 mb-3">
                    <input type="text" class="form-control form-control-sm">
                </div>
            </div>
            <section>
            <div class="table-responsive">
            <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_hargasatuan">
                <thead class="bg-primary">
                    <tr>
                        <th width="10%">Satuan</th>
                        <th width="60%">Nama</th>
                        <th width="30%">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Satuan 1</td>
                        <td><input type="text" class="form-control form-control-sm" readonly=""></td>
                        <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                    </tr>
                    <tr>
                        <td>Satuan 2</td>
                        <td><input type="text" class="form-control form-control-sm" readonly=""></td>
                        <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                    </tr>
                    <tr>
                        <td>Satuan 3</td>
                        <td><input type="text" class="form-control form-control-sm" readonly=""></td>
                        <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                    </tr>
                </tbody>
            </table>
            </div>
            </section>
        </div>
        <div class="col-md-6 col-sm-12">
        <div class="table-responsive">
        <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_list_item">
        <div>
            <button class="btn btn-sm btn-primary" style="float:left; margin-right:-50px;">Simpan</button>
        </div>
            <thead class="bg-primary">
                <tr>
                    <th width="60%">Nama</th>
                    <th width="20%">Satuan</th>
                    <th width="20%">Harga</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Item</td>
                    <td>Botol</td>
                    <td>0.00</td>								
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-warning btn-edit" title="Edit" type="button" data-toggle="modal" data-target="#edit"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-primary btn-modal" type="button" title="Info" data-toggle="modal" data-target="#info"><i class="fa fa-info-circle"></i></button>
                        </div>
					</td>
                </tr>
            </tbody>
        </table>
        </div>
        </div>
        </div>
		</div>
	</div>
</div>
