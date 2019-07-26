<!-- Modal -->
<!-- <div id="detailDO" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Order Produk</h4>
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
                                   readonly/>
                        </div>

                        <div class="col-md-2 col-sm-2">
                            <label>Nota</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_nota"
                                   class="form-control-sm form-control-plaintext"
                                   readonly/>
                        </div>

                        <div class="col-md-2 col-sm-2">
                            <label>Status</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_status"
                                   class="form-control-sm form-control-plaintext"
                                   readonly/>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="col-md-2 col-sm-2">
                            <label>Penjual</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_penjual"
                                   class="form-control-sm form-control-plaintext"
                                   readonly/>
                        </div>

                        <div class="col-md-2 col-sm-2">
                            <label>Pembeli</label>
                        </div>
                        <div class="col-md-10 col-sm-10 mb-3">
                            <input type="text"
                                   id="txt_pembeli"
                                   class="form-control-sm form-control-plaintext"
                                   readonly/>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap w-100" cellspacing="0" id="table_itemDO" width="100%">
                        <thead class="bg-primary">
                        <tr>
                            <th width="30%">Kode Barang/Nama Barang</th>
                            <th width="20%">Jumlah</th>
                            <th width="25%">Harga Satuan</th>
                            <th width="25%">Sub Total</th>
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
</div> -->

<div class="modal fade" id="detailDO" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Order Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cabang">Nama Cabang</label>
                        <input type="text" class="form-control bg-light" id="txt_penjual" value="" readonly=""
                               disabled="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nota">Nomer Nota</label>
                        <input type="text" class="form-control bg-light" id="txt_nota" value="" readonly=""
                               disabled="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="agen">Nama Agen</label>
                        <input type="text" class="form-control bg-light" id="txt_pembeli" value="" readonly=""
                               disabled="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="tanggal">Tanggal Order</label>
                        <input type="text" class="form-control bg-light" id="txt_tanggal" value="" readonly=""
                               disabled="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="agen">Status Order</label>
                        <input type="text" class="form-control bg-light" id="txt_status" value="" readonly=""
                               disabled="">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover display nowrap w-100" cellspacing="0" id="table_itemDO">
                        <thead class="bg-primary">
                        <tr>
                            <th width="30%">Kode Barang/Nama Barang</th>
                            <th width="20%">Jumlah</th>
                            <th width="25%">Harga Satuan</th>
                            <th width="25%">Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
