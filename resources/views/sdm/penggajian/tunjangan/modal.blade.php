<div class="modal fade" id="modal_mastertunjangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Master Tunjangan </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
                    <div class="col-2">
                        <label>Tambah Master Tunjangan</label>
                    </div>
                    <div class="col-7 mb-3">
                        <input type="text" class="form-control form-control-sm add_mastertunjangan" id="add_mastertunjangan">
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-primary" onclick="tambahMasterBenefits('T')">Tambah</button>
                    </div>
                    <div class="table-responsive col-12">
                        <table class="table table-hover table-striped display nowrap" style="width:100%" cellspacing="0" id="table_mastertunjangan">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="80%">Nama Tunjangan</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_detailmastertunjangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Detail Tunjangan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-2 mb-2">
                        <label>Nama :</label>
                    </div>
                    <div class="col-10">
                        <span class="nama_pegawai"></span>
                    </div>
                    <div class="col-2 mb-2">
                            <label>NIP :</label>
                        </div>
                        <div class="col-10">
                            <span class="nip_pegawai"></span>
                        </div>
                    <div class="table-responsive col-12">
                        <table class="table table-hover table-striped display nowrap" style="width:100%" cellspacing="0" id="table_detailtunjangan">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="60%">Nama</th>
                                    <th width="20%">Jenis</th>
                                    <th width="20%">Jumlah</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanMasterTunjangan()">
                    <i class="glyphicon glyphicon-floppy-disk"></i> Simpan
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_editMasterTunjangan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Edit Master Tunjangan </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
                    <div class="col-2">
                        <label>Nama Tunjangan</label>
                    </div>
                    <div class="col-7 mb-3">
                        <input type="text" class="form-control form-control-sm edit_mastertunjangan" id="edit_mastertunjangan">
                        <input type="hidden" class="form-control form-control-sm edit_idtunjangan" id="edit_idtunjangan">
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-primary" onclick="updateMasterBenefits('T')">Update</button>
                    </div>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
