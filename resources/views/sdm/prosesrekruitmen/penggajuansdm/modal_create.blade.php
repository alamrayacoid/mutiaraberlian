<!-- Modal -->
<div id="modal_create_penggajuan" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Create Data Pengajuan SDM</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="simpanPengajuan">
        <section>
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <label for="">Divisi</label>
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="form-group">
                        <select name="ss_department" id="ss_department" class="form-control form-control-sm select2">
                            <option value="" selected="" disabled="">=== Pilih Divisi ===</option>
                            @foreach($divisi as $key => $d)
                                <option value="{{$d->m_id}}">{{$d->m_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Posisi</label>
                </div>
                <div class="col-md-9 col-sm-12">
                <div class="form-group">
                    <select name="ss_position" id="ss_position" class="form-control form-control-sm select2">
                        <option value="" selected="" disabled="">=== Pilih Posisi/Jabatan ===</option>
                        @foreach($jabatan as $key => $j)
                            <option value="{{$j->j_id}}">{{$j->j_name}}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="">Kebutuhan</label>
                </div>
                <div class="col-md-9 col-sm-12">
                    <div class="form-group">
                        <input type="number" name="ss_qtyneed" class="form-control form-control-sm">
                    </div>
                </div>
            </div>
        </section>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="simpanPengajuan()">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
