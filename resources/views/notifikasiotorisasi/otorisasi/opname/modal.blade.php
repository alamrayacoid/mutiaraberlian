
{{-- Modal detail--}}
<div id="detail" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Detail Opname Stock</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-6 border-right">
            <div class="form-group">
              <label for="item">Nama Barang</label>
              <input type="text" class="form-control bg-light" id="itemS" disabled="">
              <input type="hidden" id="item" >
              <input type="hidden" id="idAdjAuth">
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-6">
                  <label for="item">Qty System</label>
                  <input type="text" class="form-control bg-light" id="qtyS" disabled="">
                  <input type="hidden" id="qty_s">
                </div>
                <div class="col-6">
                  <label for="item">Qty Real</label>
                  <input type="text" class="form-control bg-light" id="qtyR" disabled="">
                  <input type="hidden" id="qty_r">
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered" id="table_detail" cellspacing="0">
                <thead class="bg-primary">
                  <tr>
                    <th>Kode Produksi</th>
                    <th>Qty</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-approve">Approve</button>
        <button type="button" class="btn btn-primary bg-danger btn-reject">Reject</button>
      </div>
    </div>
  </div>
</div>