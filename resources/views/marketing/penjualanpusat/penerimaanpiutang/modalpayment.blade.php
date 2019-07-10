{{-- Modal Nota --}}
<div id="modal_payment" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Pembayaran Piutang</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <label for="nominal">Jumlah Nominal</label>
                    </div>
                    <div class="col-md-12 col-sm-12 mb-3">
                        <input type="text" class="form-control form-control-sm rupiah-without-comma" id="nominal" placeholder="Masukkan jumlah nominal pembayaran">
                        <input type="hidden" id="sc_nota">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="savePayment()">Simpan</button>
            </div>
        </div>

    </div>
</div>