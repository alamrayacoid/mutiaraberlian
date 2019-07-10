<!-- Modal -->
<div id="searchNotaModal" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Cari Nota</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <section>
      <div class="row mb-3">
        <div class="col-md-4 col-sm-12">
            <input type="hidden" id="supplierId">
            <input type="text" class="form-control form-control-sm" id="findSupplier" placeholder="Cari supplier">
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="input-group input-group-sm input-daterange">
                <input type="text" class="form-control form-control-sm" id="date_from" placeholder="Tanggal Awal" autocomplete="off">
                <span class="input-group-addon">-</span>
                <input type="text" class="form-control form-control-sm" id="date_to" placeholder="Tanggal Akhir" autocomplete="off">
            </div>
        </div>
        <div class="col-md-1 col-sm-12">
            <button class="btn btn-md btn-primary" id="search-nota">Cari</button>
        </div>
      </div>
      </section>
        <div class="table-responsive table-modal">
          <table class="table table-striped table-hover display" cellspacing="0" id="table_search_nota" width="100%">
            <thead class="bg-primary">
              <tr>
                <th>Tgl</th>
                <th>Nota</th>
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
