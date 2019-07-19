<!-- Modal -->
<div id="modalDetail_agen" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Data Inventory Agen</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-4">
                                <label for="owner_r">Pemilik</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control form-control-sm bg-light" id="owner_s" readonly="" disabled="">
                                <input type="hidden" class="form-control form-control-sm" id="owner_r" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-4">
                                <label for="position_r">Posisi</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control form-control-sm bg-light" id="position_r" readonly="" disabled="">
                                <input type="hidden" class="form-control form-control-sm" id="position_r" readonly="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-4">
                                <label for="item_r">Nama Barang</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control form-control-sm bg-light" id="item_r" readonly="" disabled="">
                                <input type="hidden" class="form-control form-control-sm" id="item_r" readonly="">
                            </div>
                        </div>
                    </div>
                <div class="row form-group">
                    <div class="table-responsive" style="padding: 0px 15px 0px 15px;">
                        <table class="table table-striped table-bordered table-hover w-100" id="table_inventory_agen">
                            <thead class="bg-primary">
                                <tr>
                                    <th>Kode</th>
                                    <th width="20%">Qty</th>
                                </tr>
                            </thead>
                        </table>
                        <tbody>

                        </tbody>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
