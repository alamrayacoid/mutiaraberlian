<!-- Modal -->
<div id="modalEditHistory" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Edit Riwayat Penerimaan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="myForm">
                    <input type="hidden" name="sm_stock" class="sm_stock">
                    <input type="hidden" name="sm_detailid" class="sm_detailid">
                    <div class="row">
                        <div class="col-md-1 col-sm-4 col-12">
                            <label>Item</label>
                        </div>
                        <div class="col-md-4 col-sm-8 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" id="itemName" readonly>
                            </div>
                        </div>

                        <div class="col-md-1 col-sm-4 col-12">
                            <label>Satuan</label>
                        </div>
                        <div class="col-md-2 col-sm-8 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" id="itemUnit" readonly>
                            </div>
                        </div>

                        <div class="col-md-1 col-sm-4 col-12">
                            <label>Qty</label>
                        </div>
                        <div class="col-md-2 col-sm-8 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" id="itemQty" name="itemQty">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div class="col-md-12">
                            <table class="table table-striped data-table table-hover w-100" cellspacing="0" id="table_listProdCode">
                                <thead class="bg-primary">
                                    <tr>
                                        <th width="60%" class="text-center">Kode Produksi</th>
                                        <th width="30%" class="text-center">Jumlah</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn_updatePenerimaan">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
