<!-- Modal -->
<div id="change" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Ganti Password</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="section">
      <div class="container">
        <form id="datachange">
          <div class="row">
                <div class="col-lg-4 col-sm-4 mt-3">
                    <label for="">Password Lama</label>
                </div>
                <div class="col-8 mb-3 mt-3">
                    <input type="password" id="lama" name="lama" class="form-control form-control-sm">
                </div>
                <div class="col-lg-4 col-sm-4">
                    <label for="">Password Baru</label>
                </div>
                <div class="col-8 mb-3">
                    <input type="password" id="baru" name="baru" class="form-control form-control-sm">
                </div>
                <div class="col-lg-4 col-sm-4">
                    <label for="">Konfirmasi Password Baru</label>
                </div>
                <div class="col-8">
                    <input type="password" id="confirm" name="confirm" class="form-control form-control-sm">
                </div>
          </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatepassword" onclick="updatepassword()">Simpan</button>
      </div>
    </div>

  </div>
</div>
