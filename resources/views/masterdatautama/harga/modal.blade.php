<!-- Modal -->
<div id="detail" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Edit Data Harga</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <section>
        <fieldset class="mb-3">
          <div class="row">

          <div class="col-2">
          <label for="">Kode Item <span style="color:red;">*</span></label>
          </div>
          <div class="col-10 mb-2">
          <input type="text" class="form-control form-control-sm" readonly="" value="BRG001">
          </div>

          <div class="col-2">
          <label for="">Nama Item <span style="color:red;">*</span></label>
          </div>
          <div class="col-10 mb-2">
          <input type="text" class="form-control form-control-sm" readonly="" value="BarangApayaw">
          </div>

          </div>
        </fieldset>
      </section>      
        <div class="table-responsive">
          <table class="table table-striped data-table table-hover" cellspacing="0">
            <thead class="bg-primary">
              <tr>
                <th>Harga A</th>
                <th>Harga B</th>
                <th>Harga C</th>
                <th>Barang Rusak</th>
              </tr>
            </thead>
            <tbody>             
               <tr>
                <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
                <td><input type="text" class="form-control form-control-sm input-rupiah"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-submit" data-dismiss="modal">Simpan</button>
      </div>
    </div>

  </div>
</div>