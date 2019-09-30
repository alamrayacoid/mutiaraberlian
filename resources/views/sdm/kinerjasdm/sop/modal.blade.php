<div class="modal fade" id="modal_master_sop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Master SOP </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
                    <div class="col-md-3">
                        <label>Tambah Master SOP</label>
                    </div>
                    <div class="col-md-5 mb-3">
                        <input type="text" class="form-control form-control-sm" name="fil_sop_name" id="fil_sop_name" placeholder="nama SOP" title="nama SOP">
                    </div>
                    <div class="col-md-2 mb-3">
                        <select class="form-control form-control-sm select2" name="fil_sop_level" id="fil_sop_level" title="Level SOP">
                            <option value="1" selected>Ringan</option>
                            <option value="2">Sedang</option>
                            <option value="3">Berat</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-primary" id="btn_sop_add">Tambah</button>
                    </div>
                    <div class="table-responsive col-12">
                        <table class="table table-hover data-table table-striped display w-100" cellspacing="0" id="table_sop_master">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="70%">Nama Peraturan</th>
                                    <th width="10%">Level</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
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

<div class="modal fade" id="modal_record_sop" tabindex="-1" role="dialog" aria-labelledby="myModalLabelRecord" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabelRecord">Catatan Pelanggaran </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">

                <form id="form_sopr">
					<input type="hidden" name="type" id="fil_sopr_type">
					<input type="hidden" name="fil_sopr_id" id="fil_sopr_id">
					<input type="hidden" name="fil_sopr_detailid" id="fil_sopr_detailid">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Tanggal</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm mb-3 datepicker" name="fil_sopr_date" id="fil_sopr_date">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Pegawai</label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control form-control-sm select2" name="fil_sopr_emp" id="fil_sopr_emp">
                                <option value="">Pegawai 1</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Pelanggaran SOP</label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control form-control-sm select2" name="fil_sopr_trespass" id="fil_sopr_trespass">
                                <option value="">Pelanggaran 1</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Tindakan yang diberikan</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control form-control-sm" name="fil_sopr_react" id="fil_sopr_react">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Catatan</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control form-control-sm" name="fil_sopr_note" id="fil_sopr_note">
                        </div>
                    </div>
                </form>

			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_sopr_add">Simpan</button>
			</div>
		</div>
	</div>
</div>
