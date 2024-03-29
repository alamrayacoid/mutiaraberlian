<!-- Modal -->
<div id="modalProcessTOP" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Proses Order</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formModalPr">

                    <div class="row">
                        <input type="hidden" id="idModalPr" name="idPO">

                        <div class="col-md-2 col-sm-12">
                            <label for="">Tanggal</label>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3">
                            <input type="text" id="dateModalPr" class="form-control-plaintext border-bottom" readonly="">
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <label for="">Nama Agen</label>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3">
                            <input type="text" id="agentModalPr" class="form-control-plaintext border-bottom" readonly="">
                            <input type="hidden" id="idAgentModalPr">
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <label for="">No Nota</label>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3">
                            <input type="text" id="notaModalPr" class="form-control-plaintext border-bottom" readonly="">
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <label for="">Total Transaksi</label>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3">
                            <input type="text" id="totalModalPr" class="form-control-plaintext border-bottom rupiah-left" readonly="">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover display" cellspacing="0" id="table_modalPr" style="width: 100%">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="20%">Barang</th>
                                    <th width="10%">Stock</th>
                                    <th width="10%">Order</th>
                                    <th width="10%">Satuan</th>
                                    <th width="13%">Harga Satuan</th>
                                    <th width="16%">Diskon @</th>
                                    <th width="16%">Total Harga</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_submitProcess">Proses</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
