<div class="modal fade" id="editGolHrgUnitHPA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Harga Golongan Agen</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formEditGolHrgUnitHPA">{{ csrf_field() }}
                <div class="modal-body">
                    <fieldset class="col-sm-12">
                        <input type="hidden" name="statusHPA" id="statusHPA">
                        <input type="hidden" name="golIdHPA" id="golIdHPA">
                        <input type="hidden" name="golDetailHPA" id="golDetailHPA">
                        <div class="row">
                            <div>
                                <label class="col-sm-3">Satuan</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="satuanBarangUnitEditHPA" id="satuanBarangUnitEditHPA"
                                        class="form-control form-control-sm select2">
                                    <option value="">Pilih Satuan</option>
                                </select>
                            </div>
                            <div style="margin-top: 20px;">
                                <label class="col-sm-3">Harga</label>
                            </div>
                            <div class="col-sm-9" style="margin-left:7px; margin-top: 20px;">
                                <input type="text" class="form-control form-control-sm input-rupiah" id="txtEditGolHrgHPA"
                                       name="edithargaHPA">
                            </div>
                        </div>

                    </fieldset>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
