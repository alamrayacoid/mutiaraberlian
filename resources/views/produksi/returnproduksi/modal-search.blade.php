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
            <div class="input-group input-daterange col-5">
                <input type="text" name="dateStart" id="dateStart" class="form-control form-control-sm" placeholder="Date Start">
            <label class="input-group-addon">-</label>
                <input type="text" name="dateEnd" id="dateEnd" class="form-control form-control-sm" placeholder="Date End">
            </div>
            <div class="col-5">
                <input type="hidden" name="idSupplier" id="idSupplier">
                <input type="text" name="supplier" id="supplier" class="form-control form-control-sm" placeholder="Cari Supplier">
            </div>
            <div class="2">
                <button class="btn btn-md btn-primary" id="btn_searchNotainTbl"><i class="fa fa-search"></i></button>
            </div>
        </div>
      </section>      
        <div class="table-responsive">
          <table class="table table-striped data-table table-hover w-100" cellspacing="0" id="tbl_nota">
            <thead class="bg-primary">
              <tr>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Nota</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>             
               {{--<tr>--}}
                {{--<td>Bambang</td>--}}
                {{--<td>07-09-2019</td>--}}
                {{--<td>KUY001</td>--}}
                {{--<td>--}}
                    {{--<div class="btn-group btn-group-sm">--}}
                        {{--<button class="btn btn-primary btn-detail" type="button" title="Detail" data-toggle="modal" data-target="#detail" data-backdrop="static" data-keyboard="false"><i class="fa fa-folder"></i></button>--}}
                        {{--<button class="btn btn-success btn-ambil" type="button" title="Ambil"><i class="fa fa-hand-lizard-o"></i></button>--}}
                    {{--</div>--}}
                {{--</td>--}}
              {{--</tr>--}}
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
