<!-- Modal -->
<div id="modal_editmasterkpi" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Edit Master KPI</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>

                    <div class="row">
                        <div class="col-2">
                            <label>Indikator</label>
                        </div>

                        <div class="col-10">
                            <div class="form-group">
                                <textarea class="form-control" id="edit_indikator_masterkpi"></textarea>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateMasterKPI()">Simpan</button>
            </div>
        </div>

    </div>
</div>
