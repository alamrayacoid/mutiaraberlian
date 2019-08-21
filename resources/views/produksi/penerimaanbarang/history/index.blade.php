<div class="tab-pane animated fadeIn show" id="history">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">History Penerimaan Barang </h3>
            </div>
        </div>
        <div class="card-block">
            <section>
            <h6>Pencarian Berdasarkan: </h6>
            <fieldset class="mb-3">
                    <div class="row">

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Tanggal Awal</label>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker" name="tgl_awal" id="tgl_awal" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <label>Tanggal Akhir</label>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm datepicker" name="tgl_akhir" id="tgl_akhir" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-2">
                            <button class="btn btn-sm btn-primary" type="button" id="btn_search"><i class="fa fa-search"></i></button>
                        </div>

                    </div>
                </fieldset>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered display nowrap w-100" cellspacing="0" id="table_history">
                        <thead class="bg-primary">
                            <tr>
                                <th width="1%">No</th>
                                <th>Nota Order</th>
                                <th>Supplier</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
