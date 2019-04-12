<!-- Modal -->
<div id="search-modal" class="modal fade animated fadeIn" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header bg-gradient-info">
        <h4 class="modal-title">Pencarian No. Nota</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
      <section>
        <div class="row">
            <div class="input-group col-3">
                <select name="" id="" class="form-control form-control-sm select2">
                    <option value="" selected-disable>Pilih Provinsi</option>
                </select>
            </div>
            <div class="col-3">
                <select name="" id="" class="form-control form-control-sm select2">
                    <option value="" selected-disable>Pilih Kota</option>
                </select>
            </div>
            
            <div class="col-5">
                <select name="" id="" class="form-control form-control-sm select2">
                    <option value="" selected-disable>Pilih Agen</option>
                </select>
            </div>
            <div class="col-1">
                <button class="btn btn-md btn-primary" id="btn_searchNotainTbl"><i class="fa fa-search"></i></button>
            </div>
        </div>
      </section>      
        <div class="table-responsive">
          <table class="table table-striped data-table table-hover w-100" cellspacing="0" id="tbl_nota">
            <thead class="bg-primary">
            <tr>
                <th>Agen</th>
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>             
            <tr>
                <td>Bambang</td>
                <td>07-09-2019</td>
                <td>KUY001</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder"></i></button>
                        <button class="btn btn-success btn-ambil" type="button" title="Ambil"><i class="fa fa-arrow-down"></i></button>
                    </div>
                </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary btn-submit" data-dismiss="modal">Simpan</button>
      </div>
    </div>

  </div>
</div>
