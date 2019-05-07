<!-- Modal -->
<div id="createReturn" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Tambah Data Return</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateReturn">{{ csrf_field() }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 col-sm-12 mt-2">
                            <label>Nama Barang</label>
                        </div>
                        <div class="col-md-10 col-sm-12 mb-3">
                            <input type="hidden" name="idPO" id="idPO">
                            <input type="hidden" name="idItem" id="idItem">
                            <input type="hidden" name="notaPO" id="notaPO">
                            <input type="text" id="txt_barang" class="form-control-plaintext form-control-sm" readonly/>
                        </div>
                        <div class="col-md-2 col-sm-12 mt-2">
                            <label>Qty</label>
                        </div>
                        <div class="col-md-10 col-sm-12 mb-3">
                            <input type="text" id="txt_qty" class="form-control-sm form-control-plaintext" readonly/>
                        </div>
                        <div class="col-md-2 col-sm-12 mt-2">
                            <label>Harga @</label>
                        </div>
                        <div class="col-md-10 col-sm-12 mb-3">
                            <input type="text" id="txt_harga" class="form-control-plaintext form-control-sm "
                                   readonly/>
                        </div>
                        <div class="col-md-2 col-sm-12 mt-2">
                            <label>Total</label>
                        </div>
                        <div class="col-md-10 col-sm-12 mb-3">
                            <input type="text" id="txt_total" class="form-control-plaintext form-control-sm "
                                   readonly/>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-12">
                            <h5 style="font-weight:bold;">Return</h5>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="col-12">
                                <label>Qty <span class="text-danger">*</span></label>
                            </div>
                            <div>
                                <input type="number" name="qty_return" id="qty_return" min="0" value="0" class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="col-12">
                                <label>Satuan <span class="text-danger">*</span></label>
                            </div>
                            <div>
                                <select name="satuan_return" id="satuan_return" class="form-control form-control-sm">

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-4">
                            <div class="col-12">
                                <label>Metode <span class="text-danger">*</span></label>
                            </div>
                            <div>
                                <select name="methode_return" id="methode_return" class="form-control form-control-sm">
                                    <option value="GB">Ganti Barang</option>
                                    <option value="PT">Potong Tagihan</option>
                                    <option value="RD">Return Dana</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label>Keterangan</label>
                        </div>
                        <div class="col-md-10">
                            <textarea name="note_return" id="note_return" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>

    </div>
</div>
