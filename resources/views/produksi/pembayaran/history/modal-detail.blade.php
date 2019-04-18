<!-- Modal -->
<div id="detailModal" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header bg-gradient-info">
            <h4 class="modal-title">Detail Pembayaran</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                <label for="detail_history_nota" class="col-sm-2 col-form-label">Nota :</label>
                <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" style="font-weight: bold; text-align: left;" id="detail_history_nota" value="email@example.com">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered" cellspacing="0" id="table_detail_history">
                    <thead class="bg-primary">
                        <tr>
                            <th>Termin</th>
                            <th>Estimasi</th>
                            <th>Nominal</th>
                            <th>Terbayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <hr style="border:0.3px solid #000;">
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary" id="btn_simpan">Simpan</button> -->
        </div>
    </div>

  </div>
</div>
