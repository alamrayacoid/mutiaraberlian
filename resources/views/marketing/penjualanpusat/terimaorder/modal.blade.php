<!-- Modal -->
<div id="detail" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2 col-sm-12">
                        <label for="">Tanggal</label>
                    </div>
                    <div class="col-md-10 col-sm-12 mb-3">
                        <input type="text" id="dateModalDt" class="form-control form-control-sm" readonly="">
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label for="">Nama Agen</label>
                    </div>
                    <div class="col-md-10 col-sm-12 mb-3">
                        <input type="text" id="agentModalDt" class="form-control form-control-sm" readonly="">
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label for="">No Nota</label>
                    </div>
                    <div class="col-md-10 col-sm-12 mb-3">
                        <input type="text" id="notaModalDt" class="form-control form-control-sm" readonly="">
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label for="">Total Transaksi</label>
                    </div>
                    <div class="col-md-10 col-sm-12 mb-3">
                        <input type="text" id="totalModalDt" class="form-control form-control-sm rupiah-left"
                               readonly="">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped data-table table-hover display nowrap" cellspacing="0"
                           id="table_modalDt">
                        <thead class="bg-primary">
                        <tr>
                            <th width="30%">Barang</th>
                            <th width="15%%">Jumlah</th>
                            <th width="15%">Satuan</th>
                            <th width="20%">Harga Satuan</th>
                            <th width="20%">Total Harga</th>
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
