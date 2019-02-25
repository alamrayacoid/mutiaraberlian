<!-- Modal -->
<div id="modal_detail_order" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Detail Order Pembelian</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        
        <label>Status : </label> <span class="badge badge-pill badge-success">Barang sudah diterima</span>
        
        <fieldset>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <label class="font-weight-bold">No Order Pembelian</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label>PO/201901/1</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label class="font-weight-bold">Cara Pembayaran</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label>CASH</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label class="font-weight-bold">Tanggal Order Pembelian</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label>2 Jan 2019</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label class="font-weight-bold">Tanggal Pengiriman</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label>3 Jan 2019</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label class="font-weight-bold">Suplier</label>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <label>Alpha</label>
            </div>
          </div>
        </fieldset>
        <hr>

        <div class="table-responsive">
          <table class="table table-striped data-table table-hover" cellspacing="0">
            <thead class="bg-primary">
              <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Qty</th>
                <th>Stock Gudang</th>
                <th>Harga Prev</th>
                <th>Harga</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              
               <tr>
                <td>Semen</td>
                <td>BRG/0001</td>
                <td>Karung</td>
                <td>10</td>
                <td>10</td>
                <td>Rp. 10.000,00</td>
                <td>Rp. 30.000,00</td>
                <td>Rp. 300.000,00</td>
              </tr>
              <tr>
                <td>Pasir</td>
                <td>BRG/0002</td>
                <td>Butir</td>
                <td>10</td>
                <td>10</td>
                <td>Rp. 10.000,00</td>
                <td>Rp. 30.000,00</td>
                <td>Rp. 300.000,00</td>
              </tr>
              <tr>
                <td>Cat</td>
                <td>BRG/0003</td>
                <td>Kaleng</td>
                <td>10</td>
                <td>10</td>
                <td>Rp. 10.000,00</td>
                <td>Rp. 30.000,00</td>
                <td>Rp. 300.000,00</td>
              </tr>
            </tbody>
          </table>
        </div>

          <div class="row mt-3">
            <div class="offset-md-6 col-md-6 col-sm-12">
              <fieldset>
                <div class="row">
                  <div class="col-lg-12">
                    <label>Total Harga :</label>
                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                  </div>

                  <div class="col-lg-12">
                    <label>Total Diskon :</label>
                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                  </div>

                  <div class="col-lg-12">
                    <label>PPN :</label>
                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                  </div>

                  <div class="col-lg-12">
                    <label>Total :</label>
                    <input type="text" readonly="" class="form-control form-control-sm" name="">
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>