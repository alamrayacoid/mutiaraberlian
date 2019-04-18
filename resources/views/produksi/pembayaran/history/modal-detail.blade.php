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
        <div class="table-responsive">
          <table class="table table-striped data-table table-hover" cellspacing="0" id="table_detail_history">
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
      <!-- <div class="section-nilai-bayar">
          <div class="row">
            <div class="col-lg-3 col-sm-4">
                <label for="" style="margin-right:10px;">Tot    al</label>
            </div>
            <div class="col-4">
              <input type="text" class="form-control-plaintext" style="font-weight: bold; text-align: right;" id="total_nominal" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-sm-4">
                <label for="" style="margin-right:10px;" id="nominal_termin_lbl">Nominal termin</label>
            </div>
            <div class="col-4">
              <input type="text" class="form-control-plaintext" style="font-weight: bold; text-align: right;" id="nominal_termin" readonly>
            </div>
          </div>
          <div class="row">
          <div class="col-lg-3 col-sm-4">
              <label for="" style="margin-right:10px;">Nilai Bayar</label>
          </div>
          <div class="col-4 mb-3">
            <div class="input-group">
              <div class="input-group-prepend">
                <button class="btn btn-primary" type="button" title="Lunasi" onclick="lunasiTermin()"><i class="fa fa-check-circle"></i></button>
              </div>
              <input type="text" class="form-control form-control-sm input-rupiah" value="0" id="nilai_bayar">
            </div>
          </div>
          </div>
          <div class="row">
          <div class="col-lg-3 col-sm-4">
              <label for="" style="margin-right:10px;">Kekurangan</label>
          </div>
          <div class="col-4">
              <input type="text" class="form-control-plaintext" style="font-weight: bold; text-align: right;" id="sisapembayaran" readonly value="Rp 0">
          </div>
          </div>
      </div>
       -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary" id="btn_simpan">Simpan</button> -->
      </div>
    </div>

  </div>
</div>
