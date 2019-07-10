<!-- Modal -->
<div id="level" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Form Ganti Level</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="section">
      <div class="container">
        <form id="datalevel">
          <div class="row">
                <div class="col-lg-4 col-sm-4 mt-3">
                    <label for="">Level</label>
                </div>
                <div class="col-8 mb-3 mt-3">
                  <select name="level" id="level" class="select2">
                  <option value="" disabled selected="">--Pilih Level--</option>
                  @foreach ($level as $key => $value)
                    <option value="{{$value->m_id}}">{{$value->m_name }}</option>
                  @endforeach
                  </select>
                </div>
          </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updatelevel" onclick="updatelevel()">Simpan</button>
      </div>
    </div>

  </div>
</div>
