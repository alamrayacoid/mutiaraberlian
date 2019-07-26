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
                <table class="table table-striped table-hover display nowrap w-100" cellspacing="0" id="table_detail_kelola" width="100%">
                    <thead class="bg-primary">
                        <tr>
                            <th>Kode/Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th style="text-align:center">Harga</th>
                            <th style="text-align:center">Diskon</th>
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
