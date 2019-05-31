<!-- Modal -->
<div id="modalCodeProd" class="modal fade animated fadeIn modalCodeProd" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Daftar Kode Produksi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <label for="">Item tanpa kode produksi : </label>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <input type="hidden" class="QtyH" value="">
                        <input type="text" class="form-control form-control-plaintext border-bottom digits restQty" name="" value="10.000" readonly style="background-color: transparent;">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label for="">Satuan yang digunakan : </label>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <input type="text" class="form-control form-control-plaintext border-bottom usedUnit" name="" value="" readonly style="background-color: transparent;">
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table_listcodeprod" cellspacing="0">
                        <thead class="bg-primary">
                            <tr>
                                <th width="50%">Kode Produksi</th>
                                <th width="25%">Jumlah</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <input type="hidden" name="prodCodeLength[]" class="prodcode-length" value="0">
                            <tr class="rowBtnAdd"><td colspan="3" class="text-center"><button class="btn btn-success btnAddProdCode btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td></tr>
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
