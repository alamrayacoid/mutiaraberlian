<!-- Modal -->
<div id="modalCodeProdBase" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Daftar Kode Produksi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table_listcodeprod" cellspacing="0">
                        <thead class="bg-primary">
                            <tr>
                                <th width="50%">Kode Produksi</th>
                                <th width="25%">Qty</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="hidden" name="prodCodeLength[]" class="prodcode-length" value="0">
                                    <input type="text" class="form-control form-control-sm" style="text-transform: uppercase" name="prodCode[]"></input>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm digits qtyProdCode" name="qtyProdCode[]" value="0"></input>
                                </td>
                                <td>
                                    <button class="btn btn-success btnAddProdCode btn-sm rounded-circle" style="color:white;" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                </td>
                            </tr>
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
