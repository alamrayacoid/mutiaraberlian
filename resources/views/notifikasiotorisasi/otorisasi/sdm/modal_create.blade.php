<!-- Modal -->
<div id="modal_create" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Input Tanggal Publikasi Rekruitment</h4>
      </div>
      <div class="modal-body">
        <form id="simpanPublikasi">
        <section>
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label for="">Tanggal Mulai</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <input type="text" name="ss_startdate" id="start_date" class="form-control form-control-sm datepicker">
                    <input type="hidden" id="id_pengajuan" name="id_pengajuan">
                </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Tanggal Akhir</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <input type="text" name="ss_enddate" id="end_date" class="form-control form-control-sm datepicker">
                </div>
                </div>
            </div>
        </section>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="simpanPublikasi()">Simpan</button>
      </div>
    </div>
  </div>
</div>
