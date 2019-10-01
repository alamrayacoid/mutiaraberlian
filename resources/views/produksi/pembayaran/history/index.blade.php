<div class="tab-pane animated fadeIn show" id="history">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">History Pembayaran Barang </h3>
            </div>
        </div>
        <div class="card-block">
            <section>
                <h6>Pencarian Berdasarkan: </h6>
                <fieldset class="mb-3">
                    <div class="input-group col-md-6 col-lg-6">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari Nota" id="findNota" autocomplete="off">
                        <button class="btn btn-primary btn-md btn-secondary" title="Cari Nota" style="border-left:none;" data-toggle="modal" data-target="#searchNotaModal"><i class="fa fa-search"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="refreshHistory()" id="btn_refresh_date_kpl"><i class="fa fa-refresh"></i></button>
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
