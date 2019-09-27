<!-- Modal -->
<div id="detailPenerimaanProduksi" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Penerimaan Order Produksi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="formDetailHistory">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cabang">Supplier :</label>
                            <input type="text" class="form-control-plaintext b-border border-bottom" id="modHisSupplier" value="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nota">Nomor Nota :</label>
                            <input type="text" class="form-control-plaintext b-border border-bottom" id="modHisNota" value="" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="agen">Tanggal Order :</label>
                            <input type="text" class="form-control-plaintext b-border border-bottom" id="modHisDate" value="" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal">Status Pembayaran (Lunas/Belum) :</label>
                            <input type="text" class="form-control-plaintext b-border border-bottom" id="modHisPayStats" value="" readonly>
                        </div>
                    </div>
                </form>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" cellspacing="0" id="table_detailhistory">
                        <thead class="bg-primary">
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="20%">Nota</th>
                                <th width="30%">Barang</th>
                                <th width="20%">Jumlah</th>
                                <th width="15%">Satuan</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
