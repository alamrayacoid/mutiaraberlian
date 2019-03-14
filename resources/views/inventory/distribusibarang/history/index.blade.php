<div class="tab-pane fade in show" id="historybarang">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">History Distribusi Barang</h3>
            </div>
            <div class=""></div>
        </div>
        <div class="card-block">
            <section>
                <fieldset class="mb-3">
                    <div class="row">

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Tanggal Awal</label>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker" id="rekrut_from" name="">
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Tanggal Akhir</label>
                        </div>

                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker" id="rekrut_to" name="">
                            </div>
                        </div>

                        <div class="col-2">
                            <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                        </div>

                    </div>
                </fieldset>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_history">
                        <thead class="bg-primary">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Tujuan</th>
                                <th>Nota</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>07/09/2019</td>
                                <td>Cabang</td>
                                <td>1231213</td>
                                <td>Penjualan</td>
                                <td>Pending</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary btn-modal-detail" data-toggle="modal" data-target="#history-detail"><i class="fa fa-folder"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
