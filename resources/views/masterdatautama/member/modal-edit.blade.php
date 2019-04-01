<!-- Modal -->
<div id="modal-edit" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Cari Agen</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">    
      <section>
      <div class="row mb-3">
        <div class="col-md-5 col-sm-12">
          <select name="" id="" class="form-control form-control-sm select2">
            <option value="" selected="" disabled="">Pilih Provinsi</option>
          </select>
        </div>
        <span>-</span>
        <div class="col-md-5 col-sm-12">
          <select name="" id="" class="form-control form-control-sm select2">
            <option value="" selected disabled>Pilih Kota</option>
          </select>
        </div>
        <div class="col-md-1 col-sm-12">
            <button class="btn btn-md btn-primary" id="search-list-agen">Cari</button>
        </div>
      </div>
      </section>  
        <div class="table-responsive table-modal d-none">
          <table class="table table-striped table-hover display nowrap data-table" cellspacing="0" id="table_search_agen" width="100%">
            <thead class="bg-primary">
              <tr>
                <th>Provinsi</th>
                <th>Kota</th>
                <th>Nama Agen</th>
                <th>Jenis</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>