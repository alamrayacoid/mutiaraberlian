<!-- Modal -->
<div id="detailKonsinyasi" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <div class="col-md-2 col-sm-2">
                            <label>Tanggal</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_tanggal"
                                   class="form-control-sm form-control-plaintext"
                                   value="12-04-2019"
                                   readonly/>
                        </div>

                        <div class="col-md-2 col-sm-2">
                            <label>Area</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_area"
                                   class="form-control-sm form-control-plaintext"
                                   value="JAWA TIMUR - KAB. KEDIRI"
                                   readonly/>
                        </div>

                        <div class="col-md-2 col-sm-2">
                            <label>Nota</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_nota"
                                   class="form-control-sm form-control-plaintext"
                                   value="PK-001/12/04/2019"
                                   readonly/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="col-md-2 col-sm-2">
                            <label>Konsigner</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_konsigner"
                                   class="form-control-sm form-control-plaintext"
                                   value="MAHMUD JAYA EFENDI"
                                   readonly/>
                        </div>

                        <div class="col-md-2 col-sm-2">
                            <label>Tipe</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_tipe"
                                   class="form-control-sm form-control-plaintext"
                                   value="KONSINYASI"
                                   readonly/>
                        </div>

                        <div class="col-md-2 col-sm-2">
                            <label>Total</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_total"
                                   class="form-control-sm form-control-plaintext"
                                   value="Rp. 1000.000"
                                   readonly/>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap" cellspacing="0" id="modal-penempatan"
                           width="100%">
                        <thead class="bg-primary">
                        <tr>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Diskon @</th>
                            <th class="text-center">Total Harga</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>
