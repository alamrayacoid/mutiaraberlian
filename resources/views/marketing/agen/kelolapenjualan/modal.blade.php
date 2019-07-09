<!-- Modal -->
<div id="detailkpl" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header bg-gradient-info">
            <h4 class="modal-title">Detail Item</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="row">
                <label for="detail_kpl_nota" class="col-sm-2 col-form-label">Nota :</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control-plaintext" style="font-weight: bold; text-align: left;" id="detail_kpl_nota">
                </div>
            </div>
            <div class="row">
                <label for="detail_kpl_member_name" class="col-sm-2 col-form-label">Member :</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control-plaintext" style="font-weight: bold; text-align: left;" id="detail_kpl_member_name">
                </div>
            </div>
            <div class="row">
                <label for="detail_kpl_total" class="col-sm-2 col-form-label">Total :</label>
                <div class="col-sm-4">
                    <input type="text" readonly class="form-control-plaintext rupiah-left" style="font-weight: bold; text-align: left;" id="detail_kpl_total">
                </div>
            </div>
            <br>

            <div class="table-responsive">
                <table class="table table-striped table-hover display nowrap" cellspacing="0" id="table_detail_kelola" width="100%">
                    <thead class="bg-primary">
                        <tr>
                            <th>Kode/Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th style="text-align:center">Harga</th>
                            <th style="text-align:center">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div id="modal_detailKPW" class="modal fade animated fadeIn" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h4 class="modal-title">Detail Penjualan via Website</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <label for="modalnama_agen" class="col-2 col-form-label">Nama Agen :</label>
                    <div class="col-10">
                        <input id="modalnama_agen" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="modalnama_customer" class="col-2 col-form-label">Nama Customer :</label>
                    <div class="col-10">
                        <input id="modalnama_customer" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="modal_website" class="col-2 col-form-label">Website :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="modal_website" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="modal_transaksi" class="col-2 col-form-label">Kode Transaksi :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="modal_transaksi" style="text-transform: uppercase" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="modal_produk" class="col-2 col-form-label">Produk :</label>
                    <div class="col-10">
                        <input type="text" class="form-control-sm form-control" id="modal_produk" style="text-transform: uppercase" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="modal_kuantitas" class="col-2 col-form-label">Kuantitas :</label>
                    <div class="col-4">
                        <input type="number" class="form-control-sm form-control" id="modal_kuantitas" readonly>
                    </div>
                    <label for="modal_satuan" class="col-2 col-form-label">Satuan :</label>
                    <div class="col-4">
                        <input id="modal_satuan" class="form-control form-control-sm" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="modal_harga" class="col-2 col-form-label">Harga/<span id="modal_label-satuan">-</span> :</label>
                    <div class="col-4">
                        <input type="text" class="form-control-sm form-control input-harga" id="modal_harga" readonly>
                    </div>
                    <label for="modal_total" class="col-2 col-form-label">Total :</label>
                    <div class="col-4">
                        <input type="text" class="form-control-sm form-control input-harga" id="modal_total" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="modal_note" class="col-2 col-form-label">Catatan :</label>
                    <div class="col-10">
                        <textarea class="form-control form-control-sm" id="modal_note" readonly></textarea>
                    </div>
                </div>
                <div class="row form-group">

                </div>
                <div class="row form-group">
                    <div class="table-responsive" style="padding: 0px 15px 0px 15px;">
                        <table class="table table-hover table-striped display" cellspacing="0" id="table_DetailKPW" style="width: 100%">
                            <thead class="bg-primary">
                            <tr>
                                <th style="width: 80%">Kode</th>
                                <th style="width: 20%">Qty</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
