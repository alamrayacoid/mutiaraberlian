<!-- Modal -->
<div id="create" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Detail Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <div class="row">
              <div class="col-md-2 col-sm-12">
                  <label>Nama Barang</label>
              </div>
              <div class="col-md-10 col-sm-12 mb-3">
                  <input type="text" value="Obat" class="form-control form-control-sm" readonly/>
              </div>
              <div class="col-md-2 col-sm-12">
                  <label>Qty</label>
              </div>
              <div class="col-md-10 col-sm-12 mb-3">
                  <input type="number" value="1" class="form-control form-control-sm" readonly/>
              </div>
              <div class="col-md-2 col-sm-12">
                  <label>Harga @</label>
              </div>
              <div class="col-md-10 col-sm-12 mb-3">
                  <input type="text" value="Rp. 50,000.00" class="form-control form-control-sm input-rupiah" readonly/>
              </div>
              <div class="col-12">
                <hr> 
              </div>
              <div class="col-12">
                <h5>Return</h5>
              </div>
             <div class="col-md-4 col-sm-12">
                <div class="col-12">
                    <label>Qty</label>
                </div>
                <div>
                    <input type="number" value="" class="form-control form-control-sm"/>
                </div>
             </div> 
             <div class="col-md-4 col-sm-12">
                <div class="col-12">
                    <label>Satuan</label>
                </div>
                <div>
                    <input type="text" value="" class="form-control form-control-sm"/>
                </div>
             </div> 
             <div class="col-md-4 col-sm-12">
                <div class="col-12">
                    <label>Metode</label>
                </div>
                <div>
                    <select name="some_name" id="some_name" class="form-control form-control-sm">
                        <option value="">Ganti Barang</option>
                        <option value="">Potong Tagihan</option>
                        <option value="">Return Dana</option>
                        
                    </select>
                </div>
             </div> 
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>

  </div>
</div>
