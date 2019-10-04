<div class="modal fade" id="setReorder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Set Reorder Poin</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Qty Reorder Poin</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm digits" name="reoderpoin" id="reorderpoin">
                        <input type="hidden" class="form-control form-control-sm" name="id_stock" id="id_stock">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="simpandata" onclick="simpan()">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editReorder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Set Reorder Poin</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="inputPassword" class="col-sm-3 col-form-label">Qty Reorder Poin</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm digits" name="edit_reorderpoin" id="edit_reorderpoin">
                        <input type="hidden" class="form-control form-control-sm" name="edit_id_stock" id="edit_id_stock">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updatedata" onclick="update()">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Perbarui
                </button>
            </div>
        </div>
    </div>
</div>

