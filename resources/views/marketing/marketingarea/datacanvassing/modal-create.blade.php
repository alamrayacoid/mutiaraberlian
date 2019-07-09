<!-- Modal -->
<div id="modalAddCanvassing" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Tambah Data Canvassing</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <section>
                    <form id="formAddCanvassing">
                        <div class="row">

                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Nama</label>
                            </div>
                            <div class="col-md-10 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" name="name">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Email</label>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm email" name="email">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>No Telp</label>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm hp" name="telp">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Alamat</label>
                            </div>
                            <div class="col-md-10 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <textarea name="address" class="form-control form-control-sm"></textarea>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6 col-xs-12">
                                <label>Note</label>
                            </div>
                            <div class="col-md-10 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" name="note">
                                </div>
                            </div>

                        </div>
                    </form>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_simpan_addcanvassing">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
