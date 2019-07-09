<!-- Modal -->
<div id="modal_approve" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Promosi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body col-12">
                <div class="row">

                    <div class="col-4">
                        <label for="">Usulan Baiaya Promosi</label>
                    </div>
                    <div class="col-8 mb-2">
                        <input type="text" class="form-control input-rupiah" id="approve_usulan" readonly value="">
                    </div>

                    <div class="col-4">
                        <label for="">Realisasi Baiaya Promosi</label>
                    </div>
                    <div class="col-8 mb-2">
                        <input type="text" class="form-control input-rupiah" id="approve_realisasi" value="">
                    </div>
                    <input type="hidden" name="id_promosi" id="id_promosi">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn btn-success btn-sm" style="color: white" onclick="setuju()">Setujui Promosi</button>
            </div>
        </div>

    </div>
</div>
