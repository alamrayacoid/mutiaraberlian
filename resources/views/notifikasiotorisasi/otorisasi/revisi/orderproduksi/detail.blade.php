<!-- Modal -->
<div id="dtlordprod" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Data Order Produksi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="col-md-12">
                        <table class="table table-striped data-table table-hover" cellspacing="0" id="tbl_dtlprod">
                            <thead class="bg-primary">
                            <tr>
                                <th width="30%">Nama Barang</th>
                                <th width="20%">Satuan</th>
                                <th width="10%">Jumlah</th>
                                <th width="20%">Harga @satuan</th>
                                <th width="20%">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                        <table class="table table-striped col-md-12 mb-4">
                            <tr>
                                <td colspan="4"><h6>Total</h6></td>
                                <td class="text-right"><h6 id="totNet"></h6></td>
                            </tr>
                        </table>

                        <div class="bg-gradient-info mb-4">
                            <h4 class="modal-title">Termin Pembayaran</h4>
                        </div>

                        <table class="table table-striped data-table table-hover" cellspacing="0" id="tbl_dtlprodtermin">
                            <thead class="bg-primary">
                            <tr>
                                <th width="10%">Termin</th>
                                <th width="45%">Estimasi</th>
                                <th width="45%">Nominal</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <table class="table table-striped col-md-12 mb-4">
                            <tr>
                                <td colspan="4"><h6>Total</h6></td>
                                <td class="text-right"><h6 id="totTermin"></h6></td>
                            </tr>
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
