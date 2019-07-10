<!-- Modal -->
<div id="editLoker" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Edit Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <section>
        <form id="formEdit">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label for="">Tanggal Awal</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <input type="hidden" id="id_loker" name="id_loker">
                    <input type="text" name="a_startdate" id="start_date_edit" class="form-control form-control-sm datepicker">
                </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Tanggal Akhir</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <input type="text" name="a_enddate" id="end_date_edit" class="form-control form-control-sm datepicker">
                </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Posisi</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <select name="a_position" id="position_edit" class="form-control form-control-sm select2">
                        <option value="" disabled="">=== Pilih Posisi ===</option>
                    </select>
                </div>
            </div>
        </form>
        </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="updateLoker()">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>