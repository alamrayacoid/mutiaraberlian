<!-- Modal -->
<div id="penerimaanOrderProduksi" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Penerimaan Order Produksi</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formTerimaBarang">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>Nama Barang</strong></td>
                                    <td colspan="3" id="txtBarang"></td>
                                </tr>
                                <tr>
                                    <td><strong>Satuan</strong></td>
                                    <td id="txtSatuan"></td>
                                    <td><strong>Jumlah</strong></td>
                                    <td id="txtJumlah"></td>
                                </tr>
                                <tr>
                                    <td><strong>QTY Diterima</strong></td>
                                    <td id="txtTerima"></td>
                                    <td><strong>Sisa</strong></td>
                                    <td id="txtSisa"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <fieldset>

                                <input type="hidden" name="idOrder" id="idOrder">
                                <input type="hidden" name="idItem" id="idItem">
                                <div class="form-group row">
                                    <label for="nota" class="col-sm-2 col-form-label">No. Nota</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nota" id="nota"
                                               placeholder="Masukkan nomor nota order produksi" autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="qty" class="col-sm-2 col-form-label">QTY Terima</label>
                                    <div class="col-sm-10">
                                        <input type="number" min="0" class="form-control" name="qty" id="qty"
                                               placeholder="Masukkan qty">
                                    </div>
                                </div>

                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-floppy-disk"></span> Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
