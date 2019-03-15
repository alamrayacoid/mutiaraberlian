<!-- Modal -->
<div id="detail" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Detail Item</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped data-table table-hover" cellspacing="0" id="table_detail">
            <thead class="bg-primary">
              <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>@ Harga</th>
                <th>Sub Total</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
        <hr style="border:0.3px solid #000;">
      </div>
      <div class="section-nilai-bayar">
          <div class="row">
            <div class="col-lg-3 col-sm-4">
                <label for="" style="margin-right:10px;">Total</label>
            </div>
            <div class="col-4">
              <input type="text" class="form-control-plaintext" id="total_nominal">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-3 col-sm-4">
                <label for="" style="margin-right:10px;">Nominal termin</label>
            </div>
            <div class="col-4">
              <input type="text" class="form-control-plaintext" id="nominal_termin">
            </div>
          </div>
          <div class="row">
          <div class="col-lg-3 col-sm-4">
              <label for="" style="margin-right:10px;">Nilai Bayar</label>
          </div>
          <div class="col-4 mb-3">
              <input type="text" class="form-control form-control-sm input-rupiah" value="Rp. 1.000.000,00">
          </div>
          </div>
          <div class="row">
          <div class="col-lg-3 col-sm-4">
              <label for="" style="margin-right:10px;">Kekurangan</label>
          </div>
          <div class="col-4">
              <p>Rp. 0</p>
          </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Simpan</button>
      </div>
    </div>

  </div>
</div>
