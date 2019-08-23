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
                    <div class="col-12" style="padding-bottom: 10px;">
                        <span>Fitur ini digunakan untuk membayar cashbon ke Pegawai</span>
                    </div>

                    <div class="col-3">
					    <label>Cashbon</label>
                    </div>
					<div class="col-9">
                        <input type="text" class="form-control form-control-sm mb-3 cashbonnow rupiah" readonly>
                        <input type="hidden" class="form-control form-control-sm mb-3 cashbonawal rupiah" id="cashbonawal">
                    </div>

                    <div class="col-3">
                        <label>Saldo</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control form-control-sm mb-3 saldopegawai rupiah" readonly>
                        <input type="hidden" class="form-control form-control-sm mb-3 saldoawalpegawai rupiah">
                        <input type="hidden" class="form-control form-control-sm id_pegawai" id="pembayaran_idpegawai">
                    </div>

                    <div class="col-3">
                        <label>Tambah Cashbon</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control form-control-sm mb-3 addcashbon rupiah" id="addcashbon">
                    </div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="simpanPembayaranCashbon()">
					<i class="glyphicon glyphicon-floppy-disk"></i> Simpan
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
				<form class="form-group row" id="form_penerimaancashbon">
                    <div class="col-12" style="padding-bottom: 10px;">
                        <span>Fitur ini digunakan untuk menerima pembayaran cashbon dari Pegawai</span>
                    </div>
                    <div class="col-3">
                        <label>Casbon</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control form-control-sm mb-3 cashbonnow rupiah" readonly>
                    </div>

                    <div class="col-3">
                        <label>Sisa Cashbon</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control form-control-sm mb-3 cashbonsisa rupiah" id="cashbonsisa" readonly>
                    </div>

                    <div class="col-3">
                        <label>Saldo</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control form-control-sm mb-3 saldopegawai rupiah" id="saldopegawai" readonly>
                        <input type="hidden" class="form-control form-control-sm saldoawalpegawai rupiah" id="saldoawalpegawai">
                        <input type="hidden" class="form-control form-control-sm id_pegawai" id="penerimaan_idpegawai">
                    </div>

                    <div class="col-3">
                        <label>Terima Cashbon</label>
                    </div>
                    <div class="col-9">
                        <input type="text" class="form-control form-control-sm mb-3 rupiah" id="terima_cashbon">
                    </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="simpanPenerimaanCashbon()">
					<i class="glyphicon glyphicon-floppy-disk"></i> Simpan
				</button>
			</div>
		</div>
	</div>
</div>

