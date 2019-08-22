<div class="modal fade" id="pembayaran_cashbon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Pembayaran Cashbon </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
                    <div class="col-sm-3">
					    <label>Cashbon</label>
                    </div>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm mb-3 cashbonnow rupiah" readonly>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="penerimaan_cashbon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="border-radius: 5px;">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Penerimaan Cashbon </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
                    <div class="col-sm-3">
                        <label>Casbon</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm mb-3 cashbonnow rupiah" readonly>
                    </div>

                    <div class="col-sm-3">
                        <label>Sisa Cashbon</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm mb-3 cashbonsisa rupiah" id="cashbonsisa" readonly>
                    </div>

                    <div class="col-sm-3">
                        <label>Saldo</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm mb-3 saldopegawai rupiah" id="saldopegawai" readonly>
                    </div>

                    <div class="col-sm-3">
                        <label>Terima Cashbon</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm mb-3 rupiah" id="terima_cashbon">
                    </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">
					<span class="glyphicon glyphicon-floppy-disk"></span> Simpan
				</button>
			</div>
		</div>
	</div>
</div>

