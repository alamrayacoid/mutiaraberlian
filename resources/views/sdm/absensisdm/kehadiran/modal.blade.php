<div id="modal_tambahaturan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Tambah Aturan Kehadiran </h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<section class="row">
                    <div class="col-4 mb-3">
                        <label>Aturan</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm" id="aturan_kehadiran">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Hukuman</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm" id="hukuman_kehadiran">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Keterangan</label>
                    </div>
                    <div class="col-8 mb-3">
                        <textarea class="form-control form-control-sm" id="note_kehadiran"></textarea>
                    </div>
				</section>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanAturanKehadiran()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="modal_editaturan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Edit Aturan Kehadiran </h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<section class="row">
                    <div class="col-4 mb-3">
                        <label>Aturan</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm" id="editaturan_kehadiran">
                        <input type="hidden" class="form-control form-control-sm" id="edit_idaturan">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Hukuman</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm" id="edithukuman_kehadiran">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Keterangan</label>
                    </div>
                    <div class="col-8 mb-3">
                        <textarea class="form-control form-control-sm" id="editnote_kehadiran"></textarea>
                    </div>
				</section>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="updateAturanKehadiran()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
