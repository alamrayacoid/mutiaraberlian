<div id="modal_createharilibur" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header bg-gradient-info">
				<h4 class="modal-title">Tambah Aturan Kehadiran </h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<section class="row">
                    <div class="col-4 mb-3">
                        <label>Tanggal Libur</label>
                    </div>
                    <div class="col-8 mb-3">
                        <input type="text" class="form-control form-control-sm text-center" id="tanggal_libur">
                    </div>

                    <div class="col-4 mb-3">
                        <label>Keterangan Libur</label>
                    </div>
                    <div class="col-8 mb-3">
                        <textarea class="form-control form-control-sm" id="keterangan_libur"></textarea>
                    </div>
				</section>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpanHariLibur()">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
