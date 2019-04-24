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
