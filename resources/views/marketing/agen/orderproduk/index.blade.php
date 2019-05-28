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
                <div class="col-3 col-md-3 col-sm-3 text-left">
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
