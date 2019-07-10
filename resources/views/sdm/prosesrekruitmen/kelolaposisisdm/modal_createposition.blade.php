<!-- Modal -->
<div id="modal_createposition" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Tambah Data Posisi SDM</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="newPosition">
                    <section>
                        <input type="hidden" id="idPosition" name="" value="">
                        <div class="row">
                            <div class="col-md-3 col-sm-12">
                                <label for="">Posisi</label>
                            </div>
                            <div class="col-md-9 col-sm-12">
                                <input type="text" class="form form-control" id="positionName" name="positionName" value="" autocomplete="off">
                            </div>
                        </div>
                    </section>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary simpanKPS" id="btnStorePos">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
