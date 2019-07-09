<div class="modal fade" id="editGolHrgRangeHPA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px;">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Harga Golongan Agen</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formEditGolHrgRangeHPA">{{ csrf_field() }}
                <div class="modal-body">
                    <fieldset class="col-sm-12">
                        <input type="hidden" name="statusRangeHPA" id="statusRangeHPA">
                        <input type="hidden" name="golIdRangeHPA" id="golIdRangeHPA">
                        <input type="hidden" name="golDetailRangeHPA" id="golDetailRangeHPA">
                        <input type="hidden" name="golItemRangeHPA" id="golItemRangeHPA">
                        <div class="row">
                            <div>
                                <label class="col-sm-3">Range</label>
                            </div>
                            <div class="col-sm-9">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="hidden" name="rangestartawalHPA" id="rangestartawalHPA">
                                        <input type="text" class="form-control form-control-sm" name="rangestarteditHPA"
                                               id="rangestarteditHPA">
                                        <div class="input-group-addon">
                                            <span>-</span>
                                        </div>
                                        <input type="hidden" name="rangestartakhirHPA" id="rangestartakhirHPA">
                                        <input type="text" class="form-control form-control-sm" name="rangeendeditHPA"
                                               id="rangeendeditHPA">
                                        <span class="text-danger" style="margin-top: 5px;">*)Tak terhingga = ~</span>
                                    </div>

                                </div>
                            </div>
                            <div>
                                <label class="col-sm-3">Satuan</label>
                            </div>
                            <div class="col-sm-9">
                                <select name="satuanBarangRangeEditHPA" id="satuanBarangRangeEditHPA"
                                        class="form-control form-control-sm select2">
                                    <option value="">Pilih Satuan</option>
                                </select>
                            </div>
                            <div style="margin-top: 20px;">
                                <label class="col-sm-3">Harga</label>
                            </div>
                            <div class="col-sm-9" style="margin-left:7px; margin-top: 20px;">
                                <input type="text" class="form-control form-control-sm input-rupiah"
                                       id="txtEditGolHrgRangeHPA"
                                       name="edithargarangeHPA" autocomplete="off">
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
