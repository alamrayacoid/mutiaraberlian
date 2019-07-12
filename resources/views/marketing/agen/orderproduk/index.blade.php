<div class="tab-pane animated fadeIn active show" id="orderprodukagenpusat">
    <div class="card">
        <div class="card-header bordered p-2">
            <div class="header-block">
                <h3 class="title">Order Produk ke Agen / Cabang</h3>
            </div>
            <div class="header-block pull-right">
                <a class="btn btn-primary" href="{{ route('orderagenpusat.create') }}"><i class="fa fa-plus"></i>&nbsp;Tambah Data</a>
            </div>
        </div>
        <div class="card-block">
            <div class="row mb-3">
                <div class="col-md-6 col-sm-12">
                    <div class="input-group input-group-sm input-daterange">
                        <input type="text" class="form-control" id="date_from_od" autocomplete="off">
                        <span class="input-group-addon">-</span>
                        <input type="text" class="form-control" id="date_to_od" autocomplete="off">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="btn_refresh_date_od"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-12"></div>
                <div class="col-md-4 col-sm-12">
                    <div class="row col-md-12 col-sm-12">
                        <div class="col-md-4">
                            <label for="statusDO">Status :</label>
                        </div>
                        <div class="col-md-8">
                            <select id="statusDO" class="select2" onchange="getStatusDO()">
                                <option value="" selected="">=== Pilih Status ===</option>
                                <option value="pending">Pending</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="dikirim">Dikirim</option>
                                <option value="diterima">Diterima</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <section>
                <div class="table-responsive">
                    <table class="table table-hover table-striped display nowrap" cellspacing="0" id="table_orderprodukagenpusat">
                        <thead class="bg-primary">
                            <tr>
                                <th>Tanggal Order</th>
                                <th>Nota DO</th>
                                <th>Penjual</th>
                                <th>Pembeli</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
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
