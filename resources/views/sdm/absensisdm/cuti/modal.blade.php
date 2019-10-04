<div id="modal_tambahcuti" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Tambah Jenis Cuti </h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<section class="row">
                    <div class="col-4 mb-3">
                        <label>Nama</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm" id="nama_cuti">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Lama Cuti</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm suffixhari" id="lama_cuti">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Keterangan</label>
                    </div>
                    <div class="col-8 mb-3">
                        <textarea class="form-control form-control-sm" id="note_cuti"></textarea>
                    </div>
				</section>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanJenisCuti()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="modal_editcuti" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Edit Jenis Cuti </h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<section class="row">
                    <div class="col-4 mb-3">
                        <label>Nama</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm" id="editnama_cuti">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Lama Cuti</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm suffixhari" id="editlama_cuti">
                        <input type="hidden" class="form-control form-control-sm editid_cuti" id="editid_cuti">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Keterangan</label>
                    </div>
                    <div class="col-8 mb-3">
                        <textarea class="form-control form-control-sm" id="editnote_cuti"></textarea>
                    </div>
				</section>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateJenisCuti()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

