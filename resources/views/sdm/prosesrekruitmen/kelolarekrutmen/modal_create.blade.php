<!-- Modal -->
<div id="modal_create" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Create Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="simpanLoker">
        <section>
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label for="">Tanggal Awal</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <input type="text" name="a_startdate" class="form-control form-control-sm datepicker">
                </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Tanggal Akhir</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <input type="text" name="a_enddate" class="form-control form-control-sm datepicker">
                </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Posisi</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <select name="a_position" id="Position" class="form-control form-control-sm select2">
                        <option value="" selected="" disabled="">=== Pilih Posisi/Jabatan ===</option>
                        @foreach($jabatan as $key => $j)
                            <option value="{{$j->j_id}}">{{$j->j_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="simpanLoker()">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>