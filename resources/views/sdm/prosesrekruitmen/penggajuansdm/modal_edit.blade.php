<!-- Modal -->
<div id="editPengajuan" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Edit Data Pengajuan</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <section>
        <form id="formEditPengajuan">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label for="">Divisi</label>
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="form-group">
                        <select name="ss_department" id="divisis_edit" class="form-control form-control-sm select2">
                            <option value="" disabled="">=== Pilih Divisi ===</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Posisi</label>
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="form-group">
                        <select name="ss_position" id="positions_edit" class="form-control form-control-sm select2">
                            <option value="" disabled="">=== Pilih Posisi ===</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Kebutuhan</label>
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="form-group">
                        <input type="hidden" id="id_pengajuan" name="id_pengajuan">
                        <input type="number" name="ss_qtyneed" id="qtyneed" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </form>
        </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="updatePengajuan()">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
